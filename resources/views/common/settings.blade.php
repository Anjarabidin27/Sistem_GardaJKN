@extends('layouts.app')

@section('title', 'Pengaturan Akun - Garda JKN')



@section('content')
<style>
    /* Force Layout Bases */
    .admin-layout { display: flex !important; min-height: 100vh !important; background: #f8fafc !important; }
    .sidebar { width: 280px !important; background: #004aad !important; color: white !important; display: flex !important; flex-direction: column !important; position: fixed !important; height: 100vh !important; z-index: 100 !important; overflow: hidden !important; border: none !important; }
    .sb-brand { padding: 28px 28px 10px; flex-shrink: 0; }
    .sb-brand-name { font-size: 1.1rem !important; font-weight: 800 !important; color: white !important; letter-spacing: 0.02em; }
    .sb-brand-sub { font-size: 0.75rem !important; color: rgba(255,255,255,0.6) !important; font-weight: 500; margin-top: 4px; }
    .sb-user-card { padding: 10px 28px 20px; flex-shrink: 0; }
    .sb-avatar { width: 52px !important; height: 52px !important; border-radius: 14px; background: rgba(255,255,255,0.15); border: 2px solid rgba(255,255,255,0.25); display: flex !important; align-items: center !important; justify-content: center !important; margin-bottom: 12px; overflow: hidden; }
    .sb-user-name { font-size: 0.95rem !important; font-weight: 800 !important; color: white !important; margin-bottom: 4px; }
    .sb-user-role { font-size: 0.7rem !important; color: rgba(255,255,255,0.5) !important; text-transform: uppercase; letter-spacing: 0.05em; }
    .sb-menu { padding: 16px 12px !important; flex: 1; overflow-y: auto !important; }
    .sb-link { display: flex !important; align-items: center !important; gap: 12px; padding: 12px 16px; border-radius: 10px; color: rgba(255,255,255,0.7) !important; text-decoration: none !important; font-weight: 600; font-size: 0.875rem; transition: 0.2s; }
    .sb-link:hover { background: rgba(255,255,255,0.1); color: white !important; }
    .sb-link.active { background: #ffffff15; color: white !important; }
    .sb-footer { padding: 20px 12px; border-top: 1px solid rgba(255,255,255,0.08); }
    .sb-section-label { font-size:0.6rem; font-weight:800; color:rgba(255,255,255,0.3); text-transform:uppercase; padding:0 16px; margin:16px 0 8px; }

    .main-body { margin-left: 280px !important; flex: 1 !important; min-width: 0 !important; }
    .top-header { height: 64px !important; background: white !important; border-bottom: 1px solid #e2e8f0 !important; padding: 0 32px !important; display: flex !important; align-items: center !important; justify-content: space-between !important; position: sticky; top: 0; z-index: 50; }
    .view-container { padding: 32px !important; }

    .settings-card { background: white !important; border: 1px solid #e2e8f0 !important; border-radius: 16px !important; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); max-width: 700px; margin: 0 auto; }
    .settings-header { padding: 24px 32px; border-bottom: 1px solid #f1f5f9; }
    .settings-body { padding: 32px; }

    .form-group { margin-bottom: 24px; }
    .form-label { display: block; font-size: 0.7rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px; }
    .form-input { width: 100%; padding: 12px 16px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 0.95rem; transition: 0.2s; background: #f8fafc; }
    .form-input:focus { border-color: #004aad; background: white; box-shadow: 0 0 0 4px rgba(0,74,173,0.1); outline: none; }

    .btn-save { background: #004aad; color: white; border: none; padding: 14px 28px; border-radius: 12px; font-weight: 800; font-size: 0.9rem; cursor: pointer; transition: 0.2s; display: inline-flex; align-items: center; gap: 8px; }
    .btn-save:hover { background: #003a8a; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,74,173,0.2); }
    .btn-save:disabled { background: #94a3b8; cursor: not-allowed; transform: none; box-shadow: none; }
</style>

<div class="admin-layout">
    <aside class="sidebar">
        <div class="sb-brand">
            <div class="sb-brand-name">Garda JKN</div>
        </div>
        <div class="sb-user-card">
            <div class="sb-avatar" id="sb-avatar-wrap"><span id="sb-initials">A</span></div>
            <div class="sb-user-name" id="sb-user-name">Administrator</div>
        </div>
        <nav class="sb-menu" id="nav-links">
            <!-- Nav links injected by JS -->
        </nav>
        <div class="sb-footer">
            <div class="sb-section-label" style="margin-top:0;">Pengaturan</div>
            <a href="/settings" class="sb-link active"><i data-lucide="settings" style="width: 16px; height: 16px;"></i> Pengaturan Akun</a>
            <a href="#" class="sb-link" onclick="logout()" style="color:#fca5a5;margin-top:4px;"><i data-lucide="log-out" style="width: 16px; height: 16px; color:#fca5a5;"></i> Keluar Sesi</a>
        </div>
    </aside>

    <main class="main-body">
        <header class="top-header">
            <div style="font-weight: 600; color: #1e293b; font-size: 1rem;">Pengaturan Akun</div>
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

<style>
    #successCard.show { transform: scale(1) !important; }
</style>

<style>
    @keyframes checkPop {
        0% { transform: scale(0); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }
    #successCard.show { transform: scale(1); }
</style>
@endsection

@push('scripts')
<script>
    const token = localStorage.getItem('auth_token');
    const role = localStorage.getItem('user_role');
    
    if (!token) window.location.href = '/login';

    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('date-now').innerText = new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        loadNav();
    });

    function loadNav() {
        const navLinks = document.getElementById('nav-links');
        const role = localStorage.getItem('user_role');
        const portalName = document.getElementById('sb-portal-name');
        const roleDisplay = document.getElementById('sb-user-role');
        
        let links = "";
        if (role === 'admin') {
            if (portalName) portalName.innerText = "Portal Admin";
            if (roleDisplay) roleDisplay.innerText = "Administrator Garda JKN";
            links = `
                <div class="sb-section-label">Menu</div>
                <a href="/admin/dashboard" class="sb-link"><i data-lucide="layout-dashboard" style="width: 16px; height: 16px;"></i> Dashboard</a>
                <a href="/admin/members" class="sb-link"><i data-lucide="users" style="width: 16px; height: 16px;"></i> Manajemen Anggota</a>
                <a href="/admin/approvals" class="sb-link"><i data-lucide="user-check" style="width: 16px; height: 16px;"></i> Persetujuan Pengurus</a>
                <a href="/admin/informations" class="sb-link"><i data-lucide="megaphone" style="width: 16px; height: 16px;"></i> Informasi</a>
                <a href="/admin/audit-logs" class="sb-link"><i data-lucide="file-clock" style="width: 16px; height: 16px;"></i> Log Audit</a>
            `;
        } else if (role === 'pengurus') {
            if (portalName) portalName.innerText = "Portal Pengurus";
            if (roleDisplay) roleDisplay.innerText = "Pengurus Garda JKN";
            links = `
                <div class="sb-section-label">Menu</div>
                <a href="/pengurus/dashboard" class="sb-link"><i data-lucide="layout-dashboard" style="width: 16px; height: 16px;"></i> Dashboard</a>
                <a href="/pengurus/members" class="sb-link"><i data-lucide="users" style="width: 16px; height: 16px;"></i> Anggota Wilayah</a>
                <a href="/pengurus/informations" class="sb-link"><i data-lucide="megaphone" style="width: 16px; height: 16px;"></i> Informasi</a>
            `;
        } else {
            if (portalName) portalName.innerText = "Portal Anggota";
            if (roleDisplay) roleDisplay.innerText = "Anggota Garda JKN";
            links = `
                <div class="sb-section-label">Menu</div>
                <a href="/member/profile" class="sb-link"><i data-lucide="user-circle" style="width:16px;height:16px;"></i> Profil Saya</a>
                <a href="/member/profile#informasi" class="sb-link"><i data-lucide="megaphone" style="width:16px;height:16px;"></i> Informasi</a>
            `;
        }
        navLinks.innerHTML = links;
        lucide.createIcons();
    }

    document.getElementById('passwordForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = document.getElementById('btnSubmit');
        const originalText = btn.innerHTML;

        const payload = {
            current_password: document.getElementById('current_password').value,
            new_password: document.getElementById('new_password').value,
            new_password_confirmation: document.getElementById('new_password_confirmation').value
        };

        btn.disabled = true;
        btn.innerHTML = '<i data-lucide="loader-2" class="spin" style="width:18px;height:18px;"></i> Memproses...';
        lucide.createIcons();

        try {
            await axios.post('settings/change-password', payload);
            openSuccessModal();
            document.getElementById('passwordForm').reset();
        } catch (error) {
            let msg = 'Gagal mengubah kata sandi.';
            if (error.response?.data?.errors) {
                msg = Object.values(error.response.data.errors).flat().join(' ');
            } else if (error.response?.data?.message) {
                msg = error.response.data.message;
            }
            showToast(msg, 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalText;
            lucide.createIcons();
        }
    });

    function openSuccessModal() {
        const modal = document.getElementById('successModal');
        const card = document.getElementById('successCard');
        modal.style.display = 'flex';
        setTimeout(() => { card.classList.add('show'); }, 10);
        lucide.createIcons();
    }

    function closeSuccessModal() {
        const modal = document.getElementById('successModal');
        const card = document.getElementById('successCard');
        card.classList.remove('show');
        setTimeout(() => { modal.style.display = 'none'; }, 300);
    }

    // Logout and initGlobalSidebar are now global in app.blade.php
</script>
@endpush

