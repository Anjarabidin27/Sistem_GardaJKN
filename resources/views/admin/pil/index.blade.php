<x-admin-layout title="Manajemen Jadwal - PIL">
    <style>
        /* Mobile Compact Layout */
        @media (max-width: 768px) {
            #title-section {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 12px;
                margin-bottom: 12px;
            }
            #title-section .flex {
                width: 100%;
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 6px;
            }
            #title-section .btn {
                padding: 8px !important;
                font-size: 0.7rem !important;
                height: 36px;
            }
            
            .table-card { padding: 12px !important; }
            .table-card > .justify-between.items-center {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 12px;
            }
            .table-card > .justify-between.items-center .flex {
                width: 100%;
                gap: 8px !important;
            }
            
            .v-search-compact { padding: 8px 12px !important; font-size: 0.85rem !important; }
            .v-filter-chips-compact { padding-bottom: 8px !important; }
            .chip { padding: 6px 12px !important; font-size: 0.75rem !important; }

            /* Form Layout Adjustments */
            .grid-2, .grid-3 { grid-template-columns: 1fr !important; gap: 12px !important; }
            
            /* Keep time inputs side-by-side */
            .grid-2:has(input[type="time"]) {
                grid-template-columns: 1fr 1fr !important;
                gap: 10px !important;
            }
        }

        /* Drive Style Components */
        .filter-chips {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            padding: 4px 0 12px;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .filter-chips::-webkit-scrollbar { display: none; }
        
        .chip {
            padding: 8px 16px;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #64748b;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.2s;
        }
        .chip.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .view-toggles {
            display: flex;
            background: #f1f5f9;
            padding: 4px;
            border-radius: 8px;
            gap: 4px;
        }
        .toggle-btn {
            padding: 6px 10px;
            border-radius: 6px;
            cursor: pointer;
            color: #64748b;
            transition: all 0.2s;
            display: flex;
            align-items: center;
        }
        .toggle-btn.active {
            background: white;
            color: var(--primary);
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .drive-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 16px;
            padding-top: 10px;
        }
        @media (max-width: 480px) {
            .drive-grid { grid-template-columns: 1fr; }
        }

        .drive-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
            position: relative;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .drive-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border-color: var(--primary);
        }
        .card-dots {
            position: absolute;
            top: 12px;
            right: 12px;
            padding: 4px;
            border-radius: 50%;
            cursor: pointer;
            color: #94a3b8;
        }
        .card-dots:hover { background: #f1f5f9; color: var(--primary); }

        .context-menu {
            position: absolute;
            top: 40px;
            right: 12px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            z-index: 50;
            width: 180px;
            display: none;
            overflow: hidden;
        }
        .menu-item {
            padding: 10px 14px;
            font-size: 0.85rem;
            color: #475569;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }
        .menu-item:hover { background: #f8fafc; color: var(--primary); }
        .menu-item.danger { color: #ef4444; }
        .menu-item.danger:hover { background: #fef2f2; }

        /* Compact Override for Input Mode */
        .sidebar-hidden .command-center {
            margin-top: 0 !important;
        }
        
        /* New Utilities */
        .flex-col { display: flex; flex-direction: column; }
        .gap-1 { gap: 0.25rem; }
        .gap-4 { gap: 1rem; }
        .items-center { align-items: center; }
    </style>
    <div class="justify-between items-end mb-4 flex" id="title-section">
        <div>
            <h1 class="topbar-title" style="font-size: 1.75rem;">Jadwal Penyuluhan / PIL</h1>
            <p class="text-muted" style="margin-top: 4px;">Manajemen pelaksanaan kegiatan Program Informasi Langsung (PIL).</p>
        </div>
        <div class="flex gap-2">
            <button class="btn btn-secondary" id="btn-back-top" style="display:none; padding: 10px 20px;" onclick="window.exitCommandCenter()">
                <i data-lucide="arrow-left" style="width:16px; margin-right:8px;"></i> Kembali ke Daftar
            </button>
            <a href="/admin/pil/dashboard" class="btn btn-secondary" id="btn-to-dashboard">Lihat Dashboard</a>
            <button class="btn btn-primary" id="btn-add" style="padding: 12px 24px;">+ Agenda PIL Baru</button>
        </div>
    </div>

    <div id="main-content-area">
        <div class="table-card p-4">
            <div class="flex-col gap-4 mb-4">
                <div class="flex items-center gap-2">
                    <div class="form-group mb-0" style="flex: 1; position: relative;">
                        <i data-lucide="search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 14px; color: var(--v-gray-400);"></i>
                        <input type="text" id="filter-judul" class="form-input v-search-compact" placeholder="Cari kegiatan..." list="judul-list-index" style="padding-left: 35px !important; width: 100%;">
                        <datalist id="judul-list-index"></datalist>
                    </div>
                    <div class="view-toggles" style="flex-shrink: 0;">
                        <div class="toggle-btn" id="toggle-grid" title="Grid View">
                            <i data-lucide="layout-grid" style="width:16px; height:16px;"></i>
                        </div>
                        <div class="toggle-btn active" id="toggle-list" title="List View">
                            <i data-lucide="list" style="width:16px; height:16px;"></i>
                        </div>
                    </div>
                </div>

                <div class="filter-chips mb-0 v-filter-chips-compact" id="status-chips" style="margin: 0 -12px; padding: 0 12px 8px;">
                    <div class="chip active" data-status="">Semua</div>
                    <div class="chip" data-status="scheduled">Terjadwal</div>
                    <div class="chip" data-status="ongoing">Berlangsung</div>
                    <div class="chip" data-status="completed">Selesai</div>
                    <div class="chip" data-status="cancelled">Dibatalkan</div>
                </div>

                <div class="flex items-center gap-2">
                    <div class="form-group mb-0" style="width: 120px;">
                        <input type="time" id="filter-jam" class="form-input v-search-compact" title="Jam Mulai" style="width: 100%;">
                    </div>
                    <div style="font-size: 0.65rem; font-weight: 800; color: var(--v-gray-400); letter-spacing: 0.05em;">FILTER JADWAL</div>
                </div>

                <div class="flex items-center gap-2">
                    <input type="date" id="filter-dari" class="form-input v-search-compact" title="Dari" style="flex: 1; min-width: 0;">
                    <span style="color: var(--v-gray-300);">→</span>
                    <input type="date" id="filter-sampai" class="form-input v-search-compact" title="Sampai" style="flex: 1; min-width: 0;">
                </div>
            </div>

            <div id="grid-view" class="drive-grid" style="display: none;">
                <!-- Card items rendered by JS -->
            </div>

            <div id="list-view" style="overflow-x: auto;">
                <table class="data-table" id="main-table">
                    <thead>
                        <tr>
                            <th>Kegiatan & Waktu</th>
                            <th>Lokasi</th>
                            <th>Status & Laporan</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="table-body">
                        <tr><td colspan="4" class="text-center text-muted p-4">Memuat data...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- COMMAND CENTER UI PIL -->
    <div id="command-center-ui" style="display:none;">
        <div class="command-center">
            <!-- Pane 1: Context -->
            <div class="command-pane" style="flex: 0.8;">
                <div class="pane-header" style="padding: 10px 15px;"><i data-lucide="award"></i> Konteks PIL</div>
                <div class="pane-body" style="padding: 15px;">
                    <div class="context-card" style="background: #f0fdf4; border-color: #dcfce7;">
                        <div class="context-label" style="color: #166534;">Jenis Modul</div>
                        <div class="context-value">Program Informasi Langsung</div>
                    </div>
                    <div class="context-card">
                        <div class="context-label">Kantor Cabang</div>
                        <div class="context-value" id="ui-kc-name">{{ optional(auth('admin')->user())->kantor_cabang ?? '-' }}</div>
                    </div>
                    <div class="context-card">
                        <div class="context-label">Wilayah Kerja</div>
                        <div class="context-value" id="ui-kw-name">{{ optional(auth('admin')->user())->kedeputian_wilayah ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <!-- Pane 2: Entry Form -->
            <div class="command-pane" style="flex: 1.5;">
                <div class="pane-header" style="padding: 10px 15px;">
                    <i data-lucide="clipboard-list"></i> Evaluasi PIL: <span id="active-kegiatan-title" style="margin-left: 5px; color: var(--success);">...</span>
                </div>
                <div class="pane-body form-compact" style="padding: 15px;">
                    <form id="pesertaForm">
                        <input type="hidden" id="entry_kegiatan_id">
                        
                        <div class="grid-2">
                            <div class="form-group">
                                <label class="form-label">NIK Peserta</label>
                                <input type="text" id="nik" name="nik" class="form-input" maxlength="16" required placeholder="NIK 16 Digit">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Nomor HP</label>
                                <input type="text" name="phone_number" class="form-input" placeholder="08xxxx">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Segmen Peserta</label>
                            <select id="segmen_peserta" name="segmen_peserta" class="form-input" required>
                                <option value="">- Pilih Segmen -</option>
                                <option value="PBPU">PBPU</option>
                                <option value="BP">BP</option>
                                <option value="PPU BU">PPU BU</option>
                                <option value="PPU Pemerintah">PPU Pemerintah</option>
                                <option value="PBI APBN">PBI APBN</option>
                                <option value="PBI APBD">PBI APBD</option>
                            </select>
                        </div>

                        <div class="grid-2">
                            <div class="form-group">
                                <div class="flex justify-between items-center mb-1">
                                    <label class="form-label mb-0">Jam Mulai Sosialisasi</label>
                                    <button type="button" onclick="window.setPilTime('mulai')" class="btn-text" style="font-size: 10px; color: var(--success);">Set Skg</button>
                                </div>
                                <input type="time" id="pil_jam_mulai" name="jam_sosialisasi_mulai" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <div class="flex justify-between items-center mb-1">
                                    <label class="form-label mb-0">Jam Selesai Sosialisasi</label>
                                    <button type="button" onclick="window.setPilTime('selesai')" class="btn-text" style="font-size: 10px; color: var(--success);">Set Skg</button>
                                </div>
                                <input type="time" id="pil_jam_selesai" name="jam_sosialisasi_selesai" class="form-input" required>
                            </div>
                        </div>

                        <div class="grid-2">
                            <div class="form-group">
                                <label class="form-label">Hasil Pemahaman (Score)</label>
                                <input type="number" name="nilai_pemahaman" class="form-input" min="0" max="100" required placeholder="0-100">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Efektifitas Sosialisasi</label>
                                <select name="efektifitas_sosialisasi" class="form-input" required>
                                    <option value="">- Pilih Efektifitas -</option>
                                    <option value="a. Sangat Tidak Memuaskan">a. Sangat Tidak Memuaskan</option>
                                    <option value="b. Tidak Memuaskan">b. Tidak Memuaskan</option>
                                    <option value="c. Kurang Memuaskan">c. Kurang Memuaskan</option>
                                    <option value="d. Cukup Memuaskan">d. Cukup Memuaskan</option>
                                    <option value="e. Memuaskan">e. Memuaskan</option>
                                    <option value="f. Sangat Memuaskan">f. Sangat Memuaskan</option>
                                </select>
                            </div>
                        </div>

                        <div style="background: #f0fdf4; padding: 10px; border-radius: 12px; border: 1px solid #dcfce7; margin-top: 5px;">
                            <label class="form-label" style="text-align: center; display: block; margin-bottom: 2px; font-weight: 800; color: #166534; font-size: 10px;">Net Promoter Score (NPS)</label>
                            <div class="grid-3" style="gap: 8px;">
                                <div class="form-group mb-0">
                                    <label class="form-label" style="font-size: 9px;">Ketertarikan</label>
                                    <select name="nps_ketertarikan" class="form-input" style="padding: 4px; font-size: 12px;" required>
                                        @for ($i = 1; $i <= 10; $i++) <option value="{{ $i }}" {{ $i == 10 ? 'selected' : '' }}>{{ $i }}</option> @endfor
                                    </select>
                                </div>
                                <div class="form-group mb-0">
                                    <label class="form-label" style="font-size: 9px;">Rekom. Program</label>
                                    <select name="nps_rekomendasi_program" class="form-input" style="padding: 4px; font-size: 12px;" required>
                                        @for ($i = 1; $i <= 10; $i++) <option value="{{ $i }}" {{ $i == 10 ? 'selected' : '' }}>{{ $i }}</option> @endfor
                                    </select>
                                </div>
                                <div class="form-group mb-0">
                                    <label class="form-label" style="font-size: 9px;">Rekom. BPJS</label>
                                    <select name="nps_rekomendasi_bpjs" class="form-input" style="padding: 4px; font-size: 12px;" required>
                                        @for ($i = 1; $i <= 10; $i++) <option value="{{ $i }}" {{ $i == 10 ? 'selected' : '' }}>{{ $i }}</option> @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div style="padding: 12px 15px; border-top: 1px solid var(--border); background: #f8fafc;">
                    <button type="submit" form="pesertaForm" class="btn btn-primary w-full" id="btn-save-peserta" style="height: 44px; box-shadow: 0 4px 12px rgba(0, 74, 173, 0.2); font-size: 0.95rem; font-weight: 800; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);">
                        <i data-lucide="check-circle"></i> Simpan Evaluasi & Entry Baru
                    </button>
                </div>
            </div>

            <style>
                #btn-save-peserta:hover {
                    transform: translateY(-2px) scale(1.02);
                    box-shadow: 0 8px 20px rgba(0, 74, 173, 0.3) !important;
                    filter: brightness(1.1);
                }
                #btn-save-peserta:active {
                    transform: translateY(0) scale(0.98);
                }
            </style>

            <!-- Pane 3: History -->
            <div class="command-pane">
                <div class="pane-header"><i data-lucide="users"></i> Daftar Peserta PIL</div>
                <div class="pane-body">
                    <input type="text" id="filter-peserta" class="form-input mb-3" placeholder="Cari NIK...">
                    <div id="peserta-list" style="display:flex; flex-direction:column; gap:12px;"></div>
                </div>
                <div style="padding: 20px; border-top: 1px solid var(--border);">
                    <button class="btn btn-primary w-full" id="btn-finish-kegiatan">
                        <i data-lucide="flag"></i> Selesaikan Agenda PIL
                    </button>
                </div>
            </div>
        </div>
    </div>

    @include('admin.pil.modals')

    @push('scripts')
        @vite(['resources/js/pages/admin_pil_index.js'])
    @endpush
</x-admin-layout>
