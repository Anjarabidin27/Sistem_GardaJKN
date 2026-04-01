<?php

namespace App\DTO;

class MemberDTO
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?string $phone = null,
        public readonly ?string $birth_date = null,
        public readonly ?string $gender = null,
        public readonly ?string $education = null,
        public readonly ?string $occupation = null,
        public readonly ?int $province_id = null,
        public readonly ?int $city_id = null,
        public readonly ?int $district_id = null,
        public readonly ?string $address_detail = null,
        public readonly ?int $dom_province_id = null,
        public readonly ?int $dom_city_id = null,
        public readonly ?int $dom_district_id = null,
        public readonly ?string $dom_address_detail = null,
        public readonly ?string $nik = null,
        public readonly ?string $jkn_number = null,
        public readonly ?string $password = null,
        public readonly mixed $photo = null,
        public readonly ?string $role = null,
        public readonly ?string $status_pengurus = null,
        public readonly ?bool $is_interested_pengurus = null,
        public readonly ?bool $has_org_experience = null,
        public readonly ?int $org_count = null,
        public readonly ?string $org_name = null,
        public readonly ?string $org_position = null,
        public readonly ?int $org_duration_months = null,
        public readonly ?string $org_description = null,
        public readonly ?string $pengurus_reason = null,
        public readonly ?string $org_certificate_path = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'] ?? null,
            phone: $data['phone'] ?? null,
            birth_date: $data['birth_date'] ?? null,
            gender: $data['gender'] ?? null,
            education: $data['education'] ?? null,
            occupation: $data['occupation'] ?? null,
            province_id: $data['province_id'] ?? null,
            city_id: $data['city_id'] ?? null,
            district_id: $data['district_id'] ?? null,
            address_detail: $data['address_detail'] ?? null,
            dom_province_id: $data['dom_province_id'] ?? null,
            dom_city_id: $data['dom_city_id'] ?? null,
            dom_district_id: $data['dom_district_id'] ?? null,
            dom_address_detail: $data['dom_address_detail'] ?? null,
            nik: $data['nik'] ?? null,
            jkn_number: $data['jkn_number'] ?? null,
            password: $data['password'] ?? null,
            photo: $data['photo'] ?? null,
            role: $data['role'] ?? null,
            status_pengurus: $data['status_pengurus'] ?? null,
            is_interested_pengurus: isset($data['is_interested_pengurus']) ? (bool)$data['is_interested_pengurus'] : null,
            has_org_experience: isset($data['has_org_experience']) ? (bool)$data['has_org_experience'] : null,
            org_count: isset($data['org_count']) ? (int)$data['org_count'] : null,
            org_name: $data['org_name'] ?? null,
            org_position: $data['org_position'] ?? null,
            org_duration_months: isset($data['org_duration_months']) ? (int)$data['org_duration_months'] : null,
            org_description: $data['org_description'] ?? null,
            pengurus_reason: $data['pengurus_reason'] ?? null,
            org_certificate_path: $data['org_certificate_path'] ?? null,
        );
    }

    public function toArray(): array
    {
        $nik = $this->nik;
        if ($nik && str_contains($nik, '*')) {
            $nik = null; // Ignore masked NIK
        }

        return array_filter([
            'nik' => $nik,
            'jkn_number' => $this->jkn_number,
            'name' => $this->name,
            'phone' => $this->phone,
            'birth_date' => $this->birth_date,
            'gender' => $this->gender,
            'education' => $this->education,
            'occupation' => $this->occupation,
            'province_id' => $this->province_id,
            'city_id' => $this->city_id,
            'district_id' => $this->district_id,
            'address_detail' => $this->address_detail,
            'dom_province_id' => $this->dom_province_id,
            'dom_city_id' => $this->dom_city_id,
            'dom_district_id' => $this->dom_district_id,
            'dom_address_detail' => $this->dom_address_detail,
            'password' => $this->password,
            'role' => $this->role,
            'status_pengurus' => $this->status_pengurus,
            'is_interested_pengurus' => $this->is_interested_pengurus,
            'has_org_experience' => $this->has_org_experience,
            'org_count' => $this->org_count,
            'org_name' => $this->org_name,
            'org_position' => $this->org_position,
            'org_duration_months' => $this->org_duration_months,
            'org_description' => $this->org_description,
            'pengurus_reason' => $this->pengurus_reason,
            'org_certificate_path' => $this->org_certificate_path,
        ], fn($val) => $val !== null);
    }
}
