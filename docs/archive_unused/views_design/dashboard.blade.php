<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Sistem Garda JKN</title>
    <!-- Modern Enterprise Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    <!-- Chart.js for Visualization -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        :root {
            /* 1. Color Tokens */
            --primary-blue: #1E3A8A; /* Deep Blue Identity */
            --secondary-blue: #3B82F6; /* Action Blue */
            --bg-light: #F3F4F6; /* Soft Gray */
            --surface-white: #FFFFFF; /* High Contrast Content */
            --text-dark: #111827; /* Near Black */
            --text-muted: #6B7280; /* Help Text */
            --border-color: #E5E7EB; /* Subtle dividers */
            
            /* Status */
            --success: #16A34A;
            --warning: #FACC15;
            --danger: #DC2626;

            /* 2. Spacing */
            --sidebar-width: 280px;
            --header-height: 72px;
            --card-radius: 12px;
            --spacing-unit: 8px; /* The 8pt grid base */
        }

        /* Dark Mode Override (Simulated here, actionable via class) */
        .dark-mode {
            --bg-light: #0F172A;
            --surface-white: #1E293B;
            --text-dark: #F8FAFC;
            --text-muted: #94A3B8;
            --border-color: #334155;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background-color: var(--bg-light);
            color: var(--text-dark);
            -webkit-font-smoothing: antialiased;
            display: flex;
            min-height: 100vh;
        }

        /* 3. Sidebar (Navigation) */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--primary-blue);
            color: #FFFFFF;
            position: fixed;
            height: 100vh;
            left: 0; top: 0;
            display: flex;
            flex-direction: column;
            z-index: 50;
        }

        .brand {
            height: var(--header-height);
            display: flex;
            align-items: center;
            padding: 0 24px;
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .brand span { color: var(--secondary-blue); margin-left: 4px; }

        .nav-menu { flex: 1; padding: 24px 16px; list-style: none; }
        .nav-item { margin-bottom: 8px; }
        
        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: #E2E8F0;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s ease;
            font-weight: 500;
        }
        
        .nav-link:hover { background-color: rgba(255,255,255,0.08); color: #fff; transform: translateX(4px); }
        .nav-link.active { background-color: var(--secondary-blue); color: #fff; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4); }
        
        .nav-icon { margin-right: 12px; width: 20px; text-align: center; }

        /* 4. Main Content Area */
        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
            width: calc(100% - var(--sidebar-width));
        }

        /* Topbar */
        header {
            height: var(--header-height);
            background: var(--surface-white);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: sticky; top: 0; z-index: 40;
        }

        .page-title { font-family: 'Poppins', sans-serif; font-size: 1.25rem; font-weight: 600; }
        
        .header-actions { display: flex; gap: 16px; align-items: center; }
        .btn-icon { width: 40px; height: 40px; border-radius: 50%; border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--text-muted); transition: 0.2s; }
        .btn-icon:hover { background: var(--bg-light); color: var(--primary-blue); }
        
        .user-profile { display: flex; align-items: center; gap: 12px; cursor: pointer; padding: 4px 8px; border-radius: 8px; transition: 0.2s; }
        .user-profile:hover { background: var(--bg-light); }
        .avatar { width: 36px; height: 36px; background: var(--primary-blue); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.9rem; }

        /* Content Wrapper */
        .content-wrapper { padding: 32px; flex: 1; overflow-y: auto; }

        /* 5. Metrics Cards (KPI) */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .card {
            background: var(--surface-white);
            border-radius: var(--card-radius);
            padding: 24px;
            border: 1px solid var(--border-color);
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover { transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05); }

        .metric-title { color: var(--text-muted); font-size: 0.875rem; font-weight: 500; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.025em; }
        .metric-value { font-size: 2rem; font-weight: 700; color: var(--text-dark); display: flex; align-items: baseline; gap: 8px; font-family: 'Poppins', sans-serif; }
        .metric-trend { font-size: 0.875rem; padding: 2px 8px; border-radius: 12px; font-weight: 500; }
        .trend-up { background: #DCFCE7; color: var(--success); }
        .trend-down { background: #FEE2E2; color: var(--danger); }

        /* 6. Charts Section */
        .chart-row {
            display: grid;
            grid-template-columns: 2fr 1fr; /* 2/3 Main Chart, 1/3 Side Chart */
            gap: 24px;
            margin-bottom: 32px;
        }

        .chart-container { position: relative; height: 300px; width: 100%; margin-top: 16px; }

        /* 7. Data Table */
        .table-section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
        
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 12px 16px; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; font-weight: 600; border-bottom: 1px solid var(--border-color); }
        td { padding: 16px; color: var(--text-dark); font-size: 0.875rem; border-bottom: 1px solid var(--border-color); vertical-align: middle; }
        
        tr:last-child td { border-bottom: none; }
        tr:hover td { background-color: #F9FAFB; } /* Striping */
        
        .status-badge { padding: 4px 12px; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
        .badge-active { background: #DCFCE7; color: var(--success); }
        .badge-inactive { background: #F3F4F6; color: var(--text-muted); }

        .btn-sm { padding: 6px 12px; font-size: 0.80rem; border-radius: 6px; border: 1px solid var(--border-color); background: white; cursor: pointer; transition: 0.2s; font-weight: 500; }
        .btn-sm:hover { border-color: var(--secondary-blue); color: var(--secondary-blue); }

        /* Responsive */
        @media (max-width: 1024px) {
            .chart-row { grid-template-columns: 1fr; }
        }
        
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s; }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; width: 100%; }
            .header-actions { display: none; }
        }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="brand">
            GARDA<span>JKN</span>
        </div>
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="#" class="nav-link active">
                    <span class="nav-icon">📊</span> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <span class="nav-icon">👥</span> Data Anggota
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <span class="nav-icon">🌍</span> Wilayah
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <span class="nav-icon">🛡️</span> Admin Users
                </a>
            </li>
            <li class="nav-item" style="margin-top: auto;">
                <a href="#" class="nav-link">
                    <span class="nav-icon">⚙️</span> Pengaturan
                </a>
            </li>
        </ul>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <!-- TOPBAR -->
        <header>
            <div class="page-title">Dashboard Overview</div>
            <div class="header-actions">
                <div class="btn-icon">🔔</div>
                <div class="user-profile">
                    <div class="avatar">AD</div>
                    <span style="font-size: 0.875rem; font-weight: 500;">Admin Pusat</span>
                </div>
            </div>
        </header>

        <!-- CONTENT AREA -->
        <div class="content-wrapper">
            
            <!-- 1. KPI CARDS -->
            <section class="metrics-grid">
                <div class="card">
                    <div class="metric-title">Total Anggota</div>
                    <div class="metric-value">
                        12,450
                        <span class="metric-trend trend-up">↑ 12%</span>
                    </div>
                    <div class="metric-desc" style="font-size: 0.75rem; color: var(--text-muted); margin-top: 4px;">Update: Realtime</div>
                </div>

                <div class="card">
                    <div class="metric-title">Anggota Baru (Bulan Ini)</div>
                    <div class="metric-value">
                        845
                        <span class="metric-trend trend-up">↑ 5.2%</span>
                    </div>
                    <div class="metric-desc" style="font-size: 0.75rem; color: var(--text-muted); margin-top: 4px;">Target: 1,000</div>
                </div>

                <div class="card">
                    <div class="metric-title">Wilayah Terdaftar</div>
                    <div class="metric-value">
                        34
                        <span class="metric-trend" style="background: #E0F2FE; color: #0284C7;">Provinsi</span>
                    </div>
                    <div class="metric-desc" style="font-size: 0.75rem; color: var(--text-muted); margin-top: 4px;">Cakupan Nasional</div>
                </div>
            </section>

            <!-- 2. CHARTS -->
            <section class="chart-row">
                <!-- Line Chart -->
                <div class="card">
                    <div class="table-section-header">
                        <h3 style="font-size: 1rem; font-weight: 600;">Tren Pendaftaran Anggota</h3>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">6 Bulan Terakhir</div>
                    </div>
                    <div class="chart-container">
                        <canvas id="membershipChart"></canvas>
                    </div>
                </div>

                <!-- Pie Chart -->
                <div class="card">
                    <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 16px;">Distribusi Gender</h3>
                    <div class="chart-container" style="height: 240px;">
                        <canvas id="genderChart"></canvas>
                    </div>
                </div>
            </section>

            <!-- 3. DATA TABLE (Preview) -->
            <section class="card">
                <div class="table-section-header">
                    <h3 style="font-size: 1.1rem; font-weight: 600;">Anggota Terbaru</h3>
                    <button class="btn-sm">Lihat Semua</button>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>NIK (Masked)</th>
                            <th>Nama Lengkap</th>
                            <th>Pendaftaran</th>
                            <th>Status</th>
                            <th style="text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="font-family: monospace;">317101********90</td>
                            <td style="font-weight: 500;">Budi Santoso</td>
                            <td>19 Feb 2026</td>
                            <td><span class="status-badge badge-active">Active</span></td>
                            <td style="text-align: right;"><button class="btn-sm">Detail</button></td>
                        </tr>
                        <tr>
                            <td style="font-family: monospace;">320405********12</td>
                            <td>Siti Aminah</td>
                            <td>18 Feb 2026</td>
                            <td><span class="status-badge badge-active">Active</span></td>
                            <td style="text-align: right;"><button class="btn-sm">Detail</button></td>
                        </tr>
                        <tr>
                            <td style="font-family: monospace;">120501********88</td>
                            <td style="color: var(--text-muted);">Akun Dihapus</td>
                            <td>15 Feb 2026</td>
                            <td><span class="status-badge badge-inactive">Inactive</span></td>
                            <td style="text-align: right;"><button class="btn-sm">Detail</button></td>
                        </tr>
                    </tbody>
                </table>
            </section>

        </div>
    </main>

    <!-- SCRIPT FOR CHARTS (DUMMY DATA FOR VISUALIZATION) -->
    <script>
        // 1. Line Chart
        const ctxLine = document.getElementById('membershipChart').getContext('2d');
        new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: ['Sep', 'Okt', 'Nov', 'Des', 'Jan', 'Feb'],
                datasets: [{
                    label: 'Anggota Baru',
                    data: [65, 59, 80, 81, 56, 120],
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [2, 4] } },
                    x: { grid: { display: false } }
                }
            }
        });

        // 2. Pie Chart
        const ctxPie = document.getElementById('genderChart').getContext('2d');
        new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: ['Laki-laki', 'Perempuan'],
                datasets: [{
                    data: [300, 240],
                    backgroundColor: ['#1E3A8A', '#F43F5E'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
                },
                cutout: '70%'
            }
        });
    </script>
</body>
</html>
