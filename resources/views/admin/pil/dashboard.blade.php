<x-admin-layout title="Dashboard PIL">
    <div class="justify-between items-end mb-6 flex">
        <div>
            <h1 class="topbar-title" style="font-size: 1.75rem;">Dashboard Laporan PIL</h1>
            <p class="text-muted" style="margin-top: 4px;">Indikator KPI Program Informasi Langsung • Konteks: <span id="ui-context" class="font-bold text-primary">Nasional</span></p>
        </div>
        <div class="flex gap-2">
            <a href="/admin/pil" class="btn btn-secondary">Kembali ke Agenda</a>
        </div>
    </div>

    <!-- SUMMARY CARDS -->
    <div class="grid-4 mb-6" style="gap: 20px;">
        <div class="stat-card" style="background: linear-gradient(135deg, #1e293b, #0f172a); color: white; border: none;">
            <div class="stat-label" style="color: #94a3b8;">Total Kegiatan</div>
            <div class="stat-value" id="tot-keg" style="color: white;">0</div>
            <div style="font-size: 0.7rem; color: #64748b; margin-top: 5px;">Sesi Sosialisasi Selesai</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total Kehadiran</div>
            <div class="stat-value text-primary" id="tot-pes">0</div>
            <div style="font-size: 0.7rem; color: var(--text-muted); margin-top: 5px;">Peserta Terdaftar SIK</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Avg. Pemahaman</div>
            <div class="stat-value text-success"><span id="avg-pemahaman">0</span>%</div>
            <div style="font-size: 0.7rem; color: var(--text-muted); margin-top: 5px;">Score Post-Test (0-100)</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">NPS Score (Avg)</div>
            <div class="stat-value text-warning" id="avg-nps-total">0</div>
            <div style="font-size: 0.7rem; color: var(--text-muted); margin-top: 5px;">Skala Kepuasan 1-10</div>
        </div>
    </div>

    <!-- MAIN CHARTS -->
    <div class="grid-2 mb-6" style="gap: 20px; grid-template-columns: 1fr 1.5fr;">
        <div class="table-card p-4">
            <h3 class="modal-title mb-4" style="font-size: 1.1rem;"><i data-lucide="pie-chart" class="inline-block w-4 h-4 mr-2"></i> Efektifitas Sosialisasi</h3>
            <div style="height: 300px; display: flex; align-items: center; justify-content: center;">
                <canvas id="chart-efektifitas"></canvas>
            </div>
            <div class="text-right mt-4">
                <p class="text-muted" style="font-size: 0.75rem;">Agregasi berdasarkan kategori standard a-f</p>
            </div>
        </div>
        <div class="table-card p-4">
            <h3 class="modal-title mb-4" style="font-size: 1.1rem;"><i data-lucide="users" class="inline-block w-4 h-4 mr-2"></i> Sebaran Segmen Peserta</h3>
            <div style="height: 320px;">
                <canvas id="chart-segmen"></canvas>
            </div>
        </div>
    </div>

    <div class="grid-2" style="gap: 20px;">
        <div class="table-card p-4">
            <h3 class="modal-title mb-4" style="font-size: 1.1rem;"><i data-lucide="map-pin" class="inline-block w-4 h-4 mr-2"></i> Lokasi Kegiatan</h3>
            <div style="height: 300px;">
                <canvas id="chart-lokasi"></canvas>
            </div>
        </div>
        <div class="table-card p-4">
            <h3 class="modal-title mb-4" style="font-size: 1.1rem;"><i data-lucide="star" class="inline-block w-4 h-4 mr-2"></i> Indikator Pasca-Penyuluhan</h3>
            <div id="nps-breakdown" style="display:flex; flex-direction:column; gap:20px; padding: 10px 0;">
                <!-- NPS Lines -->
                 <div class="flex-col">
                    <div class="flex justify-between font-bold mb-2">
                        <span style="font-size:0.85rem;">Ketertarikan JKN</span>
                        <span class="text-primary" id="nps-val-1">0</span>
                    </div>
                    <div style="height:10px; background:#f1f5f9; border-radius:10px; overflow:hidden;">
                        <div id="nps-bar-1" style="width:0%; height:100%; background:var(--primary); transition: width 1s;"></div>
                    </div>
                 </div>
                 <div class="flex-col">
                    <div class="flex justify-between font-bold mb-2">
                        <span style="font-size:0.85rem;">Rekomendasi Program</span>
                        <span class="text-primary" id="nps-val-2">0</span>
                    </div>
                    <div style="height:10px; background:#f1f5f9; border-radius:10px; overflow:hidden;">
                        <div id="nps-bar-2" style="width:0%; height:100%; background:var(--primary); transition: width 1s;"></div>
                    </div>
                 </div>
                 <div class="flex-col">
                    <div class="flex justify-between font-bold mb-2">
                        <span style="font-size:0.85rem;">Rekomendasi BPJS</span>
                        <span class="text-primary" id="nps-val-3">0</span>
                    </div>
                    <div style="height:10px; background:#f1f5f9; border-radius:10px; overflow:hidden;">
                        <div id="nps-bar-3" style="width:0%; height:100%; background:var(--primary); transition: width 1s;"></div>
                    </div>
                 </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            window.axios.get('admin/pil/dashboard')
                .then(res => {
                    const d = res.data.data;
                    document.getElementById('ui-context').innerText = d.context;
                    document.getElementById('tot-keg').innerText = d.total_kegiatan;
                    document.getElementById('tot-pes').innerText = d.total_peserta;
                    document.getElementById('avg-pemahaman').innerText = Number(d.rata_pemahaman).toFixed(1);
                    
                    const avgNps = (Number(d.rata_nps_ketertarikan) + Number(d.rata_nps_rekomendasi_program) + Number(d.rata_nps_rekomendasi_bpjs)) / 3;
                    document.getElementById('avg-nps-total').innerText = avgNps.toFixed(1);

                    // --- NPS Bars ---
                    const n1 = (Number(d.rata_nps_ketertarikan)/10)*100;
                    const n2 = (Number(d.rata_nps_rekomendasi_program)/10)*100;
                    const n3 = (Number(d.rata_nps_rekomendasi_bpjs)/10)*100;
                    
                    document.getElementById('nps-val-1').innerText = Number(d.rata_nps_ketertarikan).toFixed(1);
                    document.getElementById('nps-bar-1').style.width = n1 + '%';
                    document.getElementById('nps-val-2').innerText = Number(d.rata_nps_rekomendasi_program).toFixed(1);
                    document.getElementById('nps-bar-2').style.width = n2 + '%';
                    document.getElementById('nps-val-3').innerText = Number(d.rata_nps_rekomendasi_bpjs).toFixed(1);
                    document.getElementById('nps-bar-3').style.width = n3 + '%';

                    // --- CHART: Efektifitas ---
                    new Chart(document.getElementById('chart-efektifitas'), {
                        type: 'doughnut',
                        data: {
                            labels: ['Sangat Efektif', 'Efektif', 'Kurang Efektif'],
                            datasets: [{
                                data: [d.count_sangat_efektif, d.count_efektif, d.count_kurang_efektif],
                                backgroundColor: ['#10b981', '#3b82f6', '#f59e0b'],
                                borderWidth: 0,
                                hoverOffset: 15
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '70%',
                            plugins: { legend: { position: 'bottom' } }
                        }
                    });

                    // --- CHART: Segmen ---
                    const segLabels = Object.keys(d.segmen_breakdown);
                    const segValues = Object.values(d.segmen_breakdown);
                    new Chart(document.getElementById('chart-segmen'), {
                        type: 'bar',
                        data: {
                            labels: segLabels,
                            datasets: [{
                                label: 'Jumlah Peserta',
                                data: segValues,
                                backgroundColor: '#6366f1',
                                borderRadius: 8,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                                x: { grid: { display: false } }
                            }
                        }
                    });

                    // --- CHART: Lokasi ---
                    const locLabels = Object.keys(d.lokasi_breakdown);
                    const locValues = Object.values(d.lokasi_breakdown);
                    new Chart(document.getElementById('chart-lokasi'), {
                        type: 'pie',
                        data: {
                            labels: locLabels,
                            datasets: [{
                                data: locValues,
                                backgroundColor: ['#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#06b6d4', '#8b5cf6'],
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { position: 'right' } }
                        }
                    });

                    if(window.lucide) window.lucide.createIcons();
                });
        });
    </script>
    @endpush
</x-admin-layout>
