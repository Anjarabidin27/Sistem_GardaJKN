<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        AdminUser::updateOrCreate(
            ['username' => 'superadmin'],
            [
                'password' => 'GardaAdmin2026!!',
                'name' => 'Super Administrator',
                'role' => 'superadmin',
                'kedeputian_wilayah' => 'KANTOR PUSAT',
                'kantor_cabang' => 'JAKARTA PUSAT',
                'zona_waktu' => 'WIB',
            ]
        );

        // Petugas BPJS Keliling (WIB)
        AdminUser::updateOrCreate(
            ['username' => 'admin_keliling'],
            [
                'password' => 'GardaKeliling2026!!',
                'name' => 'Petugas BPJS Keliling',
                'role' => 'petugas_keliling',
                'kedeputian_wilayah' => '05 - Jawa Barat',
                'kantor_cabang' => 'KC Bandung',
                'zona_waktu' => 'WIB',
            ]
        );

        // Petugas PIL (WITA)
        AdminUser::updateOrCreate(
            ['username' => 'admin_pil'],
            [
                'password' => 'GardaPil2026!!',
                'name' => 'Petugas Penyuluhan (PIL)',
                'role' => 'petugas_pil',
                'kedeputian_wilayah' => '09 - Sulselbartra dan Maluku',
                'kantor_cabang' => 'KC Makassar',
                'zona_waktu' => 'WITA',
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

