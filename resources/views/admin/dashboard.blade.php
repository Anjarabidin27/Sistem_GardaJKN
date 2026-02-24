@extends('layouts.app')

@section('title', 'Admin Dashboard - Garda JKN')

@push('styles')
<style>
    .admin-layout { display: flex; min-height: 100vh; background: #f8fafc; }
    
    /* Elegant Brand Sidebar */
    .sidebar { 
        width: 260px; 
        background: #004aad; 
        color: white;
        display: flex; flex-direction: column;
        position: fixed; height: 100vh;
        z-index: 100;
    }
    .sb-brand { padding: 24px 32px; font-size: 1.1rem; font-weight: 700; border-bottom: 1px solid rgba(255,255,255,0.1); }
    .sb-menu { padding: 20px 12px; flex: 1; }
    .sb-link { 
        display: flex; align-items: center; padding: 10px 16px; 
        color: rgba(255,255,255,0.7); text-decoration: none;
        border-radius: 6px; font-weight: 500; font-size: 0.875rem;
        margin-bottom: 4px; transition: 0.15s;
        gap: 12px;
    }
    .sb-link:hover, .sb-link.active { background: rgba(255,255,255,0.1); color: white; }
    
    /* Main Area */
    .main-body { margin-left: 260px; flex: 1; }
    .top-header { height: 64px; background: white; border-bottom: 1px solid #e2e8f0; padding: 0 32px; display: flex; align-items: center; justify-content: space-between; }
    .view-container { padding: 32px; max-width: 1400px; }

    /* Professional Stats Cards */
    .stat-card { background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 24px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .stat-label { font-size: 0.65rem; text-transform: uppercase; font-weight: 600; color: #64748b; margin-bottom: 4px; letter-spacing: 0.025em; }
    .stat-value { font-size: 1.5rem; font-weight: 700; color: #1e293b; }

    /* Chart Containers */
    .chart-box { background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 32px; margin-bottom: 24px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .title-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
</style>
@endpush

@section('content')
<div class="admin-layout">
    <aside class="sidebar">
        <div class="sb-brand">Garda JKN</div>
        <nav class="sb-menu">
            <a href="/admin/dashboard" class="sb-link active"><i data-lucide="layout-dashboard" style="width: 16px; height: 16px;"></i> Dashboard</a>
            <a href="/admin/members" class="sb-link"><i data-lucide="users" style="width: 16px; height: 16px;"></i> Manajemen Anggota</a>
            <a href="/admin/informations" class="sb-link"><i data-lucide="megaphone" style="width: 16px; height: 16px;"></i> Informasi</a>
            <a href="/admin/audit-logs" class="sb-link"><i data-lucide="file-clock" style="width: 16px; height: 16px;"></i> Log Audit</a>
            <div style="margin-top: auto; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.05);">
                <a href="#" class="sb-link" onclick="logout()"><i data-lucide="log-out" style="width: 16px; height: 16px;"></i> Logout</a>
            </div>
        </nav>
    </aside>

    <main class="main-body">
        <header class="top-header">
            <div style="font-weight: 600; color: #1e293b; font-size: 1rem;">Monitoring Nasional Terintegrasi</div>
            <div style="display: flex; align-items: center; gap: 12px;">
                <span id="date-now" style="font-size: 0.75rem; color: #94a3b8; font-weight: 500;"></span>
                <div style="width: 32px; height: 32px; background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem;">AD</div>
            </div>
        </header>

        <div class="view-container">
            <div class="summary-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 32px;">
                <div class="stat-card">
                    <div class="stat-label">Basis Anggota</div>
                    <div class="stat-value" id="count-total">...</div>
                    <div style="font-size: 0.70rem; color: #3b82f6; font-weight: 600; margin-top: 8px;">Total Data Terdaftar</div>
                </div>
                <div class="stat-card" style="border-left: 3px solid #10b981;">
                    <div class="stat-label">Pertumbuhan Bulanan</div>
                    <div class="stat-value" id="count-month" style="color: #059669;">...</div>
                    <div style="font-size: 0.7rem; color: #059669; font-weight: 600; margin-top: 8px;">+ Terverifikasi Bulan Ini</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Cakupan Wilayah</div>
                    <div class="stat-value" id="count-provinces">...</div>
                    <div style="font-size: 0.7rem; color: #94a3b8; font-weight: 500; margin-top: 8px;">Provinsi Terdaftar</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Aktivitas Sistem</div>
                    <div class="stat-value" id="count-logs">...</div>
                    <div style="font-size: 0.7rem; color: #94a3b8; font-weight: 500; margin-top: 8px;">Log Transaksi Terintegrasi</div>
                </div>
            </div>

            <div class="chart-box">
                <div class="title-row">
                    <div>
                        <h3 style="font-size: 0.9375rem; font-weight: 600; color: #1e293b;">Analitik Pertumbuhan Anggota</h3>
                        <p style="font-size: 0.8125rem; color: #64748b; margin-top: 2px;">Laju pendaftaran berbasis periode waktu.</p>
                    </div>
                    <select id="rangeSelector" class="form-input" style="width: 160px; padding: 8px 12px; border-radius: 6px; font-size: 0.8125rem;" onchange="updateDashboard(this.value)">
                        <option value="3">3 Bulan Terakhir</option>
                        <option value="6" selected>6 Bulan Terakhir</option>
                        <option value="12">1 Tahun Terakhir</option>
                    </select>
                </div>
                <div style="position: relative; width: 100%; height: 350px;"><canvas id="mainChart"></canvas></div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;">
                <div class="chart-box" style="padding: 24px;">
                    <div class="title-row" style="margin-bottom: 20px;"><h3 style="font-size: 0.9375rem; font-weight: 600; color: #1e293b;">Demografi Gender</h3></div>
                    <div style="position: relative; width: 100%; height: 220px;"><canvas id="genderChart"></canvas></div>
                </div>
                <div class="chart-box" style="padding: 24px;">
                    <div class="title-row" style="margin-bottom: 20px;"><h3 style="font-size: 0.9375rem; font-weight: 600; color: #1e293b;">Latar Belakang Pendidikan</h3></div>
                    <div style="position: relative; width: 100%; height: 220px;"><canvas id="educationChart"></canvas></div>
                </div>
                <div class="chart-box" style="padding: 24px;">
                    <div class="title-row" style="margin-bottom: 20px;"><h3 style="font-size: 0.9375rem; font-weight: 600; color: #1e293b;">Klasifikasi Pekerjaan</h3></div>
                    <div style="position: relative; width: 100%; height: 220px;"><canvas id="occupationChart"></canvas></div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script>
    const token = localStorage.getItem('auth_token');
    if (!token || localStorage.getItem('user_role') !== 'admin') window.location.href = '/login';

    let mainChartObj = null;
    let genderChartObj = null;
    let educationChartObj = null;
    let occupationChartObj = null;

    document.addEventListener('DOMContentLoaded', () => updateDashboard(6));

    async function updateDashboard(range) {
        document.getElementById('date-now').innerText = new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        lucide.createIcons();
        
        try {
            const res = await axios.get(`admin/dashboard?range=${range}`);
            const data = res.data.data;
            
            document.getElementById('count-total').innerText = data.total_members.toLocaleString();
            document.getElementById('count-month').innerText = (data.members_per_month[data.members_per_month.length-1]?.total || 0).toLocaleString();
            document.getElementById('count-provinces').innerText = data.active_provinces;
            document.getElementById('count-logs').innerText = data.total_audit_logs.toLocaleString();

            renderMainChart(data.members_per_month);
            genderChartObj = renderPie('genderChart', genderChartObj, data.gender_distribution, ['L', 'P'], ['#004aad', '#3b82f6']);
            educationChartObj = renderPie('educationChart', educationChartObj, data.education_distribution, null, ['#001f4d', '#004aad', '#3b82f6', '#93c5fd', '#dbeafe', '#f1f5f9']);
            occupationChartObj = renderPie('occupationChart', occupationChartObj, data.occupation_distribution, null, ['#004aad', '#3b82f6', '#60a5fa', '#93c5fd', '#bfdbfe', '#e2e8f0', '#f1f5f9']);
        } catch (e) {
            console.error(e);
            showToast('Gagal memuat data dashboard: ' + (e.response?.data?.message || e.message), 'error');
        }
    }

    function renderMainChart(data) {
        if (mainChartObj) mainChartObj.destroy();
        const ctx = document.getElementById('mainChart').getContext('2d');
        
        // Create Gradient
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
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#001f4d',
                        padding: 12,
                        titleFont: { size: 13, weight: 'bold' },
                        bodyFont: { size: 12 },
                        cornerRadius: 8,
                        displayColors: false
                    }
                },
                scales: { 
                    x: { 
                        grid: { display: false },
                        ticks: { font: { size: 11, weight: '500' }, color: '#64748b' }
                    },
                    y: { 
                        grid: { color: '#f1f5f9', drawBorder: false },
                        ticks: { font: { size: 11 }, color: '#64748b', precision: 0 },
                        beginAtZero: true
                    }
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
                    return i.education || i.occupation || 'Lainnya';
                }),
                datasets: [{ 
                    data: data.map(i => i.total), 
                    backgroundColor: colors, 
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { 
                    legend: { 
                        position: 'bottom', 
                        labels: { 
                            boxWidth: 10, 
                            padding: 15,
                            usePointStyle: true,
                            font: { size: 11, weight: '600' },
                            color: '#334155'
                        } 
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        padding: 10,
                        cornerRadius: 6
                    }
                },
                cutout: '70%'
            }
        });
    }

    function logout() { localStorage.clear(); window.location.href = '/login'; }
</script>
@endpush
