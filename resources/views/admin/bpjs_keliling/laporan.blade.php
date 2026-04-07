<x-admin-layout title="Daftar Terinput - BPJS Keliling">
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 800; color: #0F172A; letter-spacing: -0.02em;">Daftar Terinput</h1>
            <p style="font-size: 0.875rem; color: #64748B; margin-top: 2px;">Rekapitulasi laporan operasional BPJS Keliling.</p>
        </div>
        <div class="flex gap-2">
            <button class="btn" style="background: #fff; border: 1px solid #E2E8F0; color: #0F172A; padding: 8px 16px; font-size: 0.8rem;" onclick="window.printReport()"><i data-lucide="printer" style="width:14px;height:14px"></i> Cetak</button>
            <button class="btn" style="background: #10B981; color: #fff; padding: 8px 16px; font-size: 0.8rem; font-weight: 700;" onclick="window.exportExcel()"><i data-lucide="download" style="width:14px;height:14px"></i> Export Excel</button>
        </div>
    </div>

    <!-- Compact Filter Bar -->
    <div style="background: #fff; border: 1px solid #E2E8F0; border-radius: 12px; padding: 12px 20px; margin-bottom: 24px; display: flex; align-items: flex-end; gap: 16px; box-shadow: 0 1px 2px rgba(0,0,0,0.03);">
        <form id="filterForm" style="display: contents;">
            <div style="flex: 1; min-width: 0;">
                <label style="display: block; font-size: 0.65rem; font-weight: 800; color: #64748B; text-transform: uppercase; margin-bottom: 6px;">Kegiatan</label>
                <select id="kegiatan_id" class="form-input" style="height: 38px; font-size: 0.85rem; border-color: #E2E8F0;" required>
                    <option value="">-- Pilih Kegiatan --</option>
                </select>
            </div>
            <div style="width: 200px;">
                <label style="display: block; font-size: 0.65rem; font-weight: 800; color: #64748B; text-transform: uppercase; margin-bottom: 6px;">Pencarian</label>
                <input type="text" id="search_peserta" class="form-input" style="height: 38px; font-size: 0.85rem; border-color: #E2E8F0;" placeholder="NIK / Layanan...">
            </div>
            <div style="width: 150px;">
                <label style="display: block; font-size: 0.65rem; font-weight: 800; color: #64748B; text-transform: uppercase; margin-bottom: 6px;">Status</label>
                <select id="status_filter" class="form-input" style="height: 38px; font-size: 0.85rem; border-color: #E2E8F0;">
                    <option value="">Semua</option>
                    <option value="Berhasil">Berhasil</option>
                    <option value="Tidak Berhasil">Gagal</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="height: 38px; padding: 0 20px; font-size: 0.85rem; font-weight: 700;">
                Tampilkan
            </button>
        </form>
    </div>

    <!-- Metric Cards (Modern Horizontal) -->
    <div id="stats-container" style="display: none; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px;">
        <div style="background: #fff; border: 1px solid #E2E8F0; padding: 16px; border-radius: 12px;">
            <div style="font-size: 0.7rem; font-weight: 700; color: #64748B; text-transform: uppercase; margin-bottom: 4px;">Total Peserta</div>
            <div id="stat-total" style="font-size: 1.5rem; font-weight: 800; color: #0F172A; font-family: 'Outfit';">0</div>
        </div>
        <div style="background: #fff; border: 1px solid #E2E8F0; padding: 16px; border-radius: 12px; border-left: 4px solid #10B981;">
            <div style="font-size: 0.7rem; font-weight: 700; color: #64748B; text-transform: uppercase; margin-bottom: 4px;">Berhasil</div>
            <div id="stat-berhasil" style="font-size: 1.5rem; font-weight: 800; color: #10B981; font-family: 'Outfit';">0</div>
        </div>
        <div style="background: #fff; border: 1px solid #E2E8F0; padding: 16px; border-radius: 12px; border-left: 4px solid #EF4444;">
            <div style="font-size: 0.7rem; font-weight: 700; color: #64748B; text-transform: uppercase; margin-bottom: 4px;">Gagal</div>
            <div id="stat-gagal" style="font-size: 1.5rem; font-weight: 800; color: #EF4444; font-family: 'Outfit';">0</div>
        </div>
        <div style="background: #fff; border: 1px solid #E2E8F0; padding: 16px; border-radius: 12px; border-left: 4px solid #3B82F6;">
            <div style="font-size: 0.7rem; font-weight: 700; color: #64748B; text-transform: uppercase; margin-bottom: 4px;">Tingkat Kepuasan</div>
            <div id="stat-puas" style="font-size: 1.5rem; font-weight: 800; color: #3B82F6; font-family: 'Outfit';">0%</div>
        </div>
    </div>

    <!-- Data Table -->
    <div id="table-container" style="display: none; background: #fff; border: 1px solid #E2E8F0; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #F8FAFC; border-bottom: 1px solid #E2E8F0;">
                <tr>
                    <th style="padding: 12px 16px; text-align: left; font-size: 0.65rem; font-weight: 800; color: #64748B; text-transform: uppercase;">Jam</th>
                    <th style="padding: 12px 16px; text-align: left; font-size: 0.65rem; font-weight: 800; color: #64748B; text-transform: uppercase;">NIK / Segmen</th>
                    <th style="padding: 12px 16px; text-align: left; font-size: 0.65rem; font-weight: 800; color: #64748B; text-transform: uppercase;">Layanan</th>
                    <th style="padding: 12px 16px; text-align: left; font-size: 0.65rem; font-weight: 800; color: #64748B; text-transform: uppercase;">Kepuasan</th>
                    <th style="padding: 12px 16px; text-align: center; font-size: 0.65rem; font-weight: 800; color: #64748B; text-transform: uppercase;">Status</th>
                </tr>
            </thead>
            <tbody id="laporanTableBody"></tbody>
        </table>
    </div>

    <div id="initial-state" class="card text-center" style="padding: 60px 20px;">
        <i data-lucide="list-filter" style="width: 48px; height: 48px; color: var(--border); margin: 0 auto 16px;"></i>
        <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--text);">Belum Ada Data yang Ditampilkan</h3>
        <p class="text-muted" style="max-width: 400px; margin: 8px auto 0;">Silakan pilih "Kegiatan" pada kolom filter di atas untuk melihat daftar lengkap peserta yang terinput pada kegiatan tersebut.</p>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
        @vite(['resources/js/pages/admin_bpjs_keliling_laporan.js'])
    @endpush
</x-admin-layout>
