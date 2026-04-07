<x-admin-layout title="Dashboard BPJS Keliling">
    <style>
        .v-card {
            background: #fff;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            padding: 16px;
            transition: border-color 0.2s;
        }
        .v-card:hover { border-color: #94A3B8; }
        .v-label {
            font-size: 0.65rem;
            font-weight: 700;
            color: #64748B;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 4px;
        }
        .v-value {
            font-family: 'Outfit', sans-serif;
            font-size: 1.5rem;
            font-weight: 800;
            color: #0F172A;
            line-height: 1.2;
        }
        .v-sub {
            font-size: 0.75rem;
            color: #64748B;
            margin-top: 4px;
        }
        .v-badge {
            display: inline-flex;
            align-items: center;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.65rem;
            font-weight: 800;
            background: #F1F5F9;
        }
        .v-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 12px;
        }
        .v-list-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #F1F5F9;
        }
        .v-list-item:last-child { border-bottom: none; }
    </style>

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 style="font-size: 1.25rem; font-weight: 800; color: #000; letter-spacing: -0.02em;">BPJS Keliling Analytics</h1>
            <div class="flex items-center gap-2 mt-1">
                <span class="v-badge" style="background: #E0F2FE; color: #0369A1;">REAL-TIME</span>
                <span style="font-size: 0.75rem; color: #64748B;">Monitoring 12 Core KPIs</span>
            </div>
        </div>
        <div class="flex gap-2">
            <button class="btn" style="background: #000; color: #fff; border-radius: 6px; padding: 6px 12px; font-size: 0.75rem; font-weight: 700;" onclick="window.print()">Export Report</button>
        </div>
    </div>

    <!-- Tier 1: Primary Metrics (Informasi, Admin, Aduan, Total) -->
    <div class="v-grid">
        <div class="v-card">
            <div class="v-label">Total Participants (4)</div>
            <div class="v-value" id="m4-total">0</div>
            <div class="v-sub">Aggregate load</div>
        </div>
        <div class="v-card">
            <div class="v-label">Information (1)</div>
            <div class="v-value" id="m1-count">0</div>
            <div class="v-sub"><span id="m1-pct" style="color:#0F172A; font-weight:700;">0%</span> yield</div>
        </div>
        <div class="v-card">
            <div class="v-label">Administration (2)</div>
            <div class="v-value" id="m2-count">0</div>
            <div class="v-sub"><span id="m2-pct" style="color:#0F172A; font-weight:700;">0%</span> yield</div>
        </div>
        <div class="v-card">
            <div class="v-label">Complaints (3)</div>
            <div class="v-value" id="m3-count">0</div>
            <div class="v-sub"><span id="m3-pct" style="color:#0F172A; font-weight:700;">0%</span> yield</div>
        </div>
    </div>

    <!-- Tier 2: Quality & Reach -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 12px;">
        <div class="v-card" style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div class="v-label">Transaction Success (5)</div>
                <div class="v-value" style="color: #10B981;" id="m5-pct">0%</div>
            </div>
            <div class="text-right">
                <div style="font-size: 0.85rem; font-weight:700;" id="m5-ok">0</div>
                <div style="font-size: 0.65rem; color:#64748B;">Succeeded</div>
            </div>
        </div>
        <div class="v-card" style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div class="v-label">SUPEL Satisfaction (6)</div>
                <div class="v-value" style="color: #3B82F6;" id="m6-supel">0%</div>
            </div>
            <i data-lucide="smile" style="width:24px; color:#3B82F6; opacity:0.5;"></i>
        </div>
        <div class="v-card" style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div class="v-label">Village Reached (8)</div>
                <div class="v-value" id="m8-count">0</div>
            </div>
            <div class="text-right">
                <div style="font-size: 0.85rem; font-weight:700;" id="m8-total-keg">0</div>
                <div style="font-size: 0.65rem; color:#64748B;">Total Activities</div>
            </div>
        </div>
    </div>

    <!-- Tier 3: Breakdowns (Lists) -->
    <div style="display: grid; grid-template-columns: 1.5fr 1fr 1fr; gap: 12px;">
        <div class="v-card">
            <div class="v-label" style="margin-bottom: 12px;">Admin Service Breakdown (9)</div>
            <div id="m9-container" style="max-height: 250px; overflow-y: auto;"></div>
        </div>
        <div class="v-card">
            <div class="v-label" style="margin-bottom: 12px;">Participant Segments (7)</div>
            <div id="m7-container"></div>
        </div>
        <div class="v-card">
            <div class="v-label" style="margin-bottom: 12px;">Quadrant Analysis (10)</div>
            <div id="m10-container" style="display: grid; grid-template-columns: 1fr; gap: 8px;"></div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 12px;">
        <div class="v-card">
            <div class="v-label" style="margin-bottom: 12px;">Activity Type (11)</div>
            <div id="m11-container"></div>
        </div>
        <div class="v-card">
            <div class="v-label" style="margin-bottom: 12px;">Activity Locations (12)</div>
            <div id="m12-container"></div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            window.axios.get('/admin/bpjs-keliling/dashboard')
                .then(res => {
                    const d = res.data.data;
                    
                    document.getElementById('m4-total').innerText = d.total_peserta;
                    document.getElementById('m1-count').innerText = d.layanan_informasi.count;
                    document.getElementById('m1-pct').innerText = d.layanan_informasi.pct + '%';
                    document.getElementById('m2-count').innerText = d.layanan_administrasi.count;
                    document.getElementById('m2-pct').innerText = d.layanan_administrasi.pct + '%';
                    document.getElementById('m3-count').innerText = d.layanan_pengaduan.count;
                    document.getElementById('m3-pct').innerText = d.layanan_pengaduan.pct + '%';

                    document.getElementById('m5-ok').innerText = d.status_transaksi.berhasil;
                    document.getElementById('m5-pct').innerText = d.status_transaksi.pct_berhasil + '%';

                    document.getElementById('m6-supel').innerText = d.avg_supel + '%';
                    document.getElementById('m8-count').innerText = d.capaian_desa.count;
                    document.getElementById('m8-total-keg').innerText = d.capaian_desa.total_kegiatan;

                    renderVList('m9-container', d.transaksi_administrasi_breakdown);
                    renderVList('m7-container', d.per_segmen);
                    renderVList('m11-container', d.per_jenis_kegiatan, true);
                    renderVList('m12-container', d.per_lokasi);
                    
                    const qContainer = document.getElementById('m10-container');
                    Object.entries(d.per_kuadran).forEach(([k, v]) => {
                        qContainer.innerHTML += `
                            <div class="flex justify-between items-center py-1">
                                <span style="font-size: 0.75rem; color:#64748B;">Kuadran ${k}</span>
                                <span style="font-size: 0.75rem; font-weight:800;">${v}</span>
                            </div>
                        `;
                    });

                    if(window.lucide) window.lucide.createIcons();
                });

            function renderVList(id, data, isObj = false) {
                const container = document.getElementById(id);
                container.innerHTML = '';
                const entries = isObj ? Object.values(data) : Object.entries(data);
                
                if (entries.length === 0) {
                    container.innerHTML = '<div style="font-size: 0.7rem; color: #94A3B8; padding: 10px 0;">No active records found...</div>';
                    return;
                }

                entries.forEach(item => {
                    const label = isObj ? item.label : item[0];
                    const val = isObj ? item.count : item[1];
                    container.innerHTML += `
                        <div class="v-list-item">
                            <span style="font-size: 0.75rem; color: #334155; font-weight: 500;">${label}</span>
                            <span style="font-size: 0.75rem; font-weight: 700; color: #000;">${val}</span>
                        </div>
                    `;
                });
            }
        });
    </script>
</x-admin-layout>
