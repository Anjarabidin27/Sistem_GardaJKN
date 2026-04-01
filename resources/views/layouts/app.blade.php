<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Garda JKN')</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Main Assets (Consolidated) -->
    @vite(['resources/css/variables.css', 'resources/css/components.css', 'resources/css/layout.css', 'resources/js/app.js'])
    
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

    <!-- Main App Content -->
    @yield('content')

    <!-- Core App Logic -->
    <script>
        // 1. Axios Config
        // Ensure window.axios is used consistently
        function getAxios() {
            return window.axios;
        }

        // Global Interceptor Setup (Attach to window.axios from Vite)
        function setupInterceptors() {
            const ax = getAxios();
            if (!ax) {
                setTimeout(setupInterceptors, 50);
                return;
            }

            // Request Interceptor (Auto Attach Token)
            ax.interceptors.request.use(config => {
                const loader = document.getElementById('global-loader');
                if (loader) loader.style.display = 'block';
                
                // Auto CSRF (For Web Requests)
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (csrfToken) config.headers['X-CSRF-TOKEN'] = csrfToken;

                return config;
            });

            // Response Interceptor (Error Handling & Loader)
            ax.interceptors.response.use(
                response => {
                    const loader = document.getElementById('global-loader');
                    if (loader) loader.style.display = 'none';
                    return response;
                },
                error => {
                    const loader = document.getElementById('global-loader');
                    if (loader) loader.style.display = 'none';
                    
                    // Handle 401 Unauthorized
                    if (error.response && error.response.status === 401) {
                        if (!window.location.pathname.includes('/login') && !window.location.pathname.includes('/register')) {
                            localStorage.removeItem('auth_token');
                            localStorage.removeItem('user_role');
                            window.location.href = '/login';
                        }
                    }
                    
                    return Promise.reject(error);
                }
            );
        }

        // 4. Utils: Toast Notification Premium
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            if (!container) return;
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            
            const icons = { 'success': 'check-circle', 'error': 'x-circle', 'warning': 'alert-circle', 'info': 'info' };
            const titles = { 'success': 'Berhasil', 'error': 'Gagal', 'warning': 'Peringatan', 'info': 'Informasi' };

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

        // 5. Utils: Custom Confirm Modal
        let confirmResolve;
        function showConfirm(title, message, options = {}) {
            const modal = document.getElementById('confirm-modal');
            const titleEl = document.getElementById('confirmTitle');
            const msgEl = document.getElementById('confirmMsg');
            const btnOk = document.getElementById('confirmBtnOk');
            const iconWrap = document.getElementById('confirmIcon');

            if (!modal) return Promise.resolve(false);

            titleEl.innerText = title;
            msgEl.innerText = message;
            btnOk.innerText = options.confirmText || 'Lanjutkan';
            btnOk.className = 'btn-confirm ' + (options.type === 'danger' ? 'danger' : '');
            
            iconWrap.className = 'confirm-icon';
            iconWrap.style.background = '#f8fafc';
            iconWrap.style.color = options.type === 'danger' ? '#ef4444' : '#004aad';
            iconWrap.innerHTML = `<i data-lucide="${options.icon || 'alert-circle'}" style="width:20px;height:20px;"></i>`;
            if (typeof lucide !== 'undefined') lucide.createIcons();

            modal.style.display = 'flex';
            modal.classList.remove('hide');

            return new Promise((resolve) => {
                const okHandler = () => {
                    modal.classList.add('hide');
                    setTimeout(() => { modal.style.display = 'none'; resolve(true); }, 200);
                    btnOk.removeEventListener('click', okHandler);
                };
                const cancelHandler = () => {
                    modal.classList.add('hide');
                    setTimeout(() => { modal.style.display = 'none'; resolve(false); }, 200);
                    document.getElementById('confirmBtnCancel').removeEventListener('click', cancelHandler);
                };

                btnOk.addEventListener('click', okHandler);
                document.getElementById('confirmBtnCancel').addEventListener('click', cancelHandler);
            });
        }

        // 7. Utils: Toggle Password Visibility
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const iconWrap = document.getElementById('icon-' + inputId);
            if (!input || !iconWrap) return;

            if (input.type === 'password') {
                input.type = 'text';
                iconWrap.innerHTML = '<i data-lucide="eye-off" style="width: 20px; height: 20px; color: #64748b;"></i>';
            } else {
                input.type = 'password';
                iconWrap.innerHTML = '<i data-lucide="eye" style="width: 20px; height: 20px; color: #64748b;"></i>';
            }
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        // 8. Global Sidebar Identity
        async function initGlobalSidebar() {
            const ax = getAxios();
            if (!ax) return;

            let sbName = localStorage.getItem('user_name') || 'Administrator';
            const sbEl = document.getElementById('sb-user-name');
            const sbInit = document.getElementById('sb-initials');
            const topInit = document.getElementById('user-initials');
            const initial = (sbName || 'A').substring(0, 1).toUpperCase();
            
            if (sbEl) sbEl.innerText = sbName;
            if (sbInit) sbInit.innerText = initial;
            if (topInit) topInit.innerText = initial;
            
            // Hanya tarik data dari server jika ada token (Mencegah console error 401 bagi Guest)
            const token = localStorage.getItem('auth_token');
            if (!token) return;

            try {
                const res = await ax.get('settings/profile');
                const d = res.data.data;
                const name = d.name || d.username || sbName;
                const finalInitial = name.substring(0, 1).toUpperCase();
                
                if (sbEl) sbEl.innerText = name;
                if (sbInit) sbInit.innerText = finalInitial;
                if (topInit) topInit.innerText = finalInitial;

                const photoUrl = d.photo_url || `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=ffffff20&color=fff&size=200&length=1`;
                const wrap = document.getElementById('sb-avatar-wrap');
                if (wrap) wrap.innerHTML = `<img src="${photoUrl}" style="width:100%;height:100%;object-fit:cover;object-position:top;" alt="Avatar">`;
            } catch (e) {}
        }

        // 9. Global Logout
        function logout() {
            const role = localStorage.getItem('user_role');
            localStorage.clear();
            window.location.href = (role === 'admin' ? '/login/admin' : '/login');
        }

        // Start App
        (function() {
            function initApp() {
                if (typeof lucide !== 'undefined' && window.axios) {
                    lucide.createIcons();
                    setupInterceptors();
                    initGlobalSidebar();
                } else {
                    setTimeout(initApp, 50);
                }
            }
            initApp();
        })();
    </script>
    @stack('scripts')
</body>
</html>
