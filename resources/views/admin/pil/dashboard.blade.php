<x-admin-layout title="Performance Analytics - PIL">
    <style>
        :root {
            --v-black: #000;
            --v-white: #fff;
            --v-gray-50: #f9fafb;
            --v-gray-100: #f3f4f6;
            --v-gray-200: #e5e7eb;
            --v-gray-400: #9ca3af;
            --v-gray-500: #6b7280;
            --v-blue-600: #2563eb;
            --v-indigo-500: #6366f1;
            --v-emerald-500: #10b981;
            --v-emerald-600: #059669;
        }

        .v-flex { display: flex; }
        .v-flex-col { display: flex; flex-direction: column; }
        .v-items-center { align-items: center; }
        .v-items-start { align-items: flex-start; }
        .v-justify-between { justify-content: space-between; }
        .v-gap-2 { gap: 0.5rem; }
        .v-gap-3 { gap: 0.75rem; }
        .v-gap-4 { gap: 1rem; }
        .v-gap-6 { gap: 1.5rem; }
        
        .v-grid { display: grid; }
        .v-grid-4 { grid-template-columns: repeat(4, 1fr); }
        .v-grid-3 { grid-template-columns: repeat(3, 1fr); }
        .v-grid-2 { grid-template-columns: repeat(2, 1fr); }
        
        @media (max-width: 1024px) {
            .v-grid-4, .v-grid-3 { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            .v-grid-4, .v-grid-3, .v-grid-2 { grid-template-columns: 1fr; }
        }

        .v-card {
            background: var(--v-white);
            padding: 1.5rem;
            border-radius: 1rem;
            border: 1px solid var(--v-gray-100);
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
        }
        .v-card:hover { border-color: var(--v-black); transform: translateY(-2px); }
        .v-card-dark {
            background: var(--v-black);
            color: var(--v-white);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .v-label-caps {
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            color: var(--v-gray-400);
        }
        .v-value-lg { font-size: 3rem; font-weight: 900; letter-spacing: -0.05em; }
        .v-value-md { font-size: 2.25rem; font-weight: 900; letter-spacing: -0.05em; }
        
        .v-progress-bg { background: var(--v-gray-100); height: 8px; border-radius: 9999px; overflow: hidden; width: 100%; }
        .v-progress-bar { height: 100%; transition: width 1s ease-out; }
        
        .v-btn-reset {
            padding: 0.5rem 1rem;
            font-size: 0.75rem;
            font-weight: 700;
            border: 1px solid var(--v-black);
            background: var(--v-white);
            border-radius: 0.375rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .v-btn-reset:hover { background: var(--v-black); color: var(--v-white); }
        
        .v-filter-bar {
            background: var(--v-white);
            padding: 0.5rem;
            border-radius: 0.75rem;
            border: 1px solid var(--v-gray-100);
            margin-bottom: 2rem;
        }
        .v-filter-group { padding: 0.75rem; flex: 1; }
        .v-filter-group + .v-filter-group { border-left: 1px solid var(--v-gray-100); }
        .v-input {
            width: 100%;
            font-size: 0.75rem;
            font-weight: 700;
            border: 0;
            background: transparent;
            padding: 0;
            color: var(--v-black);
        }
        .v-input:focus { outline: none; }
    </style>

    <!-- Page Header -->
    <div class="v-flex v-justify-between v-items-center v-gap-4" style="margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid var(--v-gray-100);">
        <div class="v-flex v-flex-col">
            <nav class="v-flex v-items-center v-gap-2" style="margin-bottom: 0.5rem;">
                <span class="v-label-caps">Analytics</span>
                <i data-lucide="chevron-right" style="width: 12px; height: 12px; color: var(--v-gray-400);"></i>
                <span class="v-label-caps" style="color: var(--v-black);">Pemberian Informasi Langsung</span>
            </nav>
            <h1 style="font-size: 1.875rem; font-weight: 900; letter-spacing: -0.025em; color: var(--v-black); margin: 0;">Performance Analytics</h1>
            <p style="margin: 0.25rem 0 0; font-size: 0.875rem; color: var(--v-gray-500);">Monitoring real-time capaian sosialisasi dan pemberdayaan masyarakat.</p>
        </div>
        <div class="v-flex v-items-center v-gap-3">
            <div class="v-flex v-items-center v-gap-2" style="background: var(--v-gray-50); padding: 0.375rem 1rem; border-radius: 9999px; border: 1px solid var(--v-gray-200);">
                <span style="width: 8px; height: 8px; border-radius: 50%; background: var(--v-emerald-500);"></span>
                <span id="ui-context" class="v-label-caps" style="color: var(--v-black);">Nasional</span>
            </div>
            <button class="v-btn-reset" id="btn-reset-filter">Reset Filters</button>
        </div>
    </div>

    <!-- Smart Filter -->
    <div class="v-flex v-filter-bar">
        <div class="v-filter-group">
            <label class="v-label-caps" style="display: block; margin-bottom: 0.5rem;">Periode Sosialisasi</label>
            <div class="v-flex v-items-center v-gap-2">
                <input type="date" id="filter-dari" class="v-input">
                <span style="color: var(--v-gray-200);">/</span>
                <input type="date" id="filter-sampai" class="v-input">
            </div>
        </div>
        <div class="v-filter-group filter-admin-only" style="display:none;">
            <label class="v-label-caps" style="display: block; margin-bottom: 0.5rem;">Wilayah Kerja</label>
            <select id="filter-kw" class="v-input" style="cursor: pointer;">
                <option value="">Seluruh Indonesia</option>
                @for($i=1; $i<=13; $i++)
                    <option value="Wilayah {{ $i }}">Kedeputian Wilayah {{ $i }}</option>
                @endfor
            </select>
        </div>
        <div class="v-filter-group">
            <label class="v-label-caps" style="display: block; margin-bottom: 0.5rem;">Status Kegiatan</label>
            <select id="filter-status" class="v-input" style="cursor: pointer;">
                <option value="">Semua Status</option>
                <option value="completed">Selesai</option>
                <option value="ongoing">Berjalan</option>
            </select>
        </div>
        <div class="v-flex v-items-center" style="padding: 0 1.5rem;">
            <div style="padding: 0.5rem; background: var(--v-gray-50); border-radius: 50%;">
                <i data-lucide="filter" style="width: 16px; height: 16px; color: var(--v-black);"></i>
            </div>
        </div>
    </div>

    <!-- KPI Bento Grid -->
    <div class="v-grid v-grid-4 v-gap-6" style="margin-bottom: 2rem;">
        <div class="v-card v-card-dark v-flex-col v-justify-between" style="position: relative; overflow: hidden;">
            <div style="position: relative; z-index: 10;">
                <p class="v-label-caps" style="color: var(--v-gray-400); margin-bottom: 0.25rem;">Total Peserta</p>
                <div class="v-flex v-items-baseline v-gap-2">
                    <h3 class="v-value-lg" id="tot-pes" style="margin: 0;">0</h3>
                    <span class="v-label-caps" style="color: var(--v-gray-500);">Realisasi</span>
                </div>
            </div>
            <div style="position: relative; z-index: 10; margin-top: 1.5rem;" class="v-flex v-justify-between v-items-center">
                <div class="v-label-caps" style="color: var(--v-gray-500);">Interaksi Aktif</div>
                <i data-lucide="trending-up" style="width: 16px; height: 16px; color: var(--v-emerald-500);"></i>
            </div>
        </div>

        <div class="v-card v-flex-col">
            <p class="v-label-caps" style="margin-bottom: 1.5rem;">Coverage JKN (%)</p>
            <div class="v-flex v-items-baseline v-gap-2" style="margin-bottom: 0.75rem;">
                <h3 class="v-value-md" id="coverage-sosialisasi" style="color: var(--v-black); margin: 0;">0.0</h3>
                <span class="v-label-caps">%</span>
            </div>
            <div class="v-progress-bg">
                <div id="coverage-bar" class="v-progress-bar" style="width: 0%; background: var(--v-black);"></div>
            </div>
        </div>

        <div class="v-card v-flex-col">
            <p class="v-label-caps" style="margin-bottom: 1.5rem;">Capaian Desa</p>
            <div class="v-flex v-items-baseline v-gap-2" style="margin-bottom: 0.75rem;">
                <h3 class="v-value-md" id="desa-count" style="color: var(--v-black); margin: 0;">0</h3>
                <span class="v-label-caps">Desa Terjangkau</span>
            </div>
            <div class="v-progress-bg">
                <div id="desa-bar" class="v-progress-bar" style="width: 0%; background: var(--v-indigo-500);"></div>
            </div>
        </div>

        <div class="v-card v-flex-col">
            <p class="v-label-caps" style="margin-bottom: 1.5rem;">KPI Pemahaman</p>
            <h3 class="v-value-md" id="avg-pemahaman" style="color: var(--v-black); margin: 0;">0%</h3>
            <p class="v-label-caps" style="margin-top: 0.5rem;">Rincian Post-Test</p>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="v-grid v-grid-2 v-gap-6" style="margin-bottom: 2rem;">
        <div class="v-card v-flex-col">
            <h3 style="font-size: 1.25rem; font-weight: 900; color: var(--v-black); margin: 0;">Rincian Segmen Peserta</h3>
            <div style="height: 300px; margin-top: 2rem;">
                <canvas id="chart-segmen"></canvas>
            </div>
        </div>
        <div class="v-card v-flex-col">
            <h3 style="font-size: 1.25rem; font-weight: 900; color: var(--v-black); margin: 0;">Sebaran Lokasi</h3>
            <div style="height: 300px; margin-top: 2rem;">
                <canvas id="chart-lokasi"></canvas>
            </div>
        </div>
    </div>

    <!-- Rating Section -->
    <div class="v-grid v-grid-2 v-gap-6" style="margin-bottom: 3rem; padding-bottom: 2rem;">
        <div class="v-card-dark" style="padding: 2.5rem; border-radius: 2rem; display: flex; flex-wrap: wrap; align-items: center; gap: 3rem;">
            <div style="text-align: center; min-width: 150px;">
                <p class="v-label-caps" style="color: var(--v-gray-400);">Avg NPS</p>
                <h2 style="font-size: 4rem; font-weight: 900; color: var(--v-white); margin: 0;" id="avg-nps-total">0.0</h2>
            </div>
            <div style="flex: 1; min-width: 200px; display: flex; flex-direction: column; gap: 1rem;">
                <div>
                    <div class="v-flex v-justify-between v-label-caps" style="color: var(--v-gray-400); margin-bottom: 0.25rem;">
                        <span>Ketertarikan</span><span id="nps-val-1">0</span>
                    </div>
                    <div class="v-progress-bg" style="height: 4px; background: rgba(255,255,255,0.1);"><div id="nps-bar-1" class="v-progress-bar" style="background: var(--v-white);"></div></div>
                </div>
                <div>
                    <div class="v-flex v-justify-between v-label-caps" style="color: var(--v-gray-400); margin-bottom: 0.25rem;">
                        <span>Rekomendasi</span><span id="nps-val-2">0</span>
                    </div>
                    <div class="v-progress-bg" style="height: 4px; background: rgba(255,255,255,0.1);"><div id="nps-bar-2" class="v-progress-bar" style="background: var(--v-white);"></div></div>
                </div>
            </div>
        </div>
        <div class="v-card v-flex-col v-justify-between">
            <div>
                <h3 style="font-size: 1.5rem; font-weight: 900; color: var(--v-black);">Total Sesi</h3>
                <p style="font-size: 0.875rem; color: var(--v-gray-500);">Jumlah kegiatan di lapangan.</p>
            </div>
            <div class="v-flex v-items-baseline v-gap-4">
                <span style="font-size: 5rem; font-weight: 900; color: var(--v-black);" id="tot-keg">0</span>
                <span class="v-label-caps">Kegiatan Selesai</span>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let chartSegmen, chartLokasi;
            const setEl = (id, val) => { const el = document.getElementById(id); if (el) el.innerText = val; };
            const setStyleWidth = (id, pct) => { const el = document.getElementById(id); if (el) el.style.width = pct + '%'; };

            const role = localStorage.getItem('user_role');
            if (role === 'superadmin' || role === 'administrator') {
                document.querySelectorAll('.filter-admin-only').forEach(el => el.style.display = 'block');
            }

            function fetchDashboardData() {
                const params = {
                    dari: document.getElementById('filter-dari').value,
                    sampai: document.getElementById('filter-sampai').value,
                    kedeputian_wilayah: document.getElementById('filter-kw').value,
                    status: document.getElementById('filter-status').value
                };

                window.axios.get('admin/pil/dashboard', { params })
                    .then(res => {
                        const d = res.data.data;
                        setEl('ui-context', d.context || 'Nasional');
                        setEl('tot-pes', d.total_peserta.toLocaleString());
                        setEl('coverage-sosialisasi', d.coverage_sosialisasi.toFixed(2));
                        setStyleWidth('coverage-bar', Math.min(d.coverage_sosialisasi, 100));
                        setEl('desa-count', d.total_desa_terjamah.toLocaleString());
                        setStyleWidth('desa-bar', Math.min(d.persentase_desa, 100));
                        setEl('tot-keg', d.total_kegiatan);
                        setEl('avg-pemahaman', d.rata_pemahaman.toFixed(1) + '%');

                        const avgNps = (Number(d.rata_nps_ketertarikan) + Number(d.rata_nps_rekomendasi_program)) / 2;
                        setEl('avg-nps-total', avgNps.toFixed(1));
                        setEl('nps-val-1', Number(d.rata_nps_ketertarikan).toFixed(1));
                        setStyleWidth('nps-bar-1', (Number(d.rata_nps_ketertarikan)/10)*100);
                        setEl('nps-val-2', Number(d.rata_nps_rekomendasi_program).toFixed(1));
                        setStyleWidth('nps-bar-2', (Number(d.rata_nps_rekomendasi_program)/10)*100);

                        // Charts
                        if (chartSegmen) chartSegmen.destroy();
                        chartSegmen = new Chart(document.getElementById('chart-segmen'), {
                            type: 'bar',
                            data: {
                                labels: Object.keys(d.segmen_breakdown),
                                datasets: [{ data: Object.values(d.segmen_breakdown), backgroundColor: '#000', borderRadius: 4 }]
                            },
                            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
                        });

                        if (chartLokasi) chartLokasi.destroy();
                        chartLokasi = new Chart(document.getElementById('chart-lokasi'), {
                            type: 'doughnut',
                            data: {
                                labels: Object.keys(d.lokasi_breakdown),
                                datasets: [{ data: Object.values(d.lokasi_breakdown), backgroundColor: ['#000', '#2563eb', '#10b981', '#f59e0b'], borderWidth: 0 }]
                            },
                            options: { responsive: true, maintainAspectRatio: false, cutout: '80%', plugins: { legend: { position: 'bottom' } } }
                        });
                        
                        if(window.lucide) window.lucide.createIcons();
                    });
            }

            ['filter-dari', 'filter-sampai', 'filter-kw', 'filter-status'].forEach(id => {
                const el = document.getElementById(id); if (el) el.addEventListener('change', fetchDashboardData);
            });

            document.getElementById('btn-reset-filter').addEventListener('click', () => {
                ['filter-dari', 'filter-sampai', 'filter-kw', 'filter-status'].forEach(id => {
                    const el = document.getElementById(id); if (el) el.value = '';
                });
                fetchDashboardData();
            });

            fetchDashboardData();
        });
    </script>
    @endpush
</x-admin-layout>
