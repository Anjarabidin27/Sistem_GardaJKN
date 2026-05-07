<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\AdminUser;
use App\Models\BpjsKeliling;
use App\Models\KantorCabang;
use App\Models\KedeputianWilayah;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MasterDummySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Master Wilayah
        $kwJabar = KedeputianWilayah::firstOrCreate(['name' => 'REGIONAL III - BANDUNG']);

        $provJabar = \App\Models\Province::updateOrCreate(['name' => 'JAWA BARAT'], ['code' => '32']);
        $cityBandung = \App\Models\City::updateOrCreate(['name' => 'KOTA BANDUNG'], ['province_id' => $provJabar->id, 'code' => '3273']);
        $distBandung = \App\Models\District::updateOrCreate(['name' => 'COBLONG'], ['city_id' => $cityBandung->id, 'code' => '327301']);

        $cityBogor = \App\Models\City::updateOrCreate(['name' => 'KOTA BOGOR'], ['province_id' => $provJabar->id, 'code' => '3271']);
        $distBogor = \App\Models\District::updateOrCreate(['name' => 'BOGOR TENGAH'], ['city_id' => $cityBogor->id, 'code' => '327101']);

        $kcBandung = KantorCabang::firstOrCreate(['name' => 'BANDUNG'], ['kedeputian_wilayah_id' => $kwJabar->id, 'province_id' => $provJabar->id, 'city_id' => $cityBandung->id]);
        $kcBogor = KantorCabang::firstOrCreate(['name' => 'BOGOR'], ['kedeputian_wilayah_id' => $kwJabar->id, 'province_id' => $provJabar->id, 'city_id' => $cityBogor->id]);

        // Admin Wilayah Jabar
        AdminUser::updateOrCreate(['username' => 'kenwil_bandung'], [
            'name' => 'Ibu Regional Bandung', 'password' => Hash::make('GardaJKN2026!'), 'role' => 'admin_wilayah',
            'kedeputian_wilayah' => $kwJabar->name, 'kantor_cabang' => $kcBandung->name, 'kantor_cabang_id' => $kcBandung->id, 'zona_waktu' => 'WIB'
        ]);

        // Petugas Jabar
        AdminUser::updateOrCreate(['username' => 'petugas_bandung'], [
            'name' => 'Rudi Petugas Bandung', 'password' => Hash::make('GardaJKN2026!'), 'role' => 'petugas_keliling',
            'kedeputian_wilayah' => $kwJabar->name, 'kantor_cabang' => $kcBandung->name, 'kantor_cabang_id' => $kcBandung->id, 'zona_waktu' => 'WIB'
        ]);

        // Member Jabar
        $dummyMembersJabar = [
            ['name' => 'Agus Permana', 'nik' => '3273010101850001', 'status' => 'aktif', 'role' => 'anggota', 'kc_id' => $kcBandung->id, 'city_id' => $cityBandung->id, 'dist_id' => $distBandung->id],
            ['name' => 'Dewi Rahayu', 'nik' => '3273010101900002', 'status' => 'pendaftaran_diterima', 'role' => 'anggota', 'kc_id' => $kcBandung->id, 'city_id' => $cityBandung->id, 'dist_id' => $distBandung->id],
            ['name' => 'Hendra Saputra', 'nik' => '3271010101880001', 'status' => 'aktif', 'role' => 'pengurus', 'kc_id' => $kcBogor->id, 'city_id' => $cityBogor->id, 'dist_id' => $distBogor->id],
        ];

        foreach ($dummyMembersJabar as $m) {
            Member::updateOrCreate(['nik' => $m['nik']], [
                'name' => $m['name'], 'password' => Hash::make('GardaJKN2026!'), 'phone' => '08121' . rand(1000000, 9999999),
                'gender' => 'L', 'occupation' => 'Karyawan Swasta', 'education' => 'S1/D4',
                'province_id' => $provJabar->id, 'city_id' => $m['city_id'], 'district_id' => $m['dist_id'],
                'address_detail' => 'Alamat Lengkap Jabar', 'status_pengurus' => $m['status'], 'role' => $m['role'],
                'is_interested_pengurus' => ($m['status'] === 'pendaftaran_diterima'),
                'interest_keliling' => ($m['status'] === 'pendaftaran_diterima'),
                'kantor_cabang_id' => $m['kc_id'],
            ]);
        }


        // Jawa Tengah
        $kwJateng = KedeputianWilayah::firstOrCreate(['name' => 'REGIONAL VI - SEMARANG']);

        $provJateng = \App\Models\Province::updateOrCreate(['name' => 'JAWA TENGAH'], ['code' => '33']);
        $citySemarang = \App\Models\City::updateOrCreate(['name' => 'KOTA SEMARANG'], ['province_id' => $provJateng->id, 'code' => '3374']);
        $distSemarang = \App\Models\District::updateOrCreate(['name' => 'SEMARANG TENGAH'], ['city_id' => $citySemarang->id, 'code' => '337401']);
        
        $cityDemak = \App\Models\City::updateOrCreate(['name' => 'KABUPATEN DEMAK'], ['province_id' => $provJateng->id, 'code' => '3321']);
        $distDemak = \App\Models\District::updateOrCreate(['name' => 'DEMAK KOTA'], ['city_id' => $cityDemak->id, 'code' => '332101']);
        
        $cityPati = \App\Models\City::updateOrCreate(['name' => 'KABUPATEN PATI'], ['province_id' => $provJateng->id, 'code' => '3318']);
        $distPati = \App\Models\District::updateOrCreate(['name' => 'PATI KOTA'], ['city_id' => $cityPati->id, 'code' => '331801']);

        $kcSemarang = KantorCabang::firstOrCreate(['name' => 'SEMARANG'], ['kedeputian_wilayah_id' => $kwJateng->id]);
        $kcPati = KantorCabang::firstOrCreate(['name' => 'PATI'], ['kedeputian_wilayah_id' => $kwJateng->id]);

        // 2. Admin & Staff
        AdminUser::updateOrCreate(['username' => 'superadmin'], [
            'name' => 'Administrator Utama', 'password' => Hash::make('GardaJKN2026!'), 'role' => 'superadmin',
            'zona_waktu' => 'WIB'
        ]);

        AdminUser::updateOrCreate(['username' => 'kenwil_semarang'], [
            'name' => 'Bpk. Regional Semarang', 'password' => Hash::make('GardaJKN2026!'), 'role' => 'admin_wilayah',
            'kedeputian_wilayah' => $kwJateng->name, 'kantor_cabang' => $kcSemarang->name, 'kantor_cabang_id' => $kcSemarang->id, 'zona_waktu' => 'WIB'
        ]);

        AdminUser::updateOrCreate(['username' => 'petugas_semarang'], [
            'name' => 'Anjar Petugas Lapangan', 'password' => Hash::make('GardaJKN2026!'), 'role' => 'petugas_keliling',
            'kedeputian_wilayah' => $kwJateng->name, 'kantor_cabang' => $kcSemarang->name, 'kantor_cabang_id' => $kcSemarang->id, 'zona_waktu' => 'WIB'
        ]);

        // 3. Members
        $dummyMembers = [
            ['name' => 'Budi Santoso', 'nik' => '3374010101800001', 'status' => 'aktif', 'role' => 'pengurus'],
            ['name' => 'Siti Aminah', 'nik' => '3374010101800002', 'status' => 'pendaftaran_diterima', 'role' => 'anggota'],
            ['name' => 'Joko Widodo (Dummy)', 'nik' => '3318010101800003', 'status' => 'aktif', 'role' => 'anggota'],
            ['name' => 'Andi Wijaya', 'nik' => '3321010101900001', 'status' => 'tidak_mendaftar', 'role' => 'anggota'],
        ];

        foreach ($dummyMembers as $m) {
            $prefixCity = substr($m['nik'], 0, 4);
            $currentCity = ($prefixCity == '3374') ? $citySemarang : (($prefixCity == '3321') ? $cityDemak : $cityPati);
            $currentDist = ($prefixCity == '3374') ? $distSemarang : (($prefixCity == '3321') ? $distDemak : $distPati);

            Member::updateOrCreate(['nik' => $m['nik']], [
                'name' => $m['name'], 'password' => Hash::make('GardaJKN2026!'), 'phone' => '08956' . rand(1000000, 9999999),
                'gender' => 'L', 'occupation' => 'Karyawan Swasta', 'education' => 'S1/D4',
                'province_id' => $provJateng->id, 'city_id' => $currentCity->id, 'district_id' => $currentDist->id,
                'address_detail' => 'Alamat Lengkap', 'status_pengurus' => $m['status'], 'role' => $m['role'],
                'is_interested_pengurus' => ($m['status'] === 'pendaftaran_diterima'),
                'interest_pil' => ($m['status'] === 'pendaftaran_diterima'),
                'kantor_cabang_id' => ($prefixCity == '3374' || $prefixCity == '3321') ? $kcSemarang->id : $kcPati->id,
            ]);
        }

        // 4. BPJS Keliling
        $kegiatan = BpjsKeliling::updateOrCreate(['judul' => 'BPJS Keliling Desa Karangayu'], [
            'jenis_kegiatan' => 'goes_to_village', 'tanggal' => now()->subDays(2)->format('Y-m-d'),
            'lokasi_detail' => 'Balai Kelurahan Karangayu', 'jumlah_petugas' => 2, 'status' => 'completed',
            'kantor_cabang' => $kcSemarang->name, 'kedeputian_wilayah' => $kwJateng->name, 'jam_mulai' => '08:00', 'jam_selesai' => '12:00',
            'rata_nps_ketertarikan' => 95.0, 'rata_nps_rekomendasi_program' => 94.0, 'rata_nps_rekomendasi_bpjs' => 96.0,
            'catatan' => 'Lancar'
        ]);

        // 5. Participants (insertOrIgnore agar aman jika dijalankan 2x)
        \DB::table('bpjs_keliling_participants')->insertOrIgnore([
            [
                'bpjs_keliling_id' => $kegiatan->id, 'name' => 'Budi Santoso', 'nik' => '3374010101800001',
                'jenis_layanan' => 'PENDAFTARAN', 'nps_ketertarikan' => 5, 'nps_rekomendasi_program' => 5, 'nps_rekomendasi_bpjs' => 5, 'created_at' => now()
            ],
            [
                'bpjs_keliling_id' => $kegiatan->id, 'name' => 'Siti Aminah', 'nik' => '3374010101800002',
                'jenis_layanan' => 'PENDAFTARAN', 'nps_ketertarikan' => 4, 'nps_rekomendasi_program' => 5, 'nps_rekomendasi_bpjs' => 5, 'created_at' => now()
            ]
        ]);
    }
}
