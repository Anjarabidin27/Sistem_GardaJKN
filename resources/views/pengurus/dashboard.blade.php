@extends('layouts.app')

@section('title', 'Pengurus Dashboard - Garda JKN')



@section('content')

<div class="admin-layout">
    <aside class="sidebar">
        <div class="sb-brand">
            <div class="sb-brand-name">Garda JKN</div>
        </div>
        <div class="sb-user-card">
            <div class="sb-avatar" id="sb-avatar-wrap"><span id="sb-initials">A</span></div>
            <div class="sb-user-name" id="sb-user-name">Administrator</div>
        </div>
        <nav class="sb-menu">
            <div class="sb-section-label">Menu</div>
            <a href="/pengurus/dashboard" class="sb-link"><i data-lucide="layout-dashboard" style="width:16px;height:16px;"></i> Dashboard</a>
            <a href="/pengurus/members" class="sb-link"><i data-lucide="users" style="width:16px;height:16px;"></i> Anggota Wilayah</a>
            <a href="/pengurus/informations" class="sb-link"><i data-lucide="megaphone" style="width:16px;height:16px;"></i> Informasi</a>
        </nav>
        <div class="sb-footer">
            <div class="sb-section-label" style="margin-top:0;margin-bottom:8px;">Pengaturan</div>
            <a href="/pengurus/settings" class="sb-link {{ request()->is('pengurus/settings') ? 'active' : '' }}"><i data-lucide="settings" style="width:16px;height:16px;"></i> Pengaturan Akun</a>
            <a href="javascript:void(0)" onclick="logout()" class="sb-link logout-link">
                <i data-lucide="log-out" class="sb-link-icon"></i> <span>Keluar Sesi</span>
            </a>
        </div>
    </aside>

    <main class="main-body">
        <header class="top-header">
            <div style="font-weight: 600; color: #1e293b; font-size: 1rem;">Portal Pengurus Operasional</div>
            <div style="display: flex; align-items: center; gap: 12px;">
                <span id="date-now" style="font-size: 0.75rem; color: #94a3b8; font-weight: 500;"></span>
                <div id="user-initials" style="width: 32px; height: 32px; background: #eff6ff; color: #004aad; border: 1px solid #dbeafe; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem;">PR</div>
            </div>
        </header>

        <div class="view-container">
            <div class="summary-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 32px;">
                <div class="stat-card">
                    <div class="stat-label">Anggota Terkelola</div>
                    <div class="stat-value" id="count-total">...</div>
                    <div style="font-size: 0.70rem; color: #3b82f6; font-weight: 600; margin-top: 8px;">Dalam Wilayah Anda</div>
                </div>
                <div class="stat-card" style="border-left: 3px solid #10b981;">
                    <div class="stat-label">Pendaftaran Baru</div>
                    <div class="stat-value" id="count-month" style="color: #059669;">...</div>
                    <div style="font-size: 0.7rem; color: #059669; font-weight: 600; margin-top: 8px;">Verifikasi Bulan Ini</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Informasi Aktif</div>
                    <div class="stat-value" id="count-info">...</div>
                    <div style="font-size: 0.75rem; color: #64748b; font-weight: 600; margin-top: 12px;">Pengumuman Berjalan</div>
                </div>
            </div>

            <div class="chart-box">
                <div class="title-row">
                    <div>
                        <h3 style="font-size: 1.125rem; font-weight: 800; color: #0f172a;">Statistik Anggota Wilayah</h3>
                        <p style="font-size: 0.85rem; color: #64748b; margin-top: 4px;">Perbandingan pendaftaran anggota per periode.</p>
                    </div>
                </div>
                <div style="position: relative; width: 100%; height: 350px;"><canvas id="mainChart"></canvas></div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px;">
                <div class="chart-box" style="padding: 24px;">
                    <div class="title-row" style="margin-bottom: 24px;"><h3 style="font-size: 1rem; font-weight: 800; color: #0f172a;">Gender Wilayah</h3></div>
                    <div style="position: relative; width: 100%; height: 250px;"><canvas id="genderChart"></canvas></div>
                </div>
                <div class="chart-box" style="padding: 24px;">
                    <div class="title-row" style="margin-bottom: 24px;"><h3 style="font-size: 1rem; font-weight: 800; color: #0f172a;">Pekerjaan Wilayah</h3></div>
                    <div style="position: relative; width: 100%; height: 250px;"><canvas id="occupationChart"></canvas></div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection

@push('scripts')
@vite(['resources/css/pages/pengurus_dashboard.css', 'resources/js/pages/pengurus_dashboard.js'])


@endpush

@push("scripts")
<script>
    window.sessionSuccess = "{{ session("success") }}";
    window.sessionError = "{{ session("error") }}";
</script>
@endpush
