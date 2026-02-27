@extends('layouts.app')

@section('title', 'Profil Saya - Garda JKN')

@push('styles')
<style>
    /* ===================== MEMBER PORTAL LAYOUT ===================== */
    .member-layout { display: flex; min-height: 100vh; background: #f1f5f9; }

    /* --- Sidebar --- */
    .member-sidebar {
        width: 280px;
        background: #004aad;
        color: white;
        display: flex;
        flex-direction: column;
        position: fixed;
        height: 100vh;
        z-index: 100;
        overflow-y: auto;
        overflow-x: hidden;
    }
    .sb-brand {
        padding: 28px 28px 10px;
    }
    .sb-brand-name { font-size: 1rem; font-weight: 800; color: rgba(255,255,255,0.9); letter-spacing: 0.02em; }
    .sb-brand-sub { font-size: 0.7rem; color: rgba(255,255,255,0.45); font-weight: 500; margin-top: 2px; }

    /* Avatar card in sidebar */
    .sb-avatar-card {
        padding: 10px 28px 24px;
    }
    .sb-avatar-wrap {
        width: 72px; height: 72px;
        border-radius: 18px;
        background: rgba(255,255,255,0.15);
        border: 2px solid rgba(255,255,255,0.25);
        overflow: hidden;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 14px;
    }
    .sb-name { font-size: 1rem; font-weight: 800; color: white; margin-bottom: 4px; line-height: 1.3; }
    .sb-nik { font-size: 0.72rem; color: rgba(255,255,255,0.5); font-weight: 500; }
    .sb-status-pill {
        display: inline-flex; align-items: center; gap: 6px;
        margin-top: 10px;
        background: rgba(16,185,129,0.2);
        border: 1px solid rgba(16,185,129,0.4);
        padding: 4px 10px; border-radius: 50px;
        font-size: 0.65rem; font-weight: 800; color: #6ee7b7;
        text-transform: uppercase; letter-spacing: 0.05em;
    }
    .sb-status-dot { width: 6px; height: 6px; background: #34d399; border-radius: 50%; }

    /* Nav menu */
    .sb-nav { padding: 16px 12px; flex: 1; }
    .sb-section-label {
        font-size: 0.6rem; font-weight: 800; color: rgba(255,255,255,0.3);
        text-transform: uppercase; letter-spacing: 0.12em;
        padding: 0 16px; margin-bottom: 8px; margin-top: 16px;
    }
    .sb-link {
        display: flex; align-items: center; gap: 12px;
        padding: 11px 16px; border-radius: 10px;
        color: rgba(255,255,255,0.65); text-decoration: none;
        font-weight: 600; font-size: 0.85rem;
        margin-bottom: 2px; transition: all 0.15s;
        cursor: pointer; border: none; background: none; width: 100%; text-align: left;
    }
    .sb-link:hover { background: rgba(255,255,255,0.1); color: white; }
    .sb-link.active { background: rgba(255,255,255,0.15); color: white; }
    .sb-link.active .sb-link-icon { color: white; }
    .sb-link-icon { width: 16px; height: 16px; flex-shrink: 0; }

    .sb-footer { padding: 16px 12px; border-top: 1px solid rgba(255,255,255,0.08); }

    /* --- Main Body --- */
    .member-main { margin-left: 280px; flex: 1; display: flex; flex-direction: column; min-height: 100vh; }

    .member-topbar {
        height: 60px;
        background: white;
        border-bottom: 1px solid #e2e8f0;
        padding: 0 32px;
        display: flex; align-items: center; justify-content: space-between;
        position: sticky; top: 0; z-index: 50;
    }
    .topbar-title { font-size: 0.95rem; font-weight: 700; color: #1e293b; }
    .topbar-date { font-size: 0.75rem; color: #94a3b8; font-weight: 500; }

    .member-content { padding: 32px; flex: 1; }

    /* --- Tab Sections --- */
    .tab-content { display: none; animation: fadeIn 0.3s ease; }
    .tab-content.active { display: block; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }

    /* --- Profile Tab Styles --- */
    .profile-hero {
        background: linear-gradient(135deg, #004aad 0%, #002d6a 100%);
        border-radius: 20px;
        padding: 32px;
        display: flex; align-items: center; gap: 24px;
        margin-bottom: 28px;
        position: relative;
        overflow: hidden;
    }
    .profile-hero::before {
        content: '';
        position: absolute; top: -40px; right: -40px;
        width: 200px; height: 200px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }
    .profile-hero-avatar {
        width: 90px; height: 90px; border-radius: 20px;
        background: rgba(255,255,255,0.15);
        border: 3px solid rgba(255,255,255,0.3);
        overflow: hidden; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
    }
    .profile-hero-info { flex: 1; }
    .profile-hero-name { font-size: 1.6rem; font-weight: 800; color: white; margin-bottom: 4px; letter-spacing: -0.01em; }
    .profile-hero-meta { font-size: 0.85rem; color: rgba(255,255,255,0.65); display: flex; gap: 16px; flex-wrap: wrap; }
    .profile-hero-actions { display: flex; gap: 10px; flex-shrink: 0; }

    /* Data cards grid */
    .data-section-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 28px; }
    .data-card {
        background: white; border-radius: 16px; padding: 24px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .data-card-title {
        font-size: 0.65rem; font-weight: 800; color: #94a3b8;
        text-transform: uppercase; letter-spacing: 0.1em;
        margin-bottom: 16px; display: flex; align-items: center; gap: 8px;
        padding-bottom: 12px; border-bottom: 1px solid #f8fafc;
    }
    .data-field { margin-bottom: 16px; }
    .data-field:last-child { margin-bottom: 0; }
    .data-field-label { font-size: 0.68rem; color: #94a3b8; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 4px; }
    .data-field-value { font-size: 0.95rem; font-weight: 700; color: #1e293b; }

    /* Info cards */
    .info-card {
        background: white; border: 1px solid #f1f5f9; border-radius: 16px;
        padding: 16px; cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4,0,0.2,1);
    }
    .info-card:hover { transform: translateY(-4px); box-shadow: 0 12px 24px -8px rgba(0,74,173,0.15); border-color: #93c5fd; }

    /* Form inputs */
    .data-label { font-size: 0.7rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.025em; margin-bottom: 6px; display: flex; align-items: center; gap: 6px; }
    .status-badge { padding: 4px 10px; border-radius: 4px; font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.025em; }

    /* --- Responsive Mobile --- */
    @media (max-width: 768px) {
        .member-sidebar { display: none; }
        .member-main { margin-left: 0; }
        .member-content { padding: 16px; }
        .data-section-grid { grid-template-columns: 1fr; }
        .profile-hero { flex-direction: column; align-items: flex-start; }
        .profile-hero-actions { width: 100%; }
    }
</style>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
@endpush

@section('content')
<div class="member-layout">

    <!-- ==================== SIDEBAR ==================== -->
    <aside class="member-sidebar">
        <!-- Brand -->
        <div class="sb-brand">
            <div class="sb-brand-name">Garda JKN</div>
            <div class="sb-brand-sub">Portal Anggota</div>
        </div>

        <!-- Avatar Section -->
        <div class="sb-avatar-card">
            <div class="sb-avatar-wrap" id="sidebarAvatar">
                <i data-lucide="user" style="width:32px;height:32px;color:rgba(255,255,255,0.5);"></i>
            </div>
            <div class="sb-name" id="sidebarName">Memuat...</div>
            <div class="sb-nik" id="sidebarNik">—</div>
            <div class="sb-status-pill">
                <div class="sb-status-dot"></div>
                Anggota Aktif
            </div>
        </div>

        <!-- Navigation -->
        <nav class="sb-nav" style="flex: 1;">
            <div class="sb-section-label">Menu</div>
            <button class="sb-link active" onclick="switchSection('profil', this)" id="nav-profil">
                <i data-lucide="user-circle" class="sb-link-icon"></i> Profil Saya
            </button>
            <button class="sb-link" onclick="switchSection('informasi', this)" id="nav-informasi">
                <i data-lucide="megaphone" class="sb-link-icon"></i> Informasi
            </button>
            <button class="sb-link" onclick="switchSection('pembayaran', this)" id="nav-pembayaran">
                <i data-lucide="wallet" class="sb-link-icon"></i> Pembayaran
            </button>
            <button class="sb-link" onclick="switchSection('laporan', this)" id="nav-laporan">
                <i data-lucide="clipboard-list" class="sb-link-icon"></i> Laporan Kegiatan
            </button>
            <button class="sb-link" onclick="switchSection('survey', this)" id="nav-survey">
                <i data-lucide="help-circle" class="sb-link-icon"></i> Survey
            </button>
        </nav>

        <!-- Footer: Pengaturan + Logout -->
        <div class="sb-footer">
            <div class="sb-section-label" style="margin-top: 0; margin-bottom: 8px;">Pengaturan</div>
            <a href="/settings" class="sb-link">
                <i data-lucide="settings" class="sb-link-icon"></i> Pengaturan Akun
            </a>
            <button class="sb-link" onclick="logout()" style="color: #fca5a5; margin-top: 4px;">
                <i data-lucide="log-out" class="sb-link-icon" style="color:#fca5a5;"></i> Keluar Sesi
            </button>
        </div>
    </aside>

    <!-- ==================== MAIN BODY ==================== -->
    <main class="member-main">

        <!-- Top Bar -->
        <header class="member-topbar">
            <div class="topbar-title" id="topbarTitle">Profil Saya</div>
            <div class="topbar-date" id="topbarDate"></div>
        </header>

        <!-- Content Area -->
        <div class="member-content">

            <!-- ===== TAB: PROFIL ===== -->
            <div id="section-profil" class="tab-content active">

                <!-- Hero Banner -->
                <div class="profile-hero">
                    <div class="profile-hero-avatar" id="avatarContainer">
                        <i data-lucide="user" style="width:36px;height:36px;color:rgba(255,255,255,0.5);"></i>
                    </div>
                    <div class="profile-hero-info">
                        <h1 class="profile-hero-name" id="nameDisplay">Memuat...</h1>
                        <div class="profile-hero-meta">
                            <span>NIK: <strong id="nikDisplay" style="color:white;">—</strong></span>
                            <span>No. JKN: <strong id="jknDisplay" style="color:white;">—</strong></span>
                        </div>
                    </div>
                    <div class="profile-hero-actions">
                        <button class="btn btn-primary" onclick="openEditModal()" style="background:rgba(255,255,255,0.2); border:1px solid rgba(255,255,255,0.4); color:white; padding:10px 20px; font-weight:700; backdrop-filter:blur(8px);">
                            <i data-lucide="edit-3" style="width:14px;height:14px;"></i> Edit Profil
                        </button>
                    </div>
                </div>

                <!-- Data Grid -->
                <div class="data-section-grid">
                    <!-- Card 1: Kontak -->
                    <div class="data-card">
                        <div class="data-card-title">
                            <i data-lucide="phone" style="width:13px;height:13px;"></i> Kontak
                        </div>
                        <div class="data-field">
                            <div class="data-field-label">No. WhatsApp</div>
                            <div class="data-field-value" id="phoneDisplay">—</div>
                        </div>
                        <div class="data-field">
                            <div class="data-field-label">Tanggal Lahir</div>
                            <div class="data-field-value" id="birthDateDisplay">—</div>
                        </div>
                        <div class="data-field">
                            <div class="data-field-label">Jenis Kelamin</div>
                            <div class="data-field-value" id="genderDisplay">—</div>
                        </div>
                    </div>

                    <!-- Card 2: Pekerjaan -->
                    <div class="data-card">
                        <div class="data-card-title">
                            <i data-lucide="briefcase" style="width:13px;height:13px;"></i> Pekerjaan
                        </div>
                        <div class="data-field">
                            <div class="data-field-label">Jenis Pekerjaan</div>
                            <div class="data-field-value" id="occupationDisplay">—</div>
                        </div>
                        <div class="data-field">
                            <div class="data-field-label">Pendidikan Terakhir</div>
                            <div class="data-field-value" id="educationDisplay">—</div>
                        </div>
                    </div>

                    <!-- Card 3: Alamat -->
                    <div class="data-card">
                        <div class="data-card-title">
                            <i data-lucide="map-pin" style="width:13px;height:13px;"></i> Domisili
                        </div>
                        <div class="data-field">
                            <div class="data-field-label">Wilayah</div>
                            <div class="data-field-value" id="regionDisplay">—</div>
                        </div>
                        <div class="data-field">
                            <div class="data-field-label">Alamat Lengkap</div>
                            <div class="data-field-value" id="addressDetail" style="font-weight:500; color:#475569; font-size:0.875rem; line-height:1.6;">—</div>
                        </div>
                    </div>
                </div>

                <!-- Pengurus Section -->
                <div id="pengurus-section" style="display:none; background:white; border-radius:16px; padding:24px; border:1px solid #f1f5f9; box-shadow:0 1px 3px rgba(0,0,0,0.04); margin-bottom:20px;">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <div>
                            <div style="font-size:0.95rem; color:#1e293b; font-weight:700;">Bergabung menjadi <span style="color:#004aad;">Pengurus Garda JKN</span>?</div>
                            <div style="font-size:0.8rem; color:#64748b; margin-top:4px;">Daftarkan diri Anda sekarang dan berkontribusi lebih bagi anggota.</div>
                        </div>
                        <button onclick="openPengurusModal()" style="padding:10px 20px; background:#004aad; color:white; border:none; border-radius:10px; font-weight:700; cursor:pointer; flex-shrink:0;">Daftar Sekarang</button>
                    </div>
                </div>

                <div id="pengurus-status-section" style="display:none; background:white; border-radius:16px; padding:24px; border:1px solid #f1f5f9; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <div>
                            <span style="font-size:0.65rem; color:#64748b; font-weight:800; text-transform:uppercase; letter-spacing:0.08em;">Peran Organisasi</span>
                            <div id="memberRoleDisplay" style="font-size:1.1rem; color:#1e293b; font-weight:800; margin-top:4px;">—</div>
                        </div>
                        <div id="statusPengurusBadge"></div>
                    </div>
                </div>

            </div><!-- /section-profil -->

            <!-- ===== TAB: INFORMASI ===== -->
            <div id="section-informasi" class="tab-content">
                <div style="margin-bottom: 24px;">
                    <h2 style="font-size:1.4rem; font-weight:800; color:#1e293b; margin:0 0 4px;">Pusat Informasi</h2>
                    <p style="color:#64748b; font-size:0.9rem; margin:0;">Pengumuman dan berita terbaru seputar Garda JKN.</p>
                </div>
                <div id="infoList" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
                    <div style="grid-column: 1 / -1; text-align: center; padding: 60px; background:white; border-radius:16px; border:1px solid #f1f5f9;">
                        <div class="spinner-border text-primary" role="status" style="width: 2rem; height: 2rem;"></div>
                        <p style="margin-top: 12px; color: #64748b; font-weight: 500;">Memuat informasi...</p>
                    </div>
                </div>
            </div>

            <!-- ===== TAB: PEMBAYARAN ===== -->
            <div id="section-pembayaran" class="tab-content">
                <div style="background:white; border-radius:20px; border:1px solid #f1f5f9; text-align:center; padding:80px 40px;">
                    <div style="width:72px;height:72px;background:#eff6ff;border-radius:18px;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
                        <i data-lucide="wallet" style="width:32px;height:32px;color:#004aad;"></i>
                    </div>
                    <h3 style="font-size:1.25rem;font-weight:800;color:#1e293b;margin-bottom:8px;">Riwayat Pembayaran</h3>
                    <p style="color:#64748b;font-size:0.95rem;max-width:400px;margin:0 auto;">Fitur riwayat pembayaran akan segera hadir untuk memudahkan Anda memantau iuran.</p>
                </div>
            </div>

            <!-- ===== TAB: LAPORAN KEGIATAN ===== -->
            <div id="section-laporan" class="tab-content">
                <div style="margin-bottom: 24px;">
                    <h2 style="font-size:1.4rem; font-weight:800; color:#1e293b; margin:0 0 4px;">Laporan Kegiatan</h2>
                    <p style="color:#64748b; font-size:0.9rem; margin:0;">Inputkan data kegiatan dan unggah dokumen pendukung Anda.</p>
                </div>
                <div style="background:white; border-radius:20px; border:1px solid #f1f5f9; padding:40px; max-width:700px;">
                    <form id="activityForm">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                            <div>
                                <label class="data-label">Nama Kegiatan</label>
                                <input type="text" class="form-input" placeholder="Contoh: Sosialisasi JKN di Desa" required style="border-radius: 12px;">
                            </div>
                            <div>
                                <label class="data-label">Tanggal Kegiatan</label>
                                <input type="date" class="form-input" required style="border-radius: 12px;">
                            </div>
                        </div>
                        <div style="margin-bottom: 24px;">
                            <label class="data-label">Deskripsi Singkat</label>
                            <textarea class="form-input" rows="3" placeholder="Apa yang Anda lakukan pada kegiatan ini?" style="resize: none; border-radius: 12px;" required></textarea>
                        </div>
                        <div style="margin-bottom: 32px;">
                            <label class="data-label">Dokumen Pendukung (PDF/JPG)</label>
                            <div id="dropZone" style="border: 2px dashed #cbd5e1; border-radius: 16px; padding: 48px; text-align: center; cursor: pointer; transition: 0.3s; background: #f8fafc;" onmouseover="this.style.borderColor='#004aad'; this.style.background='#eff6ff'" onmouseout="this.style.borderColor='#cbd5e1'; this.style.background='#f8fafc'">
                                <i data-lucide="upload-cloud" style="width: 36px; height: 36px; color: #94a3b8; margin-bottom: 12px;"></i>
                                <div style="font-size: 0.9rem; color: #1e293b; font-weight: 700;">Klik untuk unggah dokumen</div>
                                <div style="font-size: 0.78rem; color: #64748b; margin-top: 4px;">Format PDF atau Gambar, maksimal 5MB</div>
                                <input type="file" style="display: none;" id="activityFile">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px; border-radius: 12px; font-weight: 800; font-size: 1rem;">Simpan Laporan Kegiatan</button>
                    </form>
                </div>
            </div>

            <!-- ===== TAB: SURVEY ===== -->
            <div id="section-survey" class="tab-content">
                <div style="margin-bottom: 24px;">
                    <h2 style="font-size:1.4rem; font-weight:800; color:#1e293b; margin:0 0 4px;">Survey Pemahaman Peserta</h2>
                    <p style="color:#64748b; font-size:0.9rem; margin:0;">Bantu kami meningkatkan layanan dengan mengisi survey singkat ini.</p>
                </div>
                <div style="background:white; border-radius:20px; border:1px solid #f1f5f9; overflow:hidden; max-width:700px;">
                    <div style="background:#f8fafc; padding:20px 32px; border-bottom:1px solid #e2e8f0; font-weight:800; font-size:0.75rem; color:#475569; text-transform:uppercase; letter-spacing:0.1em; display:flex; align-items:center; gap:8px;">
                        <i data-lucide="file-text" style="width:14px;height:14px;"></i> Kuesioner Pemahaman
                    </div>
                    <div style="padding:36px;">
                        <form id="surveyForm">
                            <div style="display:flex; flex-direction:column; gap:36px;">
                                <div>
                                    <p style="font-weight:700; color:#1e293b; margin-bottom:16px; font-size:1rem;">1. Seberapa paham Anda mengenai manfaat program JKN?</p>
                                    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(140px, 1fr)); gap:12px;">
                                        <label style="background:#f8fafc; padding:12px 16px; border:1px solid #e2e8f0; border-radius:12px; display:flex; align-items:center; gap:10px; cursor:pointer; transition:0.2s;">
                                            <input type="radio" name="q1" value="1" style="width:16px;height:16px;"> <span style="font-size:0.875rem; font-weight:600; color:#475569;">Sangat Paham</span>
                                        </label>
                                        <label style="background:#f8fafc; padding:12px 16px; border:1px solid #e2e8f0; border-radius:12px; display:flex; align-items:center; gap:10px; cursor:pointer; transition:0.2s;">
                                            <input type="radio" name="q1" value="2" style="width:16px;height:16px;"> <span style="font-size:0.875rem; font-weight:600; color:#475569;">Cukup Paham</span>
                                        </label>
                                        <label style="background:#f8fafc; padding:12px 16px; border:1px solid #e2e8f0; border-radius:12px; display:flex; align-items:center; gap:10px; cursor:pointer; transition:0.2s;">
                                            <input type="radio" name="q1" value="3" style="width:16px;height:16px;"> <span style="font-size:0.875rem; font-weight:600; color:#475569;">Kurang Paham</span>
                                        </label>
                                    </div>
                                </div>
                                <div>
                                    <p style="font-weight:700; color:#1e293b; margin-bottom:16px; font-size:1rem;">2. Apakah Anda mengetahui prosedur pendaftaran anggota baru?</p>
                                    <div style="display:flex; gap:16px;">
                                        <label style="background:#f8fafc; padding:12px 24px; border:1px solid #e2e8f0; border-radius:12px; display:flex; align-items:center; gap:10px; cursor:pointer; transition:0.2s;">
                                            <input type="radio" name="q2" value="ya" style="width:16px;height:16px;"> <span style="font-size:0.875rem; font-weight:600; color:#475569;">Ya, Mengerti</span>
                                        </label>
                                        <label style="background:#f8fafc; padding:12px 24px; border:1px solid #e2e8f0; border-radius:12px; display:flex; align-items:center; gap:10px; cursor:pointer; transition:0.2s;">
                                            <input type="radio" name="q2" value="tidak" style="width:16px;height:16px;"> <span style="font-size:0.875rem; font-weight:600; color:#475569;">Belum Mengerti</span>
                                        </label>
                                    </div>
                                </div>
                                <div>
                                    <p style="font-weight:700; color:#1e293b; margin-bottom:12px; font-size:1rem;">3. Berikan saran atau masukan Anda:</p>
                                    <textarea class="form-input" rows="4" placeholder="Ketik saran atau pengalaman Anda menggunakan layanan ini..." style="resize:none; border-radius:16px; padding:16px;"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary" style="padding:14px 48px; align-self:flex-start; border-radius:12px; font-weight:800;">Kirim Tanggapan Survey</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div><!-- /member-content -->
    </main>
</div>

<!-- Modal Pendaftaran Pengurus -->
<div id="pengurusModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15,23,42,0.6); z-index:1001; align-items:center; justify-content:center; backdrop-filter: blur(4px);">
    <div style="background: white; width:500px; padding:0; border-radius: 20px; overflow:hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
        <div style="padding:20px 24px; border-bottom:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center;">
            <h3 style="font-size:1rem; font-weight:800; color:#1e293b; margin:0;">Formulir Calon Pengurus</h3>
            <button onclick="closePengurusModal()" style="background:#f1f5f9; border:none; width:32px; height:32px; border-radius:50%; color:#64748b; font-size:1.25rem; cursor:pointer; display:flex; align-items:center; justify-content:center;">&times;</button>
        </div>
        <div id="pengurusStep1" style="padding:32px; text-align:center;">
            <div style="width:64px;height:64px;background:#eff6ff;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;"><i data-lucide="help-circle" style="width:32px;height:32px;color:#3b82f6;"></i></div>
            <h4 style="font-size:1.125rem; font-weight:700; color:#1e293b; margin-bottom:12px;">Ingin Jadi Pengurus?</h4>
            <p style="color:#64748b; font-size:0.875rem; margin-bottom:24px;">Apakah anda bersedia berkontribusi lebih sebagai pengurus di Garda JKN?</p>
            <div style="display:flex; gap:12px;">
                <button class="btn btn-secondary" onclick="submitPengurusInterest(false)" style="flex:1; padding:12px;">Tidak Sekarang</button>
                <button class="btn btn-primary" onclick="showPengurusStep(2)" style="flex:1; padding:12px; background:#004aad; border:none;">Ya, Saya Ingin</button>
            </div>
        </div>
        <div id="pengurusStep2" style="padding:32px; text-align:center; display:none;">
            <div style="width:64px;height:64px;background:#f0fdf4;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;"><i data-lucide="users" style="width:32px;height:32px;color:#22c55e;"></i></div>
            <h4 style="font-size:1.125rem; font-weight:700; color:#1e293b; margin-bottom:12px;">Pengalaman Organisasi</h4>
            <p style="color:#64748b; font-size:0.875rem; margin-bottom:24px;">Apakah anda pernah memiliki pengalaman berorganisasi sebelumnya?</p>
            <div style="display:flex; gap:12px;">
                <button class="btn btn-secondary" onclick="submitPengurusInterest(true, false)" style="flex:1; padding:12px;">Tidak Ada</button>
                <button class="btn btn-primary" onclick="showPengurusStep(3)" style="flex:1; padding:12px; background:#004aad; border:none;">Ya, Ada</button>
            </div>
        </div>
        <div id="pengurusStep3" style="padding:32px; display:none;">
            <h4 style="font-size:1rem; font-weight:700; color:#1e293b; margin-bottom:20px;">Detail Pengalaman & Motivasi</h4>
            <div style="margin-bottom:16px;"><label class="data-label">Berapa Organisasi yang Pernah Diikuti?</label><input type="number" id="appOrgCount" class="form-input" placeholder="Contoh: 3" style="width:100%; margin-top:4px;"></div>
            <div style="margin-bottom:16px;"><label class="data-label">Apa Saja Organisasi Tersebut?</label><textarea id="appOrgName" class="form-input" rows="3" placeholder="Sebutkan nama-nama organisasi..." style="width:100%; margin-top:4px; resize:none;"></textarea></div>
            <div style="margin-bottom:24px;"><label class="data-label">Alasan Ingin Menjadi Pengurus?</label><textarea id="appReason" class="form-input" rows="3" placeholder="Tuliskan motivasi anda..." style="width:100%; margin-top:4px; resize:none;"></textarea></div>
            <div style="display:flex; gap:12px;">
                <button class="btn btn-secondary" onclick="showPengurusStep(2)" style="flex:1; padding:12px;">Kembali</button>
                <button class="btn btn-primary" onclick="submitPengurusInterest(true, true)" style="flex:2; padding:12px; background:#004aad; border:none;">Kirim Pendaftaran</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Profil -->
<div id="editModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15,23,42,0.6); z-index:1000; align-items:center; justify-content:center; backdrop-filter: blur(8px);">
    <div style="background: white; width:640px; padding:0; overflow:hidden; border-radius: 20px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
        <div style="padding:24px 32px; border-bottom:1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 36px; height: 36px; background: #eff6ff; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #004aad;"><i data-lucide="user-plus" style="width: 20px; height: 20px;"></i></div>
                <h3 style="font-size:1.1rem; font-weight:800; color: #1e293b; margin: 0;">Pembaruan Profil</h3>
            </div>
            <button onclick="closeEditModal()" style="background: #f1f5f9; border: none; width: 32px; height: 32px; border-radius: 50%; color: #64748b; cursor: pointer; display: flex; align-items: center; justify-content: center;">&times;</button>
        </div>
        <div style="padding:32px; max-height: 75vh; overflow-y: auto;">
            <div style="margin-bottom: 24px;">
                <label class="data-label">Foto Profil</label>
                <div style="display: flex; align-items: center; gap: 16px; margin-top:6px;">
                    <img id="editPhotoPreview" src="" style="width: 72px; height: 72px; border-radius: 12px; object-fit: cover; object-position: top; background: #f1f5f9; border: 2px solid #e2e8f0;">
                    <div style="flex: 1;">
                        <input type="file" id="editPhoto" accept="image/*" class="form-input" style="width: 100%; padding: 6px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.75rem;">
                        <small style="color: #94a3b8; font-size: 0.7rem; margin-top: 4px; display: block;">Rekomendasi: 400x400px, JPG/PNG. Max 10MB.</small>
                    </div>
                </div>
            </div>
            <div style="margin-bottom: 20px;"><label class="data-label">Nama Lengkap</label><input type="text" id="editName" class="form-input" style="width: 100%; margin-top:4px;"></div>
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px;">
                <div><label class="data-label">Nomor Kartu JKN</label><input type="text" id="editJknNumber" class="form-input" style="width: 100%; margin-top:4px;" placeholder="Opsional (13 digit)"></div>
                <div><label class="data-label">No. WhatsApp</label><input type="text" id="editPhone" class="form-input" style="width: 100%; margin-top:4px;"></div>
            </div>
            <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:20px; margin-bottom:20px;">
                <div><label class="data-label">Tanggal Lahir</label><input type="date" id="editBirthDate" class="form-input" style="width: 100%; margin-top:4px;"></div>
                <div><label class="data-label">Jenis Kelamin</label><select id="editGender" class="form-input" style="width: 100%; margin-top:4px;"><option value="L">Laki-laki</option><option value="P">Perempuan</option></select></div>
                <div><label class="data-label">Pendidikan</label><select id="editEducation" class="form-input" style="width: 100%; margin-top:4px;"><option value="SD">SD</option><option value="SMP">SMP</option><option value="SMA">SMA</option><option value="Diploma">Diploma</option><option value="S1/D4">S1/D4</option><option value="S2">S2</option></select></div>
            </div>
            <div style="margin-bottom:20px;"><label class="data-label">Jenis Pekerjaan</label>
                <select id="editOccupation" class="form-input" style="width: 100%; margin-top:4px;">
                    <option value="BELUM/TIDAK BEKERJA">BELUM/TIDAK BEKERJA</option><option value="MENGURUS RUMAH TANGGA">MENGURUS RUMAH TANGGA</option><option value="PELAJAR/MAHASISWA">PELAJAR/MAHASISWA</option><option value="PENSIUNAN">PENSIUNAN</option><option value="PEGAWAI NEGERI SIPIL">PEGAWAI NEGERI SIPIL</option><option value="TNI/POLRI">TNI / POLRI</option><option value="KARYAWAN SWASTA">KARYAWAN SWASTA</option><option value="KARYAWAN BUMN/BUMD">KARYAWAN BUMN/BUMD</option><option value="WIRASWASTA">WIRASWASTA</option><option value="PETANI/PEKEBUN">PETANI/PEKEBUN</option><option value="NELAYAN/PERIKANAN">NELAYAN/PERIKANAN</option><option value="BURUH HARIAN LEPAS">BURUH HARIAN LEPAS</option><option value="PEDAGANG">PEDAGANG</option><option value="PERANGKAT DESA">PERANGKAT DESA</option><option value="TENAGA MEDIS">TENAGA MEDIS (DOKTER/PERAWAT)</option><option value="LAINNYA">LAINNYA</option>
                </select>
            </div>
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px;">
                <div><label class="data-label">Provinsi</label><select id="editProvince" class="form-input" style="width: 100%; margin-top:4px;" onchange="loadCities(this.value)"><option value="">Pilih...</option></select></div>
                <div><label class="data-label">Kab/Kota</label><select id="editCity" class="form-input" style="width: 100%; margin-top:4px;" onchange="loadDistricts(this.value)"><option value="">Pilih...</option></select></div>
            </div>
            <div style="margin-bottom:20px;"><label class="data-label">Kecamatan</label><select id="editDistrict" class="form-input" style="width: 100%; margin-top:4px;"><option value="">Pilih...</option></select></div>
            <div><label class="data-label">Alamat Lengkap</label><textarea id="editAddress" class="form-input" rows="2" style="width: 100%; margin-top:4px; resize: none;"></textarea></div>
        </div>
        <div style="padding:20px 32px; background:#f8fafc; border-top:1px solid #e2e8f0; display:flex; justify-content:flex-end; gap:12px;">
            <button class="btn btn-secondary" onclick="closeEditModal()">Batal</button>
            <button class="btn btn-primary" onclick="submitUpdate(event)" style="background:#004aad; border:none; color:white;">Simpan Perubahan</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let currentData = null;

    document.addEventListener('DOMContentLoaded', async () => {
        fetchProfile();
        fetchInformations();
        document.getElementById('topbarDate').innerText = new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        
        // Handle URL hash navigation (e.g. /member/profile#survey from settings sidebar)
        const hash = window.location.hash.replace('#', '');
        const validSections = ['profil', 'informasi', 'pembayaran', 'laporan', 'survey'];
        if (hash && validSections.includes(hash)) {
            // Wait a tiny tick for DOM to be ready
            setTimeout(() => switchSection(hash), 50);
        }

        // Activity form handler
        document.getElementById('activityForm').addEventListener('submit', (e) => {
            e.preventDefault();
            showToast('Laporan kegiatan berhasil disimpan!', 'success');
            e.target.reset();
        });

        // Survey form handler
        document.getElementById('surveyForm').addEventListener('submit', (e) => {
            e.preventDefault();
            showToast('Terima kasih! Survey Anda telah kami terima.', 'success');
            e.target.reset();
        });
    });

    async function fetchInformations() {
        try {
            const res = await axios.get('member/informations');
            renderInformations(res.data.data);
        } catch (e) {
            console.error(e);
            document.getElementById('infoList').innerHTML = '<div style="grid-column: 1/-1; text-align: center; color: #64748b;">Gagal memuat informasi.</div>';
        }
    }

    function renderInformations(items) {
        const container = document.getElementById('infoList');
        if (items.length === 0) {
            container.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 40px; color: #64748b;">Belum ada informasi terbaru.</div>';
            return;
        }

        container.innerHTML = '';
        items.forEach(item => {
            let preview = '';
            if (item.type === 'image' && item.attachment_url) {
                preview = `<img src="${item.attachment_url}" style="width: 100%; height: 140px; object-fit: cover; border-radius: 12px; margin-bottom: 12px;">`;
            } else if (item.type === 'pdf') {
                preview = `<div style="width: 100%; height: 140px; background: #fee2e2; border-radius: 12px; margin-bottom: 12px; display: flex; align-items: center; justify-content: center; color: #b91c1c;"><i data-lucide="file-text" style="width: 48px; height: 48px;"></i></div>`;
            }

            container.innerHTML += `
                <div class="info-card" onclick="showInfoDetail(${item.id})" style="background: white; border: 1px solid #f1f5f9; border-radius: 16px; padding: 16px; cursor: pointer; transition: 0.2s;">
                    ${preview}
                    <div style="font-size: 0.7rem; color: #94a3b8; font-weight: 700; text-transform: uppercase; margin-bottom: 4px;">${new Date(item.created_at).toLocaleDateString('id-ID')}</div>
                    <div style="font-weight: 800; color: #1e293b; font-size: 0.95rem; margin-bottom: 6px; line-height: 1.4;">${item.title}</div>
                    <div style="font-size: 0.8rem; color: #64748b; line-height: 1.5; height: 3.6em; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">${item.content || ''}</div>
                </div>
            `;
        });
        lucide.createIcons();
    }

    async function showInfoDetail(id) {
        try {
            const res = await axios.get(`member/informations/${id}`);
            const item = res.data.data;
            
            let attachmentHtml = '';
            if (item.type === 'image' && item.attachment_url) {
                attachmentHtml = `<img src="${item.attachment_url}" style="width: 100%; border-radius: 12px; margin-top: 16px;">`;
            } else if (item.type === 'pdf' && item.attachment_url) {
                attachmentHtml = `
                    <div style="margin-top: 20px; padding: 16px; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 40px; height: 40px; background: #fee2e2; color: #b91c1c; border-radius: 10px; display: flex; align-items: center; justify-content: center;"><i data-lucide="file-text" style="width: 20px; height: 20px;"></i></div>
                            <div style="font-weight: 700; color: #1e293b; font-size: 0.85rem;">Dokumen Lampiran (PDF)</div>
                        </div>
                        <a href="${item.attachment_url}" target="_blank" class="btn btn-primary" style="padding: 8px 16px; font-size: 0.75rem;">Buka Berkas</a>
                    </div>
                `;
            }

            const modalHtml = `
                <div id="infoDetailModal" style="position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15,23,42,0.6); z-index:1001; display:flex; align-items:center; justify-content:center; backdrop-filter: blur(4px); padding: 20px;">
                    <div style="background: white; width:100%; max-width: 600px; padding:0; border-radius: 20px; overflow:hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
                        <div style="padding:20px 24px; border-bottom:1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center;">
                            <h3 style="font-size:1rem; font-weight:800; color:#1e293b; margin:0;">Detail Informasi</h3>
                            <button onclick="document.getElementById('infoDetailModal').remove()" style="background: #f1f5f9; border:none; width: 32px; height: 32px; border-radius: 50%; color:#64748b; font-size:1rem; cursor:pointer;">&times;</button>
                        </div>
                        <div style="padding:32px; max-height: 70vh; overflow-y: auto;">
                            <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 800; text-transform: uppercase; margin-bottom: 8px;">Diterbitkan pada: ${new Date(item.created_at).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'})}</div>
                            <h2 style="font-size: 1.25rem; font-weight: 800; color: #1e293b; margin-bottom: 16px; line-height: 1.4;">${item.title}</h2>
                            <div style="font-size: 1rem; color: #475569; line-height: 1.7; white-space: pre-wrap;">${item.content || ''}</div>
                            ${attachmentHtml}
                        </div>
                    </div>
                </div>
            `;
            
            const div = document.createElement('div');
            div.innerHTML = modalHtml;
            document.body.appendChild(div.firstElementChild);
            lucide.createIcons();
        } catch (e) {
            showToast('Gagal memuat detail informasi.', 'error');
        }
    }

    const sectionTitles = {
        'profil': 'Profil Saya',
        'informasi': 'Pusat Informasi',
        'pembayaran': 'Riwayat Pembayaran',
        'laporan': 'Laporan Kegiatan',
        'survey': 'Survey'
    };

        // Logic for direct hash navigation
    window.addEventListener('hashchange', () => {
        const h = window.location.hash.replace('#', '');
        if (h && ['profil', 'informasi', 'pembayaran', 'laporan', 'survey'].includes(h)) {
            switchSection(h);
        }
    });

    function switchSection(sectionId, btn) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
        const target = document.getElementById('section-' + sectionId);
        if (!target) return;
        target.classList.add('active');

        // Update active sidebar link — support both btn param and hash-based nav
        document.querySelectorAll('.sb-link').forEach(el => el.classList.remove('active'));
        if (btn) {
            btn.classList.add('active');
        } else {
            // Find the matching sidebar link by ID
            const matchingLink = document.getElementById('nav-' + sectionId);
            if (matchingLink) matchingLink.classList.add('active');
        }

        // Update topbar title
        document.getElementById('topbarTitle').innerText = sectionTitles[sectionId] || sectionId;

        // Clean up URL hash without triggering reload
        if (window.location.hash !== '#' + sectionId) {
            history.replaceState(null, '', '#' + sectionId);
        }

        lucide.createIcons();
    }

    async function fetchProfile() {
        try {
            const res = await axios.get('member/profile');
            currentData = res.data.data;
            updateUI(currentData);
            lucide.createIcons();
        } catch (e) {
            console.error(e);
            if (e.response?.status === 403) {
                const role = localStorage.getItem('user_role');
                if (role === 'admin' || role === 'administrator') {
                    // Admin can view profile but maybe API blocks it, or we just let it be.
                    // For now, let's just show an error but not redirect if admin
                    showToast('Admin tidak memiliki profil member.', 'warning');
                } else {
                    showToast('Akses ditolak. Halaman ini hanya untuk Anggota.', 'error');
                    setTimeout(() => window.location.href = '/login', 2000);
                }
            } else {
                showToast('Gagal memuat profil. Silakan coba lagi.', 'error');
            }
        }
    }

    function updateUI(d) {
        document.getElementById('nameDisplay').innerText = d.name;
        document.getElementById('nikDisplay').innerText = d.nik;
        document.getElementById('jknDisplay').innerText = d.jkn_number || '-';
        document.getElementById('phoneDisplay').innerText = d.phone;
        document.getElementById('birthDateDisplay').innerText = d.birth_date ? d.birth_date : '-';
        document.getElementById('genderDisplay').innerText = d.gender === 'L' ? 'Laki-laki' : 'Perempuan';
        document.getElementById('educationDisplay').innerText = d.education;
        document.getElementById('occupationDisplay').innerText = d.occupation;
        document.getElementById('addressDetail').innerText = d.address_detail;
        document.getElementById('regionDisplay').innerText = `${d.district.name}, ${d.city.name}, ${d.province.name}`;
        
        // Photo or Initials — hero & sidebar
        const initials = d.name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
        if (d.photo_path) {
            const imgTag = `<img src="${d.photo_url}" style="width:100%;height:100%;object-fit:cover;object-position:top;" alt="${d.name}">`;
            document.getElementById('avatarContainer').innerHTML = imgTag;
            document.getElementById('sidebarAvatar').innerHTML = imgTag;
        } else {
            document.getElementById('avatarContainer').innerHTML = `<span style="font-weight:800;color:white;font-size:2rem;">${initials}</span>`;
            document.getElementById('sidebarAvatar').innerHTML = `<span style="font-weight:800;color:white;font-size:1.5rem;">${initials}</span>`;
        }
        // Update sidebar info
        document.getElementById('sidebarName').innerText = d.name;
        document.getElementById('sidebarNik').innerText = 'NIK: ' + d.nik;

        // Pengurus Logic
        const pSection = document.getElementById('pengurus-section');
        const psSection = document.getElementById('pengurus-status-section');
        const statusBadge = document.getElementById('statusPengurusBadge');
        const roleDisplay = document.getElementById('memberRoleDisplay');

        if (d.status_pengurus === 'tidak_mendaftar') {
            pSection.style.display = 'block';
            psSection.style.display = 'none';
        } else {
            pSection.style.display = 'none';
            psSection.style.display = 'block';
            roleDisplay.innerText = d.role === 'pengurus' ? 'PENGURUS GARDA JKN' : 'Anggota Biasa';
            
            let badgeHtml = '';
            if (d.status_pengurus === 'pendaftaran_diterima') {
                badgeHtml = '<span class="status-badge" style="background:#fffbeb; color:#92400e; border:1px solid #fde68a; border-radius: 50px; padding: 4px 14px; font-weight: 700; font-size: 0.75rem;">MENUNGGU VERIFIKASI</span>';
            } else if (d.status_pengurus === 'aktif') {
                badgeHtml = '<span class="status-badge" style="background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; border-radius: 50px; padding: 4px 14px; font-weight: 700; font-size: 0.75rem;">KEPENGURUSAN AKTIF</span>';
            } else {
                badgeHtml = `<span class="status-badge" style="border-radius: 50px; padding: 4px 14px; font-weight: 700; font-size: 0.75rem; background: #f1f5f9; color: #475569;">${d.status_pengurus.toUpperCase()}</span>`;
            }
            statusBadge.innerHTML = badgeHtml;
        }
    }

    // --- Pengurus Modal Logic ---
    function openPengurusModal() {
        showPengurusStep(1);
        document.getElementById('pengurusModal').style.display = 'flex';
    }

    function closePengurusModal() {
        document.getElementById('pengurusModal').style.display = 'none';
    }

    function showPengurusStep(step) {
        document.getElementById('pengurusStep1').style.display = step === 1 ? 'block' : 'none';
        document.getElementById('pengurusStep2').style.display = step === 2 ? 'block' : 'none';
        document.getElementById('pengurusStep3').style.display = step === 3 ? 'block' : 'none';
    }

    async function submitPengurusInterest(interested, hasOrg = false) {
        const btn = event?.currentTarget;
        const originalText = btn ? btn.innerText : 'Kirim';
        
        const payload = {
            is_interested_pengurus: interested,
            has_org_experience: hasOrg
        };

        if (interested && hasOrg) {
            payload.org_count = document.getElementById('appOrgCount').value;
            payload.org_name = document.getElementById('appOrgName').value;
            payload.pengurus_reason = document.getElementById('appReason').value;

            if (!payload.org_count || !payload.org_name || !payload.pengurus_reason) {
                showToast('Mohon lengkapi semua data pendaftaran.', 'warning');
                return;
            }
        }

        if (btn) {
            btn.disabled = true;
            btn.innerText = 'Mengirim...';
        }

        try {
            await axios.post('member/apply-pengurus', payload);
            showToast('Data kepengurusan berhasil disimpan!', 'success');
            closePengurusModal();
            fetchProfile(); // Refresh UI
        } catch (e) {
            console.error(e);
            let msg = 'Gagal menyimpan data.';
            if (e.response?.data?.errors) {
                msg = Object.values(e.response.data.errors).flat().join(' ');
            } else if (e.response?.data?.message) {
                msg = e.response.data.message;
            }
            showToast(msg, 'error');
        } finally {
            if (btn) {
                btn.disabled = false;
                btn.innerText = originalText;
            }
        }
    }

    // --- Modal Logic ---
    async function openEditModal() {
        if(!currentData) return;
        
        document.getElementById('editName').value = currentData.name;
        document.getElementById('editJknNumber').value = currentData.jkn_number || '';
        document.getElementById('editPhone').value = currentData.phone;
        document.getElementById('editBirthDate').value = currentData.birth_date;
        document.getElementById('editGender').value = currentData.gender;
        document.getElementById('editEducation').value = currentData.education;
        document.getElementById('editOccupation').value = currentData.occupation;
        document.getElementById('editAddress').value = currentData.address_detail;
        document.getElementById('editPhotoPreview').src = currentData.photo_url;
        document.getElementById('editPhoto').value = '';
        
        document.getElementById('editModal').style.display = 'flex';
        
        // Populate regions
        await loadProvinces(currentData.province_id);
        await loadCities(currentData.province_id, currentData.city_id);
        await loadDistricts(currentData.city_id, currentData.district_id);
    }

    function closeEditModal() { document.getElementById('editModal').style.display = 'none'; }

    async function loadProvinces(selectedId = null) {
        const res = await axios.get('master/provinces');
        const sel = document.getElementById('editProvince');
        sel.innerHTML = '<option value="">Pilih...</option>';
        res.data.data.forEach(p => {
            sel.innerHTML += `<option value="${p.id}" ${p.id == selectedId ? 'selected' : ''}>${p.name}</option>`;
        });
    }

    async function loadCities(provId, selectedId = null) {
        const sel = document.getElementById('editCity');
        const distSel = document.getElementById('editDistrict');
        
        // Reset both child dropdowns
        sel.innerHTML = '<option value="">Pilih...</option>';
        distSel.innerHTML = '<option value="">Pilih...</option>';
        
        if(!provId) return;

        const res = await axios.get(`master/cities?province_id=${provId}`);
        res.data.data.forEach(c => {
            const prefix = c.type === 'KOTA' ? 'KOTA ' : 'KAB. ';
            sel.innerHTML += `<option value="${c.id}" ${c.id == selectedId ? 'selected' : ''}>${prefix}${c.name}</option>`;
        });
    }

    async function loadDistricts(cityId, selectedId = null) {
        const sel = document.getElementById('editDistrict');
        sel.innerHTML = '<option value="">Pilih...</option>';
        
        if(!cityId) return;

        const res = await axios.get(`master/districts?city_id=${cityId}`);
        res.data.data.forEach(d => {
            sel.innerHTML += `<option value="${d.id}" ${d.id == selectedId ? 'selected' : ''}>${d.name}</option>`;
        });
    }

    async function submitUpdate(event) {
        if (event) event.preventDefault();
        const formData = new FormData();
        formData.append('_method', 'PUT'); // Spofing method for multipart data
        const name = document.getElementById('editName').value;
        const jkn = document.getElementById('editJknNumber').value.replace(/\D/g, '');
        const phone = document.getElementById('editPhone').value.replace(/\D/g, '');
        const birthDate = document.getElementById('editBirthDate').value;
        const gender = document.getElementById('editGender').value;
        const education = document.getElementById('editEducation').value;
        const occupation = document.getElementById('editOccupation').value;

        formData.append('name', name);
        if (jkn) formData.append('jkn_number', jkn);
        formData.append('phone', phone);
        formData.append('birth_date', birthDate);
        formData.append('gender', gender);
        formData.append('education', education);
        formData.append('occupation', occupation);
        const provId = document.getElementById('editProvince').value;
        const cityId = document.getElementById('editCity').value;
        const distId = document.getElementById('editDistrict').value;

        if (provId) formData.append('province_id', provId);
        if (cityId) formData.append('city_id', cityId);
        if (distId) formData.append('district_id', distId);

        formData.append('address_detail', document.getElementById('editAddress').value);

        const photoInput = document.getElementById('editPhoto');
        if (photoInput.files[0]) {
            formData.append('photo', photoInput.files[0]);
        }

        const btn = event ? event.currentTarget : document.querySelector('button[onclick^="submitUpdate"]');
        const originalText = btn.innerText;
        btn.disabled = true;
        btn.innerText = 'Menyimpan...';

        try {
            await axios.post('member/profile', formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            });
            showToast('Profil berhasil diperbarui!', 'success');
            closeEditModal();
            fetchProfile(); // Refresh UI
        } catch (e) {
            console.error(e);
            let msg = 'Gagal memperbarui profil.';
            if (e.response?.data?.errors) {
                // Get the first error message from the object
                const errs = e.response.data.errors;
                msg = Object.values(errs).flat()[0] || msg;
            } else if (e.response?.data?.message) {
                msg = e.response.data.message;
            }
            showToast(msg, 'error');
        } finally {
            if (btn) {
                btn.disabled = false;
                btn.innerText = originalText;
            }
        }
    }

    function logout() { localStorage.clear(); window.location.href = '/login'; }
</script>
@endpush