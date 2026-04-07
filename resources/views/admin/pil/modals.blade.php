<!-- Modal Form PIL -->
<div id="pilModal" class="modal-overlay" style="display:none;">
    <div class="modal-content" style="max-width: 1100px;">
        <div class="modal-header">
            <h3 class="modal-title" id="modal-title">Jadwal Agenda PIL</h3>
            <button class="modal-close" id="btn-close-modal" onclick="document.getElementById('pilModal').style.display='none'">&times;</button>
        </div>
        <div class="modal-body">
            <form id="pilForm">
                <input type="hidden" id="pil_id" name="id">
                
                <div class="grid-3" style="grid-template-columns: 1fr 1fr 1fr; gap: 24px;">
                    <!-- Kolom 1 -->
                    <div class="flex-col" style="gap: 16px;">
                        <div class="form-group">
                            <label class="form-label">Nama/Judul Kegiatan Penyuluhan</label>
                            <input type="text" id="judul" name="judul" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nama Frontliner</label>
                            <input type="text" id="nama_frontliner" name="nama_frontliner" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Jumlah Petugas</label>
                            <input type="number" id="jumlah_petugas" name="jumlah_petugas" class="form-input" value="1" min="1" required>
                        </div>
                    </div>

                    <!-- Kolom 2 -->
                    <div class="flex-col" style="gap: 16px;">
                        <div class="grid-1" style="gap: 10px;">
                            <div class="form-group">
                                <label class="form-label">Tanggal Pelaksanaan</label>
                                <input type="date" id="tanggal" name="tanggal" class="form-input" required>
                            </div>
                        </div>
                        <div class="grid-2" style="gap: 10px;">
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
                            <label class="form-label" style="display:flex; justify-content:space-between; align-items:center;">
                                Status Pelaksanaan
                                <span id="status-auto-badge" class="badge-status-pill" style="font-size: 10px; background: var(--bg-soft); color: var(--text-muted); border: 1px solid var(--border); padding: 2px 6px; border-radius: 4px; display:none;">
                                    <i data-lucide="zap" style="width:10px; height:10px; display:inline"></i> Otomatis
                                </span>
                            </label>
                            <select id="status" name="status" class="form-input" required>
                                <option value="scheduled">Terjadwal</option>
                                <option value="ongoing">Berlangsung</option>
                                <option value="completed">Selesai</option>
                                <option value="cancelled">Dibatalkan</option>
                            </select>
                        </div>
                    </div>

                    <!-- Kolom 3 -->
                    <div class="flex-col" style="gap: 16px;">
                        <div class="form-group">
                            <label class="form-label">Wilayah (Master DB)</label>
                            <div class="flex-col" style="gap: 8px;">
                                <select id="provinsi_id" name="provinsi_id" class="form-input"><option value="">Provinsi...</option></select>
                                <select id="kota_id" name="kota_id" class="form-input" disabled><option value="">Kota/Kab...</option></select>
                                <select id="kecamatan_id" name="kecamatan_id" class="form-input" disabled><option value="">Kecamatan...</option></select>
                            </div>
                        </div>
                        <div class="form-group" style="margin-top: -4px;">
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
                            <label class="form-label">Detail Lapangan</label>
                            <input type="text" id="lokasi_detail" name="lokasi_detail" class="form-input">
                        </div>
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



