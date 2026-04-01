<!-- Modal Form Kegiatan -->
<div id="bpjsModal" class="modal-overlay" style="display:none;">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h3 class="modal-title" id="modal-title">Jadwal BPJS Keliling</h3>
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
                            <option value="goes_to_office">Goes To Office</option>
                            <option value="institusi">Kunjungan Institusi</option>
                            <option value="pameran">Pameran / Event</option>
                            <option value="other">Lainnya</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Judul Kegiatan</label>
                        <input type="text" id="judul" name="judul" class="form-input" required>
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
                        <label class="form-label">Titik Lokasi / Desa</label>
                        <input type="text" id="nama_desa" name="nama_desa" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Lokasi Detail</label>
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

<!-- Modal Laporan -->
<div id="laporanModal" class="modal-overlay" style="display:none;">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h3 class="modal-title">Laporan Data Layanan</h3>
            <button class="modal-close" onclick="document.getElementById('laporanModal').style.display='none'">&times;</button>
        </div>
        <div class="modal-body">
            <form id="laporanForm">
                <input type="hidden" id="lap_kegiatan_id">
                
                <h4 class="form-label" style="font-size: 0.85rem; color: var(--primary); margin-bottom: 16px;">Rincian Layanan</h4>
                <div class="grid-3">
                    <div class="form-group">
                        <label class="form-label">Informasi</label>
                        <input type="number" id="layanan_informasi" name="layanan_informasi" class="form-input" value="0" min="0" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Administrasi</label>
                        <input type="number" id="layanan_administrasi" name="layanan_administrasi" class="form-input" value="0" min="0" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pengaduan</label>
                        <input type="number" id="layanan_pengaduan" name="layanan_pengaduan" class="form-input" value="0" min="0" required>
                    </div>
                </div>

                <div class="grid-2" style="margin-top:20px;">
                    <div class="form-group">
                        <label class="form-label text-success">Transaksi Berhasil</label>
                        <input type="number" id="transaksi_berhasil" name="transaksi_berhasil" class="form-input" value="0" min="0" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label text-danger">Transaksi Gagal</label>
                        <input type="number" id="transaksi_gagal" name="transaksi_gagal" class="form-input" value="0" min="0" required>
                    </div>
                </div>

                <div class="form-group" style="padding-bottom: 20px; border-bottom: 1px solid var(--border); margin-bottom: 20px;">
                    <label class="form-label">Total Peserta Kunjungan</label>
                    <input type="number" id="jumlah_peserta" name="jumlah_peserta" class="form-input" value="0" min="0" required>
                </div>

                <h4 class="form-label" style="font-size: 0.85rem; color: var(--primary); margin-bottom: 16px;">Feedback Kepuasan Peserta</h4>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label" style="color: var(--success);">Jumlah Puas</label>
                        <input type="number" id="kepuasan_puas" name="kepuasan_puas" class="form-input" value="0" min="0" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" style="color: var(--danger);">Jumlah Tidak Puas</label>
                        <input type="number" id="kepuasan_tidak_puas" name="kepuasan_tidak_puas" class="form-input" value="0" min="0" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Catatan Evaluasi / Keterangan</label>
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
