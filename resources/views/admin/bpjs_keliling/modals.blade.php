<!-- Modal Form Kegiatan -->
<div id="bpjsModal" class="modal-overlay" style="display:none;">
    <div class="modal-content" style="max-width: 1100px;">
        <div class="modal-header">
            <h3 class="modal-title" id="modal-title">BPJS Keliling</h3>
            <button class="modal-close" id="btn-close-modal" onclick="document.getElementById('bpjsModal').style.display='none'">&times;</button>
        </div>
        <div class="modal-body">
            <form id="bpjsForm">
                <input type="hidden" id="bpjs_id" name="id">
                
                <div class="grid-3" style="grid-template-columns: 1fr 1fr 1fr; gap: 24px;">
                    <!-- Kolom 1: Informasi Dasar -->
                    <div class="flex-col" style="gap: 16px;">
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
                    </div>

                    <!-- Kolom 2: Waktu & Petugas -->
                    <div class="flex-col" style="gap: 16px;">
                        <div class="form-group">
                            <label class="form-label">Nama Frontliner</label>
                            <input type="text" id="nama_frontliner" name="nama_frontliner" class="form-input" required>
                        </div>
                        <div class="grid-2" style="gap: 10px;">
                            <div class="form-group">
                                <label class="form-label">Tanggal</label>
                                <input type="date" id="tanggal" name="tanggal" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Mulai</label>
                                <input type="time" id="jam_mulai" name="jam_mulai" class="form-input">
                            </div>
                        </div>
                        <div class="grid-2" style="gap: 10px;">
                            <div class="form-group">
                                <label class="form-label">Selesai</label>
                                <input type="time" id="jam_selesai" name="jam_selesai" class="form-input">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Petugas (Org)</label>
                                <input type="number" id="jumlah_petugas" name="jumlah_petugas" class="form-input" value="1" min="1" required>
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

                    <!-- Kolom 3: Lokasi -->
                    <div class="flex-col" style="gap: 16px;">
                        <div class="form-group">
                            <div class="flex justify-between items-center mb-1">
                                <label class="form-label mb-0">Wilayah (Master Data)</label>
                                <button type="button" onclick="window.detectGPS()" class="btn btn-secondary" style="font-size: 10px; padding: 2px 8px; height: auto; display: flex; align-items: center; gap: 4px; background: #fff; border-color: #000; color: #000;">
                                    <i data-lucide="map-pin" style="width: 10px; height: 10px;"></i> Deteksi Lokasi (GPS)
                                </button>
                            </div>
                            <div class="flex-col" style="gap: 8px;">
                                <select id="provinsi_id" name="provinsi_id" class="form-input"><option value="">Provinsi...</option></select>
                                <select id="kota_id" name="kota_id" class="form-input" disabled><option value="">Kota/Kab...</option></select>
                                <select id="kecamatan_id" name="kecamatan_id" class="form-input" disabled><option value="">Kecamatan...</option></select>
                            </div>
                        </div>
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
                            <label class="form-label">Nama Desa / Kelurahan</label>
                            <input type="text" id="nama_desa" name="nama_desa" class="form-input" placeholder="Nama Desa...">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Detail / Titik Nol</label>
                            <input type="text" id="lokasi_detail" name="lokasi_detail" class="form-input">
                        </div>
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




