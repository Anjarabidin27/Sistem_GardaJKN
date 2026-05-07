<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Http\Resources\StaffResource;

class StaffController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $user = $request->user();
        
        // 1. Query Admin Users
        $adminQuery = AdminUser::with('kantorCabang.kedeputianWilayah');
        
        // 2. Query Members (Pengurus)
        $memberQuery = \App\Models\Member::with(['province', 'city'])
            ->whereIn('role', ['admin_wilayah', 'petugas', 'pengurus'])
            ->where('status_pengurus', 'aktif');

        // Apply Hierarchical Filter (Strict ID-Based)
        if ($user && $user->role !== 'superadmin') {
            // Exclude superadmins from regional view
            $adminQuery->where('role', '!=', 'superadmin');

            $userKCId = $user->kantor_cabang_id;
            $userKWName = trim($user->kedeputian_wilayah ?? '');

            if (($user->role === 'admin_wilayah' || $user->role === 'admin') && $userKWName) {
                // Admin Wilayah: Filter by Kedeputian Wilayah relation
                $adminQuery->whereHas('kantorCabang.kedeputianWilayah', function($q) use ($userKWName) {
                    $q->where('name', $userKWName);
                });
                
                $memberQuery->whereHas('kantorCabang.kedeputianWilayah', function($q) use ($userKWName) {
                    $q->where('name', $userKWName);
                });
            } else if ($userKCId) {
                // Admin Cabang/Petugas: Filter by Branch ID
                $adminQuery->where('kantor_cabang_id', $userKCId);
                $memberQuery->where('kantor_cabang_id', $userKCId);
            } else {
                // Safety: No region assigned
                $adminQuery->where('id', 0);
                $memberQuery->where('id', 0);
            }
        }

        $admins = $adminQuery->get();
        $members = $memberQuery->get();

        // Merge, Sort and Wrap
        $unified = $admins->concat($members)->sortBy('role')->values();
            
        return $this->successResponse('Daftar Petugas', \App\Http\Resources\StaffResource::collection($unified));
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $allowedRoles = ['admin_wilayah', 'petugas'];
        
        // Only superadmin can create other superadmins
        if ($user && $user->role === 'superadmin') {
            $allowedRoles[] = 'superadmin';
        }

        $request->validate([
            'username' => 'required|unique:admin_users,username',
            'password' => 'required|min:6',
            'name' => 'required',
            'role' => ['required', Rule::in($allowedRoles)],
            'kantor_cabang_id' => 'required|exists:kantor_cabangs,id',
        ]);

        $kc = \App\Models\KantorCabang::with('kedeputianWilayah')->findOrFail($request->kantor_cabang_id);

        $staff = AdminUser::create([
            'username' => $request->username,
            'password' => $request->password, 
            'name' => $request->name,
            'role' => $request->role,
            'kantor_cabang_id' => $request->kantor_cabang_id,
            'kantor_cabang' => $kc->name,
            'kedeputian_wilayah' => $kc->kedeputianWilayah?->name ?? '-',
        ]);

        return $this->successResponse('Petugas berhasil didaftarkan', $staff, 201);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        $source = $request->input('source', 'asli');
        
        if ($source === 'member') {
            $staff = \App\Models\Member::findOrFail($id);
            $request->validate(['role' => 'required|in:admin_wilayah,petugas,pengurus']);
            $staff->update(['role' => $request->role]);
            return $this->successResponse('Role member diperbarui', $staff);
        }

        $staff = AdminUser::findOrFail($id);
        
        $allowedRoles = ['admin_wilayah', 'petugas'];
        if ($user && $user->role === 'superadmin') {
            $allowedRoles[] = 'superadmin';
        }

        $request->validate([
            'username' => ['required', Rule::unique('admin_users')->ignore($staff->id)],
            'name' => 'required',
            'role' => ['required', Rule::in($allowedRoles)],
            'kantor_cabang_id' => 'required|exists:kantor_cabangs,id',
            'password' => 'nullable|min:6',
        ]);

        $data = $request->only(['username', 'name', 'role', 'kantor_cabang_id']);

        // Selalu sync string KC & KW agar konsisten dengan ID yang dipilih
        $kc = \App\Models\KantorCabang::with('kedeputianWilayah')
            ->findOrFail($request->kantor_cabang_id);
        $data['kantor_cabang']     = $kc->name;
        $data['kedeputian_wilayah'] = $kc->kedeputianWilayah?->name ?? '-';


        if ($request->password) {
            $data['password'] = $request->password;
        }

        $staff->update($data);
        $staff->load('kantorCabang.kedeputianWilayah');

        return $this->successResponse('Data petugas diperbarui', new StaffResource($staff));
    }

    public function destroy($id)
    {
        $staff = AdminUser::findOrFail($id);
        if ($staff->id === auth('admin')->id()) {
            return $this->errorResponse('Tidak dapat menghapus akun sendiri', null, 400);
        }
        $staff->delete();
        return $this->successResponse('Petugas berhasil dihapus');
    }
}
