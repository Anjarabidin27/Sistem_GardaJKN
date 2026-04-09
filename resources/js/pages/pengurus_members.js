    const token = localStorage.getItem('auth_token');
    const role = localStorage.getItem('user_role');
    const allowedRoles = ['admin', 'admin_wilayah', 'petugas_pil', 'petugas_keliling', 'pengurus'];
    
    if (!token || !allowedRoles.includes(role)) window.location.href = '/login';

    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('memberSearch');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                const q = e.target.value.toLowerCase();
                const rows = document.querySelectorAll('#memberTableBody tr');
                rows.forEach(row => {
                    const text = row.innerText.toLowerCase();
                    row.style.display = text.includes(q) ? '' : 'none';
                });
            });
        }

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
        if (!body) return;
        body.innerHTML = '';
        members.forEach(m => {
            body.innerHTML += `
                <tr>
                    <td>
                        <div class="v-flex v-items-center v-gap-3">
                            <div style="width: 32px; height: 32px; background: var(--v-gray-50); border: 1px solid var(--v-gray-100); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 0.7rem; color: var(--v-gray-500);">
                                ${m.name.substring(0,1)}
                            </div>
                            <div>
                                <span style="font-weight: 800; color: var(--v-black); display: block;">${m.name}</span>
                                <span style="font-size: 10px; color: var(--v-gray-400); font-weight: 700;">${m.nik}</span>
                            </div>
                        </div>
                    </td>
                    <td style="font-weight: 600; font-size: 0.8rem; color: var(--v-gray-500);">${m.phone}</td>
                    <td>
                        <span style="font-weight: 700; color: var(--v-black); display: block;">${m.city?.name || '-'}</span>
                        <span style="font-size: 9px; color: var(--v-gray-400); font-weight: 700;">${m.province?.name || '-'}</span>
                    </td>
                    <td><span style="padding: 2px 8px; border-radius: 9999px; font-size: 9px; font-weight: 900; background: rgba(37, 99, 235, 0.05); color: var(--v-blue-600); border: 1px solid rgba(37, 99, 235, 0.1);">${m.occupation || 'UMUM'}</span></td>
                    <td style="text-align: right;">
                        <span style="color: var(--v-emerald-500); font-weight: 900; font-size: 10px;">AKTIF</span>
                    </td>
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