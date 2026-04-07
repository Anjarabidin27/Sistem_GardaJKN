# Sistem Garda JKN

Sistem manajemen pelaporan kegiatan BPJS Keliling dan Pemberian Informasi Langsung (PIL) yang terintegrasi dengan data wilayah Indonesia dan sistem pelaporan Master-Detail. Proyek ini dibangun menggunakan Laravel 12 dengan standar Enterprise Architecture.

## Fitur Utama

- **Manajemen BPJS Keliling**: Pencatatan kegiatan operasional lapangan dengan pendataan peserta (NIK, Segmen, Jenis Layanan, Transaksi, dan Kepuasan Pelanggan).
- **Pemberian Informasi Langsung (PIL)**: Edukasi masyarakat dengan fitur penilaian pemahaman peserta dan evaluasi Net Promoter Score (NPS).
- **Dashboard Real-time**: Agregasi data otomatis untuk persentase layanan, status transaksi, dan tingkat kepuasan wilayah.
- **Integrasi Wilayah**: Sinkronisasi dengan basis data wilayah (Provinsi, Kota, Kecamatan) untuk pemetaan lokasi kegiatan yang akurat.

## Struktur Proyek (Pristine Standard)

Proyek ini telah dikelola mengikuti standar profesional untuk kemudahan pemeliharaan jangka panjang:

- `app/Models/`: Model database teroptimasi (BPJS Keliling, PIL, Member, Wilayah).
- `database/migrations/`: Migrasi terstruktur dengan penamaan formal dan urutan eksekusi yang konsisten.
- `docs/`: Dokumentasi teknis pusat (Design System, Panduan Setup, OpenAPI).
- `resources/views/`: Antarmuka administratif yang responsif dengan optimasi visual produksi.

## Panduan Instalasi Lokal

1.  Clone repositori ke lingkungan lokal Anda.
2.  Jalankan perintah `composer install` dan `npm install`.
3.  Konfigurasi file `.env` (Database MySQL).
4.  Jalankan migrasi dan seeding data awal:
    ```bash
    php artisan migrate:fresh --seed
    ```
5.  Jalankan server pengembangan:
    ```bash
    php artisan serve
    npm run dev
    ```

## Dokumentasi Teknis

Informasi lebih lanjut dapat ditemukan pada direktori `docs/`:

- Spesifikasi Desain (`DESIGN_SYSTEM.md`)
- Panduan Instalasi Backend (`README_SETUP.md`)
- Spesifikasi API (`openapi.yaml`)

---
**Garda JKN Development Team**
