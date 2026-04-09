@extends('layouts.app')

@section('title', 'Pengaturan Akun - Garda JKN')

@section('content')

<div class="admin-layout">
    <aside class="sidebar">
        <div class="sb-brand">
            <div class="sb-brand-name">Garda JKN</div>
            <div class="sb-brand-sub" id="sb-portal-name">Portal Anggota</div>
        </div>
        <div class="sb-avatar-card">
            <div class="sb-avatar-wrap" id="sb-avatar-wrap">
                <i data-lucide="user" style="width:32px;height:32px;color:rgba(255,255,255,0.5);"></i>
            </div>
            <div class="sb-name" id="sb-user-name">Memuat...</div>
            <div class="sb-nik" id="sidebarNik">...</div>
            <div id="sb-status-badge"></div>
        </div>
        <nav class="sb-menu" id="nav-links">
            <!-- Nav links injected by JS -->
        </nav>
        <div class="sb-footer">
            <div class="sb-section-label" style="margin-top:0;">Pengaturan</div>
            <a href="/settings" class="sb-link active">
                <i data-lucide="settings" style="width: 16px; height: 16px;"></i> Pengaturan Akun
            </a>
            <a href="javascript:void(0)" onclick="logout()" class="sb-link logout-link">
                <i data-lucide="log-out" class="sb-link-icon"></i> <span>Keluar Sesi</span>
            </a>
        </div>
    </aside>

    <main class="main-body">
        <header class="top-header">
            <div style="font-weight: 600; color: #1e293b; font-size: 1rem;" id="top-title">Pengaturan Akun</div>
            <div id="user-info-header" style="display: flex; align-items: center; gap: 12px;">
                <span id="date-now" style="font-size: 0.75rem; color: #94a3b8; font-weight: 500;"></span>
                <div id="user-initials" style="width: 32px; height: 32px; background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem;">...</div>
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

<!-- Success Animation Modal -->
<div id="successModal" class="modal-backdrop" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15,23,42,0.6); z-index:9999; align-items:center; justify-content:center; backdrop-filter: blur(4px);">
    <div style="background: white; width:400px; padding:40px; border-radius: 24px; text-align:center; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); transform: scale(0.9); transition: 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);" id="successCard">
        <div style="width: 80px; height: 80px; background: #ecfdf5; color: #10b981; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
            <i data-lucide="check-circle" style="width: 48px; height: 48px; stroke-width: 2.5;"></i>
        </div>
        <h3 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin-bottom: 8px;">Berhasil!</h3>
        <p style="color: #64748b; font-size: 0.95rem; margin-bottom: 24px; line-height: 1.5;">Kata sandi Anda telah berhasil diperbarui dengan aman.</p>
        <button onclick="closeSuccessModal()" style="width: 100%; padding: 14px; background: #0f172a; color: white; border: none; border-radius: 12px; font-weight: 700; cursor: pointer; transition: 0.2s;">Selesai</button>
    </div>
</div>

@endsection

@push('scripts')
@vite(['resources/css/pages/common_settings.css', 'resources/js/pages/common_settings.js'])
@endpush

@push("scripts")
<script>
    window.sessionSuccess = "{{ session("success") }}";
    window.sessionError = "{{ session("error") }}";
</script>
@endpush
