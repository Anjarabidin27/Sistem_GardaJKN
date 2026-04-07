# Garda JKN - Enterprise Design System
**Versi**: 1.1.0
**Filosofi**: Modern, Profesional, Data-Centric

Sistem desain ini bertujuan untuk menciptakan antarmuka yang konsisten, bersih padasisi visual, dan efisien pada fungsionalitas pengumpulan data.

## 1. Palet Warna (Sistem Token)

Kami menggunakan sistem token warna dengan tingkat kontras tinggi untuk fleksibilitas tema operasional.

### Warna Utama (Identitas Institusi)
- **Primary**: `#004AAD` (Deep Blue) - *Sidebar, Tombol Utama.*
- **Primary Hover**: `#003A8C` (Darker Blue) - *Interaksi Hover.*
- **Accent**: `#3B82F6` (Modern Blue) - *Highlight & Link.*

### Warna Fungsional (Status & Sinyal)
- **Success**: `#10B981` (Emerald) - *Status Selesai, Toast Sukses.*
- **Warning**: `#F59E0B` (Amber) - *Status Proses, Notifikasi Peringatan.*
- **Danger**: `#EF4444` (Red) - *Status Batal, Tombol Hapus, Pesan Error.*
- **Info**: `#3B82F6` (Blue) - *Bantuan dan Tooltips.*

### Warna Netral & Permukaan
- **Body Background**: `#F1F5F9` (Slate 100)
- **Surface (Card/Modal)**: `#FFFFFF`
- **Teks Utama**: `#0F172A` (Slate 900)
- **Teks Sekunder**: `#64748B` (Slate 500)
- **Border/Garis**: `#E2E8F0` (Slate 200)

## 2. Sistem Tipografi

Tipografi difokuskan pada keterbacaan tinggi untuk data numerik dan teks panjang.

- **Primary Font**: `Plus Jakarta Sans` (Navigasi & Data UI)
- **Heading Font**: `Outfit` (Branding & Judul Seksi)
- **Fallback**: `Inter`, `sans-serif`

### Skala Tipografi
- **H1 Header**: Bold (800) / 32px (Outfit)
- **H2 Page Title**: Bold (700) / 24px (Outfit)
- **Body Base**: Regular (400) / 16px (Jakarta Sans)
- **Label Form**: Bold (700) / 12px (Jakarta Sans, Uppercase)
- **Table Data**: Medium (500) / 14px (Jakarta Sans)

## 3. Sistem Jarak (Gridding)

Layout menggunakan grid berkelipatan 8px (8pt system) untuk konsistensi.

- **Padding Tabel**: 10px (Vertical) - Mengurangi pemborosan ruang pada layar data.
- **Padding Modal**: 24px - Ruang napas antarmuka di tengah layar.
- **Sidebar Width**: 260px - Optimal untuk navigasi tanpa memakan area kerja utama.

## 4. Spesifikasi Komponen

### Tombol (Buttons)
- **Shape**: Rounded (Border Radius: 12px).
- **Weight**: Semibold (600).
- **Transition**: All 0.2s cubic-bezier.

### Kartu (Cards)
- **Design**: White background, Thin Border (Slate 200), Soft Shadow.
- **Function**: Container utama untuk data dan form.

### Form (Inputs)
- **Style**: Minimalist outlined dengan border 1.5px.
- **Focus State**: Border warna Primary disertai Shadow Ring halus (Blur).

## 5. Micro-interactions

1.  **Modal Transitions**: Animasi 'In' dari bawah ke atas dengan skala masuk yang halus untuk kesan premium.
2.  **Notification (Toasts)**: Muncul di pojok kanan atas dengan bar progres otomatis untuk menghitung durasi visibilitas.
3.  **Loading Line**: Garis biru progres bergerak cepat di bagian paling atas layar selama permintaan API berlangsung.

---
**Garda JKN UI/UX Standard**
