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
        $user = $request->user();
        $filters = $request->only(['search', 'province_id', 'city_id', 'only_deleted']);

        // Hierarchical Security Filter
        // Hierarchical Security Filter
        if ($user && !in_array($user->role, ['superadmin', 'administrator'])) {
            $userKC = trim($user->kantor_cabang ?? '');
            $userKW = trim($user->kedeputian_wilayah ?? '');

            if ($user->role === 'admin_wilayah' && $userKW) {
                $filters['kedeputian_wilayah'] = $userKW;
            } else if ($userKC) {
                $filters['kantor_cabang'] = $userKC;
            } else if ($user instanceof \App\Models\Member && $user->role === 'pengurus') {
                $user->load('kantorCabang');
                if ($user->kantorCabang) {
                    $filters['kantor_cabang'] = $user->kantorCabang->name;
                }
            }
        }

        $members = $this->memberRepo->getFilteredList($filters);
        
        // Superadmin & Admin Wilayah can see full NIK
        $canSeeFullNik = $user && in_array($user->role, ['superadmin', 'administrator', 'admin_wilayah']);

        // Security Masking
        $members->getCollection()->transform(function ($member) use ($canSeeFullNik) {
            if (!$canSeeFullNik && !empty($member->nik)) {
                $nik = (string) $member->nik;
                if (strlen($nik) >= 8) {
                    $member->nik = substr($nik, 0, 8) . '********';
                }
            }
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

    public function permanentlyDelete(int $id): JsonResponse
    {
        $this->memberService->forceDeleteMember($id);
        return $this->successResponse('Member berhasil dihapus secara permanen');
    }

    public function verifyPengurus(Request $request, int $id): JsonResponse
    {
        $user = $request->user();
        $member = $this->memberRepo->findById($id);

        // Region Security Check
        if (optional($user)->role === 'admin_wilayah' && $user?->kedeputian_wilayah) {
            $memberKW = $member->kantorCabang?->kedeputianWilayah?->name;
            if ($memberKW !== $user->kedeputian_wilayah) {
                return $this->errorResponse('Anda tidak memiliki wewenang untuk memverifikasi anggota di luar wilayah Anda.', 403);
            }
        }

        $request->validate([
            'status' => 'required|in:setujui,tolak',
            'note' => 'nullable|string'
        ]);

        $member = $this->memberService->verifyPengurus($id, $request->status, $request->note);
        
        $msg = $request->status === 'setujui' 
            ? "Pendaftaran Pengurus untuk {$member->name} BERHASIL DISETUJUI." 
            : "Pendaftaran Pengurus untuk {$member->name} TELAH DITOLAK.";

        return $this->successResponse($msg, $member);
    }
}
