<?php

namespace Tests\Feature;

use App\Models\AdminUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login()
    {
        $admin = AdminUser::create([
            'username' => 'admin',
            'password' => 'password',
            'name' => 'Super Admin',
            'role' => 'admin',
        ]);

        $response = $this->postJson('/api/admin/login', [
            'username' => 'admin',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => ['token']]);
    }
}
