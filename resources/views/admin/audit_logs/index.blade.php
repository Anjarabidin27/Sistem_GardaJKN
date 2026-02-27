@extends('layouts.app')

@section('title', 'Audit Logs - Garda JKN')



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
            <a href="/admin/dashboard" class="sb-link"><i data-lucide="layout-dashboard" style="width:16px;height:16px;"></i> Dashboard</a>
            <a href="/admin/members" class="sb-link"><i data-lucide="users" style="width:16px;height:16px;"></i> Manajemen Anggota</a>
            <a href="/admin/approvals" class="sb-link"><i data-lucide="user-check" style="width:16px;height:16px;"></i> Persetujuan Pengurus</a>
            <a href="/admin/informations" class="sb-link"><i data-lucide="megaphone" style="width:16px;height:16px;"></i> Informasi</a>
            <a href="/admin/audit-logs" class="sb-link"><i data-lucide="file-clock" style="width:16px;height:16px;"></i> Log Audit</a>
        </nav>
        <div class="sb-footer">
            <div class="sb-section-label" style="margin-top:0;margin-bottom:8px;">Pengaturan</div>
            <a href="/settings" class="sb-link"><i data-lucide="settings" style="width:16px;height:16px;"></i> Pengaturan Akun</a>
            <a href="#" class="sb-link" onclick="logout()" style="color:#fca5a5;margin-top:4px;"><i data-lucide="log-out" style="width:16px;height:16px;color:#fca5a5;"></i> Keluar Sesi</a>
        </div>
    </aside>

    <main class="main-body">
        <header class="top-header">
            <div style="font-weight: 600; color: #1e293b; font-size: 1rem;">Rekam Jejak Aktivitas Sistem</div>
            <div id="user-info-header" style="display: flex; align-items: center; gap: 12px;">
                <span id="date-now" style="font-size: 0.75rem; color: #94a3b8; font-weight: 500;"></span>
                <div id="user-initials" style="width: 32px; height: 32px; background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem;">...</div>
            </div>
        </header>

        <div class="view-container">
            <div class="log-card">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Waktu & Tanggal</th>
                            <th>Aktor Pelaksana</th>
                            <th>Jenis Log</th>
                            <th>Entitas Target</th>
                            <th>Metadata Perubahan</th>
                        </tr>
                    </thead>
                    <tbody id="logTableBody">
                        <!-- Data loaded via JS -->
                    </tbody>
                </table>
                <div id="pagination" style="padding: 16px 32px; display: flex; justify-content: center; background: white; border-top: 1px solid #f1f5f9;">
                    <!-- Pagination info -->
                </div>
            </div>
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script>
    if (!localStorage.getItem('auth_token') || localStorage.getItem('user_role') !== 'admin') window.location.href = '/login';

    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('date-now').innerText = new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });

    
        fetchLogs();
    });

    async function fetchLogs(page = 1) {
        try {
            const res = await axios.get(`admin/audit-logs?page=${page}`);
            const data = res.data.data;
            renderLogs(data.data);
            lucide.createIcons();
        } catch (e) {
            console.error(e);
            showToast('Gagal memuat log audit: ' + (e.response?.data?.message || e.message), 'error');
        }
    }

    function renderLogs(logs) {
        const tbody = document.getElementById('logTableBody');
        tbody.innerHTML = '';
        logs.forEach(log => {
            const dateObj = new Date(log.created_at);
            const date = dateObj.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
            const time = dateObj.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            
            let actionClass = 'bg-update';
            if (log.action.includes('reset')) actionClass = 'bg-reset';
            if (log.action.includes('delete')) actionClass = 'bg-delete';
            if (log.action.includes('login')) actionClass = 'bg-login';
            if (log.action.includes('logout')) actionClass = 'bg-logout';
            
            tbody.innerHTML += `
                <tr>
                    <td style="white-space:nowrap;">
                        <div style="font-weight:700; color:#0f172a;">${date}</div>
                        <div style="font-size:0.75rem; color:#64748b; font-weight:500;">${time} WIB</div>
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div style="width: 24px; height: 24px; background: #f1f5f9; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 0.7rem;"><i data-lucide="user" style="width: 12px; height: 12px; color: #64748b;"></i></div>
                            <div>
                                <div style="font-weight:700; color:#334155;">${log.actor?.name || (log.actor_type === 'system' ? 'Sistem' : 'Unknown')}</div>
                                <div style="font-size:0.7rem; color:#64748b; font-weight:600; text-transform: uppercase;">ID: ${log.actor_id} | ${log.actor_type.split('\\').pop()}</div>
                            </div>
                        </div>
                    </td>
                    <td><span class="action-badge ${actionClass}">${formatAction(log.action)}</span></td>
                    <td>
                        <div style="font-weight:700; color:#334155;">${log.entity_type}</div>
                        <div style="font-size:0.75rem; color:#64748b;">Target ID: ${log.entity_id}</div>
                    </td>
                    <td>
                        <div id="metadata-${log.id}">
                            ${renderMetadata(log.changes_json)}
                        </div>
                    </td>
                </tr>
            `;
        });
    }

    function formatAction(action) {
        const map = {
            'login_admin': 'LOGIN ADMIN',
            'logout_admin': 'LOGOUT ADMIN',
            'login_member': 'LOGIN MEMBER',
            'logout_member': 'LOGOUT MEMBER',
            'create_member': 'TAMBAH ANGGOTA',
            'update_member_by_admin': 'UPDATE ANGGOTA',
            'delete_member': 'HAPUS ANGGOTA',
            'reset_password_by_admin': 'RESET PASSWORD',
            'update_profile': 'UPDATE PROFIL',
            'restore_member': 'PULIHKAN ANGGOTA',
            'verify_pengurus': 'VERIFIKASI PENGURUS'
        };
        return map[action] || action.replace('_', ' ').toUpperCase();
    }

    function renderMetadata(json) {
        if (!json || Object.keys(json).length === 0) return '<span class="metadata-empty">Tidak ada detail perubahan</span>';
        
        const labels = {
            'name': 'Nama',
            'phone': 'WhatsApp',
            'gender': 'Gender',
            'education': 'Pendidikan',
            'occupation': 'Pekerjaan',
            'province_id': 'Provinsi',
            'city_id': 'Kab/Kota',
            'district_id': 'Kecamatan',
            'address_detail': 'Alamat',
            'nik': 'NIK',
            'deleted_at': 'Dihapus pada',
            'restored_at': 'Dipulihkan pada',
            'ip': 'Alamat IP',
            'user_agent': 'Perangkat'
        };

        const formatValue = (val) => {
            if (!val) return '-';
            if (typeof val !== 'string') return val;
            
            // Format Tanggal ISO jika ada
            if (/^\d{4}-\d{2}-\d{2}T/.test(val)) {
                const d = new Date(val);
                if (!isNaN(d)) {
                    return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) + 
                           ' ' + d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WIB';
                }
            }

            // Bersihkan teks (Wilayah & Title Case)
            let t = val.replace(/KAB\.?\s+KABUPATEN/gi, 'Kabupaten');
            t = t.replace(/KOTA\s+KOTA/gi, 'Kota');
            return t.toLowerCase().replace(/\b\w/g, s => s.toUpperCase());
        };

        let html = '';
        for (const [key, value] of Object.entries(json)) {
            const label = labels[key] || key;
            
            // Deteksi apakah ini UPDATE (ada data lama & baru) atau CREATE (hanya data baru)
            if (value && typeof value === 'object' && 'new' in value && 'old' in value) {
                html += `
                    <div class="change-item">
                        <span class="change-label">${label}</span>
                        <span class="change-separator">:</span>
                        <div class="change-values">
                            <span class="value-old">${formatValue(value.old)}</span>
                            <span class="change-arrow">â†’</span>
                            <span class="value-new">${formatValue(value.new)}</span>
                        </div>
                    </div>
                `;
            } else {
                // Tampilan untuk Pendaftaran (Create) - Tanpa panah dan tanpa data lama
                html += `
                    <div class="change-item">
                        <span class="change-label">${label}</span>
                        <span class="change-separator">:</span>
                        <span class="value-new">${formatValue(value)}</span>
                    </div>
                `;
            }
        }
        return html;
    }

    // Global functions will handle initGlobalSidebar and logout from app.blade.php
</script>
@endpush

