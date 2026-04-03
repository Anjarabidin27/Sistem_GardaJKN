<!-- Modal Form Kegiatan -->
<div id="bpjsModal" class="modal-overlay" style="display:none;">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h3 class="modal-title" id="modal-title">BPJS Keliling</h3>
            <button class="modal-close" id="btn-close-modal">&times;</button>
        </div>
        <div class="modal-body">
            <form id="bpjsForm">
                <input type="hidden" id="bpjs_id" name="id">
                
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Jenis Kegiatan</label>
                        <select id="jenis_kegiatan" name="jenis_kegiatan" class="form-input" required>
                            <option value="goes_to_village">Goes To Village</option>
                            <option value="around_city">Around City</option>
                            <option value="hi_customer">Hi Customer</option>
                            <option value="corporate_gathering">Corporate Gathering</option>
                            <option value="cfd">CFD</option>
                            <option value="other">Lainnya</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Judul Kegiatan</label>
                        <input type="text" id="judul" name="judul" class="form-input" required>
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Kuadran</label>
                        <select id="kuadran" name="kuadran" class="form-input" required>
                            <option value="">- Pilih -</option>
                            <option value="1- Engagement">1- Engagement</option>
                            <option value="2- Rekrutmen">2- Rekrutmen</option>
                            <option value="3- Pembaharuan Data">3- Pembaharuan Data</option>
                            <option value="4- Iuran">4- Iuran</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Frontliner</label>
                        <input type="text" id="nama_frontliner" name="nama_frontliner" class="form-input" required>
                    </div>
                </div>

                <div class="grid-3">
                    <div class="form-group">
                        <label class="form-label">Tanggal</label>
                        <input type="date" id="tanggal" name="tanggal" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Mulai</label>
                        <input type="time" id="jam_mulai" name="jam_mulai" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Selesai</label>
                        <input type="time" id="jam_selesai" name="jam_selesai" class="form-input">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Pilih Wilayah (Opsional)</label>
                    <div class="grid-3">
                        <select id="provinsi_id" name="provinsi_id" class="form-input"><option value="">Provinsi...</option></select>
                        <select id="kota_id" name="kota_id" class="form-input" disabled><option value="">Kota/Kab...</option></select>
                        <select id="kecamatan_id" name="kecamatan_id" class="form-input" disabled><option value="">Kecamatan...</option></select>
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Lokasi Kegiatan</label>
                        <select id="lokasi_kegiatan" name="lokasi_kegiatan" class="form-input" required>
                            <option value="">-- Pilih --</option>
                            <option value="Kantor Kecamatan">Kantor Kecamatan</option>
                            <option value="Kantor Kelurahan">Kantor Kelurahan</option>
                            <option value="Kantor Desa">Kantor Desa</option>
                            <option value="Puskesmas">Puskesmas</option>
                            <option value="Rumah Warga">Rumah Warga</option>
                            <option value="Lainnya">Lainnya</option>
                            <option value="Kantor Badan Usaha Swasta">Kantor Badan Usaha Swasta</option>
                            <option value="Kantor BUMN">Kantor BUMN</option>
                            <option value="Kantor BUMD">Kantor BUMD</option>
                            <option value="Kantor Instansi Pemerintah">Kantor Instansi Pemerintah</option>
                            <option value="Sekolah/Kampus">Sekolah/Kampus</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Lokasi Detail / Titik Nol</label>
                        <input type="text" id="lokasi_detail" name="lokasi_detail" class="form-input">
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Jumlah Petugas</label>
                        <input type="number" id="jumlah_petugas" name="jumlah_petugas" class="form-input" value="1" min="1" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select id="status" name="status" class="form-input" required>
                            <option value="scheduled">Terjadwal</option>
                            <option value="ongoing">Berlangsung</option>
                            <option value="completed">Selesai</option>
                            <option value="cancelled">Dibatalkan</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('bpjsModal').style.display='none'">Batal</button>
            <button type="submit" form="bpjsForm" class="btn btn-primary" id="btn-save">Simpan Jadwal</button>
        </div>
    </div>
