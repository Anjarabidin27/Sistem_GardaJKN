@extends('layouts.app')

@section('title', 'Portal Keanggotaan - Garda JKN')

@section('content')


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
                <button id="btn-pengurus" onclick="switchRole('pengurus')">Pengurus</button>
            </div>

            <form id="loginForm">
                <div class="auth-group" style="margin-bottom: 24px;">
                    <label class="label" id="identityLabel">NIK Anggota (16 Digit)</label>
                    <input type="text" id="identity" class="form-input" placeholder="3324560004677870" required>
                </div>
                
                <div class="auth-group" style="margin-bottom: 32px;">
                    <label class="label">Kata Sandi</label>
                    <div class="input-group-password" style="position: relative; width: 100%;">
                        <input type="password" id="password" class="form-input" placeholder="Masukkan password" required>
                        <button type="button" class="password-toggle-btn" onclick="togglePassword('password')" tabindex="-1">
                            <span id="icon-password" style="display: flex;">
                                <i data-lucide="eye"></i>
                            </span>
                        </button>
                    </div>
                </div>

                <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; width: 100%;">
                    <label style="font-size: 0.75rem; color: var(--text-muted); display: flex; align-items: center; gap: 6px; cursor: pointer;">
                        <input type="checkbox"> Ingat saya
                    </label>
                    <a href="#" style="font-size: 0.75rem; color: var(--primary); font-weight: 600; text-decoration: none;">Lupa password?</a>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    Masuk ke Sistem
                </button>

                <div style="margin-top: 24px; text-align: center; font-size: 0.875rem;">
                    <span style="color: var(--text-muted);">Belum punya akun?</span>
                    <a href="{{ route('register') }}" style="color: var(--primary); font-weight: 600; text-decoration: none; margin-left: 4px;">Daftar di sini</a>
                </div>
            </form>

            <div style="margin-top: 40px; text-align: center; font-size: 0.75rem; color: var(--text-muted);">
                &copy; 2026 BPJS Kesehatan Garda JKN. Seluruh Hak Cipta Dilindungi.
            </div>
        </div>
    </div>
</div>
@endsection
<style>
    .input-group-password { position: relative !important; width: 100% !important; display: block !important; }
    .password-toggle-btn { 
        position: absolute !important; 
        right: 4px !important; 
        top: 50% !important; 
        transform: translateY(-50%) !important; 
        background: transparent !important; 
        border: none !important; 
        z-index: 100 !important; cursor: pointer;
    }
</style>
@push('scripts')
@vite(['resources/css/pages/auth_login.css', 'resources/js/pages/auth_login.js'])


@endpush
@push("scripts")
<script>
    window.sessionSuccess = "{{ session("success") }}";
    window.sessionError = "{{ session("error") }}";
</script>
@endpush
