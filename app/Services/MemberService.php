<?php

namespace App\Services;

use App\Models\Member;
use App\Repositories\MemberRepository;
use App\DTO\MemberDTO;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MemberService
{
    public function __construct(
        protected MemberRepository $memberRepo,
        protected AuditService $auditService
    ) {}

    public function registerMember(MemberDTO $dto): Member
    {
        return DB::transaction(function () use ($dto) {
            $data = $dto->toArray();
            $data['password'] = Hash::make($dto->password);

            // Automated Kantor Cabang Assignment
            if ($dto->city_id) {
                $kc = \App\Models\KantorCabang::where('city_id', $dto->city_id)->first();
                if ($kc) {
                    $data['kantor_cabang_id'] = $kc->id;
                }
            }

            // Jika berminat jadi pengurus, status otomatis jadi 'pendaftaran_diterima' (Menunggu Persetujuan)
            if ($dto->is_interested_pengurus) {
                $data['status_pengurus'] = 'pendaftaran_diterima';
            }
            
            $member = $this->memberRepo->create($data);

            $this->auditService->log(
                'create_member',
                'member',
                $member->id,
                ['name' => $member->name, 'nik' => $member->nik]
            );

            return $member;
        });
    }

    public function updateMember(int $id, MemberDTO $dto): Member
    {
        return DB::transaction(function () use ($id, $dto) {
            $member = $this->memberRepo->findById($id);
            $original = $member->getAttributes();
            
            $data = $dto->toArray();

            // Handle Photo Upload
            if ($dto->photo instanceof \Illuminate\Http\UploadedFile) {
                if ($member->photo_path) {
                    Storage::disk('public')->delete($member->photo_path);
                }
                $data['photo_path'] = $dto->photo->store('profiles', 'public');
            }
            
            $member->update($data);
            
            if ($member->wasChanged()) {
                $changes = $this->getChanges($original, $member->getChanges());
                
                $this->auditService->log(
                    'update_member',
                    'member',
                    $member->id,
                    $changes
                );
            }

            return $member;
        });
    }

    public function deleteMember(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $member = $this->memberRepo->findById($id);
            
            $this->auditService->log(
                'delete_member',
                'member',
                $member->id,
                ['name' => $member->name, 'nik' => $member->nik, 'deleted_at' => now()]
            );

            return $member->delete();
        });
    }

    public function resetMemberPassword(int $id, string $newPassword = 'GardaJKN2026!'): Member
    {
        return DB::transaction(function () use ($id, $newPassword) {
            $member = $this->memberRepo->findById($id);
            $member->password = Hash::make($newPassword);
            $member->save();

            $this->auditService->log(
                'reset_password_by_admin',
                'member',
                $member->id,
                ['name' => $member->name, 'nik' => $member->nik, 'reason' => 'Admin reset to default password']
            );

            return $member;
        });
    }

    public function restoreMember(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            // Find in trashed records
            $member = \App\Models\Member::onlyTrashed()->findOrFail($id);
            $res = $member->restore();

            if ($res) {
                $this->auditService->log(
                    'restore_member',
                    'member',
                    $member->id,
                    ['name' => $member->name, 'nik' => $member->nik, 'restored_at' => now()]
                );
            }

            return $res;
        });
    }

    public function forceDeleteMember(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $member = \App\Models\Member::onlyTrashed()->findOrFail($id);
            
            // Delete photo if exists
            if ($member->photo_path) {
                Storage::disk('public')->delete($member->photo_path);
            }

            $this->auditService->log(
                'force_delete_member',
                'member',
                $member->id,
                ['name' => $member->name, 'nik' => $member->nik, 'deleted_forever_at' => now()]
            );

            return $member->forceDelete();
        });
    }

    public function verifyPengurus(int $id, string $status, ?string $adminNote = null): Member
    {
        return DB::transaction(function () use ($id, $status, $adminNote) {
            $member = $this->memberRepo->findById($id);
            
            if ($status === 'setujui') {
                $member->status_pengurus = 'aktif';
                
                // Smart Role Assignment based on registration interests
                if ($member->interest_pil && $member->interest_keliling) {
                    $member->role = 'admin_wilayah'; // Can do both
                } elseif ($member->interest_pil) {
                    $member->role = 'petugas_pil';
                } elseif ($member->interest_keliling) {
                    $member->role = 'petugas_keliling';
                } else {
                    $member->role = 'pengurus'; // Fallback generic
                }

                // Sync to AdminUser table for Dashboard Access
                \App\Models\AdminUser::updateOrCreate(
                    ['username' => $member->nik],
                    [
                        'password' => $member->password, // Password is already hashed
                        'name' => $member->name,
                        'role' => $member->role,
                        'kantor_cabang_id' => $member->kantor_cabang_id,
                        'kantor_cabang' => $member->kantor_cabang_id ? $member->kantorCabang?->name : null,
                        'kedeputian_wilayah' => $member->kantor_cabang_id ? $member->kantorCabang?->kedeputianWilayah?->name : null,
                    ]
                );

            } else {
                $member->role = 'anggota';
                $member->status_pengurus = 'ditolak';

                // Remove from AdminUser if rejected later
                \App\Models\AdminUser::where('username', $member->nik)->delete();
            }
            
            $member->save();

            $this->auditService->log(
                'verify_pengurus',
                'member',
                $member->id,
                [
                    'name' => $member->name,
                    'nik' => $member->nik,
                    'status' => $status,
                    'note' => $adminNote,
                    'new_role' => $member->role,
                    'new_status' => $member->status_pengurus
                ]
            );

            return $member;
        });
    }

    protected function getChanges(array $original, array $dirty): array
    {
        $changes = [];
        foreach ($dirty as $key => $newValue) {
            if ($key === 'updated_at') continue;
            
            $oldValue = $original[$key] ?? null;

            // Resolve Region IDs to Names
            if (in_array($key, ['province_id', 'city_id', 'district_id'])) {
                $oldValue = $this->resolveRegionName($key, $oldValue);
                $newValue = $this->resolveRegionName($key, $newValue);
            }

            // Standardize Casing for professional looks (Title Case)
            // Skip for NIK, status, or complex IDs
            $skipCasing = ['nik', 'status', 'password'];
            if (!in_array($key, $skipCasing)) {
                if (is_string($oldValue)) $oldValue = ucwords(strtolower($oldValue));
                if (is_string($newValue)) $newValue = ucwords(strtolower($newValue));
            }

            $changes[$key] = [
                'old' => $oldValue,
                'new' => $newValue,
            ];
        }
        return $changes;
    }

    protected function resolveRegionName(string $key, $id): ?string
    {
        if (!$id || !is_numeric($id)) return $id;
        try {
            if ($key === 'province_id') return \App\Models\Province::find($id)?->name;
            if ($key === 'city_id') {
                $city = \App\Models\City::find($id);
                if (!$city) return null;
                
                $name = strtoupper($city->name);
                $type = strtoupper($city->type);
                
                // Bersihkan nama dari kata KABUPATEN atau KOTA di awal agar tidak dobel
                $cleanName = preg_replace('/^(KABUPATEN|KOTA|KAB\.?)\s+/i', '', $name);
                
                if ($type === 'KOTA') {
                    return 'Kota ' . ucwords(strtolower($cleanName));
                } else {
                    return 'Kab. ' . ucwords(strtolower($cleanName));
                }
            }
            if ($key === 'district_id') return \App\Models\District::find($id)?->name;
        } catch (\Exception $e) {
            return $id;
        }
        return $id;
    }
}
