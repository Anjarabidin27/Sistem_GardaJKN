<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    use ApiResponse;

    public function index()
    {
        // Get Admin Users
        $admins = AdminUser::with('kantorCabang.kedeputianWilayah')->get();

        // Get Approved Members with Staff Roles
        $members = \App\Models\Member::with(['province', 'city'])->whereIn('role', ['admin_wilayah', 'petugas_keliling', 'petugas_pil', 'pengurus'])
            ->where('status_pengurus', 'aktif')
            ->get();

        // Merge, Sort and Wrap in Resource
        $unified = $admins->concat($members)->sortBy('role')->values();
            
        return $this->successResponse('Daftar Petugas', \App\Http\Resources\StaffResource::collection($unified));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:admin_users,username',
            'password' => 'required|min:6',
            'name' => 'required',
            'role' => 'required|in:superadmin,administrator,admin_wilayah,petugas_keliling,petugas_pil',
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
        $source = $request->input('source', 'asli');
        
        if ($source === 'member') {
            $staff = \App\Models\Member::findOrFail($id);
            $request->validate(['role' => 'required|in:admin_wilayah,petugas_keliling,petugas_pil,pengurus']);
            $staff->update(['role' => $request->role]);
            return $this->successResponse('Role member diperbarui', $staff);
        }

        $staff = AdminUser::findOrFail($id);
        
        $request->validate([
            'username' => ['required', Rule::unique('admin_users')->ignore($staff->id)],
            'name' => 'required',
            'role' => 'required|in:superadmin,administrator,admin_wilayah,petugas_keliling,petugas_pil',
            'kantor_cabang_id' => 'required|exists:kantor_cabangs,id',
            'password' => 'nullable|min:6',
        ]);

        $data = $request->only(['username', 'name', 'role', 'kantor_cabang_id']);
        
        // Sync strings if KC changed
        if ($staff->kantor_cabang_id != $request->kantor_cabang_id) {
            $kc = \App\Models\KantorCabang::with('kedeputianWilayah')->findOrFail($request->kantor_cabang_id);
            $data['kantor_cabang'] = $kc->name;
            $data['kedeputian_wilayah'] = $kc->kedeputianWilayah?->name ?? '-';
        }

        if ($request->password) {
            $data['password'] = $request->password;
        }

        $staff->update($data);

        return $this->successResponse('Data petugas diperbarui', $staff);
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
