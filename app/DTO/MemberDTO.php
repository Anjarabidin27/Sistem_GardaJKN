<?php

namespace App\DTO;

class MemberDTO
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?string $phone = null,
        public readonly ?string $gender = null,
        public readonly ?string $education = null,
        public readonly ?string $occupation = null,
        public readonly ?int $province_id = null,
        public readonly ?int $city_id = null,
        public readonly ?int $district_id = null,
        public readonly ?string $address_detail = null,
        public readonly ?string $nik = null,
        public readonly ?string $password = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'] ?? null,
            phone: $data['phone'] ?? null,
            gender: $data['gender'] ?? null,
            education: $data['education'] ?? null,
            occupation: $data['occupation'] ?? null,
            province_id: $data['province_id'] ?? null,
            city_id: $data['city_id'] ?? null,
            district_id: $data['district_id'] ?? null,
            address_detail: $data['address_detail'] ?? null,
            nik: $data['nik'] ?? null,
            password: $data['password'] ?? null,
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
            'name' => $this->name,
            'phone' => $this->phone,
            'gender' => $this->gender,
            'education' => $this->education,
            'occupation' => $this->occupation,
            'province_id' => $this->province_id,
            'city_id' => $this->city_id,
            'district_id' => $this->district_id,
            'address_detail' => $this->address_detail,
            'password' => $this->password,
        ], fn($val) => $val !== null);
    }
}