</div>

<!-- Modal Laporan / Entry Peserta -->
<div id="entryPesertaModal" class="modal-overlay" style="display:none;">
    <div class="modal-content" style="max-width: 800px; display: flex; gap: 20px;">
        <!-- Area Form Entry -->
        <div style="flex: 1;">
            <div class="modal-header">
                <h3 class="modal-title">Entry Peserta</h3>
                <button class="modal-close" onclick="document.getElementById('entryPesertaModal').style.display='none'">&times;</button>
            </div>
            <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                <form id="pesertaForm">
                    <input type="hidden" id="entry_kegiatan_id">
                    
                    <div class="form-group">
                        <label class="form-label">NIK (16 Digit)</label>
                        <input type="text" id="nik" name="nik" class="form-input" maxlength="16" required>
                    </div>

                    <div class="grid-2">
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
                        <div class="form-group">
                            <label class="form-label">Nomor HP</label>
                            <input type="text" id="phone_number" name="phone_number" class="form-input">
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Jam Mulai</label>
                            <input type="time" id="peserta_jam_mulai" name="jam_mulai" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Jam Selesai</label>
                            <input type="time" id="peserta_jam_selesai" name="jam_selesai" class="form-input" required>
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
                        <label class="form-label">Transaksi Layanan</label>
                        <select id="transaksi_layanan" name="transaksi_layanan" class="form-input">
                            <option value="">- Pilih Transaksi -</option>
                            <option value="Pendaftaran Baru">1. Pendaftaran Baru</option>
                            <option value="Penambahan Anggota Keluarga">2. Penambahan Anggota Keluarga</option>
                            <option value="Pengaktifan Kembali Status Kepesertaan (Anak >21 Tahun masih Kuliah)">3. Pengaktifan Kembali Status Kepesertaan (Anak >21 Tahun masih Kuliah)</option>
                            <option value="Pengaktifan Kembali Status Kepesertaan (Data Ganda dan Rekonsiliasi Data)">4. Pengaktifan Kembali Status Kepesertaan (Data Ganda dan Rekonsiliasi Data)</option>
                            <option value="Pengaktifan Kembali Status Kepesertaan (PBI JK dan PBPU BP Pemda)">5. Pengaktifan Kembali Status Kepesertaan (PBI JK dan PBPU BP Pemda)</option>
                            <option value="Pengaktifan Kembali Status Kepesertaan (Registrasi Ulang dan Rekonsiliasi Data)">6. Pengaktifan Kembali Status Kepesertaan (Registrasi Ulang dan Rekonsiliasi Data)</option>
                            <option value="Pengaktifan Kembali Status Kepesertaan (Update VA PBPU)">7. Pengaktifan Kembali Status Kepesertaan (Update VA PBPU)</option>
                            <option value="Pengaktifan Kembali Status Kepesertaan (WNI Kembali dari Luar Negeri)">8. Pengaktifan Kembali Status Kepesertaan (WNI Kembali dari Luar Negeri)</option>
                            <option value="Pengantian Kartu Hilang">9. Pengantian Kartu Hilang</option>
                            <option value="Pengurangan Anggota Keluarga (Pelaporan Peserta Meninggal Dunia)">10. Pengurangan Anggota Keluarga (Pelaporan Peserta Meninggal Dunia dan Rekonsiliasi Data)</option>
                            <option value="Pengurangan Anggota Keluarga (Pelaporan WNI pergi keluar Negeri)">11. Pengurangan Anggota Keluarga (Pelaporan WNI pergi keluar Negeri)</option>
                            <option value="Peralihan Jenis Kepesertaan">12. Peralihan Jenis Kepesertaan</option>
                            <option value="Peralihan Jenis Kepesertaan (Tanpa Administrasi 14 Hari)">13. Peralihan Jenis Kepesertaan (Tanpa Administrasi 14 Hari)</option>
                            <option value="Perubahan/Perbaikan Data FKTP">14. Perubahan/Perbaikan Data FKTP</option>
                            <option value="Perubahan/Perbaikan Data Golongan dan Gaji">15. Perubahan/Perbaikan Data Golongan dan Gaji</option>
                            <option value="Perubahan/Perbaikan Data Identitas (NIK, No KK, Nama, DLL)">16. Perubahan/Perbaikan Data Identitas (NIK, No KK, Nama, Tanggal Lahir, Jenis Kelamin, Alamat)</option>
                            <option value="Perubahan/Perbaikan Data Kelas Rawat">17. Perubahan/Perbaikan Data Kelas Rawat</option>
                            <option value="Perubahan/Perbaikan Data Nomor Handphone">18. Perubahan/Perbaikan Data Nomor Handphone</option>
                            <option value="Perubahan/Perbaikan Data Pembaharuan KK (Gabung/Pisah KK)">19. Perubahan/Perbaikan Data Pembaharuan KK (Gabung/Pisah KK)</option>
                            <option value="Rekonsiliasi Iuran (Refund Iuran)">20. Rekonsiliasi Iuran (Refund Iuran)</option>
                            <option value="Rekonsiliasi Iuran (VA to VA)">21. Rekonsiliasi Iuran (VA to VA)</option>
                        </select>
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
                            <label class="form-label">Jika Tidak Berhasil</label>
                            <select id="keterangan_gagal" name="keterangan_gagal" class="form-input">
                                <option value="">- Alasan -</option>
                                <option value="Adanya tindaklanjut rekonsiliasi data">Adanya tindaklanjut rekonsiliasi data</option>
                                <option value="Berkas persyaratan belum lengkap">Berkas persyaratan belum lengkap</option>
                                <option value="NIK tidak sesuai, tidak padan dengan Dukcapil atau data tidak valid">NIK tidak sesuai, tidak padan dengan Dukcapil atau data tidak valid</option>
                                <option value="Perlu koordinasi atau laporan ke Dinsos/Dukcapil">Perlu koordinasi atau laporan ke Dinsos/Dukcapil</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Suara Pelanggan</label>
                        <select id="suara_pelanggan" name="suara_pelanggan" class="form-input" required>
                            <option value="">- Pilih -</option>
                            <option value="Puas">Puas</option>
                            <option value="Tidak puas">Tidak puas</option>
                        </select>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="pesertaForm" class="btn btn-primary" id="btn-save-peserta" style="width: 100%;">Save & Muncul Form Baru</button>
            </div>
        </div>
        
        <!-- Area Riwayat Peserta -->
        <div style="flex: 1; border-left: 1px solid var(--border); padding-left: 20px; display: flex; flex-direction: column;">
            <div class="modal-header">
                <h3 class="modal-title" style="font-size: 1rem;">Daftar Terinput</h3>
            </div>
            <div class="modal-body" style="flex: 1; max-height: 70vh; overflow-y: auto;">
                <button class="btn btn-secondary mb-3" id="btn-refresh-peserta" style="width: 100%; font-size: 0.8rem;"><i data-lucide="refresh-cw" style="width:14px;height:14px;display:inline;"></i> Segarkan Daftar</button>
                <div id="peserta-list" style="display:flex; flex-direction:column; gap:10px;">
                    <div class="text-muted text-center" style="padding: 20px; font-size: 0.85rem;">Belum ada peserta</div>
                </div>
            </div>
            <div style="margin-top: auto; padding-top: 15px;">
                <button type="button" class="btn btn-success" id="btn-finish-kegiatan" style="width: 100%;">Tutup & Selesaikan Kegiatan</button>
            </div>
        </div>
    </div>
</div>

