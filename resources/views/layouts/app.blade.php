<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Garda JKN')</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Design System CSS (Inline for Performance/MVP) -->
    <style>
        :root {
            /* High-End Enterprise Palette */
            --primary: #004aad;       /* Deep Institutional Blue */
            --primary-hover: #003a8c;
            --accent: #3b82f6;        
            --success: #10b981;
            --danger: #ef4444;
            
            --bg-base: #f1f5f9;       /* Slate 100 */
            --bg-surface: #ffffff;
            --text-title: #0f172a;    /* Slate 900 */
            --text-body: #334155;     /* Slate 700 */
            --text-muted: #64748b;    /* Slate 500 */
            --border: #e2e8f0;        /* Slate 200 */
            
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            
            --radius-md: 8px;
            --radius-lg: 12px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Inter', system-ui, -apple-system, sans-serif; 
            background: var(--bg-base); 
            color: var(--text-body);
            -webkit-font-smoothing: antialiased;
        }

        /* Enterprise Components */
        .btn { 
            display: inline-flex; align-items: center; justify-content: center;
            padding: 10px 20px; border-radius: var(--radius-md); 
            font-size: 0.875rem; font-weight: 600; cursor: pointer; 
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid transparent; gap: 8px;
        }
        .btn-primary { 
            background: var(--primary); color: white; 
            box-shadow: var(--shadow-sm);
        }
        .btn-primary:hover { 
            background: var(--primary-hover); 
            transform: translateY(-1px);
            box-shadow: var(--shadow);
        }
        .btn-secondary { 
            background: white !important; border-color: #e2e8f0 !important; 
            color: #475569 !important; 
        }
        .btn-secondary:hover { background: #f8fafc !important; border-color: #cbd5e1 !important; color: #1e293b !important; }

        .card { 
            background: var(--bg-surface); 
            border: 1px solid var(--border); 
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
        }

        .form-input { 
            width: 100%; padding: 10px 14px; 
            border: 1px solid var(--border); border-radius: var(--radius-md); 
            font-size: 0.875rem; transition: all 0.2s;
            background: #ffffff;
        }
        .form-input:focus { 
            outline: none; border-color: var(--primary); 
            box-shadow: 0 0 0 3px rgba(0, 74, 173, 0.1); 
        }


        .error-text { color: var(--danger); font-size: 0.75rem; margin-top: 4px; display: none; }

        /* Toast Premium */
        #toast-container { position: fixed; top: 24px; right: 24px; z-index: 9999; display: flex; flex-direction: column; gap: 12px; pointer-events: none; }
        .toast { 
            position: relative; overflow: hidden; padding: 16px 20px; background: white; 
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -4px rgba(0,0,0,0.1), 0 0 0 1px rgba(0,0,0,0.05); 
            border-radius: 12px; animation: toastIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275); 
            width: 340px; pointer-events: auto; display: flex; align-items: center; gap: 14px;
        }
        .toast-icon { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .toast-content { flex: 1; }
        .toast-title { font-size: 0.875rem; font-weight: 700; color: var(--text-title); line-height: 1.2; }
        .toast-msg { font-size: 0.8125rem; color: var(--text-muted); margin-top: 2px; line-height: 1.4; }
        
        .toast.success .toast-icon { background: #ecfdf5; color: #10b981; }
        .toast.error .toast-icon { background: #fef2f2; color: #ef4444; }
        .toast.warning .toast-icon { background: #fffbeb; color: #f59e0b; }
        .toast.info .toast-icon { background: #eff6ff; color: #3b82f6; }

        .toast::after {
            content: ''; position: absolute; bottom: 0; left: 0; height: 3px; width: 100%;
            background: rgba(0,0,0,0.05);
        }
        .toast-progress {
            position: absolute; bottom: 0; left: 0; height: 3px; width: 100%;
            background: var(--primary); transform-origin: left;
            animation: toastProgress 3s linear forwards;
        }
        .toast.success .toast-progress { background: #10b981; }
        .toast.error .toast-progress { background: #ef4444; }

        .toast.hide { animation: toastOut 0.4s cubic-bezier(0.4, 0, 1, 1) forwards; }
        
        @keyframes toastIn { 
            from { transform: translateX(120%) scale(0.9); opacity: 0; } 
            to { transform: translateX(0) scale(1); opacity: 1; } 
        }
        @keyframes toastOut { 
            from { transform: translateX(0) scale(1); opacity: 1; } 
            to { transform: translateX(120%) scale(0.9); opacity: 0; } 
        }
        @keyframes toastProgress { from { transform: scaleX(1); } to { transform: scaleX(0); } }

        /* Modal Premium Animations */
        .modal-overlay { 
            position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
            background: rgba(15, 23, 42, 0.65); z-index: 1000; 
            display: none; align-items: center; justify-content: center; 
            backdrop-filter: blur(8px); padding: 20px;
            animation: fadeIn 0.3s ease;
        }
        .modal-content {
            background: white; border-radius: 16px; width: 100%; max-width: 650px;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
            animation: modalIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            overflow: hidden;
        }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes modalIn { 
            from { transform: translateY(30px) scale(0.95); opacity: 0; } 
            to { transform: translateY(0) scale(1); opacity: 1; } 
        }
        .modal-overlay.hide { animation: fadeOut 0.3s ease forwards; }
        .modal-overlay.hide .modal-content { animation: modalOut 0.3s ease forwards; }
        @keyframes fadeOut { from { opacity: 1; } to { opacity: 0; } }
        @keyframes modalOut { 
            from { transform: translateY(0) scale(1); opacity: 1; } 
            to { transform: translateY(20px) scale(0.98); opacity: 0; } 
        }

        /* Simplified Confirm Modal */
        #confirm-modal { z-index: 10001; }
        .confirm-card {
            background: white; border-radius: 20px; width: 360px; padding: 32px 24px;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
            animation: modalIn 0.3s cubic-bezier(0.2, 0.8, 0.2, 1);
            text-align: center;
        }
        .confirm-icon {
            width: 48px; height: 48px; background: #f8fafc; color: #64748b;
            border-radius: 14px; display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px; font-size: 20px;
        }
        .confirm-title { font-size: 1.125rem; font-weight: 800; color: #0f172a; margin-bottom: 8px; letter-spacing: -0.02em; }
        .confirm-msg { font-size: 0.875rem; color: #64748b; margin-bottom: 28px; line-height: 1.5; padding: 0 10px; }
        .confirm-actions { display: flex; gap: 10px; }
        .confirm-actions button { 
            flex: 1; padding: 12px; border-radius: 12px; font-weight: 700; 
            font-size: 0.875rem; cursor: pointer; transition: 0.2s; border: none; 
        }
        .btn-cancel { background: #f1f5f9; color: #475569; }
        .btn-confirm { background: #0f172a; color: white; }
        .btn-confirm.danger { background: #ef4444; }
        .btn-confirm:hover { filter: brightness(1.1); }
        
        #confirm-modal.hide { animation: fadeOut 0.2s ease forwards; }
        #confirm-modal.hide .confirm-card { animation: modalOut 0.2s ease forwards; }

        /* Global Loader */
        #global-loader { 
            position: fixed; top: 0; left: 0; width: 100%; height: 3px; 
            background: linear-gradient(to right, var(--primary), var(--accent)); 
            z-index: 9999; display: none; 
            box-shadow: 0 0 10px rgba(0, 74, 173, 0.3);
        }
    </style>
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
        axios.defaults.baseURL = '/api/'; // Menggunakan path relatif
        axios.defaults.headers.common['Accept'] = 'application/json';

        // 2. Request Interceptor (Auto Attach Token)
        axios.interceptors.request.use(config => {
            document.getElementById('global-loader').style.display = 'block';
            
            // Cek token di localStorage
            const token = localStorage.getItem('auth_token');
            if (token) {
                config.headers.Authorization = `Bearer ${token}`;
            }
            return config;
        });

        // 3. Response Interceptor (Error Handling)
        axios.interceptors.response.use(
            response => {
                document.getElementById('global-loader').style.display = 'none';
                return response;
            },
            error => {
                document.getElementById('global-loader').style.display = 'none';
                
                // Handle 401 Unauthorized
                if (error.response && error.response.status === 401) {
                    // Jangan redirect jika sedang di halaman login
                    if (!window.location.pathname.includes('/login')) {
                        localStorage.removeItem('auth_token');
                        localStorage.removeItem('user_role');
                        window.location.href = '/login';
                    }
                }
                
                return Promise.reject(error);
            }
        );

        // 4. Utils: Toast Notification Premium
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
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
            lucide.createIcons();
            
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

            titleEl.innerText = title;
            msgEl.innerText = message;
            btnOk.innerText = options.confirmText || 'Lanjutkan';
            btnOk.className = 'btn-confirm ' + (options.type === 'danger' ? 'danger' : '');
            
            iconWrap.className = 'confirm-icon';
            iconWrap.style.background = '#f8fafc';
            iconWrap.style.color = options.type === 'danger' ? '#ef4444' : '#004aad';
            iconWrap.innerHTML = `<i data-lucide="${options.icon || 'alert-circle'}" style="width:20px;height:20px;"></i>`;
            lucide.createIcons();

            modal.style.display = 'flex';
            modal.classList.remove('hide');

            return new Promise((resolve) => {
                confirmResolve = resolve;
            });
        }

        document.getElementById('confirmBtnOk').onclick = () => {
            closeConfirm(true);
        };
        document.getElementById('confirmBtnCancel').onclick = () => {
            closeConfirm(false);
        };

        function closeConfirm(result) {
            const modal = document.getElementById('confirm-modal');
            modal.classList.add('hide');
            setTimeout(() => {
                modal.style.display = 'none';
                confirmResolve(result);
            }, 200);
        }

        // 6. Utils: Format Number
        function formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        }
    </script>
    @stack('scripts')
</body>
</html>
