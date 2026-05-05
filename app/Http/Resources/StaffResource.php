<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StaffResource extends JsonResource
{
    public function toArray($request)
    {
        $source = $this->resource instanceof \App\Models\AdminUser ? 'asli' : 'member';
        
        return [
            'id'                 => $this->id,
            'source'             => $source,
            'source_label'       => $source === 'asli' ? 'Admin Staff' : 'Member Promoted',
            'username'           => $source === 'asli' ? $this->username : $this->nik,
            'name'               => $this->name,
            'role'               => $this->role,
            'kantor_cabang_id'   => $source === 'asli' ? $this->kantor_cabang_id : null,
            'kantor_cabang'      => $this->getOfficeName(),
            'kedeputian_wilayah' => $this->getRegionName(),
            'is_editable'        => true,
        ];
    }

    protected function getOfficeName()
    {
        if ($this->resource instanceof \App\Models\AdminUser) {
            // Prioritaskan nama dari relasi jika tersedia, baru kemudian kolom string
            return $this->kantorCabang?->name ?? $this->kantor_cabang ?? '-';
        }
        
        // Member (Petugas dari jalur member)
        return $this->city?->name ?? $this->province?->name ?? '-';
    }

    protected function getRegionName()
    {
        if ($this->resource instanceof \App\Models\AdminUser) {
            // Ambil dari relasi KW melalui KC, atau kolom string
            return $this->kantorCabang?->kedeputianWilayah?->name ?? $this->kedeputian_wilayah ?? '-';
        }
        
        // Member
        return $this->province?->name ?? '-';
    }
}
