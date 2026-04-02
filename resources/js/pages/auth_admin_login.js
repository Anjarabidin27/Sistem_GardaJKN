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
                    localStorage.setItem('user_role', 'admin');
                    localStorage.setItem('user_name', 'Administrator');
                    
                    setTimeout(() => {
                        window.location.href = '/admin/dashboard';
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