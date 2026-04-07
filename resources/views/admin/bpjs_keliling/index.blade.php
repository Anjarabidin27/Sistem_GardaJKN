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
            <i data-lucide="heart" style="width: 48px; height: 48px; color: var(--primary); margin-bottom: 16px;"></i>
            <h2 class="feedback-title">Konfirmasi Kepuasan</h2>
            <p class="feedback-subtitle">Bagaimana tingkat kepuasan peserta terhadap layanan yang diberikan?</p>
            
            <div class="feedback-options">
                <button type="button" class="comfort-btn puas" onclick="window.submitWithFeedback('Puas')">
                    <i data-lucide="smile"></i>
                    <span>Puas</span>
                </button>
                <button type="button" class="comfort-btn tidak-puas" onclick="window.submitWithFeedback('Tidak puas')">
                    <i data-lucide="frown"></i>
                    <span>Tidak Puas</span>
                </button>
            </div>
            
            <button type="button" class="btn btn-secondary w-full mt-4" onclick="document.getElementById('feedback-overlay').style.display='none'">
                Batal / Perbaiki Data
            </button>
        </div>
    </div>

    @include('admin.bpjs_keliling.modals')

    @push('scripts')
        @vite(['resources/js/pages/admin_bpjs_keliling_index.js'])
    @endpush
</x-admin-layout>
