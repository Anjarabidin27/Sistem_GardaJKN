<?php

namespace App\Http\Controllers\Api\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\Member\LoginRequest;
use App\Models\Member;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Http\Requests\Member\RegisterRequest;
use App\DTO\MemberDTO;
use App\Services\MemberService;

class AuthController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected \App\Services\AuditService $auditService,
        protected MemberService $memberService
    ) {}

    public function register(RegisterRequest $request)
    {
        $dto = MemberDTO::fromRequest($request->validated());
        $member = $this->memberService->registerMember($dto);

        return $this->successResponse('Pendaftaran berhasil! Silakan login.', $member, 201);
    }

    public function login(LoginRequest $request)
    {
        // Secara default Laravel SoftDeletes akan meng-exclude record yang dihapus
        $member = Member::where('nik', $request->nik)->first();

        if (! $member) {
            // Cek apakah sebenarnya ada tapi ter-soft-delete
            $deletedMember = Member::withTrashed()->where('nik', $request->nik)->first();
            if ($deletedMember && $deletedMember->trashed()) {
                return $this->errorResponse('Akun Anda telah diarsipkan. Silakan hubungi admin.', null, 403);
            }
            return $this->errorResponse('Nomor NIK ini belum terdaftar di sistem kami.', null, 401);
        }

        if (! Hash::check($request->password, $member->password)) {
            return $this->errorResponse('Password yang Anda masukkan salah. Silakan coba lagi.', null, 401);
        }

        $token = $member->createToken('member-token')->plainTextToken;

        try {
            $this->auditService->log('login_member', 'member', $member->id, [
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
