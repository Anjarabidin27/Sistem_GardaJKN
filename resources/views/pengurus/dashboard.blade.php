<x-pengurus-layout title="Dashboard Pengurus - Garda JKN">
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

    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-top: 24px;">
        <div class="chart-box" style="padding: 24px;">
            <div class="title-row" style="margin-bottom: 24px;"><h3 style="font-size: 1rem; font-weight: 800; color: #0f172a;">Gender Wilayah</h3></div>
            <div style="position: relative; width: 100%; height: 250px;"><canvas id="genderChart"></canvas></div>
        </div>
        <div class="chart-box" style="padding: 24px;">
            <div class="title-row" style="margin-bottom: 24px;"><h3 style="font-size: 1rem; font-weight: 800; color: #0f172a;">Pekerjaan Wilayah</h3></div>
            <div style="position: relative; width: 100%; height: 250px;"><canvas id="occupationChart"></canvas></div>
        </div>
    </div>

    @push('scripts')
    @vite(['resources/css/pages/pengurus_dashboard.css', 'resources/js/pages/pengurus_dashboard.js'])
    @endpush
</x-pengurus-layout>
