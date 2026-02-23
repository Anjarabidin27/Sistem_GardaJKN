@extends('layouts.app')

@section('title', 'Portal Keanggotaan - Garda JKN')

@section('content')
<style>
    .split-layout {
        display: flex;
        min-height: 100vh;
        background: white;
    }

    /* Left Side: Brand/Visual */
    .brand-side {
        flex: 1;
        background: linear-gradient(135deg, #004aad 0%, #002d6a 100%);
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 80px;
        color: white;
        position: relative;
        overflow: hidden;
    }
    
    .brand-side::before {
        content: '';
        position: absolute;
        top: -10%; right: -10%;
        width: 400px; height: 400px;
        background: rgba(255,255,255,0.03);
        border-radius: 50%;
    }

    .brand-title { font-size: 2.5rem; font-weight: 800; letter-spacing: -1px; margin-bottom: 16px; }
    .brand-subtitle { font-size: 1.125rem; opacity: 0.8; line-height: 1.6; max-width: 480px; }

    /* Right Side: Form */
    .form-side {
        width: 520px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 80px;
        background: #fdfdfd;
    }

    .form-container { width: 100%; max-width: 360px; margin: 0 auto; }

    .welcome-text { margin-bottom: 32px; }
    .welcome-text h2 { font-size: 1.5rem; font-weight: 700; color: var(--text-title); margin-bottom: 8px; }
    .welcome-text p { color: var(--text-muted); font-size: 0.875rem; }

    .role-nav {
        display: flex;
        gap: 8px;
        background: #f1f5f9;
        padding: 4px;
        border-radius: 10px;
        margin-bottom: 24px;
    }
    .role-nav button {
        flex: 1;
        padding: 10px;
        border: none;
        background: none;
        font-size: 0.875rem;
        font-weight: 600;
        border-radius: 8px;
        cursor: pointer;
        transition: 0.2s;
        color: var(--text-muted);
    }
    .role-nav button.active {
        background: white;
        color: var(--primary);
        box-shadow: var(--shadow-sm);
    }

    .input-group { margin-bottom: 20px; }
    .label { display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 8px; color: var(--text-title); }

    @media (max-width: 1024px) {
        .brand-side { display: none; }
        .form-side { width: 100%; padding: 40px; }
    }
</style>

<div class="split-layout">
    <!-- Left Section -->
    <div class="brand-side">
        <div class="brand-title">Garda JKN</div>
        <p class="brand-subtitle">Sistem Informasi Pengelolaan Database dan Keanggotaan Nasional. Keamanan data dan integritas informasi tingkat tinggi.</p>
        
        <div style="margin-top: 60px; display: flex; gap: 40px;">
            <div>
                <div style="font-size: 1.5rem; font-weight: 700;">34</div>
                <div style="font-size: 0.875rem; opacity: 0.7;">Provinsi</div>
            </div>
            <div>
                <div style="font-size: 1.5rem; font-weight: 700;">Data</div>
                <div style="font-size: 0.875rem; opacity: 0.7;">Terverifikasi</div>
            </div>
        </div>
    </div>

    <!-- Right Section -->
    <div class="form-side">
        <div class="form-container">
            <div class="welcome-text">
                <h2>Selamat Datang</h2>
                <p>Silakan masuk ke akun Anda untuk melanjutkan.</p>
            </div>

            <div class="role-nav">
                <button id="btn-member" class="active" onclick="switchRole('member')">Anggota</button>
                <button id="btn-admin" onclick="switchRole('admin')">Admin</button>
            </div>

            <form id="loginForm">
                <div class="input-group">
                    <label class="label" id="identityLabel">NIK Anggota (16 Digit)</label>
                    <input type="text" id="identity" class="form-input" placeholder="Contoh: 3171..." required>
                </div>
                
                <div class="input-group">
                    <label class="label">Kata Sandi</label>
                    <input type="password" id="password" class="form-input" placeholder="Masukkan password" required>
                </div>

                <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
                    <label style="font-size: 0.75rem; color: var(--text-muted); display: flex; align-items: center; gap: 6px; cursor: pointer;">
                        <input type="checkbox"> Ingat saya
                    </label>
                    <a href="#" style="font-size: 0.75rem; color: var(--primary); font-weight: 600; text-decoration: none;">Lupa password?</a>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    Masuk ke Sistem
                </button>


            </form>

            <div style="margin-top: 40px; text-align: center; font-size: 0.75rem; color: var(--text-muted);">
                &copy; 2026 BPJS Kesehatan Garda JKN. Seluruh Hak Cipta Dilindungi.
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let role = 'member';

    function switchRole(newRole) {
        role = newRole;
        document.getElementById('btn-member').classList.toggle('active', role === 'member');
        document.getElementById('btn-admin').classList.toggle('active', role === 'admin');
        
        const label = document.getElementById('identityLabel');
        const input = document.getElementById('identity');
        
        if(role === 'member') {
            label.innerText = 'NIK Anggota (16 Digit)';
            input.placeholder = 'Contoh: 3171...';
        } else {
            label.innerText = 'Username Admin';
            input.placeholder = 'Masukkan username';
        }
    }

    document.getElementById('loginForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const payload = { password: document.getElementById('password').value };
        let endpoint = '';

        if(role === 'member') {
            payload.nik = document.getElementById('identity').value;
            endpoint = 'member/login';
        } else {
            payload.username = document.getElementById('identity').value;
            endpoint = 'admin/login';
        }

        try {
            const res = await axios.post(endpoint, payload);
            if(res.data.success) {
                showToast('Login berhasil, mengalihkan...', 'success');
                localStorage.setItem('auth_token', res.data.data.token);
                localStorage.setItem('user_role', role);
                setTimeout(() => {
                    window.location.href = (role === 'admin') ? '/admin/dashboard' : '/member/profile';
                }, 1000);
            }
        } catch (error) {
            showToast(error.response?.data?.message || 'Identitas atau password salah.', 'error');
        }
    });
</script>
@endpush