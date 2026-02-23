<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\Province;
use App\Models\City;
use App\Models\District;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MemberAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Setup Master Data untuk Foreign Key constraints
        $this->province = Province::create(['code' => '31', 'name' => 'DKI JAKARTA']);
        $this->city = City::create(['province_id' => $this->province->id, 'code' => '3171', 'name' => 'JAKARTA SELATAN', 'type' => 'KOTA']);
        $this->district = District::create(['city_id' => $this->city->id, 'code' => '317101', 'name' => 'TEBET']);
    }

    /**
     * A basic feature test example.
     */
    public function test_member_can_login_with_valid_credentials(): void
    {
        $member = Member::create([
            'nik' => '1234567890123456',
            'password' => 'password', // Hashed automatically thanks to cast
            'name' => 'Test User',
            'phone' => '081234567890',
            'gender' => 'L',
            'education' => 'S1/D4',
            'occupation' => 'Karyawan',
            'address_detail' => 'Jl. Test',
            'province_id' => $this->province->id,
            'city_id' => $this->city->id,
            'district_id' => $this->district->id,
        ]);

        $response = $this->postJson('/api/member/login', [
            'nik' => '1234567890123456',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'token',
                    'member'
                ]
            ]);
    }

    public function test_member_cannot_login_with_invalid_credentials(): void
    {
        $response = $this->postJson('/api/member/login', [
            'nik' => '1234567890123456',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401);
    }
}
