@extends('layouts.app')

@section('title', 'Pengaturan Akun - Pengurus Garda JKN')

@push('styles')
    @vite(['resources/css/pages/pengurus_dashboard.css', 'resources/css/pages/common_settings.css'])
@endpush

@section('content')
<div class="admin-layout">
    <aside class="sidebar">
        <div class="sb-brand">
            <div class="sb-brand-name">Garda JKN</div>
        </div>
        <div class="sb-user-card">
            <div class="sb-avatar" id="sb-avatar-wrap"><span id="sb-initials">A</span></div>
            <div class="sb-user-name" id="sb-user-name">Administrator</div>
        </div>
        <nav class="sb-menu">
            <div class="sb-section-label">Menu</div>
            <a href="/pengurus/dashboard" class="sb-link"><i data-lucide="layout-dashboard" style="width:16px;height:16px;"></i> Dashboard</a>
            <a href="/pengurus/members" class="sb-link"><i data-lucide="users" style="width:16px;height:16px;"></i> Anggota Wilayah</a>
            <a href="/pengurus/informations" class="sb-link"><i data-lucide="megaphone" style="width:16px;height:16px;"></i> Informasi</a>
        </nav>
        <div class="sb-footer">
            <div class="sb-section-label" style="margin-top:0;margin-bottom:8px;">Pengaturan</div>
            <a href="/pengurus/settings" class="sb-link active"><i data-lucide="settings" style="width:16px;height:16px;"></i> Pengaturan Akun</a>
            <a href="javascript:void(0)" onclick="logout()" class="sb-link logout-link">
                <i data-lucide="log-out" class="sb-link-icon"></i> <span>Keluar Sesi</span>
            </a>
        </div>
    </aside>

    <main class="main-body">
        <header class="top-header">
            <div style="font-weight: 600; color: #1e293b; font-size: 1rem;">Pengaturan Akun</div>
            <div style="display: flex; align-items: center; gap: 12px;">
                <span id="date-now" style="font-size: 0.75rem; color: #94a3b8; font-weight: 500;"></span>
                <div id="user-initials" style="width: 32px; height: 32px; background: #eff6ff; color: #004aad; border: 1px solid #dbeafe; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem;">...</div>
            </div>
        </header>

        <div class="view-container">
            <div class="settings-card" style="margin-top: 20px;">
                <div class="settings-header">
                    <h2 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0;">Keamanan & Password</h2>
                    <p style="font-size: 0.85rem; color: #64748b; margin-top: 4px;">Perbarui kata sandi Anda secara berkala untuk menjaga keamanan akun.</p>
                </div>
                <div class="settings-body">
                    <form id="passwordForm">
                        <div class="form-group">
                            <label class="form-label">Kata Sandi Saat Ini</label>
                            <div style="position: relative;">
                                <input type="password" id="current_password" class="form-input" placeholder="Masukkan password sekarang" required>
                                <button type="button" onclick="togglePassword('current_password')" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); border: none; background: none; color: #64748b; padding: 4px;" id="icon-current_password">
                                    <i data-lucide="eye" style="width: 18px; height: 18px;"></i>
                                </button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kata Sandi Baru</label>
                            <div style="position: relative;">
                                <input type="password" id="new_password" class="form-input" placeholder="Minimal 8 karakter" required>
                                <button type="button" onclick="togglePassword('new_password')" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); border: none; background: none; color: #64748b; padding: 4px;" id="icon-new_password">
                                    <i data-lucide="eye" style="width: 18px; height: 18px;"></i>
                                </button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Konfirmasi Kata Sandi Baru</label>
                            <div style="position: relative;">
                                <input type="password" id="new_password_confirmation" class="form-input" placeholder="Ulangi password baru" required>
                                <button type="button" onclick="togglePassword('new_password_confirmation')" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); border: none; background: none; color: #64748b; padding: 4px;" id="icon-new_password_confirmation">
                                    <i data-lucide="eye" style="width: 18px; height: 18px;"></i>
                                </button>
                            </div>
                        </div>
                        <div style="display: flex; justify-content: flex-end; margin-top: 32px;">
                            <button type="submit" class="btn-save" id="btnSubmit">
                                <i data-lucide="save" style="width:18px;height:18px;"></i> Update Kata Sandi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Success Modal -->
<div id="successModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15,23,42,0.6); z-index:9999; align-items:center; justify-content:center; backdrop-filter: blur(4px);">
    <div style="background: white; width:400px; padding:40px; border-radius: 24px; text-align:center;">
        <div style="width: 80px; height: 80px; background: #ecfdf5; color: #10b981; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
            <i data-lucide="check-circle" style="width: 48px; height: 48px;"></i>
        </div>
        <h3 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin-bottom: 8px;">Berhasil!</h3>
        <p style="color: #64748b; font-size: 0.95rem; margin-bottom: 24px;">Kata sandi Anda telah diperbarui.</p>
        <button onclick="closeSuccessModal()" style="width: 100%; padding: 14px; background: #0f172a; color: white; border: none; border-radius: 12px; font-weight: 700;">Selesai</button>
    </div>
</div>

@endsection

@push('scripts')
    @vite(['resources/js/pages/common_settings.js'])
@endpush

@push("scripts")
<script>
    window.sessionSuccess = "{{ session("success") }}";
    window.sessionError = "{{ session("error") }}";
</script>
@endpush
