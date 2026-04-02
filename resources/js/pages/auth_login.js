let role = 'member';

    function switchRole(newRole) {
        role = newRole;
        document.getElementById('btn-member').classList.toggle('active', role === 'member');
        document.getElementById('btn-pengurus').classList.toggle('active', role === 'pengurus');
        
        const label = document.getElementById('identityLabel');
        if (label) {
            label.innerText = (role === 'member') ? 'NIK Anggota (16 Digit)' : 'NIK Pengurus (16 Digit)';
        }
    }

    // Pastikan fungsi tersedia secara global untuk onclick di HTML
    window.switchRole = switchRole;

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('loginForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const payload = { 
            nik: document.getElementById('identity').value,
            password: document.getElementById('password').value 
        };

        try {
            const btn = document.querySelector('#btn-login') || document.querySelector('button[type="submit"]');
            if (btn) { btn.disabled = true; btn.innerText = 'Memproses...'; }
            
            const errDiv = document.getElementById('login-error-msg');
            if (errDiv) errDiv.style.display = 'none';

            const res = await axios.post('member/login', payload);
            if(res.data.success) {
                const userData = res.data.data;
                const memberRole = userData.member.role;

                if (role === 'pengurus' && memberRole !== 'pengurus') {
                    window.showToast('Maaf, NIK Anda belum terdaftar sebagai Pengurus JKN.', 'error');
                    if (errDiv) { errDiv.innerText = 'Maaf, NIK Anda belum terdaftar sebagai Pengurus JKN.'; errDiv.style.display = 'block'; }
                    if (btn) { btn.disabled = false; btn.innerText = 'Masuk ke Sistem'; }
                    return;
                }

                localStorage.setItem('auth_token', userData.token);
                localStorage.setItem('user_role', (role === 'pengurus') ? 'pengurus' : 'member');
                localStorage.setItem('user_name', userData.member.name);
                
                window.showToast('Login berhasil, mengalihkan...', 'success');
                
                setTimeout(() => {
                    if (role === 'pengurus') {
                        window.location.href = '/pengurus/dashboard';
                    } else {
                        window.location.href = '/member/profile';
                    }
                }, 1000);
            }
        } catch (error) {
            const btn = document.querySelector('#btn-login') || document.querySelector('button[type="submit"]');
            if (btn) { btn.disabled = false; btn.innerText = 'Masuk ke Sistem'; }

            console.error('Login Error:', error.response?.data);
            let errorMsg = 'Identitas atau password salah.';
            if (error.response?.data?.errors) {
                errorMsg = Object.values(error.response.data.errors).flat()[0];
            } else if (error.response?.data?.message) {
                errorMsg = error.response.data.message;
            }
            // Fire Toast
            if(typeof window.showToast === 'function') window.showToast(errorMsg, 'error');
            
            // Fire direct UI error message
            const errDiv = document.getElementById('login-error-msg');
            if (errDiv) {
                errDiv.innerText = errorMsg;
                errDiv.style.display = 'block';
            }
        }
    });
});