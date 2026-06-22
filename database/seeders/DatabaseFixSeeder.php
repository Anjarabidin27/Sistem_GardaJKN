<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminUser;
use App\Models\KantorCabang;
use App\Models\City;

/**
 * DatabaseFixSeeder
 *
 * Tujuan: Memperbaiki struktur data LAMA agar kompatibel dengan kode BARU
 * tanpa menghapus data kegiatan, laporan, member, dll yang sudah ada.
 *
 * Cara pakai di VPS:
 *   php artisan db:seed --class=DatabaseFixSeeder
 *
 * Aman dijalankan berkali-kali (idempotent).
 */
class DatabaseFixSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('========================================');
        $this->command->info('  DATABASE FIX SEEDER - GARDA JKN');
        $this->command->info('========================================');

        $this->fixKantorCabangProvince();
        $this->fixAdminUserRoles();
        $this->fixAdminUserKantorCabangId();

        $this->command->info('');
        $this->command->info('✅ Semua perbaikan selesai!');
        $this->command->info('========================================');
    }

    /**
     * Fix 1: Isi province_id di tabel kantor_cabangs yang masih null
     * dengan mengambil dari data City berdasarkan city_id.
     */
    private function fixKantorCabangProvince(): void
    {
        $this->command->info('');
        $this->command->info('📍 [Fix 1] Memperbaiki province_id di KantorCabang...');

        $fixed = 0;

        // KC yang punya city_id tapi province_id null
        $kcs = KantorCabang::whereNull('province_id')->whereNotNull('city_id')->get();
        foreach ($kcs as $kc) {
            $city = City::find($kc->city_id);
            if ($city && $city->province_id) {
                $kc->province_id = $city->province_id;
                $kc->save();
                $fixed++;
                $this->command->line("   ✔ KC [{$kc->name}] → Province ID: {$city->province_id}");
            }
        }

        // KC yang city_id juga null, coba cocokkan dari nama KC
        $kcsNoCityId = KantorCabang::whereNull('province_id')->whereNull('city_id')->get();
        foreach ($kcsNoCityId as $kc) {
            $kcNameUpper = strtoupper(trim($kc->name));
            
            // Coba exact match dulu
            $city = City::whereRaw("UPPER(REPLACE(REPLACE(name, 'KOTA ', ''), 'KABUPATEN ', '')) = ?", [$kcNameUpper])->first();
            
            // Fallback: nama kota mengandung nama KC secara penuh
            if (!$city) {
                $city = City::where('name', 'LIKE', '%' . $kcNameUpper . '%')->first();
            }
            
            if ($city) {
                $kc->city_id    = $city->id;
                $kc->province_id = $city->province_id;
                $kc->save();
                $fixed++;
                $this->command->line("   ✔ KC [{$kc->name}] → City: {$city->name}, Province ID: {$city->province_id}");
            } else {
                $this->command->warn("   ⚠ KC [{$kc->name}] tidak bisa dicocokkan ke kota manapun (perlu input manual)");
            }
        }

        $this->command->info("   Total KC diperbaiki: {$fixed}");
    }

    /**
     * Fix 2: Normalisasi role AdminUser dari format lama ke format baru.
     *
     * Mapping:
     *   admin_regional  → admin_wilayah
     *   regional        → admin_wilayah
     *   admin_cabang    → petugas_keliling
     *   staff           → petugas_keliling
     *   pegawai         → petugas_keliling
     *   petugas         → petugas_keliling
     *   frontliner      → petugas_keliling
     */
    private function fixAdminUserRoles(): void
    {
        $this->command->info('');
        $this->command->info('👤 [Fix 2] Normalisasi role AdminUser...');

        $roleMap = [
            'admin_regional' => 'admin_wilayah',
            'regional'       => 'admin_wilayah',
            'admin_cabang'   => 'petugas_keliling',
            'staff'          => 'petugas_keliling',
            'pegawai'        => 'petugas_keliling',
            'petugas'        => 'petugas_keliling',
            'frontliner'     => 'petugas_keliling',
        ];

        $fixed = 0;
        foreach ($roleMap as $oldRole => $newRole) {
            $count = AdminUser::where('role', $oldRole)->count();
            if ($count > 0) {
                AdminUser::where('role', $oldRole)->update(['role' => $newRole]);
                $fixed += $count;
                $this->command->line("   ✔ Role [{$oldRole}] → [{$newRole}] ({$count} user)");
            }
        }

        // Pastikan ada minimal 1 superadmin
        if (AdminUser::where('role', 'superadmin')->count() === 0) {
            $this->command->warn('   ⚠ Tidak ada superadmin! Mencari user admin untuk di-upgrade...');
            $firstAdmin = AdminUser::where('role', 'admin')->first();
            if ($firstAdmin) {
                $firstAdmin->role = 'superadmin';
                $firstAdmin->save();
                $this->command->line("   ✔ User [{$firstAdmin->name}] dijadikan superadmin");
            }
        }

        $this->command->info($fixed === 0
            ? '   ℹ Tidak ada role lama yang perlu diperbaiki.'
            : "   Total user diperbaiki: {$fixed}");
    }

    /**
     * Fix 3: Isi kantor_cabang_id di AdminUser yang masih null
     * dengan mencocokkan kolom kantor_cabang (string) ke tabel kantor_cabangs.
     * Juga sinkronkan kedeputian_wilayah jika masih kosong.
     */
    private function fixAdminUserKantorCabangId(): void
    {
        $this->command->info('');
        $this->command->info('🏢 [Fix 3] Menghubungkan AdminUser ke KantorCabang...');

        $users = AdminUser::whereNull('kantor_cabang_id')
                          ->whereNotNull('kantor_cabang')
                          ->where('kantor_cabang', '!=', '')
                          ->get();

        $fixed = 0;
        foreach ($users as $user) {
            $kc = KantorCabang::where('name', 'LIKE', '%' . strtoupper(trim($user->kantor_cabang)) . '%')->first();
            if (!$kc) {
                $firstWord = explode(' ', strtoupper(trim($user->kantor_cabang)))[0];
                if (strlen($firstWord) > 3) {
                    $kc = KantorCabang::where('name', 'LIKE', '%' . $firstWord . '%')->first();
                }
            }
            if ($kc) {
                $user->kantor_cabang_id = $kc->id;
                if (!$user->kedeputian_wilayah && $kc->kedeputianWilayah) {
                    $user->kedeputian_wilayah = $kc->kedeputianWilayah->name;
                }
                $user->save();
                $fixed++;
                $this->command->line("   ✔ User [{$user->name}] → KC [{$kc->name}] (ID: {$kc->id})");
            } else {
                $this->command->warn("   ⚠ User [{$user->name}] - KC [{$user->kantor_cabang}] tidak ditemukan");
            }
        }

        $this->command->info($fixed === 0
            ? '   ℹ Tidak ada user yang perlu diperbaiki.'
            : "   Total user diperbaiki: {$fixed}");
    }
}

