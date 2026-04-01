<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin Dashboard - Garda JKN' }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    
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

    <div class="app-layout">
        <aside class="sidebar" id="garda-sidebar">
            <div class="sb-brand">
                <div class="sb-brand-name">Garda JKN</div>
                <div style="font-size: 0.7rem; color: rgba(255,255,255,0.4); font-weight: 600; margin-top: 2px;">PANEL ADMINISTRASI</div>
            </div>

            <div class="sb-user-card">
                <div class="sb-avatar" id="sb-avatar-wrap">
                    <span id="sb-initials">A</span>
                </div>
                <div class="sb-user-info">
                    <div class="sb-user-name" id="sb-user-name">Administrator</div>
                    <div class="sb-user-role" id="sb-user-role">Super Admin</div>
                </div>
            </div>
            
            <nav class="sb-menu">
                <div class="sb-section-label">Main Menu</div>
                
                <a href="/admin/dashboard" class="sb-link @if(Request::is('admin/dashboard')) active @endif">
                    <i data-lucide="layout-dashboard" class="sb-link-icon"></i> <span>Dashboard</span>
                </a>
                
                <a href="/admin/members" class="sb-link @if(Request::is('admin/members*')) active @endif">
                    <i data-lucide="users" class="sb-link-icon"></i> <span>Manajemen Anggota</span>
                </a>
                
                <a href="/admin/approvals" class="sb-link @if(Request::is('admin/approvals*')) active @endif">
                    <i data-lucide="user-check" class="sb-link-icon"></i> <span>Persetujuan Pengurus</span>
                </a>
                
                <a href="/admin/informations" class="sb-link @if(Request::is('admin/informations*')) active @endif">
                    <i data-lucide="megaphone" class="sb-link-icon"></i> <span>Informasi</span>
                </a>
                
                <div class="sb-section-label" style="margin-top: 24px;">Layanan Lapangan</div>
                
                <a href="/admin/bpjs-keliling" class="sb-link @if(Request::is('admin/bpjs-keliling') && !Request::is('admin/bpjs-keliling/dashboard')) active @endif">
                    <i data-lucide="map-pin" class="sb-link-icon"></i> <span>Jadwal BPJS Keliling</span>
                </a>
                
                <a href="/admin/bpjs-keliling/dashboard" class="sb-link @if(Request::is('admin/bpjs-keliling/dashboard')) active @endif">
                    <i data-lucide="bar-chart-2" class="sb-link-icon"></i> <span>Dashboard BPJS</span>
                </a>
                
                <a href="/admin/pil" class="sb-link @if(Request::is('admin/pil') && !Request::is('admin/pil/dashboard')) active @endif">
                    <i data-lucide="mic" class="sb-link-icon"></i> <span>Agenda PIL</span>
                </a>

                <a href="/admin/pil/dashboard" class="sb-link @if(Request::is('admin/pil/dashboard')) active @endif">
                    <i data-lucide="pie-chart" class="sb-link-icon"></i> <span>Dashboard PIL</span>
                </a>
 
                <a href="/admin/audit-logs" class="sb-link @if(Request::is('admin/audit-logs*')) active @endif">
                    <i data-lucide="file-clock" class="sb-link-icon"></i> <span>Log Audit</span>
                </a>
            </nav>
            
            <div class="sb-footer">
                <div class="sb-section-label">Settings</div>
                <a href="/admin/settings" class="sb-link @if(Request::is('admin/settings')) active @endif">
                    <i data-lucide="settings" class="sb-link-icon"></i> <span>Pengaturan Akun</span>
                </a>
                <button class="sb-link" onclick="window.logout()" style="color: #fca5a5; background: none; border: none; width: 100%; text-align: left; cursor: pointer;">
                    <i data-lucide="log-out" class="sb-link-icon" style="color: #fca5a5;"></i> <span>Keluar</span>
                </button>
            </div>
        </aside>

        <main class="main-body">
            <header class="top-header">
                <div class="topbar-title">Sistem Garda JKN</div>
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
            if (document.getElementById('sb-user-role')) document.getElementById('sb-user-role').innerText = role.toUpperCase();
            if (document.getElementById('sb-initials')) document.getElementById('sb-initials').innerText = initial;
            if (document.getElementById('user-initials')) document.getElementById('user-initials').innerText = initial;
        });

        window.showToast = showToast;
        window.showConfirm = showConfirm;
        window.logout = () => { localStorage.clear(); window.location.href = '/login'; };
    </script>
    
    @stack('scripts')
</body>
</html>
