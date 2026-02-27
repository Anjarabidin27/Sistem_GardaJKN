@extends('layouts.app')

@section('title', 'Data Anggota Wilayah - Pengurus Garda JKN')



@section('content')
<style>
    /* Force Layout Bases */
    .admin-layout { display: flex !important; min-height: 100vh !important; background: #f8fafc !important; }
    .sidebar { width: 280px !important; background: #004aad !important; color: white !important; display: flex !important; flex-direction: column !important; position: fixed !important; height: 100vh !important; z-index: 100 !important; overflow: hidden !important; border: none !important; }
    .sb-brand { padding: 28px 28px 10px; flex-shrink: 0; }
    .sb-brand-name { font-size: 1.1rem !important; font-weight: 800 !important; color: white !important; letter-spacing: 0.02em; }
    .sb-brand-sub { font-size: 0.75rem !important; color: rgba(255,255,255,0.6) !important; font-weight: 500; margin-top: 4px; }
    .sb-user-card { padding: 10px 28px 20px; flex-shrink: 0; }
    .sb-avatar { width: 52px !important; height: 52px !important; border-radius: 14px; background: rgba(255,255,255,0.15); border: 2px solid rgba(255,255,255,0.2); display: flex !important; align-items: center !important; justify-content: center !important; margin-bottom: 12px; overflow: hidden; }
    .sb-user-name { font-size: 0.95rem !important; font-weight: 800 !important; color: white !important; margin-bottom: 4px; }
    .sb-user-role { font-size: 0.7rem !important; color: rgba(255,255,255,0.5) !important; text-transform: uppercase; letter-spacing: 0.05em; }
    .sb-menu { padding: 16px 12px !important; flex: 1; overflow-y: auto !important; }
    .sb-link { display: flex !important; align-items: center !important; gap: 12px; padding: 12px 16px; border-radius: 10px; color: rgba(255,255,255,0.7) !important; text-decoration: none !important; font-weight: 600; font-size: 0.875rem; transition: 0.2s; }
    .sb-link:hover { background: rgba(255,255,255,0.1); color: white !important; }
    .sb-link.active { background: #ffffff15; color: white !important; }
    .sb-footer { padding: 20px 12px; border-top: 1px solid rgba(255,255,255,0.08); }

    .main-body { margin-left: 280px !important; flex: 1 !important; min-width: 0 !important; }
    .top-header { height: 64px !important; background: white !important; border-bottom: 1px solid #e2e8f0 !important; padding: 0 32px !important; display: flex !important; align-items: center !important; justify-content: space-between !important; position: sticky; top: 0; z-index: 50; }
    .view-container { padding: 32px !important; }

    /* Component Styles */
    .table-card, .log-card, .info-card, .approvals-card { background: white !important; border: 1px solid #e2e8f0 !important; border-radius: 16px !important; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); margin-bottom: 24px; }
    .table-header { padding: 24px 32px; border-bottom: 1px solid #f1f5f9; display: flex !important; align-items: center !important; justify-content: space-between !important; }
    .data-table { width: 100% !important; border-collapse: collapse !important; }
    .data-table th { background: #f8fafc !important; padding: 16px 32px !important; text-align: left !important; font-size: 0.75rem !important; font-weight: 700 !important; color: #64748b !important; text-transform: uppercase !important; border-bottom: 1px solid #e2e8f0 !important; }
    .data-table td { padding: 16px 32px !important; border-bottom: 1px solid #f1f5f9 !important; font-size: 0.875rem !important; color: #334155 !important; vertical-align: middle !important; background: white !important; }
    .data-table tr:hover td { background: #f8fafc !important; }
    
    .badge { padding: 5px 12px; border-radius: 6px; font-size: 0.75rem; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; }
    .badge-success { background: #ecfdf5; color: #10b981; }
    .badge-primary { background: #eff6ff; color: #3b82f6; }

    .btn-action { 
        width: 32px; height: 32px; 
        display: inline-flex; align-items: center; justify-content: center; 
        background: white; border: 1px solid #e2e8f0; border-radius: 8px; 
        color: #64748b; cursor: pointer; transition: 0.2s; 
    }
    .btn-action:hover { background: #f8fafc; border-color: #cbd5e1; color: #0f172a; transform: translateY(-1px); box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    .sb-section-label { font-size:0.6rem; font-weight:800; color:rgba(255,255,255,0.3); text-transform:uppercase; padding:0 16px; margin:16px 0 8px; }
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
        <nav class="sb-menu">
            <div class="sb-section-label">Menu</div>
            <a href="/pengurus/dashboard" class="sb-link"><i data-lucide="layout-dashboard" style="width:16px;height:16px;"></i> Dashboard</a>
            <a href="/pengurus/members" class="sb-link active"><i data-lucide="users" style="width:16px;height:16px;"></i> Anggota Wilayah</a>
            <a href="/pengurus/informations" class="sb-link"><i data-lucide="megaphone" style="width:16px;height:16px;"></i> Informasi</a>
        </nav>
        <div class="sb-footer">
            <div class="sb-section-label" style="margin-top:0;margin-bottom:8px;">Pengaturan</div>
            <a href="/settings" class="sb-link"><i data-lucide="settings" style="width:16px;height:16px;"></i> Pengaturan Akun</a>
            <a href="#" class="sb-link" onclick="logout()" style="color:#fca5a5;margin-top:4px;"><i data-lucide="log-out" style="width:16px;height:16px;color:#fca5a5;"></i> Keluar Sesi</a>
        </div>
    </aside>

    <main class="main-body">
        <header class="top-header">
            <div style="font-weight: 600; color: #1e293b; font-size: 1rem;">Administrasi Keanggotaan Wilayah</div>
            <div id="user-info-header" style="display: flex; align-items: center; gap: 12px;">
                <span id="date-now" style="font-size: 0.75rem; color: #94a3b8; font-weight: 500;"></span>
                <div id="user-initials" style="width: 32px; height: 32px; background: #eff6ff; color: #004aad; border: 1px solid #dbeafe; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem;">...</div>
            </div>
        </header>

        <div class="view-container">
            <div class="table-card">
                <div class="table-header">
                    <div>
                        <h2>Daftar Anggota Terkelola</h2>
                        <p style="font-size: 0.8125rem; color: #64748b; margin-top: 2px;">Data anggota di wilayah koordinasi Anda.</p>
                    </div>
                </div>

                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Informasi Anggota</th>
                            <th>Kontak Aktif</th>
                            <th>Domisili Wilayah</th>
                            <th>Klasifikasi</th>
                            <th style="text-align: right;">Status</th>
                        </tr>
                    </thead>
                    <tbody id="memberTableBody">
                        <!-- Data loaded via JS -->
                    </tbody>
                </table>
                <div id="pagination" style="padding: 16px 32px; display: flex; justify-content: center; background: white; border-top: 1px solid #f1f5f9;"></div>
            </div>
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script>
    const token = localStorage.getItem('auth_token');
    const role = localStorage.getItem('user_role');
    if (!token || (role !== 'pengurus' && role !== 'admin')) window.location.href = '/login';

    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('date-now').innerText = new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        fetchMembers();
    });

    async function fetchMembers(page = 1) {
        try {
            // Kita gunakan endpoint admin/members sementara
            const res = await axios.get(`admin/members?page=${page}`);
            const data = res.data.data;
            renderTable(data.data);
            renderPagination(data);
        } catch (e) {
            showToast('Gagal memuat data anggota', 'error');
        }
    }

    function renderTable(members) {
        const body = document.getElementById('memberTableBody');
        body.innerHTML = '';
        members.forEach(m => {
            body.innerHTML += `
                <tr>
                    <td>
                        <div style="font-weight: 700;">${m.name}</div>
                        <div style="font-size: 0.75rem; color: #64748b;">NIK: ${m.nik}</div>
                    </td>
                    <td style="font-weight: 500;">${m.phone}</td>
                    <td>
                        <div style="font-weight: 600;">${m.city?.name || '-'}</div>
                        <div style="font-size: 0.7rem; color: #94a3b8;">${m.province?.name || '-'}</div>
                    </td>
                    <td><span class="badge badge-blue">${m.occupation || 'UMUM'}</span></td>
                    <td style="text-align: right;"><span style="color: #10b981; font-weight: 700;">AKTIF</span></td>
                </tr>
            `;
        });
    }

    function renderPagination(meta) {
        const p = document.getElementById('pagination');
        p.innerHTML = '';
        for(let i=1; i<=meta.last_page; i++) {
            p.innerHTML += `<button onclick="fetchMembers(${i})" style="margin: 0 4px; padding: 4px 10px; border: 1px solid #e2e8f0; background: ${meta.current_page === i ? '#004aad' : 'white'}; color: ${meta.current_page === i ? 'white' : '#334155'}; border-radius: 4px; cursor: pointer;">${i}</button>`;
        }
    }

    // Global functions will handle initGlobalSidebar and logout from app.blade.php
</script>
@endpush
