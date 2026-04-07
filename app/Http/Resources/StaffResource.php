<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StaffResource extends JsonResource
{
    public function toArray($request)
    {
        $source = $this->resource instanceof \App\Models\AdminUser ? 'asli' : 'member';
        
        return [
            'id' => $this->id,
            'source' => $source,
            'source_label' => $source === 'asli' ? 'Admin Staff' : 'Member Promoted',
            'username' => $source === 'asli' ? $this->username : $this->nik,
            'name' => $this->name,
            'role' => $this->role,
            'kantor_cabang' => $this->getOfficeName(),
            'kedeputian_wilayah' => $this->getRegionName(),
            'is_editable' => true, // Harmonize this to true, let business logic handle limitations
        ];
    }

    protected function getOfficeName()
    {
        if ($this->resource instanceof \App\Models\AdminUser) {
            return $this->kantor_cabang ?? $this->kantorCabang?->name ?? 'Pusat';
        }
        
        // Member
        return $this->city?->name ?? $this->province?->name ?? 'Zoneless';
    }

    protected function getRegionName()
    {
        if ($this->resource instanceof \App\Models\AdminUser) {
            return $this->kedeputian_wilayah ?? $this->kantorCabang?->kedeputianWilayah?->name ?? '-';
        }
        
        // Member
        return $this->province?->name ?? '-';
    }
}
