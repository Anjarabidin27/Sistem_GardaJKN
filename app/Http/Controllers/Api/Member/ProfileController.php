<?php

namespace App\Http\Controllers\Api\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\Member\UpdateProfileRequest;
use App\Http\Requests\Member\PengurusApplicationRequest;
use App\Services\MemberService;
use App\DTO\MemberDTO;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected MemberService $memberService,
        protected \App\Services\AuditService $auditService
    ) {}

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

    public function applyPengurus(PengurusApplicationRequest $request): JsonResponse
    {
        $user = $request->user();
        if (!$user instanceof \App\Models\Member) {
            return $this->errorResponse('Unauthorized.', null, 403);
        }

        $data = $request->validated();
        
        // Logical check: If interested=yes, status becomes 'menunggu_persetujuan'? 
        // User said: "jika tidak tetap jadi member biasa dan jika iya lanjut pertanyaan... jika tidak langsung tersimpan"
        
        $updateData = [
            'is_interested_pengurus' => $data['is_interested_pengurus'],
            'status_pengurus' => $data['is_interested_pengurus'] ? 'mendaftar' : 'tidak_mendaftar',
        ];

        if ($data['is_interested_pengurus']) {
            $updateData['role'] = 'anggota'; // Still anggota until approved? Or directly pengurus?
            // Usually such things need approval, but I'll follow the user's "langsung tersimpan" flow.
            
            $updateData['has_org_experience'] = $data['has_org_experience'];
            if ($data['has_org_experience']) {
                $updateData['org_count'] = $data['org_count'];
                $updateData['org_name'] = $data['org_name'];
                $updateData['pengurus_reason'] = $data['pengurus_reason'];
            }
            
            // If they are interested, we can set status to 'pendaftaran_diterima'
            $updateData['status_pengurus'] = 'pendaftaran_diterima';
        }

        $user->update($updateData);

        try {
            $this->auditService->log('apply_pengurus', 'member', $user->id, $updateData);
        } catch (\Exception $e) {}

        return $this->successResponse('Data kepengurusan berhasil disimpan.', $user);
    }
}
