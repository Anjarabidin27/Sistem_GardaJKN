<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Portal Anggota - Garda JKN' }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    @vite(['resources/css/variables.css', 'resources/css/components.css', 'resources/css/layout.css'])
    @stack('styles')
</head>
<body>
    <!-- Global Loader Line -->
    <div id="global-loader"></div>

    <!-- Toast Container -->
    <div id="toast-container"></div>

    <!-- Confirm Modal -->
    <div id="confirm-modal" class="modal-overlay" style="display:none;">
        <div class="confirm-card">
            <div class="confirm-icon" id="confirmIcon"><i data-lucide="alert-triangle"></i></div>
            <div class="confirm-title" id="confirmTitle">Konfirmasi Tindakan</div>
            <div class="confirm-msg" id="confirmMsg">Apakah Anda yakin ingin melanjutkan tindakan ini?</div>
            <div class="confirm-actions">
                <button class="btn-cancel" id="confirmBtnCancel">Batal</button>
                <button class="btn-confirm" id="confirmBtnOk">Ya, Lanjutkan</button>
            </div>
        </div>
    </div>

    <div class="app-layout">
        
        <aside class="sidebar">
            <div class="sb-brand">
                <div class="sb-brand-name">Garda JKN</div>
                <div style="font-size: 0.7rem; color: rgba(255,255,255,0.4); font-weight: 600; margin-top: 2px;">PORTAL ANGGOTA</div>
            </div>

            <div class="sb-user-card" style="padding: 20px 16px; display: flex; flex-direction: column; gap: 12px; border-bottom: 1px solid rgba(255,255,255,0.05); margin-bottom: 4px;">
                <div class="sb-avatar" id="sb-avatar-wrap" style="width: 60px; height: 60px; border-radius: 16px; margin: 0; background: rgba(255,255,255,0.1); border: 2px solid rgba(255,255,255,0.2); overflow: hidden;">
                    <span id="sb-initials" style="font-size: 1.25rem; font-weight: 800;">U</span>
                </div>
                <div class="sb-user-info" style="display: flex; flex-direction: column; gap: 2px;">
                    <div class="sb-user-name" id="sidebarName" style="font-size: 1.1rem; font-weight: 800; color: white; line-height: 1.2;">Loading...</div>
                    <div class="sb-user-role" id="sidebarNik" style="font-size: 0.75rem; color: rgba(255,255,255,0.45); font-weight: 500;">NIK: memuat...</div>
                    <div id="sidebarBadgeWrap" style="margin-top: 6px;">
                        <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: #10b981; display: inline-flex; align-items: center; gap: 5px; padding: 5px 12px; border-radius: 50px; font-size: 0.6rem; font-weight: 800; text-transform: uppercase;">
                            <span style="width: 5px; height: 5px; background: #10b981; border-radius: 50%; box-shadow: 0 0 6px #10b981;"></span>
                            ANGGOTA AKTIF
                        </div>
                    </div>
                </div>
            </div>
            
            <nav class="sb-menu">
                <div class="sb-section-label" style="font-size: 0.65rem; color: rgba(255,255,255,0.35); font-weight: 800; text-transform: uppercase; padding: 12px 20px; letter-spacing: 0.1em;">Menu</div>
                
                <a href="/member/profile" class="sb-link @if(Request::is('member/profile')) active @endif">
                    <i data-lucide="user-circle" class="sb-link-icon"></i> <span>Profil Saya</span>
                </a>
                
                <a href="/member/profile#informasi" class="sb-link">
                    <i data-lucide="megaphone" class="sb-link-icon"></i> <span>Informasi</span>
                </a>
                
                <a href="/member/profile#pembayaran" class="sb-link">
                    <i data-lucide="wallet" class="sb-link-icon"></i> <span>Pembayaran</span>
                </a>
                
                <a href="/member/profile#laporan" class="sb-link">
                    <i data-lucide="clipboard-list" class="sb-link-icon"></i> <span>Laporan Kegiatan</span>
                </a>
                
                <a href="/member/profile#survey" class="sb-link">
                    <i data-lucide="help-circle" class="sb-link-icon"></i> <span>Survey</span>
                </a>
            </nav>
            
            <div class="sb-footer">
                <div class="sb-section-label" style="font-size: 0.65rem; color: rgba(255,255,255,0.35); font-weight: 800; text-transform: uppercase; padding: 12px 20px; letter-spacing: 0.1em;">Pengaturan</div>
                <a href="/member/settings" class="sb-link @if(Request::is('member/settings')) active @endif">
                    <i data-lucide="settings" class="sb-link-icon"></i> <span>Pengaturan Akun</span>
                </a>
                <a href="javascript:void(0)" onclick="logout()" class="sb-link logout-link">
                    <i data-lucide="log-out" class="sb-link-icon"></i> <span>Keluar Sesi</span>
                </a>
            </div>
        </aside>

        <main class="main-body">
            <header class="top-header">
                <div id="topbarTitle" class="topbar-title">{{ $title ?? 'Garda JKN' }}</div>
                <div style="display: flex; align-items: center; gap: 16px;">
                    <span class="topbar-date" id="date-now"></span>
                    <div id="user-initials" style="width: 36px; height: 36px; background: var(--bg-surface); color: var(--primary); border: 1px solid var(--border); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.9rem;">U</div>
                </div>
            </header>

            <div class="view-container">
                {{ $slot }}
            </div>
        </main>
    </div>

    <script>
        // 1. Axios Global Config
        if (typeof axios !== 'undefined') {
            axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
            axios.defaults.baseURL = '/api/';
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (csrfToken) {
                axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
                axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
            }

            // Request Interceptor
            axios.interceptors.request.use(config => {
                const loader = document.getElementById('global-loader');
                if (loader) loader.style.display = 'block';
                
                const token = localStorage.getItem('auth_token');
                if (token) config.headers.Authorization = `Bearer ${token}`;
                return config;
            });

            // Response Interceptor
            axios.interceptors.response.use(
                response => {
                    const loader = document.getElementById('global-loader');
                    if (loader) loader.style.display = 'none';
                    return response;
                },
                error => {
                    const loader = document.getElementById('global-loader');
                    if (loader) loader.style.display = 'none';
                    
                    if (error.response && error.response.status === 401) {
                        if (!window.location.pathname.includes('/login')) {
                            localStorage.removeItem('auth_token');
                            window.location.href = '/login';
                        }
                    }
                    return Promise.reject(error);
                }
            );
        }

        // 2. Utils: Toast Notification
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            if (!container) return;
            
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            
            const icons = {
                'success': 'check-circle',
                'error': 'x-circle',
                'warning': 'alert-circle',
                'info': 'info'
            };
            const titles = {
                'success': 'Berhasil',
                'error': 'Gagal',
                'warning': 'Peringatan',
                'info': 'Informasi'
            };

            toast.innerHTML = `
                <div class="toast-icon"><i data-lucide="${icons[type] || 'info'}"></i></div>
                <div class="toast-content">
                    <div class="toast-title">${titles[type] || 'Notifikasi'}</div>
                    <div class="toast-msg">${message}</div>
                </div>
                <div class="toast-progress"></div>
            `;
            
            container.appendChild(toast);
            if (typeof lucide !== 'undefined') lucide.createIcons();
            
            setTimeout(() => {
                toast.classList.add('hide');
                setTimeout(() => { toast.remove(); }, 400);
            }, 3000);
        }

        // 3. Utils: Custom Confirm Modal
        let confirmResolve;
        function showConfirm(title, message, options = {}) {
            const modal = document.getElementById('confirm-modal');
            const titleEl = document.getElementById('confirmTitle');
            const msgEl = document.getElementById('confirmMsg');
            const btnOk = document.getElementById('confirmBtnOk');
            const iconWrap = document.getElementById('confirmIcon');

            if (!modal) return Promise.resolve(window.confirm(message));

            titleEl.innerText = title;
            msgEl.innerText = message;
            btnOk.innerText = options.confirmText || 'Lanjutkan';
            btnOk.className = 'btn-confirm ' + (options.type === 'danger' ? 'danger' : '');
            
            iconWrap.className = 'confirm-icon';
            iconWrap.innerHTML = `<i data-lucide="${options.icon || 'alert-circle'}" style="width:20px;height:20px;"></i>`;
            if (typeof lucide !== 'undefined') lucide.createIcons();

            modal.style.display = 'flex';
            modal.classList.remove('hide');

            return new Promise((resolve) => {
                confirmResolve = resolve;
            });
        }

        document.getElementById('confirmBtnOk').onclick = () => closeConfirm(true);
        document.getElementById('confirmBtnCancel').onclick = () => closeConfirm(false);

        function closeConfirm(result) {
            const modal = document.getElementById('confirm-modal');
            modal.classList.add('hide');
            setTimeout(() => {
                modal.style.display = 'none';
                confirmResolve(result);
            }, 200);
        }

        // 4. Utils: Formatters
        function formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        }

        // 5. Global Sidebar Identity
        async function initGlobalSidebar() {
            const name = localStorage.getItem('user_name') || 'Anggota JKN';
            let role = localStorage.getItem('user_role') || 'Anggota Aktif';
            const nik = localStorage.getItem('user_nik') || '-';
            
            const sbName = document.getElementById('sidebarName');
            const sbNik = document.getElementById('sidebarNik');
            const sbInit = document.getElementById('sb-initials');
            const topInit = document.getElementById('user-initials');
            
            const initial = name.charAt(0).toUpperCase();
            
            if (sbName) sbName.innerText = name;
            if (sbNik) sbNik.innerText = (nik !== '-') ? 'NIK: ' + nik : 'Anggota Aktif';
            if (sbInit) sbInit.innerText = initial;
            if (topInit) topInit.innerText = initial;

            const photoUrl = localStorage.getItem('user_photo_url');
            if (photoUrl) {
                const wrap = document.getElementById('sb-avatar-wrap');
                if (wrap) wrap.innerHTML = `<img src="${photoUrl}" style="width:100%;height:100%;object-fit:cover;object-position:top;">`;
            }

            // Sync with server if possible, but don't block
            try {
                const res = await axios.get('member/profile');
                if (res.data && res.data.data) {
                    const d = res.data.data;
                    if (sbName) sbName.innerText = d.name;
                    if (sbNik) sbNik.innerText = 'NIK: ' + (d.nik || '-');
                    
                    const avatarContent = (d.photo_url) 
                        ? `<img src="${d.photo_url}" style="width:100%;height:100%;object-fit:cover;object-position:top;">`
                        : `<span style="font-size: 1.25rem; font-weight: 800;">${d.name.charAt(0).toUpperCase()}</span>`;
                    
                    const wrap = document.getElementById('sb-avatar-wrap');
                    if (wrap) wrap.innerHTML = avatarContent;
                    
                    // Update cache
                    localStorage.setItem('user_name', d.name);
                    localStorage.setItem('user_nik', d.nik);
                    if (d.photo_url) localStorage.setItem('user_photo_url', d.photo_url);
                }
            } catch (e) {
                console.warn('Sidebar sync failed', e);
            }
        }

        // 6. Global Logout
        function logout() {
            localStorage.clear();
            window.location.href = '/login';
        }

        document.addEventListener('DOMContentLoaded', () => {
            if (typeof lucide !== 'undefined') lucide.createIcons();
            initGlobalSidebar();
            
            const dateEl = document.getElementById('date-now');
            if (dateEl) dateEl.innerText = new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        });

        window.showToast = showToast;
        window.showConfirm = showConfirm;
        window.formatNumber = formatNumber;
        window.logout = logout;
    </script>
    @stack('scripts')
</body>
</html>
