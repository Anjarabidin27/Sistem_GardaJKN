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
                
                <div class="form-group">
                    <label class="form-label">Nama/Judul Kegiatan Penyuluhan</label>
                    <input type="text" id="judul" name="judul" class="form-input" required>
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
                        <label class="form-label">Titik Lokasi Tambahan / Desa</label>
                        <input type="text" id="nama_desa" name="nama_desa" class="form-input">
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

<!-- Modal Laporan PIL -->
<div id="laporanModal" class="modal-overlay" style="display:none;">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h3 class="modal-title">Laporan Analisis PIL</h3>
            <button class="modal-close" onclick="document.getElementById('laporanModal').style.display='none'">&times;</button>
        </div>
        <div class="modal-body">
            <form id="laporanForm">
                <input type="hidden" id="lap_kegiatan_id">
                
                <h4 class="form-label" style="font-size: 0.9rem; color: #004aad; margin-bottom: 20px;">Tingkat Partisipasi</h4>
                <div class="grid-2" style="padding-bottom: 20px; border-bottom: 1px dashed #e2e8f0; margin-bottom: 20px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" style="color: var(--primary);">Total Kehadiran Peserta</label>
                        <input type="number" id="jumlah_peserta" name="jumlah_peserta" class="form-input" min="0" value="0" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" style="color: var(--warning);">Rata Rata Uji Pemahaman</label>
                        <input type="number" id="rata_uji_pemahaman" name="rata_uji_pemahaman" class="form-input" min="0" max="100" step="0.1" value="0" placeholder="Skala 0-100" required>
                    </div>
                </div>

                <h4 class="form-label" style="font-size: 0.9rem; color: #004aad; margin-bottom: 20px;">Kinerja Efektivitas (Skala 1 - 10)</h4>
                <div class="grid-3" style="padding-bottom: 20px; border-bottom: 1px dashed #e2e8f0; margin-bottom: 20px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label text-success">Ketertarikan JKN</label>
                        <input type="number" id="efek_ketertarikan_jkn" name="efek_ketertarikan_jkn" class="form-input" min="1" max="10" value="1" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label text-success">Rekomen. JKN</label>
                        <input type="number" id="efek_rekomendasi_jkn" name="efek_rekomendasi_jkn" class="form-input" min="1" max="10" value="1" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label text-success">Rekomen. BPJS</label>
                        <input type="number" id="efek_rekomendasi_bpjs" name="efek_rekomendasi_bpjs" class="form-input" min="1" max="10" value="1" required>
                    </div>
                </div>

                <div class="form-group mb-0">
                    <label class="form-label">Catatan Evaluasi / Resume Umum</label>
                    <textarea id="catatan" name="catatan" class="form-input" rows="3" style="resize:none;"></textarea>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('laporanModal').style.display='none'">Batal</button>
            <button type="submit" form="laporanForm" class="btn btn-primary" id="btn-save-laporan">Simpan Laporan</button>
        </div>
    </div>
</div>
