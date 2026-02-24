<?php

namespace App\Services;

use App\Models\Information;
use App\Repositories\InformationRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class InformationService
{
    public function __construct(
        protected InformationRepository $infoRepo,
        protected AuditService $auditService
    ) {}

    public function createInformation(array $data): Information
    {
        return DB::transaction(function () use ($data) {
            if (isset($data['attachment'])) {
                $file = $data['attachment'];
                $data['attachment_path'] = $file->store('informations', 'public');
                unset($data['attachment']);
            }

            if (isset($data['is_active'])) {
                $data['is_active'] = (bool)$data['is_active'];
            }

            $info = $this->infoRepo->create($data);

            $this->auditService->log(
                'create_information',
                'information',
                $info->id,
                ['title' => $info->title, 'type' => $info->type]
            );

            return $info;
        });
    }

    public function updateInformation(int $id, array $data): Information
    {
        return DB::transaction(function () use ($id, $data) {
            $info = $this->infoRepo->findById($id);

            if (isset($data['attachment'])) {
                if ($info->attachment_path) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($info->attachment_path);
                }
                $file = $data['attachment'];
                $data['attachment_path'] = $file->store('informations', 'public');
                unset($data['attachment']);
            }

            if (isset($data['is_active'])) {
                $data['is_active'] = (bool)$data['is_active'];
            }

            $info->update($data);

            if ($info->wasChanged()) {
                $this->auditService->log(
                    'update_information',
                    'information',
                    $info->id,
                    ['title' => $info->title]
                );
            }

            return $info;
        });
    }

    public function deleteInformation(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $info = $this->infoRepo->findById($id);
            
            if ($info->attachment_path) {
                Storage::disk('public')->delete($info->attachment_path);
            }

            $this->auditService->log(
                'delete_information',
                'information',
                $info->id,
                ['title' => $info->title]
            );

            return $info->forceDelete();
        });
    }

    public function toggleStatus(int $id): Information
    {
        $info = $this->infoRepo->findById($id);
        $info->is_active = !$info->is_active;
        $info->save();

        return $info;
    }
}
