<x-admin-layout title="Dashboard PIL">
    <div class="justify-between items-end mb-4 flex">
        <div>
            <h1 class="topbar-title" style="font-size: 1.75rem;">Dashboard Laporan PIL</h1>
            <p class="text-muted" style="margin-top: 4px;">Indikator KPI Program Informasi Langsung (Penyuluhan).</p>
        </div>
        <div class="flex gap-2">
            <a href="/admin/pil" class="btn btn-secondary">Kembali ke Agenda</a>
        </div>
    </div>

    <!-- SUMMARY -->
    <div class="summary-grid" id="dashboard-summary">
        <div class="stat-card">
            <div class="stat-label">Total Kehadiran Peserta</div>
            <div class="stat-value" id="tot-pes">0</div>
        </div>
        <div class="stat-card stat-card-blue">
            <div class="stat-label">Rata-Rata Uji Pemahaman</div>
            <div class="stat-value"><span id="avg-pemahaman">0</span>%</div>
            <div class="text-muted" style="font-size: 0.70rem; font-weight: 600; margin-top: 4px;">Metode Post-Test (0-100)</div>
        </div>
        <div class="stat-card stat-card-green">
            <div class="stat-label">Ketertarikan JKN (1-10)</div>
            <div class="stat-value text-success" id="avg-ketertarikan">0</div>
        </div>
        <div class="stat-card stat-card-warning">
            <div class="stat-label">Total Sesi Kegiatan</div>
            <div class="stat-value text-warning" id="tot-keg">0</div>
        </div>
    </div>

    <div class="table-card p-4 mx-auto" style="max-width: 800px;">
        <h3 class="modal-title mb-4">Evaluasi Tingkat Efektivitas Program</h3>
        <p class="text-muted mb-4" style="font-size: 0.85rem;">Nilai agregat diambil berdasarkan survei skala 1 - 10 dari peserta program pasca-penyuluhan.</p>
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Indikator Kuesioner KPI</th>
                        <th class="text-right">Skala Nilai 1 - 10</th>
                    </tr>
                </thead>
                <tbody id="evaluasi-summary">
                    <tr><td colspan="2" class="text-muted text-center p-4">Memuat data...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            window.axios.get('admin/pil/dashboard')
                .then(res => {
                    const d = res.data.data;
                    document.getElementById('tot-keg').innerText = d.total_kegiatan;
                    document.getElementById('tot-pes').innerText = d.total_peserta;
                    document.getElementById('avg-pemahaman').innerText = Number(d.rata_pemahaman).toFixed(1);
                    document.getElementById('avg-ketertarikan').innerText = Number(d.rata_efek_ketertarikan).toFixed(1);

                    const tlay = document.getElementById('evaluasi-summary');
                    tlay.innerHTML = `
                        <tr>
                            <td class="font-bold flex items-center gap-2"><i data-lucide="heart" style="width:16px;color:var(--success)"></i> Tingkat Ketertarikan pada Program JKN</td>
                            <td class="text-right font-bold text-success" style="font-size: 1.15rem;">${Number(d.rata_efek_ketertarikan).toFixed(1)}</td>
                        </tr>
                        <tr>
                            <td class="font-bold flex items-center gap-2"><i data-lucide="share-2" style="width:16px;color:var(--primary)"></i> Probabilitas Merekomendasikan JKN</td>
                            <td class="text-right font-bold text-primary" style="font-size: 1.15rem;">${Number(d.rata_efek_rekomendasi_jkn).toFixed(1)}</td>
                        </tr>
                        <tr>
                            <td class="font-bold flex items-center gap-2" style="color:var(--primary);"><i data-lucide="building-2" style="width:16px;"></i> Kepercayaan & Rekomendasi Instansi BPJS</td>
                            <td class="text-right font-bold" style="font-size: 1.15rem; color:var(--primary);">${Number(d.rata_efek_rekomendasi_bpjs).toFixed(1)}</td>
                        </tr>
                    `;

                    if(window.lucide) window.lucide.createIcons();
                });
        });
    </script>
</x-admin-layout>
