let role = 'member';

    function switchRole(newRole) {
        role = newRole;
        document.getElementById('btn-member').classList.toggle('active', role === 'member');
        document.getElementById('btn-pengurus').classList.toggle('active', role === 'pengurus');
        document.getElementById('btn-petugas').classList.toggle('active', role === 'petugas');
        
        const label = document.getElementById('identityLabel');
        if (label) {
            if (role === 'member') label.innerText = 'NIK Anggota (16 Digit)';
            else if (role === 'pengurus') label.innerText = 'NIK Pengurus (16 Digit)';
            else label.innerText = 'NIK Petugas / Username';
        }
    }

    // Pastikan fungsi tersedia secara global untuk onclick di HTML
    window.switchRole = switchRole;

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('loginForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const identity = document.getElementById('identity').value;
        const password = document.getElementById('password').value;

        try {
            const btn = document.querySelector('#btn-login') || document.querySelector('button[type="submit"]');
            if (btn) { btn.disabled = true; btn.dataset.oldText = btn.innerHTML; btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Memproses...'; }
            
            const errDiv = document.getElementById('login-error-msg');
            if (errDiv) errDiv.style.display = 'none';

            // Branch endpoint based on role
            let endpoint = 'member/login';
            let payload = { nik: identity, password: password };

            if (role === 'petugas') {
                endpoint = 'admin/login'; // Petugas matches against admin_users
                payload = { username: identity, password: password };
            }

            const res = await window.axios.post(endpoint, payload);
            
            if(res.data.success) {
                const userData = res.data.data;
                
                // Redirection targets
                let targetUrl = '/member/profile';
                if (role === 'pengurus') targetUrl = '/pengurus/dashboard';
                else if (role === 'petugas') {
                    targetUrl = '/admin/dashboard';
                    if (userData.role === 'petugas_pil') targetUrl = '/admin/pil/dashboard';
                    else if (userData.role === 'petugas_keliling') targetUrl = '/admin/bpjs-keliling/dashboard';
                }

                // Security check for Pengurus role
                if (role === 'pengurus' && userData.member.role !== 'pengurus') {
                    const msg = 'Maaf, NIK Anda belum terdaftar sebagai Pengurus JKN.';
                    throw new Error(msg);
                }

                // Save Auth
                localStorage.setItem('auth_token', userData.token);
                localStorage.setItem('user_role', userData.role || (role === 'pengurus' ? 'pengurus' : 'member'));
                localStorage.setItem('user_name', userData.name || userData.member.name);
                
                // Save region context for sidebar/reporting
                if (userData.kantor_cabang) localStorage.setItem('kantor_cabang', userData.kantor_cabang);
                if (userData.kedeputian_wilayah) localStorage.setItem('kedeputian_wilayah', userData.kedeputian_wilayah);
                
                if (window.showToast) window.showToast('Login berhasil, mengalihkan...', 'success');
                
                setTimeout(() => {
                    window.location.href = targetUrl;
                }, 1000);
            }
        } catch (error) {
            const btn = document.querySelector('#btn-login') || document.querySelector('button[type="submit"]');
            if (btn) { btn.disabled = false; btn.innerHTML = btn.dataset.oldText || 'Masuk ke Sistem'; }

            console.error('Login Error Full:', error);
            
            let errorMsg = 'Identitas atau password salah. Silakan coba lagi.';
            
            // Extract error message from API response
            if (error.response && error.response.data) {
                const data = error.response.data;
                if (data.errors) {
                    errorMsg = Object.values(data.errors).flat()[0];
                } else if (data.message) {
                    errorMsg = data.message;
                }
            } else if (error.message) {
                errorMsg = error.message;
            }

            // Fire Toast (Utama)
            if (window.showToast) {
                window.showToast(errorMsg, 'error');
            } else {
                alert(errorMsg); // Fallback jika toast gagal
            }
            
            // Fire direct UI error message (Secondary)
            const errDiv = document.getElementById('login-error-msg');
            if (errDiv) {
                errDiv.innerText = errorMsg;
                errDiv.style.display = 'block';
            }
        }
    });
});