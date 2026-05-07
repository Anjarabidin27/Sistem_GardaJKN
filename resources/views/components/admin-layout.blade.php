<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin Dashboard - Garda JKN' }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- JS LIBS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- MAIN CSS -->
    @vite(['resources/css/variables.css', 'resources/css/components.css', 'resources/css/layout.css'])
    
    @stack('styles')
</head>
<body>
    <div id="global-loader"></div>
    <div id="toast-container"></div>

    <!-- Mobile Sidebar Overlay -->
    <div id="mobile-overlay" style="display: none; position: fixed; inset: 0; background: rgba(0,18,51,0.5); z-index: 999; backdrop-filter: blur(4px); opacity: 0; transition: opacity 0.3s;"></div>

    <div class="app-layout">
        <aside class="sidebar" id="garda-sidebar">
            <!-- Sidebar Brand Header -->
            <div class="sb-header" style="padding: 16px 16px; border-bottom: 1px solid rgba(255,255,255,0.05); margin-bottom: 8px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 28px; height: 28px; background: #fff; border-radius: 6px; display: flex; align-items: center; justify-content: center; transform: rotate(-5deg); box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                        <i data-lucide="shield-check" style="width: 18px; height: 18px; color: var(--primary);"></i>
                    </div>
                    <div>
                        <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1rem; font-weight: 800; color: #fff; letter-spacing: -0.02em;">Garda JKN</div>
                        <div style="font-size: 0.55rem; color: rgba(255,255,255,0.3); font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; margin-top: -1px;">Admin Console</div>
                    </div>
                </div>
            </div>

            <!-- Profile Widget -->
            <div class="sb-profile-widget" style="padding: 0 12px 10px;">
                <div style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 8px 10px; display: flex; align-items: center; gap: 10px;">
                    <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #3B82F6, #1D4ED8); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-family: 'Plus Jakarta Sans'; font-weight: 800; color: #fff; font-size: 0.95rem; border: 1.5px solid rgba(255,255,255,0.15);" id="sb-initials">A</div>
                    <div style="flex: 1; overflow: hidden;">
                        <div style="font-size: 0.775rem; font-weight: 700; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" id="sb-user-name">Administrator</div>
                        <div style="font-size: 0.65rem; color: #64748B; font-weight: 600; text-transform: capitalize;" id="sb-user-role">Super admin</div>
                    </div>
                </div>
            </div>

            <!-- Institutional Context -->
            <div style="padding: 0 12px 12px;">
                <div style="background: linear-gradient(to right, rgba(16, 185, 129, 0.08), transparent); border-left: 2px solid #10B981; border-radius: 0 6px 6px 0; padding: 6px 12px;">
                    <div style="font-size: 0.55rem; color: rgba(255,255,255,0.3); font-weight: 800; text-transform: uppercase; letter-spacing: 0.12em; margin-bottom: 4px;">Unit Kerja Aktif</div>
                    <div class="flex items-center gap-2">
                        <i data-lucide="building-2" style="width: 12px; height: 12px; color: #10B981;"></i>
                    <div class="flex-col">
                        <div id="sb-kc-name" style="font-size: 0.775rem; color: #fff; font-weight: 800; letter-spacing: 0.02em;">-</div>
                        <div id="sb-kw-name" style="font-size: 0.6rem; color: #10B981; font-weight: 700; text-transform: uppercase; margin-top: 1px;">-</div>
                    </div>
                    </div>
                </div>
            </div>

                <!-- Navigation Links -->
                <nav class="sb-menu" style="padding: 0 12px;">
                    <div class="menu-group" data-role-allow="superadmin,administrator,admin_wilayah">
                        <div class="sb-nav-section-title" style="font-size: 0.65rem; font-weight: 800; color: rgba(255,255,255,0.3); text-transform: uppercase; letter-spacing: 0.1em; padding: 0 16px 10px;">Menu Utama</div>
                        <a href="/admin/dashboard" class="sb-link @if(Request::is('admin/dashboard')) active @endif">
                            <i data-lucide="layout-dashboard" class="sb-link-icon"></i> <span>Dashboard Sentral</span>
                        </a>
                        <a href="/admin/members" class="sb-link @if(Request::is('admin/members*')) active @endif">
                            <i data-lucide="users" class="sb-link-icon"></i> <span>Manajemen Member</span>
                        </a>
                        <a href="/admin/staff" class="sb-link @if(Request::is('admin/staff*')) active @endif">
                            <i data-lucide="user-cog" class="sb-link-icon"></i> <span>Master Petugas</span>
                        </a>
                        <a href="/admin/approvals" class="sb-link @if(Request::is('admin/approvals*')) active @endif">
                            <i data-lucide="user-plus" class="sb-link-icon"></i> <span>Persetujuan Pengurus</span>
                        </a>
                    </div>

                    <div class="sb-nav-section-title" style="font-size: 0.65rem; font-weight: 800; color: rgba(255,255,255,0.3); text-transform: uppercase; letter-spacing: 0.1em; padding: 16px 16px 10px;">Pelayanan Lapangan</div>

                    <!-- Modul BPJS Keliling -->
                    <div class="menu-group" data-role-allow="superadmin,administrator,admin_wilayah,petugas,petugas_keliling">
                        <div class="sb-folding-menu @if(Request::is('admin/bpjs-keliling*')) active @endif">
                            <div class="sb-folding-header">
                                <i data-lucide="truck" class="sb-link-icon"></i> <span>BPJS Keliling</span>
                            </div>
                            <div class="sb-folding-items">
                                <a href="/admin/bpjs-keliling" class="sb-sub-link @if(Request::is('admin/bpjs-keliling') && !Request::is('admin/bpjs-keliling/*')) active @endif">📅 Jadwal Kegiatan</a>
                                <a href="/admin/bpjs-keliling/laporan" class="sb-sub-link @if(Request::is('admin/bpjs-keliling/laporan')) active @endif">📋 Daftar Terinput</a>
                                <a href="/admin/bpjs-keliling/dashboard" class="sb-sub-link @if(Request::is('admin/bpjs-keliling/dashboard')) active @endif">📈 Dashboard BPJS Keliling</a>
                            </div>
                        </div>
                    </div>

                    <!-- Modul PIL -->
                    <div class="menu-group" data-role-allow="superadmin,administrator,admin_wilayah,petugas,petugas_pil">
                        <div class="sb-folding-menu @if(Request::is('admin/pil*')) active @endif">
                            <div class="sb-folding-header">
                                <i data-lucide="mic" class="sb-link-icon"></i> <span>Penyuluhan (PIL)</span>
                            </div>
                            <div class="sb-folding-items">
                                <a href="/admin/pil" class="sb-sub-link @if(Request::is('admin/pil') && !Request::is('admin/pil/*')) active @endif">📅 Jadwal PIL</a>
                                <a href="/admin/pil/dashboard" class="sb-sub-link @if(Request::is('admin/pil/dashboard')) active @endif">📈 Dashboard PIL</a>
                            </div>
                        </div>
                    </div>

                    <div class="menu-group" data-role-allow="superadmin,administrator">
                        <div class="sb-nav-section-title" style="font-size: 0.65rem; font-weight: 800; color: rgba(255,255,255,0.3); text-transform: uppercase; letter-spacing: 0.1em; padding: 24px 16px 10px;">Pusat Kontrol</div>
                        <a href="/admin/informations" class="sb-link @if(Request::is('admin/informations*')) active @endif">
                            <i data-lucide="megaphone" class="sb-link-icon"></i> <span>Broadcast Info</span>
                        </a>
                        <a href="/admin/audit-logs" class="sb-link @if(Request::is('admin/audit-logs*')) active @endif">
                            <i data-lucide="file-clock" class="sb-link-icon"></i> <span>Log Aktivitas</span>
                        </a>
                    </div>

                    <div style="margin-top: 40px; padding: 0 16px;">
                        <a href="/admin/settings" class="sb-link @if(Request::is('admin/settings')) active @endif">
                            <i data-lucide="settings" class="sb-link-icon"></i> <span>Pengaturan</span>
                        </a>
                        <a href="javascript:void(0)" onclick="window.logout()" class="sb-link logout-link">
                            <i data-lucide="log-out" class="sb-link-icon"></i> <span>Keluar Sistem</span>
                        </a>
                    </div>
                </nav>
            </aside>

        <main class="main-body">
            <header class="top-header">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <button id="mobileMenuBtn" style="background: none; border: none; color: #1e293b; cursor: pointer; padding: 0;">
                        <i data-lucide="menu" style="width: 24px; height: 24px;"></i>
                    </button>
                    <div class="topbar-title">Sistem Garda JKN</div>
                </div>
                <div style="display: flex; align-items: center; gap: 16px;">
                    <span class="topbar-date" id="date-now"></span>
                    <div id="user-initials" style="width: 36px; height: 36px; background: #eff6ff; color: #1d4ed8; border: 1px solid #dbeafe; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.9rem;">A</div>
                </div>
            </header>

            <div class="view-container">
                {{ $slot }}
            </div>
        </main>
    </div>

    <div id="confirm-modal" class="modal-overlay" style="display:none;">
        <div class="confirm-card">
            <div class="confirm-icon" id="confirmIcon"><i data-lucide="alert-triangle"></i></div>
            <div class="confirm-title" id="confirmTitle">Konfirmasi</div>
            <div class="confirm-msg" id="confirmMsg">Apakah Anda yakin?</div>
            <div class="confirm-actions">
                <button class="btn-cancel" id="confirmBtnCancel">Batal</button>
                <button class="btn-confirm" id="confirmBtnOk">Ya, Lanjutkan</button>
            </div>
        </div>
    </div>

    <!-- GLOBAL JS -->
    <script>
        // 1. Axios Configuration
        if (typeof axios !== 'undefined') {
            axios.defaults.baseURL = '/api/';
            axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
            axios.defaults.headers.common['Accept'] = 'application/json';

            const token = localStorage.getItem('auth_token');
            if (token) {
                axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
            }

            axios.interceptors.request.use(config => {
                const loader = document.getElementById('global-loader');
                if (loader) loader.style.display = 'block';
                return config;
            }, error => {
                document.getElementById('global-loader').style.display = 'none';
                return Promise.reject(error);
            });

            axios.interceptors.response.use(
                response => {
                    const loader = document.getElementById('global-loader');
                    if (loader) loader.style.display = 'none';
                    return response;
                },
                err => {
                    const loader = document.getElementById('global-loader');
                    if (loader) loader.style.display = 'none';
                    
                    if (err.response && err.response.status === 401) {
                        localStorage.clear();
                        window.location.href = '/login';
                    }
                    return Promise.reject(err);
                }
            );
            
            // Mirror to window.axios for Vite compatibility
            window.axios = axios;
        }

        // 2. UI Utilities
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            if (!container) return;
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            const iconMap = { 'success': 'check-circle', 'error': 'x-circle', 'warning': 'alert-circle', 'info': 'info' };
            toast.innerHTML = `
                <div class="toast-icon"><i data-lucide="${iconMap[type] || 'info'}"></i></div>
                <div class="toast-content">
                    <div class="toast-title">${type.toUpperCase()}</div>
                    <div class="toast-msg">${message}</div>
                </div>
            `;
            container.appendChild(toast);
            if (typeof lucide !== 'undefined') lucide.createIcons();
            setTimeout(() => { toast.classList.add('hide'); setTimeout(() => toast.remove(), 400); }, 3000);
        }

        let confirmResolve;
        function showConfirm(title, message, options = {}) {
            const modal = document.getElementById('confirm-modal');
            const titleEl = document.getElementById('confirmTitle');
            const msgEl = document.getElementById('confirmMsg');
            const btnOk = document.getElementById('confirmBtnOk');
            const iconWrap = document.getElementById('confirmIcon');

            titleEl.innerText = title;
            msgEl.innerText = message;
            btnOk.innerText = options.confirmText || 'Lanjutkan';
            btnOk.className = 'btn-confirm ' + (options.type === 'danger' ? 'danger' : '');
            iconWrap.innerHTML = `<i data-lucide="${options.icon || 'alert-circle'}" style="width:20px;height:20px;"></i>`;
            
            if (typeof lucide !== 'undefined') lucide.createIcons();
            modal.style.display = 'flex';
            modal.classList.remove('hide');

            return new Promise((resolve) => { confirmResolve = resolve; });
        }

        document.getElementById('confirmBtnOk').onclick = () => { closeConfirm(true); };
        document.getElementById('confirmBtnCancel').onclick = () => { closeConfirm(false); };

        function closeConfirm(result) {
            const modal = document.getElementById('confirm-modal');
            modal.classList.add('hide');
            setTimeout(() => { modal.style.display = 'none'; confirmResolve(result); }, 200);
        }

        // 3. Global Initialization
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof lucide !== 'undefined') lucide.createIcons();
            
            const dateEl = document.getElementById('date-now');
            if (dateEl) dateEl.innerText = new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
            
            // Sync Profile Identity
            const name = localStorage.getItem('user_name') || 'Admin User';
            const role = localStorage.getItem('user_role') || 'Admin';
            const initial = name.charAt(0).toUpperCase();

            if (document.getElementById('sb-user-name')) document.getElementById('sb-user-name').innerText = name;
            
            const roleEl = document.getElementById('sb-user-role');
            if (roleEl) {
                // Sesuai permintaan USER: Hilangkan keterangan role jika akun adalah Petugas BPJS Keliling (Redundansi)
                if (role.includes('petugas')) {
                    roleEl.style.display = 'none';
                } else {
                    roleEl.innerText = role.split('_').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ');
                }
            }
            if (document.getElementById('sb-initials')) document.getElementById('sb-initials').innerText = initial;
            if (document.getElementById('user-initials')) document.getElementById('user-initials').innerText = initial;

            // Sync Institutional Context
            let kc = localStorage.getItem('kantor_cabang');
            let kw = localStorage.getItem('kedeputian_wilayah');
            
            // Clean up 'null' strings from localStorage
            if (kc === 'null' || !kc) kc = (role === 'superadmin') ? 'NASIONAL' : '-';
            if (kw === 'null' || !kw) kw = (role === 'superadmin') ? 'PUSAT' : '-';

            if (document.getElementById('sb-kc-name')) document.getElementById('sb-kc-name').innerText = kc;
            if (document.getElementById('sb-kw-name')) document.getElementById('sb-kw-name').innerText = kw;
            if (document.getElementById('ui-kc-name')) document.getElementById('ui-kc-name').innerText = kc;
            if (document.getElementById('ui-kw-name')) document.getElementById('ui-kw-name').innerText = kw;
            if (document.getElementById('ui-petugas-name')) document.getElementById('ui-petugas-name').innerText = name;

            // Handle Menu Visibility based on localStorage role
            const groups = document.querySelectorAll('.menu-group');
            groups.forEach(g => {
                const allowed = g.getAttribute('data-role-allow').split(',');
                if (allowed.includes(role) || role === 'superadmin' || role === 'administrator') {
                    g.style.display = 'block';
                } else {
                    g.style.display = 'none';
                }
            });

            // GLOBAL Sidebar folding logic
            document.querySelectorAll('.sb-folding-header').forEach(header => {
                header.addEventListener('click', () => {
                    const menu = header.parentElement;
                    menu.classList.toggle('active');
                });
            });

            // --- Mobile Sidebar Logic ---
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const sidebar = document.getElementById('garda-sidebar');
            const mobileOverlay = document.getElementById('mobile-overlay');

            if (mobileMenuBtn && sidebar && mobileOverlay) {
                function toggleSidebar() {
                    sidebar.classList.toggle('open');
                    if (sidebar.classList.contains('open')) {
                        mobileOverlay.style.display = 'block';
                        setTimeout(() => { mobileOverlay.style.opacity = '1'; }, 10);
                    } else {
                        mobileOverlay.style.opacity = '0';
                        setTimeout(() => { mobileOverlay.style.display = 'none'; }, 300);
                    }
                }

                mobileMenuBtn.addEventListener('click', toggleSidebar);
                mobileOverlay.addEventListener('click', toggleSidebar);
                
                // Auto close on link click (mobile)
                document.querySelectorAll('.sb-link, .sb-sub-link').forEach(link => {
                    link.addEventListener('click', () => {
                        if (window.innerWidth <= 768 && sidebar.classList.contains('open')) {
                            toggleSidebar();
                        }
                    });
                });
            }
        });

        window.showToast = showToast;
        window.showConfirm = showConfirm;
        window.logout = () => { localStorage.clear(); window.location.href = '/login'; };
    </script>
    <style>
        /* Sidebar Toggle for Fullscreen Input */
        .app-layout.sidebar-hidden .sidebar {
            transform: translateX(-100%);
            width: 0;
            padding: 0;
            margin: 0;
            opacity: 0;
            pointer-events: none;
        }
        /* Force Reset Top Gaps */
        html, body {
            margin: 0 !important;
            padding: 0 !important;
            overflow-x: hidden;
        }
        .app-layout.sidebar-hidden .main-body {
            margin-left: 0 !important;
            width: 100% !important;
            padding-top: 40px !important; /* Thinner for new header */
            margin-top: 0 !important;
        }
        .app-layout.sidebar-hidden .top-header {
            position: fixed !important; 
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            height: 40px !important; /* Even thinner header */
            display: flex;
            align-items: center; /* Keep centered but in smaller box */
            margin-top: 0 !important;
            padding: 0 15px !important;
            z-index: 1001;
            box-shadow: none !important;
            border-bottom: 1px solid #e2e8f0;
            background: #fff;
        }
        .app-layout.sidebar-hidden #title-section {
            margin-bottom: 8px !important; /* Minimal margin */
        }
        .app-layout.sidebar-hidden .topbar-title {
            font-size: 1.25rem !important; /* Smaller title */
        }
        .app-layout.sidebar-hidden .text-muted {
            display: none; /* Hide subtitle to save space */
        }
        .sidebar, .main-body, .top-header {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
    
    @stack('scripts')
</body>
</html>
