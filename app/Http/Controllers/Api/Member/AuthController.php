<?php

namespace App\Http\Controllers\Api\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\Member\LoginRequest;
use App\Models\Member;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponse;

    public function __construct(protected \App\Services\AuditService $auditService) {}

    public function login(LoginRequest $request)
    {
        $member = Member::where('nik', $request->nik)->first();

        if (! $member || ! Hash::check($request->password, $member->password)) {
            return $this->errorResponse('Kredensial tidak valid.', null, 401);
        }

        $token = $member->createToken('member-token')->plainTextToken;

        try {
            $this->auditService->record('member', $member->id, 'login_member', 'member', $member->id, [
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
        } catch (\Exception $e) {}

        return $this->successResponse('Login berhasil.', [
            'token' => $token,
            'member' => $member,
        ]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user) {
            try {
                $this->auditService->log('logout_member', 'member', $user->id);
                $user->currentAccessToken()->delete();
            } catch (\Exception $e) {}
        }

        return $this->successResponse('Logout berhasil.');
    }
}
