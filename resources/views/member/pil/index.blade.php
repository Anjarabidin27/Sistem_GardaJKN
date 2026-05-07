<x-pengurus-layout title="Laporan PIL - Garda JKN">
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
            <h1 class="v-title">Sesi Sosialisasi (PIL)</h1>
            <p class="v-subtitle">Edukasi masyarakat dan pengukuran NPS secara langsung di wilayah koordinasi Anda.</p>
        </div>
        <button class="btn-primary" onclick="showModal('modalKegiatan')">
            <i data-lucide="mic" style="width: 18px; height: 18px;"></i>
            <span>Mulai Sosialisasi Baru</span>
        </button>
    </div>

    <!-- Summary Stats -->
    <div class="v-summary-grid">
        <div class="v-stat-card">
            <div class="v-stat-icon"><i data-lucide="megaphone"></i></div>
            <div>
                <span class="v-label-caps">Total Sesi</span>
                <div class="v-stat-val" id="count-kegiatan">0</div>
            </div>
        </div>
        <div class="v-stat-card">
            <div class="v-stat-icon" style="color: var(--v-emerald-500);"><i data-lucide="users"></i></div>
            <div>
                <span class="v-label-caps">Peserta Hadir</span>
                <div class="v-stat-val" id="count-peserta">0</div>
            </div>
        </div>
        <div class="v-stat-card">
            <div class="v-stat-icon" style="color: #f59e0b;"><i data-lucide="star"></i></div>
            <div>
                <span class="v-label-caps">Rata-rata NPS</span>
                <div class="v-stat-val" id="avg-nps">-</div>
            </div>
        </div>
    </div>

    <!-- Table List -->
    <div class="v-card">
        <table class="v-table">
            <thead>
                <tr>
                    <th width="15%">Tanggal</th>
                    <th width="35%">Judul Sosialisasi</th>
                    <th width="20%">Frontliner</th>
                    <th width="15%">Peserta</th>
                    <th width="15%" style="text-align: right;">Rata NPS</th>
                </tr>
            </thead>
            <tbody id="list-pil">
                <tr>
                    <td colspan="5" style="padding: 4rem; text-align: center;">
                        <span class="loading-spinner"></span>
                        <p class="v-label-caps" style="margin-top: 1rem;">Menghubungkan ke Command Hub...</p>
                    </td>
                </tr>
            </tbody>
        </table>
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
</x-pengurus-layout>
