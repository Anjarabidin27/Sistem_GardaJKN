import axios from 'axios';

document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('petugasLoginForm');
    const submitBtn = document.getElementById('submitBtn');
    const errorBox = document.getElementById('error-box');

    if (!loginForm) return;

    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        // Reset state
        errorBox.style.display = 'none';
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="loading-spinner"></span> Memverifikasi...';

        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;

        try {
            const response = await axios.post('/admin/login', {
                username: username,
                password: password
            });

            if (response.data.success) {
                const data = response.data.data;
                
                // Store in localStorage for across-page access
                localStorage.setItem('auth_token', data.token);
                localStorage.setItem('user_role', data.role);
                localStorage.setItem('user_name', data.name);
                localStorage.setItem('kantor_cabang', data.kantor_cabang);
                localStorage.setItem('kedeputian_wilayah', data.kedeputian_wilayah);

                // Determine redirection Target based on role
                let targetUrl = '/admin/dashboard';
                
                if (data.role === 'petugas_pil') {
                    targetUrl = '/admin/pil/dashboard';
                } else if (data.role === 'petugas_keliling') {
                    targetUrl = '/admin/bpjs-keliling/dashboard';
                }

                submitBtn.innerHTML = 'Berhasil! Redirecting...';
                window.location.href = targetUrl;
            }
        } catch (error) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Masuk Sekarang <i data-lucide="arrow-right" style="width: 20px; height: 20px;"></i>';
            if(window.lucide) window.lucide.createIcons();

            let message = 'Terjadi kesalahan sistem. Silakan coba lagi.';
            if (error.response && error.response.data) {
                message = error.response.data.message;
            }
            
            errorBox.innerText = message;
            errorBox.style.display = 'block';
        }
    });
});
