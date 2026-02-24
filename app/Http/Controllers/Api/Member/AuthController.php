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
        $member = Member::where('nik', $request->nik)->first();

        if (! $member || ! Hash::check($request->password, $member->password)) {
            return $this->errorResponse('Kredensial tidak valid.', null, 401);
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
