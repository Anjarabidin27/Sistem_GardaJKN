<x-pengurus-layout title="Command Hub Pengurus - Garda JKN">
    <style>
        :root {
            --v-black: #000;
            --v-white: #fff;
            --v-gray-50: #fbfbfc;
            --v-gray-100: #f3f4f6;
            --v-gray-200: #e5e7eb;
            --v-gray-400: #9ca3af;
            --v-gray-500: #6b7280;
            --v-emerald-500: #10b981;
            --v-blue-600: #2ea0fb;
        }

        .v-summary-bar {
            background: var(--v-black);
            color: var(--v-white);
            padding: 1.5rem 2rem;
            border-radius: 1.25rem;
            margin-bottom: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: space-around;
            box-shadow: 0 20px 40px -10px rgba(0,0,0,0.15);
        }
        .v-summary-item { text-align: center; }
        .v-summary-val { font-size: 1.75rem; font-weight: 900; letter-spacing: -0.02em; line-height: 1; }
        .v-label-caps {
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--v-gray-400);
            display: block;
            margin-bottom: 4px;
        }

        .v-grid { display: grid; gap: 1.5rem; }
        .v-grid-3 { grid-template-columns: repeat(3, 1fr); }
        .v-grid-2 { grid-template-columns: repeat(2, 1fr); }

        .v-card-nav {
            background: var(--v-white);
            padding: 1.25rem;
            border-radius: 1rem;
            border: 1px solid var(--v-gray-100);
            text-decoration: none;
            color: inherit;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }
        .v-card-nav:hover {
            border-color: var(--v-black);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(0,0,0,0.05);
        }
        .v-card-icon {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.75rem;
            background: var(--v-gray-50);
            flex-shrink: 0;
        }
        .v-card-nav:hover .v-card-icon {
            background: var(--v-black);
            color: var(--v-white);
        }

        .chart-box {
            background: white;
            border-radius: 1.25rem;
            padding: 1.5rem;
            border: 1px solid var(--v-gray-100);
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }

        @media (max-width: 768px) {
            .v-summary-bar { flex-direction: column; gap: 1.5rem; padding: 2rem; }
            .v-grid-3, .v-grid-2 { grid-template-columns: 1fr; }
        }
    </style>

    <!-- Header Section -->
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 1.75rem; font-weight: 900; letter-spacing: -0.04em; color: var(--v-black); margin: 0;">Command Hub Pengurus</h1>
        <p style="font-size: 0.875rem; color: var(--v-gray-500); margin-top: 4px;">Pantau data anggota dan aktivitas pelayanan di wilayah koordinasi Anda.</p>
    </div>

    <!-- Summary Bar -->
    <div class="v-summary-bar">
        <div class="v-summary-item">
            <span class="v-label-caps" style="color: rgba(255,255,255,0.4);">Total Anggota Wilayah</span>
            <div class="v-summary-val" id="count-total">...</div>
        </div>
        <div style="width: 1px; height: 32px; background: rgba(255,255,255,0.1);"></div>
        <div class="v-summary-item">
            <span class="v-label-caps" style="color: rgba(255,255,255,0.4);">Pendaftaran Baru</span>
            <div class="v-summary-val" id="count-month" style="color: var(--v-emerald-500);">...</div>
        </div>
        <div style="width: 1px; height: 32px; background: rgba(255,255,255,0.1);"></div>
        <div class="v-summary-item">
            <span class="v-label-caps" style="color: rgba(255,255,255,0.4);">Informasi Berjalan</span>
            <div class="v-summary-val" id="count-info">...</div>
        </div>
    </div>

    <!-- Quick Navigation -->
    <div class="v-grid v-grid-3" style="margin-bottom: 2.5rem;">
        <a href="/pengurus/members" class="v-card-nav">
            <div class="v-card-icon"><i data-lucide="users" style="width: 18px; height: 18px;"></i></div>
            <div style="flex: 1;">
                <span class="v-label-caps">Basis Data</span>
                <div style="font-size: 0.9rem; font-weight: 800;">Anggota Wilayah</div>
            </div>
        </a>
        <a href="/pengurus/informations" class="v-card-nav">
            <div class="v-card-icon"><i data-lucide="megaphone" style="width: 18px; height: 18px;"></i></div>
            <div style="flex: 1;">
                <span class="v-label-caps">Edukasi</span>
                <div style="font-size: 0.9rem; font-weight: 800;">Manajemen Informasi</div>
            </div>
        </a>
        <a href="/pengurus/bpjs-keliling" class="v-card-nav">
            <div class="v-card-icon"><i data-lucide="truck" style="width: 18px; height: 18px;"></i></div>
            <div style="flex: 1;">
                <span class="v-label-caps">Layanan</span>
                <div style="font-size: 0.9rem; font-weight: 800;">BPJS Keliling</div>
            </div>
        </a>
    </div>

    <!-- Analytics Section -->
    <div class="chart-box" style="margin-bottom: 1.5rem;">
        <div style="margin-bottom: 1.5rem;">
            <h3 style="font-size: 1rem; font-weight: 900; color: var(--v-black); margin: 0;">Tren Pertumbuhan Anggota</h3>
            <p style="font-size: 0.75rem; color: var(--v-gray-500); margin-top: 2px;">Data pendaftaran anggota dalam 12 bulan terakhir.</p>
        </div>
        <div style="position: relative; width: 100%; height: 300px;"><canvas id="mainChart"></canvas></div>
    </div>

    <div class="v-grid v-grid-2">
        <div class="chart-box">
            <div style="margin-bottom: 1.5rem;">
                <h3 style="font-size: 0.9rem; font-weight: 900; color: var(--v-black); margin: 0;">Distribusi Gender</h3>
            </div>
            <div style="position: relative; width: 100%; height: 220px;"><canvas id="genderChart"></canvas></div>
        </div>
        <div class="chart-box">
            <div style="margin-bottom: 1.5rem;">
                <h3 style="font-size: 0.9rem; font-weight: 900; color: var(--v-black); margin: 0;">Klasifikasi Pekerjaan</h3>
            </div>
            <div style="position: relative; width: 100%; height: 220px;"><canvas id="occupationChart"></canvas></div>
        </div>
    </div>

    @push('scripts')
    @vite(['resources/css/pages/pengurus_dashboard.css', 'resources/js/pages/pengurus_dashboard.js'])
    @endpush
</x-pengurus-layout>
