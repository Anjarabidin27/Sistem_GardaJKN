@extends('layouts.app')

@section('title', 'Laporan BPJS Keliling - Garda JKN')

@section('content')
<div class="main-layout">
    <div class="page-header">
        <div>
            <h1 class="page-title">BPJS Keliling</h1>
            <p class="page-subtitle">Pencatatan kegiatan layanan di lapangan secara real-time.</p>
        </div>
        <button class="btn btn-primary" onclick="showModal('modalKegiatan')">
            <i data-lucide="plus-circle" style="width:18px;"></i>
            <span>Laporan Baru</span>
        </button>
    </div>

    <!-- Stats Row -->
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e0f2fe; color:#0369a1;"><i data-lucide="calendar"></i></div>
            <div class="stat-value" id="count-kegiatan">0</div>
            <div class="stat-label">Total Kegiatan</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#f0fdf4; color:#15803d;"><i data-lucide="users"></i></div>
            <div class="stat-value" id="count-peserta">0</div>
            <div class="stat-label">Terlayani</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#fff7ed; color:#c2410c;"><i data-lucide="smile"></i></div>
            <div class="stat-value" id="count-puas">0%</div>
            <div class="stat-label">Kepuasan</div>
        </div>
    </div>

    <!-- Table List -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="table-bpjs">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Tanggal</th>
                            <th>Kegiatan</th>
                            <th>Wilayah</th>
                            <th>Peserta</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="list-bpjs">
                        <!-- Loaded by JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
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
@endsection
