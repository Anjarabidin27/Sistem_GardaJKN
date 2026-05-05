<x-admin-layout title="Manajemen Jadwal - BPJS Keliling">
    <div class="justify-between items-end mb-4 flex">
        <div>
            <h1 class="topbar-title" style="font-size: 1.75rem;">BPJS Keliling</h1>
            <p class="text-muted" style="margin-top: 4px;">Manajemen pelaksanaan kegiatan BPJS Keliling di lapangan.</p>
        </div>
        <div class="flex gap-2">
            <a href="/admin/bpjs-keliling/dashboard" class="btn btn-secondary">Lihat Dashboard</a>
            <button class="btn btn-primary" id="btn-add" style="padding: 12px 24px;">+ Jadwal Baru</button>
        </div>
    </div>

    <div id="main-content-area">
        <div class="table-card p-4">
            <div class="justify-between items-center mb-4 flex">
                <h3 class="modal-title">Daftar Kegiatan</h3>
                <div class="flex gap-2">
                    <select id="filter-status" class="form-input" style="width:auto;">
                        <option value="">Semua Status</option>
                        <option value="scheduled">Terjadwal</option>
                        <option value="ongoing">Berlangsung</option>
                        <option value="completed">Selesai</option>
                        <option value="cancelled">Dibatalkan</option>
                    </select>
                </div>
            </div>

            <div style="overflow-x: auto;">
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
                    <hr style="margin: 15px 0; border: 0; border-top: 1px solid var(--border);">
                    <div class="context-card" style="background: #f8fafc; border-color: #e2e8f0;">
                        <div class="context-label">Petugas Login</div>
                        <div class="context-value" style="color: var(--primary);" id="ui-petugas-name">-</div>
                    </div>

                    <div style="margin-top: 20px;">
                        <button class="btn btn-secondary" onclick="window.exitCommandCenter()" style="width: 100%;">
                            <i data-lucide="arrow-left"></i> Kembali ke Daftar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Pane 2: Primary Entry Form -->
            <div class="command-pane">
                <div class="pane-header">
                    <i data-lucide="edit-3"></i> Entry Laporan: <span id="active-kegiatan-title" style="margin-left: 5px; color: var(--primary);">...</span>
                </div>
                <div class="pane-body form-compact">
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
