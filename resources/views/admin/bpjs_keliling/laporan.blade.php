<x-admin-layout title="Daftar Terinput - BPJS Keliling">
    <style>
        .filter-pill {
            display: inline-flex; align-items: center; gap: 6px;
            background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe;
            padding: 4px 12px; border-radius: 20px; font-size: 0.72rem; font-weight: 700;
        }
        .tab-btn {
            padding: 8px 20px; border-radius: 8px; font-size: 0.82rem; font-weight: 700;
            border: 1px solid #e2e8f0; background: #f8fafc; color: #64748b;
            cursor: pointer; transition: all 0.2s;
            flex: 1; text-align: center;
        }
        .tab-btn.active {
            background: #0f172a; color: #fff; border-color: #0f172a;
        }
        .range-warning {
            font-size: 0.72rem; color: #b45309; background: #fffbeb;
            border: 1px solid #fde68a; padding: 6px 12px; border-radius: 6px;
            display: none; margin-top: 8px;
        }
        
        /* Mobile Compact Layout */
        @media (max-width: 768px) {
            .page-header { flex-direction: column; align-items: flex-start !important; gap: 12px; }
            .header-actions { width: 100%; display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
            .header-actions .btn { width: 100%; justify-content: center; padding: 10px !important; }
            
            .tab-container { flex-direction: column; gap: 8px; border-bottom: none !important; padding-bottom: 0 !important; }
            .tab-btn { width: 100%; }
            
            .filter-form { flex-direction: column; align-items: stretch !important; gap: 12px !important; }
            .filter-form > div { width: 100% !important; min-width: 100% !important; }
            .filter-form button { width: 100%; padding: 12px !important; }
            
            #stats-container { grid-template-columns: 1fr 1fr !important; }
            
            .v-table-wrapper { overflow-x: auto; }
            .v-table { min-width: 700px; }
        }
    </style>

    <!-- Page Header -->
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 800; color: #0F172A; letter-spacing: -0.02em; margin: 0;">Daftar Terinput</h1>
            <p style="font-size: 0.875rem; color: #64748B; margin-top: 4px;">Rekapitulasi laporan operasional BPJS Keliling.</p>
        </div>
        <div class="header-actions" style="display: flex; gap: 10px; align-items: center;">
            <button class="btn" style="background: #fff; border: 1px solid #E2E8F0; color: #0F172A; padding: 9px 16px; font-size: 0.8rem; font-weight: 600;" onclick="window.printReport()">
                <i data-lucide="printer" style="width:14px;height:14px"></i> Cetak
            </button>
            <button id="btn-export" class="btn" style="background: #10B981; color: #fff; padding: 9px 18px; font-size: 0.8rem; font-weight: 700; opacity: 0.5; cursor: not-allowed;" disabled onclick="window.exportExcel()">
                <i data-lucide="file-spreadsheet" style="width:14px;height:14px"></i> Export Excel
            </button>
        </div>
    </div>

    <!-- Filter Mode Tabs -->
    <div style="background: #fff; border: 1px solid #E2E8F0; border-radius: 14px; padding: 20px 24px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
        <div class="tab-container" style="display: flex; gap: 8px; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 16px;">
            <button class="tab-btn active" id="tab-kegiatan" onclick="switchTab('kegiatan')">
                <i data-lucide="calendar-check" style="width:14px;height:14px;display:inline;vertical-align:-2px;margin-right:4px;"></i> Per Kegiatan
            </button>
            <button class="tab-btn" id="tab-range" onclick="switchTab('range')">
                <i data-lucide="calendar-range" style="width:14px;height:14px;display:inline;vertical-align:-2px;margin-right:4px;"></i> Rentang Tanggal
            </button>
            <button class="tab-btn" id="tab-month" onclick="switchTab('month')">
                <i data-lucide="calendar-days" style="width:14px;height:14px;display:inline;vertical-align:-2px;margin-right:4px;"></i> Per Bulan
            </button>
        </div>

        <!-- Filter: Per Kegiatan -->
        <div id="panel-kegiatan">
            <form id="filterForm" class="filter-form" style="display: flex; align-items: flex-end; gap: 14px; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 200px;">
                    <label style="display: block; font-size: 0.65rem; font-weight: 800; color: #64748B; text-transform: uppercase; margin-bottom: 6px;">Pilih Kegiatan</label>
                    <select id="kegiatan_id" class="form-input" style="height: 40px; font-size: 0.85rem; border-color: #E2E8F0;" required>
                        <option value="">-- Pilih Kegiatan --</option>
                    </select>
                </div>
                <div style="width: 200px;">
                    <label style="display: block; font-size: 0.65rem; font-weight: 800; color: #64748B; text-transform: uppercase; margin-bottom: 6px;">Cari NIK / Layanan</label>
                    <input type="text" id="search_peserta" class="form-input" style="height: 40px; font-size: 0.85rem; border-color: #E2E8F0;" placeholder="Ketik untuk filter...">
                </div>
                <div style="width: 150px;">
                    <label style="display: block; font-size: 0.65rem; font-weight: 800; color: #64748B; text-transform: uppercase; margin-bottom: 6px;">Status</label>
                    <select id="status_filter" class="form-input" style="height: 40px; font-size: 0.85rem; border-color: #E2E8F0;">
                        <option value="">Semua</option>
                        <option value="Berhasil">Berhasil</option>
                        <option value="Tidak Berhasil">Gagal</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" style="height: 40px; padding: 0 22px; font-size: 0.85rem; font-weight: 700;">
                    Tampilkan
                </button>
            </form>
        </div>

        <!-- Filter: Rentang Tanggal (untuk Export) -->
        <!-- Filter: Per Bulan -->
        <div id="panel-month" style="display: none;">
            <form id="monthForm" class="filter-form" style="display: flex; align-items: flex-end; gap: 14px; flex-wrap: wrap;">
                <div style="width: 170px;">
                    <label style="display: block; font-size: 0.65rem; font-weight: 800; color: #64748B; text-transform: uppercase; margin-bottom: 6px;">Pilih Bulan <span style="color:#ef4444;">*</span></label>
                    <select id="filter_bulan" class="form-input" style="height: 40px; font-size: 0.85rem;" required>
                        <option value="01">Januari</option>
                        <option value="02">Februari</option>
                        <option value="03">Maret</option>
                        <option value="04">April</option>
                        <option value="05">Mei</option>
                        <option value="06">Juni</option>
                        <option value="07">Juli</option>
                        <option value="08">Agustus</option>
                        <option value="09">September</option>
                        <option value="10">Oktober</option>
                        <option value="11">November</option>
                        <option value="12">Desember</option>
                    </select>
                </div>
                <div style="width: 120px;">
                    <label style="display: block; font-size: 0.65rem; font-weight: 800; color: #64748B; text-transform: uppercase; margin-bottom: 6px;">Tahun <span style="color:#ef4444;">*</span></label>
                    <select id="filter_tahun" class="form-input" style="height: 40px; font-size: 0.85rem;" required>
                        @php $currentYear = date('Y'); @endphp
                        @for($y = $currentYear; $y >= $currentYear - 2; $y--)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div style="flex: 1; min-width: 150px;">
                    <div style="font-size: 0.75rem; color: #64748b; margin-top: 4px;">Data akan ditarik untuk satu bulan penuh.</div>
                </div>
                <button type="submit" class="btn btn-primary" style="height: 40px; padding: 0 22px; font-size: 0.85rem; font-weight: 700;">
                    <i data-lucide="eye" style="width:14px;height:14px;display:inline;vertical-align:-2px;margin-right:4px;"></i> Pratinjau
                </button>
            </form>
        </div>
        <div id="panel-range" style="display: none;">
            <form id="rangeForm" style="display: flex; align-items: flex-end; gap: 14px; flex-wrap: wrap;">
                <div style="width: 170px;">
                    <label style="display: block; font-size: 0.65rem; font-weight: 800; color: #64748B; text-transform: uppercase; margin-bottom: 6px;">Tanggal Mulai <span style="color:#ef4444;">*</span></label>
                    <input type="date" id="range_dari" class="form-input" style="height: 40px; font-size: 0.85rem;" required onchange="validateRange()">
                </div>
                <div style="width: 170px;">
                    <label style="display: block; font-size: 0.65rem; font-weight: 800; color: #64748B; text-transform: uppercase; margin-bottom: 6px;">Tanggal Selesai <span style="color:#ef4444;">*</span></label>
                    <input type="date" id="range_sampai" class="form-input" style="height: 40px; font-size: 0.85rem;" required onchange="validateRange()">
                </div>
                <div style="flex: 1; min-width: 150px;">
                    <div class="range-warning" id="range-warning">
                        <i data-lucide="alert-triangle" style="width:12px;height:12px;display:inline;vertical-align:-1px;"></i>
                        Rentang waktu melebihi <strong>31 hari</strong>. Silakan sesuaikan tanggal.
                    </div>
                    <div style="font-size: 0.75rem; color: #64748b; margin-top: 4px;" id="range-info"></div>
                </div>
                <button type="submit" class="btn btn-primary" style="height: 40px; padding: 0 22px; font-size: 0.85rem; font-weight: 700;" id="btn-range-preview">
                    <i data-lucide="eye" style="width:14px;height:14px;display:inline;vertical-align:-2px;margin-right:4px;"></i> Pratinjau
                </button>
            </form>
            <div style="margin-top: 12px; padding: 10px 14px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; font-size: 0.78rem; color: #166534;">
                <i data-lucide="info" style="width:13px;height:13px;display:inline;vertical-align:-2px;"></i>
                <strong>Mode Export:</strong> Pilih rentang tanggal maksimal <strong>1 bulan (31 hari)</strong>. Data semua peserta dalam rentang tersebut akan direkap dalam 1 file Excel.
                Data anonim dari QR Survei tidak akan ikut ter-export.
            </div>
        </div>
    </div>

    <!-- Metric Cards -->
    <div id="stats-container" style="display: none; display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px;">
        <div style="background: #fff; border: 1px solid #E2E8F0; padding: 16px 20px; border-radius: 12px;">
            <div style="font-size: 0.68rem; font-weight: 700; color: #64748B; text-transform: uppercase; margin-bottom: 6px;">Total Peserta</div>
            <div id="stat-total" style="font-size: 1.75rem; font-weight: 900; color: #0F172A; font-family: 'Outfit', sans-serif; letter-spacing: -0.02em;">0</div>
        </div>
        <div style="background: #fff; border: 1px solid #E2E8F0; padding: 16px 20px; border-radius: 12px; border-left: 4px solid #10B981;">
            <div style="font-size: 0.68rem; font-weight: 700; color: #64748B; text-transform: uppercase; margin-bottom: 6px;">Berhasil</div>
            <div id="stat-berhasil" style="font-size: 1.75rem; font-weight: 900; color: #10B981; font-family: 'Outfit', sans-serif; letter-spacing: -0.02em;">0</div>
        </div>
        <div style="background: #fff; border: 1px solid #E2E8F0; padding: 16px 20px; border-radius: 12px; border-left: 4px solid #EF4444;">
            <div style="font-size: 0.68rem; font-weight: 700; color: #64748B; text-transform: uppercase; margin-bottom: 6px;">Gagal</div>
            <div id="stat-gagal" style="font-size: 1.75rem; font-weight: 900; color: #EF4444; font-family: 'Outfit', sans-serif; letter-spacing: -0.02em;">0</div>
        </div>
        <div style="background: #fff; border: 1px solid #E2E8F0; padding: 16px 20px; border-radius: 12px; border-left: 4px solid #3B82F6;">
            <div style="font-size: 0.68rem; font-weight: 700; color: #64748B; text-transform: uppercase; margin-bottom: 6px;">Kepuasan</div>
            <div id="stat-puas" style="font-size: 1.75rem; font-weight: 900; color: #3B82F6; font-family: 'Outfit', sans-serif; letter-spacing: -0.02em;">0%</div>
        </div>
    </div>

    <!-- Context Info Bar (only shown for range mode) -->
    <div id="range-meta-bar" style="display: none; background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 10px; padding: 12px 18px; margin-bottom: 20px; display: none;">
        <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
            <i data-lucide="calendar-range" style="width:16px;height:16px;color:#0284c7;"></i>
            <span style="font-size: 0.8rem; font-weight: 700; color: #0c4a6e;" id="range-meta-text">-</span>
            <span class="filter-pill" id="range-meta-kegiatan">0 kegiatan</span>
            <span class="filter-pill" id="range-meta-peserta">0 peserta</span>
        </div>
    </div>

    <!-- Data Table -->
    <div id="table-container" style="display: none; background: #fff; border: 1px solid #E2E8F0; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,0.04);">
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 14px 20px; border-bottom: 1px solid #f1f5f9;">
            <div style="font-size: 0.8rem; font-weight: 700; color: #0f172a;" id="table-context-label">Data Peserta</div>
            <div id="table-filter-badge"></div>
        </div>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; min-width: 700px;">
                <thead style="background: #F8FAFC; border-bottom: 1px solid #E2E8F0;">
                    <tr>
                        <th id="col-tanggal" style="padding: 12px 16px; text-align: left; font-size: 0.65rem; font-weight: 800; color: #64748B; text-transform: uppercase; display: none;">Tanggal/Kegiatan</th>
                        <th style="padding: 12px 16px; text-align: left; font-size: 0.65rem; font-weight: 800; color: #64748B; text-transform: uppercase;">Jam</th>
                        <th style="padding: 12px 16px; text-align: left; font-size: 0.65rem; font-weight: 800; color: #64748B; text-transform: uppercase;">NIK / Segmen</th>
                        <th style="padding: 12px 16px; text-align: left; font-size: 0.65rem; font-weight: 800; color: #64748B; text-transform: uppercase;">Layanan</th>
                        <th style="padding: 12px 16px; text-align: left; font-size: 0.65rem; font-weight: 800; color: #64748B; text-transform: uppercase;">Kepuasan</th>
                        <th style="padding: 12px 16px; text-align: center; font-size: 0.65rem; font-weight: 800; color: #64748B; text-transform: uppercase;">Status</th>
                    </tr>
                </thead>
                <tbody id="laporanTableBody"></tbody>
            </table>
        </div>
    </div>

    <!-- Initial / Empty State -->
    <div id="initial-state" style="background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 60px 20px; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
        <i data-lucide="list-filter" style="width: 44px; height: 44px; color: #cbd5e1; margin: 0 auto 16px; display: block;"></i>
        <h3 style="font-size: 1.1rem; font-weight: 700; color: #475569;">Pilih Filter untuk Menampilkan Data</h3>
        <p style="font-size: 0.875rem; color: #94a3b8; max-width: 380px; margin: 8px auto 0;">
            Pilih kegiatan spesifik atau tentukan rentang tanggal (maks. 1 bulan) untuk melihat dan mengekspor data peserta.
        </p>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
        @vite(['resources/js/pages/admin_bpjs_keliling_laporan.js'])
    @endpush
</x-admin-layout>
