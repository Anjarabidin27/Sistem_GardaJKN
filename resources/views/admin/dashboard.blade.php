@extends('layouts.app')

@section('title', 'Admin Dashboard - Garda JKN')

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

    /* Dashboard Specifics */
    .stat-card { background: white; padding: 24px; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .stat-label { font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; }
    .stat-value { font-size: 2rem; font-weight: 800; color: #1e293b; margin: 8px 0; }
    
    .chart-box { background: white; padding: 32px; border-radius: 16px; border: 1px solid #e2e8f0; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .title-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 32px; }
    
    .pie-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-top: 8px; }
    .pie-card { background: white; padding: 24px; border-radius: 16px; border: 1px solid #e2e8f0; display: flex; flex-direction: column; align-items: center; }
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
            <div class="sb-section-label" style="font-size:0.6rem; font-weight:800; color:rgba(255,255,255,0.3); text-transform:uppercase; padding:0 16px; margin:16px 0 8px;">Menu</div>
            <a href="/admin/dashboard" class="sb-link active"><i data-lucide="layout-dashboard" style="width:16px;height:16px;"></i> Dashboard</a>
            <a href="/admin/members" class="sb-link"><i data-lucide="users" style="width:16px;height:16px;"></i> Manajemen Anggota</a>
            <a href="/admin/approvals" class="sb-link"><i data-lucide="user-check" style="width:16px;height:16px;"></i> Persetujuan Pengurus</a>
            <a href="/admin/informations" class="sb-link"><i data-lucide="megaphone" style="width:16px;height:16px;"></i> Informasi</a>
            <a href="/admin/audit-logs" class="sb-link"><i data-lucide="file-clock" style="width:16px;height:16px;"></i> Log Audit</a>
        </nav>
        <div class="sb-footer">
            <div class="sb-section-label" style="font-size:0.6rem; font-weight:800; color:rgba(255,255,255,0.3); text-transform:uppercase; padding:0 16px; margin-bottom:8px;">Pengaturan</div>
            <a href="/settings" class="sb-link"><i data-lucide="settings" style="width:16px;height:16px;"></i> Pengaturan Akun</a>
            <a href="#" class="sb-link" onclick="logout()" style="color:#fca5a5;margin-top:4px;"><i data-lucide="log-out" style="width:16px;height:16px;color:#fca5a5;"></i> Keluar Sesi</a>
        </div>
    </aside>

    <main class="main-body">
        <header class="top-header">
            <div style="font-weight: 600; color: #1e293b; font-size: 1rem;">Monitoring Nasional Terintegrasi</div>
            <div id="user-info-header" style="display: flex; align-items: center; gap: 12px;">
                <span id="date-now" style="font-size: 0.75rem; color: #94a3b8; font-weight: 500;"></span>
                <div id="user-initials" style="width: 32px; height: 32px; background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem;">...</div>
            </div>
        </header>

        <div class="view-container">
            <div class="summary-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 32px;">
                <div class="stat-card">
                    <div class="stat-label">Basis Anggota</div>
                    <div class="stat-value" id="count-total">...</div>
                    <div style="font-size: 0.70rem; color: #3b82f6; font-weight: 600;">Total Data Terdaftar</div>
                </div>
                <div class="stat-card" style="border-left: 3px solid #10b981;">
                    <div class="stat-label">Pertumbuhan Bulanan</div>
                    <div class="stat-value" id="count-month" style="color: #059669;">...</div>
                    <div style="font-size: 0.7rem; color: #059669; font-weight: 600;">+ Terverifikasi Bulan Ini</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Cakupan Wilayah</div>
                    <div class="stat-value" id="count-provinces">...</div>
                    <div style="font-size: 0.75rem; color: #64748b; font-weight: 600;">Provinsi Terdaftar</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Aktivitas Sistem</div>
                    <div class="stat-value" id="count-logs">...</div>
                    <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 600;">Log Transaksi</div>
                </div>
            </div>

            <div class="chart-box">
                <div class="title-row">
                    <div>
                        <h3 style="font-size: 1.125rem; font-weight: 800; color: #0f172a;">Analitik Pertumbuhan Anggota</h3>
                        <p style="font-size: 0.85rem; color: #64748b; margin-top: 4px;">Laju pendaftaran berbasis periode waktu global.</p>
                    </div>
                    <select id="rangeSelector" style="width: 200px; padding: 10px 16px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 0.85rem; font-weight: 600;" onchange="updateDashboard(this.value)">
                        <option value="3">3 Bulan Terakhir</option>
                        <option value="6" selected>6 Bulan Terakhir</option>
                        <option value="12">1 Tahun Terakhir</option>
                    </select>
                </div>
                <div style="position: relative; width: 100%; height: 400px;"><canvas id="mainChart"></canvas></div>
            </div>

            <div class="pie-grid">
                <div class="pie-card">
                    <h4 style="font-size:0.75rem; font-weight:800; color:#64748b; margin-bottom:20px;">Distribusi Gender</h4>
                    <div style="width: 100%; height: 200px;"><canvas id="genderChart"></canvas></div>
                </div>
                <div class="pie-card">
                    <h4 style="font-size:0.75rem; font-weight:800; color:#64748b; margin-bottom:20px;">Tingkat Pendidikan</h4>
                    <div style="width: 100%; height: 200px;"><canvas id="eduChart"></canvas></div>
                </div>
                <div class="pie-card">
                    <h4 style="font-size:0.75rem; font-weight:800; color:#64748b; margin-bottom:20px;">Kelompok Usia</h4>
                    <div style="width: 100%; height: 200px;"><canvas id="ageChart"></canvas></div>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Global functions will handle initGlobalSidebar and logout from app.blade.php

    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('date-now').innerText = new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        updateDashboard(6);
    });

    let mainChart, genderChart, eduChart, ageChart;

    async function updateDashboard(months) {
        try {
            const res = await axios.get(`admin/dashboard?range=${months}`);
            const d = res.data.data;
            
            document.getElementById('count-total').innerText = d.summary.total_members.toLocaleString();
            document.getElementById('count-month').innerText = d.summary.new_this_month.toLocaleString();
            document.getElementById('count-provinces').innerText = d.summary.total_provinces;
            document.getElementById('count-logs').innerText = d.summary.total_logs.toLocaleString();

            renderMainChart(d.growth);
            renderPieChart('genderChart', d.distribution.gender, ['#3b82f6', '#f43f5e']);
            renderPieChart('eduChart', d.distribution.education, ['#6366f1', '#8b5cf6', '#ec4899', '#f97316', '#10b981']);
            renderPieChart('ageChart', d.distribution.age, ['#0ea5e9', '#22c55e', '#eab308', '#f97316', '#ef4444']);
        } catch (e) {
            console.error("Dashboard error:", e);
        }
    }

    function renderMainChart(data) {
        const ctx = document.getElementById('mainChart').getContext('2d');
        if (mainChart) mainChart.destroy();
        mainChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(i => i.month),
                datasets: [{
                    label: 'Pendaftaran Anggota',
                    data: data.map(i => i.total),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.05)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f1f5f9' }, border: { display: false } },
                    x: { grid: { display: false }, border: { display: false } }
                }
            }
        });
    }

    function renderPieChart(id, data, colors) {
        const ctx = document.getElementById(id).getContext('2d');
        const chartMap = { genderChart, eduChart, ageChart };
        if (chartMap[id]) chartMap[id].destroy();
        
        const newChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(data),
                datasets: [{
                    data: Object.values(data),
                    backgroundColor: colors,
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 15, font: { size: 11, weight: '600' } } } }
            }
        });

        if (id === 'genderChart') genderChart = newChart;
        if (id === 'eduChart') eduChart = newChart;
        if (id === 'ageChart') ageChart = newChart;
    }
</script>
@endsection
