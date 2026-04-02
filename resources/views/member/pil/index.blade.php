@extends('layouts.app')

@section('title', 'Laporan PIL - Garda JKN')

@section('content')
<div class="main-layout">
    <div class="page-header">
        <div>
            <h1 class="page-title">Pemberian Informasi Langsung (PIL)</h1>
            <p class="page-subtitle">Pencatatan sesi sosialisasi dan survei NPS kepada masyarakat.</p>
        </div>
        <button class="btn btn-primary" onclick="showModal('modalKegiatan')">
            <i data-lucide="mic" style="width:18px;"></i>
            <span>Sosialisasi Baru</span>
        </button>
    </div>

    <!-- Stats Row -->
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e0f2fe; color:#0369a1;"><i data-lucide="megaphone"></i></div>
            <div class="stat-value" id="count-kegiatan">0</div>
            <div class="stat-label">Total Sesi</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#f0fdf4; color:#15803d;"><i data-lucide="users"></i></div>
            <div class="stat-value" id="count-peserta">0</div>
            <div class="stat-label">Peserta Hadir</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#fff7ed; color:#c2410c;"><i data-lucide="star"></i></div>
            <div class="stat-value" id="avg-nps">-</div>
            <div class="stat-label">Rata-rata NPS</div>
        </div>
    </div>

    <!-- Table List -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Tanggal</th>
                            <th>Kegiatan</th>
                            <th>Frontliner</th>
                            <th>Peserta</th>
                            <th>Rata NPS</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="list-pil">
                        <!-- Loaded by JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Header PIL -->
<div id="modalKegiatan" class="modal-overlay" style="display:none;">
    <div class="modal-content" style="max-width:800px;">
        <div class="modal-header">
            <h2 class="modal-title">Mulai Sesi Sosialisasi</h2>
            <button type="button" class="modal-close" style="z-index: 50; position: relative; pointer-events: auto;" onclick="document.getElementById('modalKegiatan').style.display='none'">&times;</button>
        </div>
        <form id="formKegiatan">
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Judul Sesi / Lokasi</label>
                        <input type="text" id="judul" class="form-control" placeholder="Contoh: Sosialisasi JKN Masjid Jami" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal</label>
                        <input type="date" id="tanggal" class="form-control" required value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Nama Frontliner (Pemateri)</label>
                        <input type="text" id="nama_frontliner" class="form-control" placeholder="Nama petugas pemateri" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Provinsi</label>
                        <select id="provinsi_id" class="form-control" onchange="window.loadCities(this.value, null, 'kota_id', 'kecamatan_id')">
                            <option value="">Pilih...</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kota/Kab</label>
                        <select id="kota_id" class="form-control" onchange="window.loadDistricts(this.value, null, 'kecamatan_id')">
                            <option value="">Pilih...</option>
                        </select>
                    </div>
                    <div class="col-md-9">
                        <label class="form-label">Kecamatan</label>
                        <select id="kecamatan_id" class="form-control">
                            <option value="">Pilih...</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Kelurahan/Desa</label>
                        <input type="text" id="nama_desa" class="form-control" placeholder="Nama Desa">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('modalKegiatan').style.display='none'">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan Edukasi & Lanjut Isi Survei NPS</button>
        </div>
        </form>
    </div>
</div>

<!-- Modal Entry Peserta PIL (NPS Focused) -->
<div id="modalParticipant" class="modal-overlay" style="display:none;">
    <div class="modal-content" style="max-width:650px;">
        <div class="modal-header">
            <h2 class="modal-title">Input Survei Peserta</h2>
            <button type="button" class="modal-close" style="z-index: 50; position: relative; pointer-events: auto;" onclick="document.getElementById('modalParticipant').style.display='none'">&times;</button>
        </div>
        <form id="formParticipant">
            <input type="hidden" id="p_activity_id">
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6 text-primary" style="font-weight:600; font-size:0.85rem;">
                        <i data-lucide="user" style="width:14px; vertical-align:middle;"></i> Data Identitas
                        <hr class="mt-1 mb-2">
                        <div class="mb-2">
                            <label class="form-label text-dark">NIK</label>
                            <input type="text" id="p_nik" class="form-control form-control-sm" required maxlength="16">
                        </div>
                        <div class="mb-2">
                            <label class="form-label text-dark">Nama / No HP</label>
                            <div class="input-group input-group-sm">
                                <input type="text" id="p_name" class="form-control" placeholder="Nama">
                                <input type="text" id="p_phone" class="form-control" placeholder="HP">
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label text-dark">Segmen Peserta</label>
                            <select id="p_segmen" class="form-control form-control-sm" required>
                                <option value="PBPU">PBPU (Mandiri)</option>
                                <option value="BP">BP (Bukan Pekerja)</option>
                                <option value="PPU BU">PPU BU (Badan Usaha)</option>
                                <option value="PPU Pemerintah">PPU Pemerintah</option>
                                <option value="PBI APBN">PBI APBN</option>
                                <option value="PBI APBD">PBI APBD</option>
                            </select>
                        </div>
                        <div class="row gx-2 mb-2">
                            <div class="col-6">
                                <label class="form-label text-dark" style="font-size:0.75rem;">Jam Mulai</label>
                                <input type="time" id="p_jam_mulai" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label text-dark" style="font-size:0.75rem;">Jam Selesai</label>
                                <input type="time" id="p_jam_selesai" class="form-control form-control-sm" required>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label text-dark">Hasil Pemahaman (0-100)</label>
                            <input type="number" id="p_pemahaman" class="form-control form-control-sm" required min="0" max="100" value="80">
                        </div>
                        <div class="mb-2">
                            <label class="form-label text-dark">Efektifitas Sosialisasi</label>
                            <select id="p_efektifitas" class="form-control form-control-sm" required>
                                <option value="Sangat Tidak Memuaskan">Sangat Tidak Memuaskan</option>
                                <option value="Tidak Memuaskan">Tidak Memuaskan</option>
                                <option value="Kurang Memuaskan">Kurang Memuaskan</option>
                                <option value="Cukup Memuaskan">Cukup Memuaskan</option>
                                <option value="Memuaskan">Memuaskan</option>
                                <option value="Sangat Memuaskan">Sangat Memuaskan</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6" style="background:#f8fafc; border-radius:12px; padding:15px;">
                        <div style="font-weight:600; color:#1e293b; font-size:0.9rem; margin-bottom:10px;">Survei NPS (Skala 1-10)</div>
                        
                        <div class="mb-3">
                            <label class="form-label" style="font-size:0.75rem;">1. Ketertarikan thd Program JKN</label>
                            <input type="range" class="form-range" id="p_nps1" min="1" max="10" step="1" oninput="this.nextElementSibling.value = this.value">
                            <output class="badge bg-primary float-end">5</output>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label" style="font-size:0.75rem;">2. Rekomendasi Program JKN</label>
                            <input type="range" class="form-range" id="p_nps2" min="1" max="10" step="1" oninput="this.nextElementSibling.value = this.value">
                            <output class="badge bg-primary float-end">5</output>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" style="font-size:0.75rem;">3. Rekomendasi BPJS Kesehatan</label>
                            <input type="range" class="form-range" id="p_nps3" min="1" max="10" step="1" oninput="this.nextElementSibling.value = this.value">
                            <output class="badge bg-primary float-end">5</output>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('modalParticipant').style.display='none'">Selesai</button>
            <button type="submit" class="btn btn-primary">Simpan & Isi Survei Lainnya</button>
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
@vite(['resources/js/pages/pil.js', 'resources/js/pages/member.js'])
@endpush
@endsection
