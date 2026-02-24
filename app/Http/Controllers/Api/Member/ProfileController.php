<?php

namespace App\Http\Controllers\Api\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\Member\UpdateProfileRequest;
use App\Services\MemberService;
use App\DTO\MemberDTO;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    use ApiResponse;

    public function __construct(protected MemberService $memberService) {}

    public function show(Request $request): JsonResponse
    {
        $user = $request->user();
        
        if (!$user instanceof \App\Models\Member) {
            return $this->errorResponse('Unauthorized. User is not a member.', null, 403);
        }

        return $this->successResponse('Profil Member', $user->load(['province', 'city', 'district']));
    }

    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $dto = MemberDTO::fromRequest($request->validated());
        $member = $this->memberService->updateMember($request->user()->id, $dto);

        return $this->successResponse('Profil berhasil diperbarui.', $member);
    }
}
