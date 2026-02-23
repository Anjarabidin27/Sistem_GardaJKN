@extends('layouts.app')

@section('title', 'Audit Logs - Garda JKN')

@push('styles')
<style>
    .admin-layout { display: flex; min-height: 100vh; background: #f8fafc; }
    .sidebar { 
        width: 260px; background: #004aad; color: white; display: flex; flex-direction: column; 
        position: fixed; height: 100vh; z-index: 100;
    }
    .sb-brand { padding: 24px 32px; font-size: 1.1rem; font-weight: 700; border-bottom: 1px solid rgba(255,255,255,0.1); }
    .sb-menu { padding: 20px 12px; flex: 1; }
    .sb-link { 
        display: flex; align-items: center; padding: 10px 16px; 
        color: rgba(255,255,255,0.7); text-decoration: none; border-radius: 6px; 
        font-weight: 500; font-size: 0.875rem; margin-bottom: 4px; transition: 0.15s; gap: 12px;
    }
    .sb-link:hover, .sb-link.active { background: rgba(255,255,255,0.1); color: white; }
    
    .main-body { margin-left: 260px; flex: 1; }
    .top-header { height: 64px; background: white; border-bottom: 1px solid #e2e8f0; padding: 0 32px; display: flex; align-items: center; justify-content: space-between; }
    .view-container { padding: 32px; max-width: 1400px; }

    .log-card { border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; background: white; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th { background: #f8fafc; padding: 12px 32px; text-align: left; font-size: 0.65rem; font-weight: 600; color: #64748b; text-transform: uppercase; border-bottom: 1px solid #e2e8f0; }
    .data-table td { padding: 16px 32px; font-size: 0.875rem; color: #334155; border-bottom: 1px solid #f1f5f9; vertical-align: top; }
    
    .action-badge { padding: 4px 10px; border-radius: 4px; font-size: 0.65rem; font-weight: 700; text-transform: uppercase; display: inline-block; }
    .bg-update, .bg-reset, .bg-delete, .bg-login, .bg-logout { 
        background: #ffffff; 
        color: #004aad; 
        border: 1px solid #e2e8f0; 
    }
    .bg-delete { border-color: #fee2e2; color: #004aad; } /* Tetap bedakan border sedikit jika perlu, tapi teks seragam */
    
    .change-item { display: grid; grid-template-columns: 100px 15px 1fr 40px 1fr; gap: 0; margin-bottom: 4px; align-items: center; }
    .change-label { font-size: 0.875rem; font-weight: 700; color: #1e293b; }
    .change-separator { font-size: 0.875rem; color: #1e293b; }
    .change-value { display: contents; }
    .value-old { color: #475569; font-size: 0.875rem; padding-right: 10px; }
    .value-new { font-weight: 700; color: #000; font-size: 0.875rem; }
    .change-arrow { color: #1e293b; font-size: 1rem; display: flex; justify-content: center; }
    .metadata-empty { font-style: italic; color: #cbd5e1; font-size: 0.8125rem; }
</style>
@endpush

@section('content')
<div class="admin-layout">
    <aside class="sidebar">
        <div class="sb-brand">Garda JKN</div>
        <nav class="sb-menu">
            <a href="/admin/dashboard" class="sb-link"><i data-lucide="layout-dashboard" style="width: 16px; height: 16px;"></i> Dashboard</a>
            <a href="/admin/members" class="sb-link"><i data-lucide="users" style="width: 16px; height: 16px;"></i> Manajemen Anggota</a>
            <a href="/admin/audit-logs" class="sb-link active"><i data-lucide="file-clock" style="width: 16px; height: 16px;"></i> Log Audit</a>
            <div style="margin-top: auto; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.05);">
                <a href="#" class="sb-link" onclick="logout()"><i data-lucide="log-out" style="width: 16px; height: 16px;"></i> Logout</a>
            </div>
        </nav>
    </aside>

    <main class="main-body">
        <header class="top-header">
            <div style="font-weight: 600; color: #1e293b; font-size: 1rem;">Rekam Jejak Aktivitas Sistem</div>
            <div style="display: flex; align-items: center; gap: 12px;">
                <span id="date-now" style="font-size: 0.75rem; color: #94a3b8; font-weight: 500;"></span>
                <div style="width: 32px; height: 32px; background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem;">AD</div>
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
                                <div style="font-weight:700; color:#334155;">${log.actor?.name || 'Unknown'}</div>
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
            'restore_member': 'PULIHKAN ANGGOTA'
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
                        <span class="value-old">${formatValue(value.old)}</span>
                        <span class="change-arrow">→</span>
                        <span class="value-new">${formatValue(value.new)}</span>
                    </div>
                `;
            } else {
                // Tampilan untuk Pendaftaran (Create) - Tanpa panah dan tanpa data lama
                html += `
                    <div class="change-item">
                        <span class="change-label">${label}</span>
                        <span class="change-separator">:</span>
                        <span class="value-new" style="grid-column: 3 / span 3;">${formatValue(value)}</span>
                    </div>
                `;
            }
        }
        return html;
    }

    function logout() { localStorage.clear(); window.location.href = '/login'; }
</script>
@endpush
