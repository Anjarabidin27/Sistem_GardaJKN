<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Seeder;

class FixRoleSeeder extends Seeder
{
    public function run(): void
    {
        $m = Member::where('nik', '3171010101900000')->first();
        if ($m) {
            $m->role = 'pengurus';
            $m->status_pengurus = 'aktif';
            $m->save();
            echo "Vini Jr is now Pengurus\n";
        } else {
            echo "Vini Jr not found\n";
        }
    }
}
