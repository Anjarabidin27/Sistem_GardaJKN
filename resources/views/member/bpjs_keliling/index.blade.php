<x-pengurus-layout title="Laporan BPJS Keliling - Garda JKN">
    <style>
        :root {
            --v-black: #000;
            --v-white: #fff;
            --v-gray-50: #fbfbfc;
            --v-gray-100: #f3f4f6;
            --v-gray-200: #e5e7eb;
            --v-gray-400: #9ca3af;
            --v-gray-500: #6b7280;
            --v-emerald-500: #10b981;
            --v-blue-600: #2ea0fb;
        }

        .header-row { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; }
        .v-title { font-size: 1.75rem; font-weight: 900; letter-spacing: -0.04em; color: var(--v-black); margin: 0; }
        .v-subtitle { font-size: 0.875rem; color: var(--v-gray-500); margin-top: 4px; }

        .btn-primary {
            background: var(--v-black); color: var(--v-white);
            padding: 12px 24px; border-radius: 12px; border: none;
            font-size: 0.875rem; font-weight: 800; cursor: pointer;
            display: flex; align-items: center; gap: 8px; transition: all 0.2s;
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 20px -5px rgba(0,0,0,0.1); }

        .v-summary-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 2rem; }
        .v-stat-card {
            background: var(--v-white); padding: 1.5rem; border-radius: 1.25rem;
            border: 1px solid var(--v-gray-100); display: flex; align-items: center; gap: 1.25rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }
        .v-stat-icon {
            width: 44px; height: 44px; border-radius: 12px; background: var(--v-gray-50);
            display: flex; align-items: center; justify-content: center; color: var(--v-black);
        }
        .v-stat-val { font-size: 1.5rem; font-weight: 900; color: var(--v-black); line-height: 1; }
        .v-label-caps {
            font-size: 9px; font-weight: 800; text-transform: uppercase;
            letter-spacing: 0.1em; color: var(--v-gray-400); display: block; margin-bottom: 2px;
        }

        .v-card {
            background: var(--v-white); border-radius: 1.25rem; border: 1px solid var(--v-gray-100);
            overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }
        .v-table { width: 100%; border-collapse: collapse; }
        .v-table th { 
            text-align: left; padding: 1rem 1.5rem; background: var(--v-gray-50);
            font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em;
            color: var(--v-gray-400); border-bottom: 1px solid var(--v-gray-100);
        }
        .v-table td { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--v-gray-50); vertical-align: middle; }
        .v-table tr:last-child td { border-bottom: none; }
        .v-table tr:hover { background: #fafbfc; }

        @media (max-width: 768px) {
            .header-row { flex-direction: column; align-items: flex-start; gap: 1rem; }
            .v-summary-grid { grid-template-columns: 1fr; }
        }
    </style>

    <div class="header-row">
        <div>
            <h1 class="v-title">Layanan BPJS Keliling</h1>
            <p class="v-subtitle">Pencatatan kegiatan pelayanan lapangan di wilayah koordinasi Anda.</p>
        </div>
        <button class="btn-primary" onclick="showModal('modalKegiatan')">
            <i data-lucide="plus-circle" style="width: 18px; height: 18px;"></i>
            <span>Buat Laporan Baru</span>
        </button>
    </div>

    <!-- Summary Stats -->
    <div class="v-summary-grid">
        <div class="v-stat-card">
            <div class="v-stat-icon"><i data-lucide="calendar"></i></div>
            <div>
                <span class="v-label-caps">Total Kegiatan</span>
                <div class="v-stat-val" id="count-kegiatan">0</div>
            </div>
        </div>
        <div class="v-stat-card">
            <div class="v-stat-icon" style="color: var(--v-emerald-500);"><i data-lucide="users"></i></div>
            <div>
                <span class="v-label-caps">Peserta Terlayani</span>
                <div class="v-stat-val" id="count-peserta">0</div>
            </div>
        </div>
        <div class="v-stat-card">
            <div class="v-stat-icon" style="color: var(--v-blue-600);"><i data-lucide="smile"></i></div>
            <div>
                <span class="v-label-caps">Tingkat Kepuasan</span>
                <div class="v-stat-val" id="count-puas">0%</div>
            </div>
        </div>
    </div>

    <!-- Table List -->
    <div class="v-card">
        <table class="v-table">
            <thead>
                <tr>
                    <th width="15%">Tanggal</th>
                    <th width="35%">Judul Kegiatan</th>
                    <th width="20%">Wilayah / Kota</th>
                    <th width="15%">Peserta</th>
                    <th width="15%" style="text-align: right;">Status</th>
                </tr>
            </thead>
            <tbody id="list-bpjs">
                <tr>
                    <td colspan="5" style="padding: 4rem; text-align: center;">
                        <span class="loading-spinner"></span>
                        <p class="v-label-caps" style="margin-top: 1rem;">Menghubungkan ke Command Hub...</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

<!-- Modal Header Kegiatan -->
<div id="modalKegiatan" class="modal-overlay" style="display:none;">
    <div class="modal-content" style="max-width:800px;">
        <div class="modal-header">
            <h2 class="modal-title">Laporan Penugasan BPJS Keliling</h2>
            <button type="button" class="modal-close" style="z-index: 50; position: relative; pointer-events: auto;" onclick="document.getElementById('modalKegiatan').style.display='none'">&times;</button>
        </div>
        <form id="formKegiatan">
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Kedeputian Wilayah</label>
                        <input type="text" class="form-control" value="Otomatis (Sesuai Login)" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kantor Cabang</label>
                        <input type="text" class="form-control" value="Otomatis (Sesuai Login)" disabled>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Judul Kegiatan / Instansi</label>
                        <input type="text" id="judul" class="form-control" placeholder="Contoh: BPJS Keliling Desa Makmur" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Jenis Kegiatan</label>
                        <select id="jenis_kegiatan" class="form-control" required>
                            <option value="Goes To Village">Goes To Village</option>
                            <option value="Around City">Around City</option>
                            <option value="Hi Customer">Hi Customer</option>
                            <option value="Corporate Gathering">Corporate Gathering</option>
                            <option value="CFD">CFD</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Pelaksanaan</label>
                        <input type="date" id="tanggal" class="form-control" required value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kuadran</label>
                        <select id="kuadran" class="form-control" required>
                            <option value="1- Engagement">1- Engagement</option>
                            <option value="2- Rekrutmen">2- Rekrutmen</option>
                            <option value="3- Pembaharuan Data">3- Pembaharuan Data</option>
                            <option value="4- Iuran">4- Iuran</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Lokasi Kegiatan</label>
                        <select id="lokasi_kegiatan" class="form-control" required>
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
                    <div class="col-md-12">
                        <label class="form-label">Nama Frontliner yang Bertugas</label>
                        <input type="text" id="nama_frontliner" class="form-control" placeholder="Nama lengkap petugas pemateri" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Provinsi</label>
                        <select id="provinsi_id" class="form-control" onchange="window.loadCities(this.value, null, 'kota_id', 'kecamatan_id')" required>
                            <option value="">Pilih Provinsi...</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kabupaten/Kota</label>
                        <select id="kota_id" class="form-control" onchange="window.loadDistricts(this.value, null, 'kecamatan_id')" required>
                            <option value="">Pilih Kota...</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kecamatan</label>
                        <select id="kecamatan_id" class="form-control" required>
                            <option value="">Pilih Kecamatan...</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kelurahan/Desa</label>
                        <input type="text" id="nama_desa" class="form-control" placeholder="Nama Desa/Kelurahan">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('modalKegiatan').style.display='none'">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan Laporan & Lanjut Isi Peserta</button>
        </div>
        </form>
    </div>
</div>

<!-- Modal Entry Peserta -->
<div id="modalParticipant" class="modal-overlay" style="display:none;">
    <div class="modal-content" style="max-width:600px;">
        <div class="modal-header">
            <h2 class="modal-title">Entry Peserta Terlayani</h2>
            <button type="button" class="modal-close" style="z-index: 50; position: relative; pointer-events: auto;" onclick="document.getElementById('modalParticipant').style.display='none'">&times;</button>
        </div>
        <form id="formParticipant">
            <input type="hidden" id="p_activity_id">
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label">NIK (16 Digit)</label>
                        <input type="text" id="p_nik" class="form-control" placeholder="33..." required maxlength="16">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nama Peserta</label>
                        <input type="text" id="p_name" class="form-control" placeholder="Nama lengkap">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nomor WhatsApp</label>
                        <input type="text" id="p_phone" class="form-control" placeholder="08...">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jam Mulai Layanan</label>
                        <input type="time" id="p_jam_mulai" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jam Selesai Layanan</label>
                        <input type="time" id="p_jam_selesai" class="form-control" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Segmen Peserta</label>
                        <select id="p_segmen" class="form-control" required>
                            <option value="PBPU">PBPU (Mandiri)</option>
                            <option value="BP">BP (Bukan Pekerja)</option>
                            <option value="PPU BU">PPU BU (Badan Usaha)</option>
                            <option value="PPU Pemerintah">PPU Pemerintah</option>
                            <option value="PBI APBN">PBI APBN</option>
                            <option value="PBI APBD">PBI APBD</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jenis Layanan</label>
                        <select id="p_jenis" class="form-control" required onchange="window.updateTransaksi(this.value)">
                            <option value="Administrasi">Administrasi</option>
                            <option value="Informasi">Informasi</option>
                            <option value="Pengaduan">Pengaduan</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Transaksi Layanan</label>
                        <select id="p_transaksi" class="form-control" required>
                            <option value="">Pilih...</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select id="p_status" class="form-control" required onchange="window.toggleGagal(this.value)">
                            <option value="Berhasil">Berhasil</option>
                            <option value="Tidak Berhasil">Tidak Berhasil</option>
                        </select>
                    </div>
                    <div class="col-md-6" id="div_keterangan_gagal" style="display:none;">
                        <label class="form-label">Alasan Tidak Berhasil</label>
                        <select id="p_keterangan_gagal" class="form-control">
                            <option value="">-- Pilih Alasan --</option>
                            <option value="Adanya tindaklanjut rekonsiliasi data">Adanya tindaklanjut rekonsiliasi data</option>
                            <option value="Berkas persyaratan belum lengkap">Berkas persyaratan belum lengkap</option>
                            <option value="NIK tidak sesuai, tidak padan dengan Dukcapil atau data tidak valid">NIK tidak sesuai, tidak padan dengan Dukcapil atau data tidak valid</option>
                            <option value="Perlu koordinasi atau laporan ke Dinsos/Dukcapil">Perlu koordinasi atau laporan ke Dinsos/Dukcapil</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Suara Pelanggan</label>
                        <select id="p_puas" class="form-control" required>
                            <option value="Puas">Puas</option>
                            <option value="Tidak puas">Tidak puas</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('modalParticipant').style.display='none'">Selesai</button>
            <button type="submit" class="btn btn-primary">Simpan & Input Peserta Lagi</button>
        </div>
        </form>
    </div>
</div>

<!-- Modal QR Code -->
<div id="modalQR" class="modal-overlay" style="display:none;">
    <div class="modal-content" style="max-width:400px; text-align:center;">
        <div class="modal-header">
            <h2 class="modal-title">QR Survei Kepuasan</h2>
            <button type="button" class="modal-close" style="z-index: 50; position: relative; pointer-events: auto;" onclick="document.getElementById('modalQR').style.display='none'">&times;</button>
        </div>
        <div class="modal-body">
            <p id="qr-title" style="font-weight:bold; margin-bottom:15px; color:#0f172a;"></p>
            <div id="qr-container" style="display:flex; justify-content:center; margin-bottom:20px;">
                <img id="qr-image" src="" alt="QR Code" style="width: 250px; height: 250px; border: 1px solid #e2e8f0; border-radius: 10px; padding: 10px;">
            </div>
            <p style="font-size:0.875rem; color:#64748b;">Minta peserta memindai QR code ini untuk memberikan rating kepuasan secara anonim.</p>
            <div style="margin-top: 15px;">
                <a id="qr-link" href="#" target="_blank" style="font-size: 0.8rem; color: #0ea5e9; text-decoration: none;">Buka Link Survei Manual</a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('click', function(e) {
        let btn = e.target.closest('.modal-close');
        if (btn) {
            let overlay = btn.closest('.modal-overlay');
            if (overlay) overlay.style.display = 'none';
        }
    });
</script>
@vite(['resources/js/pages/bpjs_keliling.js', 'resources/js/pages/member.js'])
@endpush
</x-pengurus-layout>
