@extends('layouts.app')

@section('title', 'Pengurus Dashboard - Garda JKN')



@section('content')
<style>
    /* Force Layout Bases */
    .admin-layout { display: flex !important; min-height: 100vh !important; background: #f8fafc !important; }
    .sidebar { width: 280px !important; background: #004aad !important; color: white !important; display: flex !important; flex-direction: column !important; position: fixed !important; height: 100vh !important; z-index: 100 !important; overflow: hidden !important; border: none !important; }
    .sb-brand { padding: 28px 28px 10px; flex-shrink: 0; }
    .sb-brand-name { font-size: 1.1rem !important; font-weight: 800 !important; color: white !important; letter-spacing: 0.02em; }
    .sb-brand-sub { font-size: 0.75rem !important; color: rgba(255,255,255,0.6) !important; font-weight: 500; margin-top: 4px; }
    .sb-user-card { padding: 10px 28px 20px; flex-shrink: 0; }
    .sb-avatar { width: 52px !important; height: 52px !important; border-radius: 14px; background: rgba(255,255,255,0.15); border: 2px solid rgba(255,255,255,0.25); display: flex !important; align-items: center !important; justify-content: center !important; margin-bottom: 12px; overflow: hidden; }
    .sb-user-name { font-size: 0.95rem !important; font-weight: 800 !important; color: white !important; margin-bottom: 4px; }
    .sb-user-role { font-size: 0.7rem !important; color: rgba(255,255,255,0.5) !important; text-transform: uppercase; letter-spacing: 0.05em; }
    .sb-menu { padding: 16px 12px !important; flex: 1; overflow-y: auto !important; }
    .sb-link { display: flex !important; align-items: center !important; gap: 12px; padding: 12px 16px; border-radius: 10px; color: rgba(255,255,255,0.7) !important; text-decoration: none !important; font-weight: 600; font-size: 0.875rem; transition: 0.2s; }
    .sb-link:hover { background: rgba(255,255,255,0.1); color: white !important; }
    .sb-link.active { background: #ffffff15; color: white !important; }
    .sb-footer { padding: 20px 12px; border-top: 1px solid rgba(255,255,255,0.08); }

    .main-body { margin-left: 280px !important; flex: 1 !important; min-width: 0 !important; }
    .top-header { height: 64px !important; background: white !important; border-bottom: 1px solid #e2e8f0 !important; padding: 0 32px !important; display: flex !important; align-items: center !important; justify-content: space-between !important; position: sticky; top: 0; z-index: 50; }
    .view-container { padding: 32px !important; }

    /* Component Styles */
    .table-card, .log-card, .info-card, .approvals-card { background: white !important; border: 1px solid #e2e8f0 !important; border-radius: 16px !important; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); margin-bottom: 24px; }
    .table-header { padding: 24px 32px; border-bottom: 1px solid #f1f5f9; display: flex !important; align-items: center !important; justify-content: space-between !important; }
    .data-table { width: 100% !important; border-collapse: collapse !important; border-spacing: 0 !important; }
    .data-table th { background: #f8fafc !important; padding: 16px 32px !important; text-align: left !important; font-size: 0.75rem !important; font-weight: 700 !important; color: #64748b !important; text-transform: uppercase !important; border-bottom: 1px solid #e2e8f0 !important; white-space: nowrap; }
    .data-table td { padding: 16px 32px !important; border-bottom: 1px solid #f1f5f9 !important; font-size: 0.875rem !important; color: #334155 !important; vertical-align: middle !important; background: white !important; }
    
    .badge { padding: 5px 12px; border-radius: 6px; font-size: 0.75rem; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; }
    .badge-success { background: #ecfdf5; color: #10b981; }
    .badge-primary { background: #eff6ff; color: #3b82f6; }
</style>
<div class="admin-layout">
    <aside class="sidebar">
        <div class="sb-brand">
            <div class="sb-brand-name">Garda JKN</div>
        </div>
        <div class="sb-user-card">
            <div class="sb-avatar" id="sb-avatar-wrap"><span id="sb-initials">A</span></div>
            <div class="sb-user-name" id="sb-user-name">Administrator</div>
        </div>
        <nav class="sb-menu">
            <div class="sb-section-label">Menu</div>
            <a href="/pengurus/dashboard" class="sb-link"><i data-lucide="layout-dashboard" style="width:16px;height:16px;"></i> Dashboard</a>
            <a href="/pengurus/members" class="sb-link"><i data-lucide="users" style="width:16px;height:16px;"></i> Anggota Wilayah</a>
            <a href="/pengurus/informations" class="sb-link"><i data-lucide="megaphone" style="width:16px;height:16px;"></i> Informasi</a>
        </nav>
        <div class="sb-footer">
            <div class="sb-section-label" style="margin-top:0;margin-bottom:8px;">Pengaturan</div>
            <a href="/settings" class="sb-link"><i data-lucide="settings" style="width:16px;height:16px;"></i> Pengaturan Akun</a>
            <a href="#" class="sb-link" onclick="logout()" style="color:#fca5a5;margin-top:4px;"><i data-lucide="log-out" style="width:16px;height:16px;color:#fca5a5;"></i> Keluar Sesi</a>
        </div>
    </aside>

    <main class="main-body">
        <header class="top-header">
            <div style="font-weight: 600; color: #1e293b; font-size: 1rem;">Portal Pengurus Operasional</div>
            <div style="display: flex; align-items: center; gap: 12px;">
                <span id="date-now" style="font-size: 0.75rem; color: #94a3b8; font-weight: 500;"></span>
                <div id="user-initials" style="width: 32px; height: 32px; background: #eff6ff; color: #004aad; border: 1px solid #dbeafe; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem;">PR</div>
            </div>
        </header>

        <div class="view-container">
            <div class="summary-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 32px;">
                <div class="stat-card">
                    <div class="stat-label">Anggota Terkelola</div>
                    <div class="stat-value" id="count-total">...</div>
                    <div style="font-size: 0.70rem; color: #3b82f6; font-weight: 600; margin-top: 8px;">Dalam Wilayah Anda</div>
                </div>
                <div class="stat-card" style="border-left: 3px solid #10b981;">
                    <div class="stat-label">Pendaftaran Baru</div>
                    <div class="stat-value" id="count-month" style="color: #059669;">...</div>
                    <div style="font-size: 0.7rem; color: #059669; font-weight: 600; margin-top: 8px;">Verifikasi Bulan Ini</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Informasi Aktif</div>
                    <div class="stat-value" id="count-info">...</div>
                    <div style="font-size: 0.75rem; color: #64748b; font-weight: 600; margin-top: 12px;">Pengumuman Berjalan</div>
                </div>
            </div>

            <div class="chart-box">
                <div class="title-row">
                    <div>
                        <h3 style="font-size: 1.125rem; font-weight: 800; color: #0f172a;">Statistik Anggota Wilayah</h3>
                        <p style="font-size: 0.85rem; color: #64748b; margin-top: 4px;">Perbandingan pendaftaran anggota per periode.</p>
                    </div>
                </div>
                <div style="position: relative; width: 100%; height: 350px;"><canvas id="mainChart"></canvas></div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px;">
                <div class="chart-box" style="padding: 24px;">
                    <div class="title-row" style="margin-bottom: 24px;"><h3 style="font-size: 1rem; font-weight: 800; color: #0f172a;">Gender Wilayah</h3></div>
                    <div style="position: relative; width: 100%; height: 250px;"><canvas id="genderChart"></canvas></div>
                </div>
                <div class="chart-box" style="padding: 24px;">
                    <div class="title-row" style="margin-bottom: 24px;"><h3 style="font-size: 1rem; font-weight: 800; color: #0f172a;">Pekerjaan Wilayah</h3></div>
                    <div style="position: relative; width: 100%; height: 250px;"><canvas id="occupationChart"></canvas></div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script>
    const token = localStorage.getItem('auth_token');
    const role = localStorage.getItem('user_role');
    
    // Auth Check
    if (!token || (role !== 'pengurus' && role !== 'admin')) window.location.href = '/login';

    let mainChartObj = null;
    let genderChartObj = null;
    let occupationChartObj = null;

    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('date-now').innerText = new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        updateDashboard();
    });



    async function updateDashboard() {
        try {
            // Kita akan gunakan API admin dashboard sementara atau buat API khusus pengurus nanti
            const res = await axios.get(`admin/dashboard?range=6`);
            const data = res.data.data;
            
            // Set initials
            const name = localStorage.getItem('user_name') || 'Pengurus';
            document.getElementById('user-initials').innerText = name.substring(0, 2).toUpperCase();

            document.getElementById('count-total').innerText = data.total_members.toLocaleString();
            document.getElementById('count-month').innerText = (data.members_per_month[data.members_per_month.length-1]?.total || 0).toLocaleString();
            document.getElementById('count-info').innerText = '12'; // Dummy for now

            renderMainChart(data.members_per_month);
            genderChartObj = renderPie('genderChart', genderChartObj, data.gender_distribution, ['L', 'P'], ['#004aad', '#3b82f6']);
            occupationChartObj = renderPie('occupationChart', occupationChartObj, data.occupation_distribution, null, ['#004aad', '#3b82f6', '#60a5fa', '#93c5fd', '#bfdbfe', '#e2e8f0', '#f1f5f9']);
        } catch (e) {
            console.error(e);
            showToast('Gagal memuat data dashboard', 'error');
        }
    }

    function renderMainChart(data) {
        if (mainChartObj) mainChartObj.destroy();
        const ctx = document.getElementById('mainChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(0, 74, 173, 0.2)');
        gradient.addColorStop(1, 'rgba(0, 74, 173, 0)');

        mainChartObj = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(i => i.month),
                datasets: [{
                    label: 'Registrasi Baru',
                    data: data.map(i => i.total),
                    borderColor: '#004aad',
                    borderWidth: 3,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#004aad',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { 
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });
    }

    function renderPie(id, chartObj, data, filter, colors) {
        if (chartObj) chartObj.destroy();
        return new Chart(document.getElementById(id), {
            type: 'doughnut',
            data: {
                labels: data.map(i => {
                    if (i.gender === 'L') return 'Laki-laki';
                    if (i.gender === 'P') return 'Perempuan';
                    return i.occupation || 'Lainnya';
                }),
                datasets: [{ 
                    data: data.map(i => i.total), 
                    backgroundColor: colors, 
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } },
                cutout: '70%'
            }
        });
    }

    function logout() { localStorage.clear(); window.location.href = '/login'; }
</script>
@endpush
