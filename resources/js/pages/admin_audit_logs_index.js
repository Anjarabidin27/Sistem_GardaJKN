const allowedRoles = ['superadmin', 'administrator', 'admin', 'admin_wilayah'];
if (!localStorage.getItem('auth_token') || !allowedRoles.includes(localStorage.getItem('user_role'))) window.location.href = '/login/admin';
let currentPage = 1;

    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('date-now').innerText = new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });

    
        fetchLogs();
    });

    async function fetchLogs(page = 1) {
        try {
            const res = await window.axios.get(`admin/audit-logs?page=${page}`);
            const data = res.data.data;
            currentPage = page;
            renderLogs(data.data);
            renderPagination(data);
            lucide.createIcons();
        } catch (e) {
            console.error(e);
            showToast('Gagal memuat log audit: ' + (e.response?.data?.message || e.message), 'error');
        }
    }

    function renderPagination(meta) {
        const p = document.getElementById('pagination');
        if (!p) return;
        
        p.innerHTML = `
            <div class="text-muted" style="font-size: 0.85rem;">Menampilkan ${meta.from || 0}-${meta.to || 0} dari ${meta.total} Entri</div>
            <div class="flex gap-2">
                <button class="btn btn-secondary btn-sm" id="btn-prev" onclick="fetchLogs(${meta.current_page - 1})" ${!meta.prev_page_url ? 'disabled' : ''}>Sebelumnya</button>
                <button class="btn btn-secondary btn-sm" id="btn-next" onclick="fetchLogs(${meta.current_page + 1})" ${!meta.next_page_url ? 'disabled' : ''}>Selanjutnya</button>
            </div>
        `;
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

    window.fetchLogs = fetchLogs;

    // Global functions will handle initGlobalSidebar and logout from app.blade.php