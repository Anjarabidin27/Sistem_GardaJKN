<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        AdminUser::updateOrCreate(
            ['username' => 'admin'],
            [
                'password' => 'password',
                'name' => 'Super Admin',
                'role' => 'superadmin',
            ]
        );
    }
}
