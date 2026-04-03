<!-- Modal Form PIL -->
<div id="pilModal" class="modal-overlay" style="display:none;">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h3 class="modal-title" id="modal-title">Jadwal Agenda PIL</h3>
            <button class="modal-close" id="btn-close-modal">&times;</button>
        </div>
        <div class="modal-body">
            <form id="pilForm">
                <input type="hidden" id="pil_id" name="id">
                
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Nama/Judul Kegiatan Penyuluhan</label>
                        <input type="text" id="judul" name="judul" class="form-input" required>
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
                        <label class="form-label">Jam Mulai</label>
                        <input type="time" id="jam_mulai" name="jam_mulai" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jam Selesai</label>
                        <input type="time" id="jam_selesai" name="jam_selesai" class="form-input">
                    </div>
                </div>

                <div class="form-group" style="padding-bottom: 20px; border-bottom: 1px dashed #e2e8f0; margin-bottom: 20px;">
                    <label class="form-label">Pilih Wilayah (Master DB)</label>
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
                        <label class="form-label">Lokasi Detail Lapangan</label>
                        <input type="text" id="lokasi_detail" name="lokasi_detail" class="form-input">
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Jumlah Petugas</label>
                        <input type="number" id="jumlah_petugas" name="jumlah_petugas" class="form-input" value="1" min="1" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status Pelaksanaan</label>
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
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('pilModal').style.display='none'">Batal</button>
            <button type="submit" form="pilForm" class="btn btn-primary" id="btn-save">Simpan Jadwal</button>
        </div>
    </div>
</div>

<!-- Modal Laporan / Entry Peserta -->
<div id="entryPesertaModal" class="modal-overlay" style="display:none;">
    <div class="modal-content" style="max-width: 800px; display: flex; gap: 20px;">
        <!-- Area Form Entry -->
        <div style="flex: 1;">
            <div class="modal-header">
                <h3 class="modal-title">Entry Peserta PIL</h3>
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
                            <input type="time" id="jam_sosialisasi_mulai" name="jam_sosialisasi_mulai" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Jam Selesai</label>
                            <input type="time" id="jam_sosialisasi_selesai" name="jam_sosialisasi_selesai" class="form-input" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nilai Pemahaman (0 - 100)</label>
                        <input type="number" min="0" max="100" id="nilai_pemahaman" name="nilai_pemahaman" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Efektivitas Sosialisasi</label>
                        <select id="efektifitas_sosialisasi" name="efektifitas_sosialisasi" class="form-input" required>
                            <option value="">- Pilih -</option>
                            <option value="Sangat Efektif">Sangat Efektif</option>
                            <option value="Efektif">Efektif</option>
                            <option value="Kurang Efektif">Kurang Efektif</option>
                        </select>
                    </div>

                    <div class="form-group" style="margin-bottom: 20px;">
                        <label class="form-label" style="font-weight:700; color:var(--primary)">Pilih Kepuasan NPS (1 - 10)</label>
                        <div class="grid-3" style="gap:10px;">
                            <div>
                                <label class="form-label" style="font-size:0.75rem;">Ketertarikan</label>
                                <input type="number" id="nps_ketertarikan" name="nps_ketertarikan" min="1" max="10" class="form-input" required>
                            </div>
                            <div>
                                <label class="form-label" style="font-size:0.75rem;">Rekom. Program</label>
                                <input type="number" id="nps_rekomendasi_program" name="nps_rekomendasi_program" min="1" max="10" class="form-input" required>
                            </div>
                            <div>
                                <label class="form-label" style="font-size:0.75rem;">Rekom. BPJS</label>
                                <input type="number" id="nps_rekomendasi_bpjs" name="nps_rekomendasi_bpjs" min="1" max="10" class="form-input" required>
                            </div>
                        </div>
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
