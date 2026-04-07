<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponse;

    public function __construct(protected \App\Services\AuditService $auditService) {}

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $admin = AdminUser::where('username', $request->username)->first();

        if (! $admin) {
            return $this->errorResponse('Username tidak terdaftar.', null, 401);
        }

        if (! Hash::check($request->password, $admin->password)) {
            return $this->errorResponse('Password yang Anda masukkan salah.', null, 401);
        }

        $token = $admin->createToken('admin-token')->plainTextToken;

        try {
            $this->auditService->log('login_admin', 'admin', $admin->id, [
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
        } catch (\Exception $e) {}

        $admin->load('kantorCabang.kedeputianWilayah');

        return $this->successResponse('Login Admin Berhasil', [
            'token' => $token,
            'role' => $admin->role,
            'name' => $admin->name,
            'kantor_cabang' => $admin->kantorCabang ? $admin->kantorCabang->name : $admin->kantor_cabang,
            'kedeputian_wilayah' => ($admin->kantorCabang && $admin->kantorCabang->kedeputianWilayah) 
                ? $admin->kantorCabang->kedeputianWilayah->name 
                : $admin->kedeputian_wilayah,
        ]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user) {
            try {
                $this->auditService->log('logout_admin', 'admin', $user->id);
                $user->currentAccessToken()->delete();
            } catch (\Exception $e) {}
        }
        return $this->successResponse('Logout Admin Berhasil');
    }
}
