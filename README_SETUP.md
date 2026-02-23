# Panduan Setup Sistem Garda JKN (Backend)

Sistem ini dibangun menggunakan Laravel 12 dengan standar Enterprise Architecture.

## Prasyarat
- PHP 8.3+
- Composer
- PostgreSQL 14+

## Langkah Instalasi Database

Karena konfigurasi `.env` sudah diset untuk PostgreSQL (`DB_CONNECTION=pgsql`), ikuti langkah ini:

1.  **Pastikan PostgreSQL Berjalan**
    Buka pgAdmin atau terminal psql Anda.

2.  **Buat Database**
    Jalankan perintah SQL berikut di pgAdmin/psql:
    ```sql
    CREATE DATABASE sistem_garda_jkn;
    ```

3.  **Konfigurasi Akun**
    Pastikan file `.env` sesuai dengan kredensial PostgreSQL Anda:
    ```env
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=sistem_garda_jkn
    DB_USERNAME=postgres  <-- Ganti jika beda
    DB_PASSWORD=          <-- Isi password postgres Anda
    ```

4.  **Jalankan Migrasi & Seeding**
    Buka terminal di folder proyek ini:
    ```bash
    php artisan migrate:fresh --seed
    ```
    Ini akan membuat semua tabel dan mengisi data dummy:
    - **Admin**: username `admin`, password `password`
    - **Member**: 50 data dummy dengan NIK valid.
    - **Wilayah**: 1 Provinsi, 1 Kota, 1 Kecamatan (Dummy).

## Testing API
Gunakan Postman atau Insomnia.

**Login Admin:**
`POST /api/admin/login`
```json
{
    "username": "admin",
    "password": "password"
}
```

**Login Member:**
`POST /api/member/login`
```json
{
    "nik": "3171010101900000",
    "password": "password"
}
```
