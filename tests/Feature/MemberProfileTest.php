<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\City;
use App\Models\District;
use App\Models\Member;
use App\Models\Province;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class MemberProfileTest extends TestCase
{
    use RefreshDatabase;

    protected $member;
    protected $prov;
    protected $city;
    protected $dist;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->prov = Province::create(['code' => '32', 'name' => 'Jawa Barat']);
        $this->city = City::create(['province_id' => $this->prov->id, 'code' => '3271', 'name' => 'Kota Bandung', 'type' => 'KOTA']);
        $this->dist = District::create(['city_id' => $this->city->id, 'code' => '327101', 'name' => 'Kec. Sukajadi']);

        $this->member = Member::create([
            'nik' => '1212312312312312',
            'name' => 'Member Test',
            'phone' => '08123456789',
            'gender' => 'L',
            'education' => 'SMA',
            'occupation' => 'Freelancer',
            'address_detail' => 'Bandung, Jawa Barat',
            'province_id' => $this->prov->id,
            'city_id' => $this->city->id,
            'district_id' => $this->dist->id,
            'password' => 'secret',
        ]);
    }

    public function test_update_profile_creates_audit_log()
    {
        Sanctum::actingAs($this->member);

        $response = $this->putJson('/api/member/profile', [
            'name' => 'Member Updated',
            'phone' => '082121212121',
            'gender' => 'L',
            'education' => 'S1/D4',
            'occupation' => 'Software Engineer',
            'address_detail' => 'Bandung Updated',
            'province_id' => $this->prov->id,
            'city_id' => $this->city->id,
            'district_id' => $this->dist->id,
        ]);

        $response->assertStatus(200);

        // Verify Data Changed
        $this->assertDatabaseHas('members', [
            'id' => $this->member->id,
            'education' => 'S1/D4',
            'occupation' => 'Software Engineer',
        ]);

        // Verify Audit Log
        $this->assertDatabaseHas('audit_logs', [
            'actor_type' => 'member',
            'actor_id' => $this->member->id,
            'action' => 'update_profile',
            'entity_type' => 'member',
            'entity_id' => $this->member->id,
        ]);
        
        // Cek isi JSON changes jika diperlukan
        $log = AuditLog::latest()->first();
        $this->assertArrayHasKey('education', $log->changes_json);
        // Cast or decode json field from array to check key content if needed
    }
}
