<x-admin-layout title="Dashboard BPJS Keliling">
    <div class="justify-between items-end mb-4 flex">
        <div>
            <h1 class="topbar-title" style="font-size: 1.75rem;">Dashboard Laporan BPJS Keliling</h1>
            <p class="text-muted" style="margin-top: 4px;">Analitik data layanan dan feedback (SUPEL).</p>
        </div>
        <div class="flex gap-2">
            <a href="/admin/bpjs-keliling" class="btn btn-secondary">Kembali ke Jadwal</a>
        </div>
    </div>

    <!-- SUMMARY -->
    <div class="summary-grid" id="dashboard-summary">
        <div class="stat-card">
            <div class="stat-label">Total Kegiatan</div>
            <div class="stat-value" id="tot-keg">0</div>
        </div>
        <div class="stat-card stat-card-blue">
            <div class="stat-label">Total Peserta Hadir</div>
            <div class="stat-value" id="tot-pes">0</div>
        </div>
        <div class="stat-card stat-card-green">
            <div class="stat-label">Indeks Kepuasan</div>
            <div class="stat-value"><span id="avg-puas">0</span>%</div>
            <div class="text-muted" style="font-size: 0.70rem; font-weight: 600; margin-top: 4px;">Puas vs Tidak Puas</div>
        </div>
        <div class="stat-card stat-card-warning">
            <div class="stat-label">Layanan Informasi</div>
            <div class="stat-value text-warning" id="tot-info">0</div>
        </div>
    </div>

    <div class="grid-2">
        <div class="table-card p-4">
            <h3 class="modal-title mb-4">Sebaran Berdasarkan Jenis Kegiatan</h3>
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Jenis Kegiatan</th>
                            <th class="text-right">Total Kegiatan</th>
                            <th class="text-right">Total Peserta</th>
                        </tr>
                    </thead>
                    <tbody id="jenis-summary">
                        <tr><td colspan="3" class="text-muted text-center p-4">Memuat data...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="table-card p-4">
            <h3 class="modal-title mb-4">Ringkasan Layanan Teknis</h3>
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Kategori Layanan</th>
                            <th class="text-right">Jumlah Dilayani</th>
                        </tr>
                    </thead>
                    <tbody id="layanan-summary">
                        <tr><td colspan="3" class="text-muted text-center p-4">Memuat data...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            window.axios.get('admin/bpjs-keliling/dashboard')
                .then(res => {
                    const d = res.data.data;
                    document.getElementById('tot-keg').innerText = d.total_kegiatan;
                    document.getElementById('tot-pes').innerText = d.total_peserta;
                    document.getElementById('avg-puas').innerText = Number(d.rata_kepuasan_persen).toFixed(1);
                    document.getElementById('tot-info').innerText = d.total_informasi;

                    // Table Jenis
                    const tbody = document.getElementById('jenis-summary');
                    tbody.innerHTML = '';
                    
                    if(Object.keys(d.per_jenis_kegiatan).length === 0) {
                         tbody.innerHTML = '<tr><td colspan="3" class="text-center p-4">Belum ada data.</td></tr>';
                    } else {
                        for (const [key, val] of Object.entries(d.per_jenis_kegiatan)) {
                            tbody.innerHTML += `
                                <tr>
                                    <td class="font-bold flex items-center gap-2"><i data-lucide="tag" style="width:14px;color:var(--text-muted)"></i> ${val.label}</td>
                                    <td class="text-right">${val.jumlah_kegiatan}</td>
                                    <td class="text-right" style="color:var(--primary); font-weight:700;">${val.total_peserta}</td>
                                </tr>
                            `;
                        }
                    }

                    // Table Layanan Teknis
                    const tlay = document.getElementById('layanan-summary');
                    tlay.innerHTML = `
                        <tr><td class="font-bold text-info">Informasi / Pendaftaran Baru</td><td class="text-right font-bold">${d.total_informasi}</td></tr>
                        <tr><td class="font-bold" style="color:var(--primary);">Administrasi / Update Data</td><td class="text-right font-bold">${d.total_administrasi}</td></tr>
                        <tr><td class="font-bold text-danger">Aduan / Komplain</td><td class="text-right font-bold">${d.total_pengaduan}</td></tr>
                    `;

                    if(window.lucide) window.lucide.createIcons();
                });
        });
    </script>
</x-admin-layout>
