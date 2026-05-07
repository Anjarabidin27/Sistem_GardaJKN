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
        .v-value-xl { font-size: 4rem; font-weight: 900; letter-spacing: -0.05em; }
        .v-value-lg { font-size: 2.25rem; font-weight: 900; letter-spacing: -0.05em; }
        .v-value-md { font-size: 1.75rem; font-weight: 900; letter-spacing: -0.05em; }
        
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
            padding: 0.25rem;
            border-radius: 1rem;
            border: 1px solid #cbd5e1;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
            margin-bottom: 2rem;
            display: flex;
            align-items: stretch;
        }
        .v-filter-group { 
            padding: 1rem 1.5rem; 
            flex: 1; 
            transition: background 0.2s;
        }
        .v-filter-group:hover {
            background: #f8fafc;
        }
        .v-filter-group + .v-filter-group { border-left: 2px solid #f1f5f9; }
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

        /* Mobile Adjustments */
        @media (max-width: 768px) {
            .page-header-responsive {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 0.5rem !important;
                margin-bottom: 0.75rem !important;
                padding-bottom: 0.75rem !important;
            }
            .page-title-responsive {
                font-size: 1.25rem !important;
                letter-spacing: -0.02em !important;
            }
            .header-actions-responsive {
                width: 100%;
                justify-content: space-between;
                gap: 8px !important;
            }
            .smart-filter-responsive {
                display: flex !important;
                flex-direction: column !important;
                padding: 12px !important;
                background: var(--v-white) !important;
                border: 1px solid var(--v-gray-100) !important;
                border-radius: 12px !important;
                gap: 10px !important;
                margin-bottom: 16px !important;
            }
            .filter-group-responsive {
                background: transparent !important;
                border: none !important;
                width: 100%;
                padding: 0 !important;
            }
            .filter-group-responsive + .filter-group-responsive {
                border-left: none !important;
                border-top: 1px solid #f1f5f9 !important;
                padding-top: 8px !important;
            }
            .v-label-caps { font-size: 9px !important; }
            .v-value-xl { font-size: 2.5rem !important; }
            .v-value-md { font-size: 1.5rem !important; }
            .card-total-peserta { grid-row: span 1 !important; }
            .icon-bg-responsive { width: 100px !important; height: 100px !important; right: -1rem !important; bottom: -1rem !important; }
            .v-table-wrap { margin-top: 1rem; border-radius: 8px; border: 1px solid var(--v-gray-100); overflow-x: auto; -webkit-overflow-scrolling: touch; }
            .v-table { font-size: 0.8rem !important; min-width: 600px !important; }
            .v-table td, .v-table th { padding: 0.75rem !important; }
            .smart-filter-responsive .v-input {
                border: 1px solid var(--v-gray-200) !important;
                padding: 10px 12px !important;
                border-radius: 8px !important;
                background: #f8fafc !important;
            }
        }    .date-filter-group {
                display: grid !important;
                grid-template-columns: 1fr 1fr;
                gap: 8px !important;
            }
            .date-filter-group span { display: none; }
            .filter-icon-hide {
                display: none !important;
            }
            .v-value-xl {
                font-size: 2.5rem !important;
            }
            .v-value-md {
                font-size: 1.5rem !important;
            }
            .v-card {
                padding: 1.25rem !important;
            }
            .nps-card-responsive {
                flex-direction: column;
                align-items: center;
                text-align: center;
                gap: 1.5rem !important;
            }
        }
    </style>

    <!-- Page Header -->
    <div class="v-flex v-justify-between v-items-center v-gap-4 page-header-responsive" style="margin-bottom: 2.5rem; padding-bottom: 2rem; border-bottom: 3px solid #000; position: relative;">
        <div style="position: absolute; bottom: -3px; left: 0; width: 80px; height: 3px; background: #2563eb; z-index: 10;"></div>
        <div class="v-flex v-flex-col">
            <nav class="v-flex v-items-center v-gap-2" style="margin-bottom: 0.75rem;">
                <span class="v-label-caps" style="color: #64748b;">Analytics Dashboard</span>
                <i data-lucide="chevron-right" style="width: 12px; height: 12px; color: #cbd5e1;"></i>
                <span class="v-label-caps" style="color: #000; background: #f1f5f9; padding: 2px 8px; border-radius: 4px;">Penyuluhan PIL</span>
            </nav>
            <h1 class="page-title-responsive" style="font-size: 2.5rem; font-weight: 900; letter-spacing: -0.04em; color: #000; margin: 0; line-height: 1;">Performance Analytics</h1>
            <p style="margin: 0.75rem 0 0; font-size: 0.95rem; color: #475569; font-weight: 500;">Pantau capaian real-time sosialisasi dan pemberdayaan masyarakat secara akurat.</p>
        </div>
        <div class="v-flex v-items-center v-gap-3 header-actions-responsive">
            <div class="v-flex v-items-center v-gap-2" style="background: var(--v-gray-50); padding: 0.375rem 1rem; border-radius: 9999px; border: 1px solid var(--v-gray-200);">
                <span style="width: 8px; height: 8px; border-radius: 50%; background: var(--v-emerald-500);"></span>
                <span id="ui-context" class="v-label-caps" style="color: var(--v-black);">Nasional</span>
            </div>
            <button class="v-btn-reset" id="btn-reset-filter">Reset Filters</button>
        </div>
    </div>

    <!-- Smart Filter -->
    <div class="v-flex v-filter-bar smart-filter-responsive">
        <div class="v-filter-group filter-group-responsive">
            <label class="v-label-caps" style="display: block; margin-bottom: 0.5rem;">Periode Sosialisasi</label>
            <div class="v-flex v-items-center v-gap-2 date-filter-group">
                <input type="date" id="filter-dari" class="v-input">
                <span style="color: var(--v-gray-200);">/</span>
                <input type="date" id="filter-sampai" class="v-input">
            </div>
        </div>
        <div class="v-filter-group filter-admin-only filter-group-responsive" style="display:none;">
            <label class="v-label-caps" style="display: block; margin-bottom: 0.5rem;">Wilayah Kerja</label>
            <select id="filter-kw" class="v-input" style="cursor: pointer;">
                <option value="">Seluruh Indonesia</option>
                @for($i=1; $i<=13; $i++)
                    <option value="Wilayah {{ $i }}">Kedeputian Wilayah {{ $i }}</option>
                @endfor
            </select>
        </div>
        <div class="v-filter-group filter-group-responsive">
            <label class="v-label-caps" style="display: block; margin-bottom: 0.5rem;">Cari Kegiatan</label>
            <input type="text" id="filter-judul" class="v-input" placeholder="Ketik nama kegiatan..." list="judul-list">
            <datalist id="judul-list"></datalist>
        </div>
        <div class="v-filter-group filter-group-responsive">
            <label class="v-label-caps" style="display: block; margin-bottom: 0.5rem;">Jam Mulai</label>
            <input type="time" id="filter-jam" class="v-input">
        </div>
        <div class="v-filter-group filter-group-responsive">
            <label class="v-label-caps" style="display: block; margin-bottom: 0.5rem;">Lokasi Kegiatan</label>
            <select id="filter-lokasi" class="v-input" style="cursor: pointer;">
                <option value="">Semua Lokasi</option>
                <option value="Kantor Kecamatan">Kantor Kecamatan</option>
                <option value="Kantor Kelurahan">Kantor Kelurahan</option>
                <option value="Kantor Desa">Kantor Desa</option>
                <option value="Puskesmas">Puskesmas</option>
                <option value="Rumah Warga">Rumah Warga</option>
                <option value="Sekolah/Kampus">Sekolah/Kampus</option>
                <option value="Kantor Instansi Pemerintah">Instansi Pemerintah</option>
                <option value="Kantor BUMN/BUMD/Swasta">BUMN/BUMD/Swasta</option>
                <option value="Lainnya">Lainnya</option>
            </select>
        </div>
        <div class="v-filter-group filter-group-responsive">
            <label class="v-label-caps" style="display: block; margin-bottom: 0.5rem;">Status Kegiatan</label>
            <select id="filter-status" class="v-input" style="cursor: pointer;">
                <option value="">Semua Status</option>
                <option value="completed">Selesai</option>
                <option value="ongoing">Berjalan</option>
            </select>
        </div>
        <div class="v-flex v-items-center filter-icon-hide" style="padding: 0 1.5rem;">
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
    <div class="v-grid v-grid-2 v-gap-6" style="margin-bottom: 3rem;">
        <div class="v-card v-flex-col">
            <p class="v-label-caps" style="margin-bottom: 1.5rem;">Segmen Peserta (Jumlah)</p>
            <div style="height: 250px;">
                <canvas id="chart-segmen"></canvas>
            </div>
        </div>
        <div class="v-card v-flex-col">
            <p class="v-label-caps" style="margin-bottom: 1.5rem;">Lokasi Kegiatan (Proporsi)</p>
            <div style="height: 250px;">
                <canvas id="chart-lokasi"></canvas>
            </div>
        </div>
    </div>

    <!-- Activity List Section -->
    <div class="v-card" style="margin-bottom: 3rem;">
        <p class="v-label-caps" style="margin-bottom: 1.5rem;">Daftar Kegiatan Terbaru (Hasil Filter)</p>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--v-gray-100); text-align: left;">
                        <th style="padding: 12px 8px;" class="v-label-caps">Judul Kegiatan</th>
                        <th style="padding: 12px 8px;" class="v-label-caps">Waktu</th>
                        <th style="padding: 12px 8px;" class="v-label-caps">Lokasi</th>
                        <th style="padding: 12px 8px;" class="v-label-caps">Status</th>
                    </tr>
                </thead>
                <tbody id="table-kegiatan-body">
                    <!-- Data will be rendered here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Rating Section -->
    <div class="v-grid v-grid-2 v-gap-6" style="margin-bottom: 3rem; padding-bottom: 2rem;">
        <div class="v-card-dark nps-card-responsive" style="padding: 2.5rem; border-radius: 2rem; display: flex; flex-wrap: wrap; align-items: center; gap: 3rem;">
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
                    status: document.getElementById('filter-status').value,
                    lokasi_kegiatan: document.getElementById('filter-lokasi').value,
                    judul: document.getElementById('filter-judul').value,
                    jam_mulai: document.getElementById('filter-jam').value
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

                        // Render Autocomplete Suggestions
                        const datalist = document.getElementById('judul-list');
                        if (datalist && d.available_titles) {
                            datalist.innerHTML = '';
                            d.available_titles.forEach(title => {
                                datalist.innerHTML += `<option value="${title}">`;
                            });
                        }

                        // Render Table
                        const tbody = document.getElementById('table-kegiatan-body');
                        if (tbody) {
                            tbody.innerHTML = d.kegiatan_list.length > 0 ? '' : '<tr><td colspan="4" style="text-align:center; padding:20px; color:var(--v-gray-400);">Tidak ada kegiatan yang sesuai filter</td></tr>';
                            d.kegiatan_list.forEach(k => {
                                tbody.innerHTML += `
                                    <tr style="border-bottom: 1px solid var(--v-gray-100);">
                                        <td style="padding: 12px 8px; font-weight: 700;">${k.judul}</td>
                                        <td style="padding: 12px 8px; color: var(--v-gray-500);">${k.tanggal} | ${k.jam}</td>
                                        <td style="padding: 12px 8px;">${k.lokasi}</td>
                                        <td style="padding: 12px 8px;"><span class="v-badge" style="background:var(--v-gray-100);">${k.status}</span></td>
                                    </tr>
                                `;
                            });
                        }
                    });
            }

            ['filter-dari', 'filter-sampai', 'filter-kw', 'filter-status', 'filter-lokasi', 'filter-jam'].forEach(id => {
                const el = document.getElementById(id); if (el) el.addEventListener('change', fetchDashboardData);
            });
            
            const filterJudul = document.getElementById('filter-judul');
            if(filterJudul) {
                let typingTimer;
                filterJudul.addEventListener('input', () => {
                    clearTimeout(typingTimer);
                    typingTimer = setTimeout(fetchDashboardData, 500);
                });
            }

            document.getElementById('btn-reset-filter').addEventListener('click', () => {
                ['filter-dari', 'filter-sampai', 'filter-kw', 'filter-status', 'filter-lokasi', 'filter-judul', 'filter-jam'].forEach(id => {
                    const el = document.getElementById(id); if (el) el.value = '';
                });
                fetchDashboardData();
            });

            fetchDashboardData();
        });
    </script>
    @endpush
</x-admin-layout>
