<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Member\StoreMemberRequest;
use App\Http\Requests\Admin\Member\UpdateMemberRequest;
use App\Repositories\MemberRepository;
use App\Services\MemberService;
use App\DTO\MemberDTO;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MemberController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected MemberRepository $memberRepo,
        protected MemberService $memberService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['search', 'province_id', 'city_id', 'only_deleted']);
        $members = $this->memberRepo->getFilteredList($filters);

        // Security Masking
        $members->getCollection()->transform(function ($member) {
            $member->nik = substr($member->nik, 0, 8) . '********';
            return $member;
        });

        return $this->successResponse('Daftar Member', $members);
    }

    public function store(StoreMemberRequest $request): JsonResponse
    {
        $dto = MemberDTO::fromRequest($request->validated());
        $member = $this->memberService->registerMember($dto);

        return $this->successResponse('Anggota baru berhasil didaftarkan', $member, 201);
    }

    public function show(int $id): JsonResponse
    {
        $member = $this->memberRepo->findById($id, ['province', 'city', 'district']);
        return $this->successResponse('Detail Member', $member);
    }

    public function update(UpdateMemberRequest $request, int $id): JsonResponse
    {
        $dto = MemberDTO::fromRequest($request->validated());
        $member = $this->memberService->updateMember($id, $dto);

        return $this->successResponse('Member berhasil diupdate', $member);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->memberService->deleteMember($id);
        return $this->successResponse('Member berhasil dihapus (soft delete)');
    }

    public function resetPassword(int $id): JsonResponse
    {
        $this->memberService->resetMemberPassword($id);
        return $this->successResponse('Password berhasil di-reset ke default');
    }

    public function restore(int $id): JsonResponse
    {
        $this->memberService->restoreMember($id);
        return $this->successResponse('Member berhasil dipulihkan');
    }
}
