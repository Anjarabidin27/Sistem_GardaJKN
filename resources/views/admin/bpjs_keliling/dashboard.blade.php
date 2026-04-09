<x-admin-layout title="Performance Analytics - BPJS Keliling">
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
            --v-rose-500: #f43f5e;
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
        .v-card:hover { border-color: var(--v-black); transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); }
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

        .v-badge {
            padding: 2px 8px;
            border-radius: 9999px;
            font-size: 9px;
            font-weight: 900;
            text-transform: uppercase;
        }
    </style>

    <!-- Page Header -->
    <div class="v-flex v-justify-between v-items-center v-gap-4" style="margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid var(--v-gray-100);">
        <div class="v-flex v-flex-col">
            <nav class="v-flex v-items-center v-gap-2" style="margin-bottom: 0.5rem;">
                <span class="v-label-caps">Analytics</span>
                <i data-lucide="chevron-right" style="width: 12px; height: 12px; color: var(--v-gray-400);"></i>
                <span class="v-label-caps" style="color: var(--v-black);">OP - BPJS Keliling</span>
            </nav>
            <h1 style="font-size: 1.875rem; font-weight: 900; letter-spacing: -0.025em; color: var(--v-black); margin: 0;">Performance Monitoring</h1>
            <p style="margin: 0.25rem 0 0; font-size: 0.875rem; color: var(--v-gray-500);">Data real-time diselaraskan dengan spesifikasi pelaporan (Excel).</p>
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
            <label class="v-label-caps" style="display: block; margin-bottom: 0.5rem;">Periode</label>
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
            <label class="v-label-caps" style="display: block; margin-bottom: 0.5rem;">Jenis Layanan</label>
            <select id="filter-jenis" class="v-input" style="cursor: pointer;">
                <option value="">Semua Jenis</option>
                <option value="MCS">MCS Sinergi</option>
                <option value="Mandiri">BPJS Keliling Mandiri</option>
            </select>
        </div>
        <div class="v-flex v-items-center" style="padding: 0 1.5rem;">
            <div style="padding: 0.5rem; background: var(--v-gray-50); border-radius: 50%;">
                <i data-lucide="filter" style="width: 16px; height: 16px; color: var(--v-black);"></i>
            </div>
        </div>
    </div>

    <!-- KPI Grid (Specs 1, 2, 3, 4) -->
    <div class="v-grid v-grid-4 v-gap-6" style="margin-bottom: 2rem;">
        <!-- Specs 4: Jumlah Peserta (Total) -->
        <div class="v-card v-card-dark v-flex-col v-justify-between" style="position: relative; overflow: hidden; grid-row: span 2;">
            <div style="position: relative; z-index: 10;">
                <p class="v-label-caps" style="color: var(--v-gray-400); margin-bottom: 0.25rem;">4. Jumlah Peserta</p>
                <h3 style="font-size: 5rem; font-weight: 900; letter-spacing: -0.05em; margin: 1rem 0;" id="tot-peserta">0</h3>
                <span class="v-label-caps" style="color: var(--v-gray-500);">Total terlayani</span>
            </div>
            <i data-lucide="users" style="position: absolute; right: -2rem; bottom: -2rem; width: 180px; height: 180px; opacity: 0.1; color: var(--v-white);"></i>
        </div>

        <!-- Specs 1: Informasi -->
        <div class="v-card v-flex-col">
            <p class="v-label-caps">1. Informasi</p>
            <div class="v-flex v-items-baseline v-gap-2" style="margin-top: 0.5rem;">
                <h3 class="v-value-md" id="stat-info-count" style="margin: 0; color: var(--v-black);">0</h3>
                <span class="v-badge" style="background: var(--v-gray-50); color: var(--v-gray-500);" id="stat-info-pct">0%</span>
            </div>
        </div>

        <!-- Specs 2: Administrasi -->
        <div class="v-card v-flex-col">
            <p class="v-label-caps">2. Administrasi</p>
            <div class="v-flex v-items-baseline v-gap-2" style="margin-top: 0.5rem;">
                <h3 class="v-value-md" id="stat-admin-count" style="margin: 0; color: var(--v-black);">0</h3>
                <span class="v-badge" style="background: var(--v-gray-50); color: var(--v-gray-500);" id="stat-admin-pct">0%</span>
            </div>
        </div>

        <!-- Specs 3: Pengaduan -->
        <div class="v-card v-flex-col">
            <p class="v-label-caps">3. Pengaduan</p>
            <div class="v-flex v-items-baseline v-gap-2" style="margin-top: 0.5rem;">
                <h3 class="v-value-md" id="stat-aduan-count" style="margin: 0; color: var(--v-black);">0</h3>
                <span class="v-badge" style="background: var(--v-gray-50); color: var(--v-gray-500);" id="stat-aduan-pct">0%</span>
            </div>
        </div>

        <!-- Specs 5: Status Transaksi -->
        <div class="v-card v-flex-col">
            <p class="v-label-caps">5. Status Transaksi</p>
            <div class="v-flex v-justify-between v-items-end" style="margin-top: 1rem;">
                <div>
                    <span class="v-label-caps" style="font-size: 8px;">Berhasil</span>
                    <h4 style="font-size: 1.5rem; font-weight: 900; color: var(--v-emerald-600); margin: 0;" id="stat-trans-success">0</h4>
                </div>
                <div style="text-align: right;">
                    <span class="v-label-caps" style="font-size: 8px;">Gagal</span>
                    <h4 style="font-size: 1.5rem; font-weight: 900; color: var(--v-rose-500); margin: 0;" id="stat-trans-failed">0</h4>
                </div>
            </div>
            <div class="v-progress-bg" style="margin-top: 1rem;">
                <div id="trans-success-bar" class="v-progress-bar" style="background: var(--v-black); width: 0%;"></div>
            </div>
        </div>

        <!-- Specs 6: SUPEL -->
        <div class="v-card v-flex-col">
            <p class="v-label-caps">6. SUPEL (Kepuasan)</p>
            <div class="v-flex v-items-center v-gap-3" style="margin-top: 1rem;">
                <h3 class="v-value-md" id="stat-supel" style="margin: 0; color: var(--v-black);">0%</h3>
                <i data-lucide="smile" style="color: var(--v-emerald-500); width: 24px; height: 24px;"></i>
            </div>
            <p style="font-size: 10px; color: var(--v-gray-400); margin-top: 0.5rem;">Avg Kepuasan Pelanggan</p>
        </div>

        <!-- Specs 8: Capaian Desa -->
        <div class="v-card v-flex-col">
            <p class="v-label-caps">8. Capaian Desa</p>
            <div class="v-flex v-items-baseline v-gap-2" style="margin-top: 1rem;">
                <h3 class="v-value-md" id="stat-desa-count" style="margin: 0; color: var(--v-black);">0</h3>
                <span class="v-label-caps">Desa Terlayani</span>
            </div>
        </div>
    </div>

    <!-- Charts Row (Specs 7, 9, 10, 11, 12) -->
    <div class="v-grid v-grid-3 v-gap-6" style="margin-bottom: 2rem;">
        <!-- Specs 7: Segmen Peserta -->
        <div class="v-card v-flex-col">
            <p class="v-label-caps" style="margin-bottom: 1.5rem;">7. Segmen Peserta</p>
            <div style="height: 200px;">
                <canvas id="chart-segmen"></canvas>
            </div>
        </div>

        <!-- Specs 10: Kuadran -->
        <div class="v-card v-flex-col">
            <p class="v-label-caps" style="margin-bottom: 1.5rem;">10. Kuadran</p>
            <div style="height: 200px;">
                <canvas id="chart-kuadran"></canvas>
            </div>
        </div>

        <!-- Specs 11: Jenis Kegiatan -->
        <div class="v-card v-flex-col">
            <p class="v-label-caps" style="margin-bottom: 1.5rem;">11. Jenis Kegiatan</p>
            <div style="height: 200px;">
                <canvas id="chart-jenis-keg"></canvas>
            </div>
        </div>
    </div>

    <div class="v-grid v-grid-2 v-gap-6" style="margin-bottom: 3rem;">
        <!-- Specs 9: Transaksi Layanan Admin -->
        <div class="v-card v-flex-col">
            <p class="v-label-caps" style="margin-bottom: 1.5rem;">9. Transaksi Layanan Admin</p>
            <div style="height: 250px;">
                <canvas id="chart-admin-detail"></canvas>
            </div>
        </div>

        <!-- Specs 12: Lokasi Kegiatan -->
        <div class="v-card v-flex-col">
            <p class="v-label-caps" style="margin-bottom: 1.5rem;">12. Lokasi Kegiatan</p>
            <div style="height: 250px;">
                <canvas id="chart-lokasi"></canvas>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let charts = {};
            
            const setEl = (id, val) => { const el = document.getElementById(id); if (el) el.innerText = val; };
            const setStyleWidth = (id, pct) => { const el = document.getElementById(id); if (el) el.style.width = pct + '%'; };

            const role = localStorage.getItem('user_role');
            if (role === 'superadmin' || role === 'administrator') {
                document.querySelectorAll('.filter-admin-only').forEach(el => el.style.display = 'block');
            }

            function updateChart(id, type, labels, data, colors = '#000') {
                if (charts[id]) charts[id].destroy();
                charts[id] = new Chart(document.getElementById(id), {
                    type: type,
                    data: {
                        labels: labels,
                        datasets: [{ 
                            data: data, 
                            backgroundColor: Array.isArray(colors) ? colors : colors,
                            borderWidth: 0,
                            borderRadius: type === 'bar' ? 4 : 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: type !== 'bar', position: 'bottom', labels: { boxWidth: 10, font: { size: 9, weight: 'bold' } } } },
                        scales: type === 'bar' ? { 
                            y: { beginAtZero: true, grid: { color: '#f3f4f6' }, ticks: { font: { size: 8 } } },
                            x: { grid: { display: false }, ticks: { font: { size: 8 } } }
                        } : {}
                    }
                });
            }

            function fetchDashboardData() {
                const params = {
                    dari: document.getElementById('filter-dari').value,
                    sampai: document.getElementById('filter-sampai').value,
                    kedeputian_wilayah: document.getElementById('filter-kw').value,
                    jenis_layanan: document.getElementById('filter-jenis').value
                };

                // FIXED ROUTE: 'admin/bpjs-keliling/dashboard' matches routes/api.php
                window.axios.get('admin/bpjs-keliling/dashboard', { params })
                    .then(res => {
                        const d = res.data.data;
                        
                        // Header
                        setEl('ui-context', d.context || 'Nasional');
                        
                        // KPI Grid
                        setEl('tot-peserta', d.total_peserta.toLocaleString());
                        
                        setEl('stat-info-count', d.layanan_informasi.count.toLocaleString());
                        setEl('stat-info-pct', d.layanan_informasi.pct + '%');
                        
                        setEl('stat-admin-count', d.layanan_administrasi.count.toLocaleString());
                        setEl('stat-admin-pct', d.layanan_administrasi.pct + '%');
                        
                        setEl('stat-aduan-count', d.layanan_pengaduan.count.toLocaleString());
                        setEl('stat-aduan-pct', d.layanan_pengaduan.pct + '%');
                        
                        setEl('stat-trans-success', d.status_transaksi.berhasil.toLocaleString());
                        setEl('stat-trans-failed', d.status_transaksi.gagal.toLocaleString());
                        setStyleWidth('trans-success-bar', d.status_transaksi.pct_berhasil);
                        
                        setEl('stat-supel', d.avg_supel + '%');
                        setEl('stat-desa-count', d.capaian_desa.count);

                        // Charts
                        updateChart('chart-segmen', 'doughnut', Object.keys(d.per_segmen), Object.values(d.per_segmen), ['#000', '#2563eb', '#10b981', '#f59e0b', '#ef4444']);
                        updateChart('chart-kuadran', 'bar', Object.keys(d.per_kuadran), Object.values(d.per_kuadran), '#000');
                        
                        const jenisLabels = Object.values(d.per_jenis_kegiatan).map(v => v.label);
                        const jenisValues = Object.values(d.per_jenis_kegiatan).map(v => v.count);
                        updateChart('chart-jenis-keg', 'doughnut', jenisLabels, jenisValues, ['#000', '#6366f1']);
                        
                        updateChart('chart-admin-detail', 'bar', Object.keys(d.transaksi_administrasi_breakdown), Object.values(d.transaksi_administrasi_breakdown), '#000');
                        updateChart('chart-lokasi', 'bar', Object.keys(d.per_lokasi), Object.values(d.per_lokasi), '#000');
                        
                        if(window.lucide) window.lucide.createIcons();
                    })
                    .catch(err => {
                        console.error("Dashboard Fetch Error:", err);
                        // Show visible error if needed, but console is start
                    });
            }

            // Listeners
            ['filter-dari', 'filter-sampai', 'filter-kw', 'filter-jenis'].forEach(id => {
                const el = document.getElementById(id); if (el) el.addEventListener('change', fetchDashboardData);
            });

            document.getElementById('btn-reset-filter').addEventListener('click', () => {
                ['filter-dari', 'filter-sampai', 'filter-kw', 'filter-jenis'].forEach(id => {
                    const el = document.getElementById(id); if (el) el.value = '';
                });
                fetchDashboardData();
            });

            fetchDashboardData();
        });
    </script>
    @endpush
</x-admin-layout>
