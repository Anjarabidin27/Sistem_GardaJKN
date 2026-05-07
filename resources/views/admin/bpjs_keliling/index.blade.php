<x-admin-layout title="Manajemen Jadwal - BPJS Keliling">
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
            
            /* Keep stats as 3 columns but smaller */
            .pane-body > .grid-3.mb-4 {
                grid-template-columns: repeat(3, 1fr) !important;
                gap: 6px !important;
            }
            .stat-card-compact { padding: 8px !important; }
            .stat-card-compact .text-2xl { font-size: 1.25rem !important; }
            
            /* Keep time inputs side-by-side */
            #pesertaForm .grid-3 { grid-template-columns: 1fr 1fr !important; gap: 10px !important; }
            #pesertaForm .grid-3 .form-group:first-child { grid-column: span 2; }
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
            <h1 class="topbar-title" style="font-size: 1.75rem;">BPJS Keliling</h1>
            <p class="text-muted" style="margin-top: 4px;">Manajemen pelaksanaan kegiatan BPJS Keliling di lapangan.</p>
        </div>
        <div class="flex gap-2">
            <button class="btn btn-secondary" id="btn-back-top" style="display:none; padding: 10px 20px;" onclick="window.exitCommandCenter()">
                <i data-lucide="arrow-left" style="width:16px; margin-right:8px;"></i> Kembali ke Daftar
            </button>
            <a href="/admin/bpjs-keliling/dashboard" class="btn btn-secondary" id="btn-to-dashboard">Lihat Dashboard</a>
            <button class="btn btn-primary" id="btn-add" style="padding: 12px 24px;">+ Jadwal Baru</button>
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

    <!-- COMMAND CENTER UI (Initially Hidden) -->
    <div id="command-center-ui" style="display:none;">
        <div class="command-center">
            <!-- Pane 1: Institutional Context -->
            <div class="command-pane">
                <div class="pane-header"><i data-lucide="info"></i> Konteks Institusi</div>
                <div class="pane-body">
                    <div class="context-card">
                        <div class="context-label">Kedeputian Wilayah</div>
                        <div class="context-value" id="ui-kw-name">-</div>
                    </div>
                    <div class="context-card">
                        <div class="context-label">Kantor Cabang</div>
                        <div class="context-value" id="ui-kc-name">-</div>
                    </div>
                    <div class="context-card">
                        <div class="context-label">Zona Waktu</div>
                        <div class="context-value" id="ui-zona-waktu">WIB</div>
                    </div>
                    <hr style="margin: 10px 0; border: 0; border-top: 1px solid var(--border);">
                    <div class="context-card" style="background: #f8fafc; border-color: #e2e8f0;">
                        <div class="context-label">Petugas Login</div>
                        <div class="context-value" style="color: var(--primary);" id="ui-petugas-name">-</div>
                    </div>
                </div>
            </div>

            <!-- Pane 2: Primary Entry Form -->
            <div class="command-pane" style="flex: 1.5;">
                <div class="pane-header" style="padding: 10px 15px;">
                    <i data-lucide="edit-3"></i> Entry Laporan: <span id="active-kegiatan-title" style="margin-left: 5px; color: var(--primary);">...</span>
                </div>
                <div class="pane-body form-compact" style="padding: 15px;">
                    <!-- LIVE SUMMARY STATS -->
                    <div class="grid-3 mb-4" style="gap: 10px;">
                        <div style="background: var(--bg-base); padding: 12px; border-radius: 12px; text-align: center; border: 1px solid var(--border);">
                            <div style="font-size: 0.65rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase;">Total</div>
                            <div id="stat-total" style="font-size: 1.25rem; font-weight: 800; color: var(--primary);">0</div>
                        </div>
                        <div style="background: #ecfdf5; padding: 12px; border-radius: 12px; text-align: center; border: 1px solid #d1fae5;">
                            <div style="font-size: 0.65rem; font-weight: 800; color: #065f46; text-transform: uppercase;">Berhasil</div>
                            <div id="stat-berhasil" style="font-size: 1.25rem; font-weight: 800; color: #10b981;">0</div>
                        </div>
                        <div style="background: #fef2f2; padding: 12px; border-radius: 12px; text-align: center; border: 1px solid #fee2e2;">
                            <div style="font-size: 0.65rem; font-weight: 800; color: #991b1b; text-transform: uppercase;">Gagal</div>
                            <div id="stat-gagal" style="font-size: 1.25rem; font-weight: 800; color: #ef4444;">0</div>
                        </div>
                    </div>

                    <form id="pesertaForm">
                        <input type="hidden" id="entry_kegiatan_id">
                        
                        <div class="grid-2">
                            <div class="form-group">
                                <label class="form-label">NIK (16 Digit)</label>
                                <input type="text" id="nik" name="nik" class="form-input" maxlength="16" required placeholder="NIK 16 Digit">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Segmen Peserta</label>
                                <select id="segmen_peserta" name="segmen_peserta" class="form-input" required>
                                    <option value="">- Pilih -</option>
                                    <option value="PBPU">PBPU</option>
                                    <option value="BP">BP</option>
                                    <option value="PPU BU">PPU BU</option>
                                    <option value="PPU Pemerintah">PPU Pemerintah</option>
                                    <option value="PBI APBN">PBI APBN</option>
                                    <option value="PBI APBD">PBI APBD</option>
                                </select>
                            </div>
                        </div>
                        <div class="grid-3" style="gap: 12px;">
                            <div class="form-group">
                                <label class="form-label">Nomor HP</label>
                                <input type="text" id="phone_number" name="phone_number" class="form-input" placeholder="08xx...">
                            </div>
                            <div class="form-group">
                                <div class="flex justify-between items-center mb-1">
                                    <label class="form-label mb-0">Jam Mulai</label>
                                    <button type="button" onclick="window.setParticipantTime('mulai')" class="btn-text" style="font-size: 10px; color: var(--primary);">Set Skg</button>
                                </div>
                                <input type="time" id="peserta_jam_mulai" name="jam_mulai" class="form-input">
                            </div>
                            <div class="form-group">
                                <div class="flex justify-between items-center mb-1">
                                    <label class="form-label mb-0">Jam Selesai</label>
                                    <button type="button" onclick="window.setParticipantTime('selesai')" class="btn-text" style="font-size: 10px; color: var(--primary);">Set Skg</button>
                                </div>
                                <input type="time" id="peserta_jam_selesai" name="jam_selesai" class="form-input">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Jenis Layanan</label>
                            <select id="jenis_layanan" name="jenis_layanan" class="form-input" required>
                                <option value="">- Pilih -</option>
                                <option value="Administrasi">Administrasi</option>
                                <option value="Informasi">Informasi</option>
                                <option value="Pengaduan">Pengaduan</option>
                            </select>
                        </div>

                        <div class="form-group" id="wrap_transaksi_layanan" style="display:none;">
                            <label class="form-label">Transaksi Layanan (Ketik untuk Mencari)</label>
                            <input type="text" id="transaksi_layanan" name="transaksi_layanan" class="form-input" list="transaksi_list" placeholder="Pilih atau Ketik Transaksi...">
                            <datalist id="transaksi_list">
                                <option value="1. Pendaftaran Baru">
                                <option value="2. Penambahan Anggota Keluarga">
                                <option value="3. Pengaktifan Kembali Status Kepesertaan (Anak >21 Tahun masih Kuliah)">
                                <option value="4. Pengaktifan Kembali Status Kepesertaan (Data Ganda dan Rekonsiliasi Data)">
                                <option value="5. Pengaktifan Kembali Status Kepesertaan (PBI JK dan PBPU BP Pemda)">
                                <option value="6. Pengaktifan Kembali Status Kepesertaan (Registrasi Ulang dan Rekonsiliasi Data)">
                                <option value="7. Pengaktifan Kembali Status Kepesertaan (Update VA PBPU)">
                                <option value="8. Pengaktifan Kembali Status Kepesertaan (WNI Kembali dari Luar Negeri)">
                                <option value="9. Pengantian Kartu Hilang">
                                <option value="10. Pengurangan Anggota Keluarga (Pelaporan Peserta Meninggal Dunia dan Rekonsiliasi Data)">
                                <option value="11. Pengurangan Anggota Keluarga (Pelaporan WNI pergi keluar Negeri)">
                                <option value="12. Peralihan Jenis Kepesertaan">
                                <option value="13. Peralihan Jenis Kepesertaan (Tanpa Administrasi 14 Hari)">
                                <option value="14. Perubahan/Perbaikan Data FKTP">
                                <option value="15. Perubahan/Perbaikan Data Golongan dan Gaji">
                                <option value="16. Perubahan/Perbaikan Data Identitas (NIK, No KK, Nama, Tanggal Lahir, Jenis Kelamin, Alamat)">
                                <option value="17. Perubahan/Perbaikan Data Kelas Rawat">
                                <option value="18. Perubahan/Perbaikan Data Nomor Handphone">
                                <option value="19. Perubahan/Perbaikan Data Pembaharuan KK (Gabung/Pisah KK)">
                                <option value="20. Rekonsiliasi Iuran (Refund Iuran)">
                                <option value="21. Rekonsiliasi Iuran (VA to VA)">
                            </datalist>
                        </div>

                        <div class="grid-2">
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select id="status_layanan" name="status" class="form-input" required>
                                    <option value="">- Pilih -</option>
                                    <option value="Berhasil">Berhasil</option>
                                    <option value="Tidak Berhasil">Tidak Berhasil</option>
                                </select>
                            </div>
                            <div class="form-group" id="wrap_keterangan_gagal" style="display:none;">
                                <label class="form-label">Alasan Gagal</label>
                                <select id="keterangan_gagal" name="keterangan_gagal" class="form-input">
                                    <option value="">- Alasan -</option>
                                    <option value="Adanya tindaklanjut rekonsiliasi data">Adanya tindaklanjut rekonsiliasi data</option>
                                    <option value="Berkas persyaratan belum lengkap">Berkas persyaratan belum lengkap</option>
                                    <option value="NIK tidak padan Dukcapil">NIK tidak padan Dukcapil</option>
                                </select>
                            </div>
                        </div>

                        <input type="hidden" id="suara_pelanggan" name="suara_pelanggan">
                        
                        <div style="margin-top: 10px;">
                            <button type="submit" form="pesertaForm" class="btn btn-primary" id="btn-save-peserta" style="width: 100%; height: 50px; font-size: 1rem;">
                                <i data-lucide="save"></i> Simpan & Entry Baru
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Pane 3: History & Search -->
            <div class="command-pane">
                <div class="pane-header"><i data-lucide="history"></i> Daftar Terinput</div>
                <div class="pane-body">
                    <div class="flex-col mb-4" style="gap: 8px;">
                        <input type="text" id="filter-peserta" class="form-input" placeholder="Cari NIK / Jenis..." style="height: 40px;">
                        <button class="btn btn-secondary w-full" id="btn-refresh-peserta">
                            <i data-lucide="refresh-cw"></i> Refresh Data
                        </button>
                    </div>
                    <div id="peserta-list" style="display:flex; flex-direction:column; gap:10px;">
                        <!-- List Items -->
                    </div>
                </div>
                <div style="padding: 20px; border-top: 1px solid var(--border); background: #f8fafc;">
                    <button class="btn btn-success w-full" id="btn-finish-kegiatan">
                        <i data-lucide="check-circle-2"></i> Selesaikan Laporan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- INDOMARET-STYLE FEEDBACK MODAL -->
    <div id="feedback-overlay" class="feedback-overlay" style="display:none;">
        <div class="feedback-card">
            <div style="margin-bottom: 24px;">
                <div style="width: 64px; height: 64px; background: #f0fdf4; color: #10b981; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                    <i data-lucide="check" style="width: 32px; height: 32px;"></i>
                </div>
                <h2 class="feedback-title">Data Berhasil Tersimpan</h2>
                <p class="feedback-subtitle">Silakan arahkan peserta untuk memindai QR Code di bawah ini untuk mengisi survei kepuasan.</p>
            </div>

            <!-- Area QR Code Utama -->
            <div style="background: #f8fafc; padding: 32px; border-radius: 24px; border: 2px dashed #e2e8f0; display: inline-block; margin-bottom: 24px;">
                <img id="feedback-qr-img" src="" alt="QR Code" style="width: 220px; height: 220px; display: block; margin: 0 auto;">
                <div style="margin-top: 16px; font-weight: 800; color: var(--primary); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.1em;">Pindai Untuk Survei</div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr; gap: 12px;">
                <button type="button" class="btn btn-primary" style="height: 50px; font-size: 1rem;" onclick="window.closeFeedbackAndReset()">
                    Selesai & Input Peserta Baru
                </button>
            </div>
        </div>
    </div>

    <!-- Modal QR Code -->
    <div id="modalQR" class="modal-overlay" style="display:none; align-items:center; justify-content:center; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999;">
        <div class="modal-content" style="max-width:400px; background:#fff; padding:20px; border-radius:10px; text-align:center;">
            <div class="modal-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
                <h3 class="modal-title" style="margin:0;">QR Survei Kepuasan</h3>
                <button type="button" class="modal-close" style="background:none; border:none; font-size:1.5rem; cursor:pointer;" onclick="document.getElementById('modalQR').style.display='none'">&times;</button>
            </div>
            <p id="qr-title" style="font-weight:bold; margin-bottom:15px;"></p>
            <div id="qr-container" style="display:flex; justify-content:center; margin-bottom:20px;">
                <img id="qr-image" src="" alt="QR Code" style="width: 250px; height: 250px; border: 1px solid #e2e8f0; border-radius: 10px; padding: 10px;">
            </div>
            <p style="font-size:0.875rem; color:#64748b;">Minta peserta memindai QR code ini untuk mengisi survei kepuasan secara anonim.</p>
            <div style="margin-top: 15px;">
                <a id="qr-link" href="#" target="_blank" style="font-size: 0.8rem; color: #0ea5e9; text-decoration: none;">Buka Link Survei Manual</a>
            </div>
        </div>
    </div>

    @include('admin.bpjs_keliling.modals')

    @push('scripts')
        @vite(['resources/js/pages/admin_bpjs_keliling_index.js'])
    @endpush
</x-admin-layout>
