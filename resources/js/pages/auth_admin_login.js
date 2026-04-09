document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('adminLoginForm');
    if (form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const payload = { 
                username: document.getElementById('username').value,
                password: document.getElementById('password').value 
            };

            try {
                const btn = document.querySelector('button[type="submit"]');
                if (btn) { btn.disabled = true; btn.dataset.oldText = btn.innerHTML; btn.innerHTML = 'Memproses...'; }
                
                // Hide error box on new attempt
                const errorBox = document.getElementById('login-error-box');
                if (errorBox) errorBox.style.display = 'none';

                const res = await window.axios.post('admin/login', payload);
                if(res.data.success) {
                    if (window.showToast) window.showToast('Otorisasi admin berhasil!', 'success');
                    localStorage.setItem('auth_token', res.data.data.token);
                    localStorage.setItem('user_role', res.data.data.role);
                    localStorage.setItem('user_name', res.data.data.name);
                    localStorage.setItem('kantor_cabang', res.data.data.kantor_cabang);
                    localStorage.setItem('kedeputian_wilayah', res.data.data.kedeputian_wilayah);
                    
                    let targetUrl = '/admin/dashboard';
                    if (res.data.data.role === 'petugas_keliling') targetUrl = '/admin/bpjs-keliling/dashboard';
                    else if (res.data.data.role === 'petugas_pil') targetUrl = '/admin/pil/dashboard';

                    setTimeout(() => {
                        window.location.href = targetUrl;
                    }, 1000);
                }
            } catch (error) {
                const btn = document.querySelector('button[type="submit"]');
                if (btn) { btn.disabled = false; btn.innerHTML = btn.dataset.oldText || 'Masuk Dashboard'; }
                
                console.error('Admin Login Error:', error.response?.data);
                let errorMsg = 'Kredensial admin tidak valid.';
                if (error.response?.data?.errors) {
                    errorMsg = Object.values(error.response.data.errors).flat()[0];
                } else if (error.response?.data?.message) {
                    errorMsg = error.response.data.message;
                }
                
                // Show in alert box
                const errorBox = document.getElementById('login-error-box');
                const errorMsgEl = document.getElementById('login-error-msg');
                if (errorBox && errorMsgEl) {
                    errorMsgEl.innerText = errorMsg;
                    errorBox.style.display = 'block';
                    if(window.lucide) window.lucide.createIcons();
                }

                if (window.showToast) window.showToast(errorMsg, 'error');
            }
        });
    }
});