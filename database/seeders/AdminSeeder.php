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
                'name' => 'Administrator',
                'role' => 'administrator',
            ]
        );

        \App\Models\BpjsKeliling::firstOrCreate(
            ['judul' => 'Layanan Desa Bumiharjo'],
            [
                'jenis_kegiatan' => 'goes_to_village',
                'tanggal' => '2026-04-12',
                'lokasi_detail' => 'Balai Desa Bumiharjo',
                'jumlah_petugas' => 2,
                'status' => 'scheduled',
                'catatan' => 'Kecamatan Keling, Jepara. Fokus pendaftaran peserta baru.',
            ]
        );

        \App\Models\BpjsKeliling::firstOrCreate(
            ['judul' => 'Sosialisasi JKN Mobile'],
            [
                'jenis_kegiatan' => 'around_city',
                'tanggal' => '2026-04-15',
                'lokasi_detail' => 'Pasar Bangsri, Jepara',
                'jumlah_petugas' => 3,
                'status' => 'scheduled',
                'catatan' => 'Pasar Bangsri. Edukasi penggunaan aplikasi JKN Mobile.',
            ]
        );
    }
}

