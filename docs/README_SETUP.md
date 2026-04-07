# Panduan Setup Sistem Garda JKN (Backend)

Sistem ini dibangun menggunakan Laravel 12 dengan standar Enterprise Architecture.

## Prasyarat
- PHP 8.3+
- Composer
- MySQL 8.0+ / MariaDB

## Langkah Instalasi Database

Sistem ini sekarang dikonfigurasi menggunakan **MySQL**. Ikuti langkah ini:

1.  **Pastikan MySQL Berjalan**
    Buka phpMyAdmin, HeidiSQL, atau terminal MySQL Anda.

2.  **Buat Database**
    Jalankan perintah SQL berikut:
    ```sql
    CREATE DATABASE sistem_garda_jkn;
    ```

3.  **Konfigurasi Akun**
    Pastikan file `.env` sesuai dengan kredensial MySQL Anda (biasanya root tanpa password di lokal):
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=sistem_garda_jkn
    DB_USERNAME=root
    DB_PASSWORD=
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
