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
                const res = await axios.post('admin/login', payload);
                if(res.data.success) {
                    window.showToast('Otorisasi admin berhasil!', 'success');
                    localStorage.setItem('auth_token', res.data.data.token);
                    localStorage.setItem('user_role', res.data.data.role);
                    localStorage.setItem('user_name', res.data.data.name);
                    localStorage.setItem('kantor_cabang', res.data.data.kantor_cabang);
                    localStorage.setItem('kedeputian_wilayah', res.data.data.kedeputian_wilayah);
                    
                    let targetUrl = '/admin/dashboard';
                    if (res.data.data.role === 'petugas_keliling') targetUrl = '/admin/bpjs-keliling';
                    else if (res.data.data.role === 'petugas_pil') targetUrl = '/admin/pil';

                    setTimeout(() => {
                        window.location.href = targetUrl;
                    }, 1000);
                }
            } catch (error) {
                console.error('Admin Login Error:', error.response?.data);
                let errorMsg = 'Kredensial admin tidak valid.';
                if (error.response?.data?.errors) {
                    errorMsg = Object.values(error.response.data.errors).flat()[0];
                } else if (error.response?.data?.message) {
                    errorMsg = error.response.data.message;
                }
                window.showToast(errorMsg, 'error');
            }
        });
    }
});