let editingId = null;
let allKCs = [];

document.addEventListener('DOMContentLoaded', () => {
    window.fetchStaff();
    window.loadKCs();

    const btnOpenAdd = document.getElementById('btnOpenAddStaffModal');
    if (btnOpenAdd) btnOpenAdd.addEventListener('click', () => window.openAddModal());
});

let rawStaff = [];

window.fetchStaff = async function() {
    try {
        const res = await window.axios.get('admin/staff');
        rawStaff = res.data.data;
        window.handleFilterChange();
        updateCounters(rawStaff);
    } catch (e) {
        console.error('Fetch Staff Error:', e);
    }
}

window.handleFilterChange = function() {
    const filterValue = document.getElementById('filterSource').value;
    let filtered = rawStaff;
    if (filterValue !== 'all') {
        filtered = rawStaff.filter(s => s.source === filterValue);
    }
    renderStaffTable(filtered);
}

function renderStaffTable(staff) {
    const tbody = document.getElementById('staffTableBody');
    if (!tbody) return;
    
    tbody.innerHTML = '';
    staff.forEach(s => {
        const badgeClass = s.role === 'superadmin' ? 'role-superadmin' : 
                          (s.role === 'administrator' || s.role === 'admin_wilayah' ? 'role-admin' : 'role-petugas');

        const sourceClass = s.source === 'asli' ? 'source-asli' : 'source-member';
        const sourceLabel = s.source_label.split(' ')[0]; // Ambil kata pertama saja (Admin/Member)

        tbody.innerHTML += `
            <tr>
                <td>
                    <div class="flex items-center gap-3">
                        <div style="width: 32px; height: 32px; background: #f1f5f9; color: #475569; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.8rem; border: 1px solid #e2e8f0;">
                            ${s.name.charAt(0).toUpperCase()}
                        </div>
                        <div>
                            <div style="font-weight: 700; color: #0f172a; font-size: 0.85rem;">${s.name}</div>
                            <div style="font-size: 0.7rem; color: #64748B; font-family: 'JetBrains Mono', monospace;">${s.username}</div>
                        </div>
                    </div>
                </td>
                <td><span class="source-badge ${sourceClass}">${sourceLabel}</span></td>
                <td><span class="role-badge ${badgeClass}">${formatRole(s.role)}</span></td>
                <td><div style="font-weight: 600; color: #334155;">${s.kantor_cabang}</div></td>
                <td><div style="color: #64748B; font-weight: 500;">${s.kedeputian_wilayah}</div></td>
                <td style="text-align: right;">
                    <div style="display: flex; justify-content: flex-end; gap: 8px;">
                        <button class="btn-action" title="Edit Role" onclick="window.openEditStaff(${JSON.stringify(s).replace(/"/g, '&quot;')})">
                            <i data-lucide="shield-alert" style="width:14px; height:14px;"></i>
                        </button>
                        ${s.source === 'asli' ? `
                            <button class="btn-action delete" title="Hapus" onclick="window.deleteStaff(${s.id})">
                                <i data-lucide="trash-2" style="width:14px; height:14px;"></i>
                            </button>
                        ` : ''}
                    </div>
                </td>
            </tr>
        `;
    });
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

function formatRole(role) {
    const map = {
        'superadmin': 'Super Admin',
        'administrator': 'Admin Sistem',
        'admin_wilayah': 'Admin Wilayah',
        'petugas_keliling': 'Petugas Keliling',
        'petugas_pil': 'Petugas PIL'
    };
    return map[role] || role;
}

function updateCounters(staff) {
    const counts = {
        'superadmin': 0,
        'wilayah': 0,
        'keliling': 0,
        'pil': 0
    };

    staff.forEach(s => {
        if (s.role === 'superadmin') counts.superadmin++;
        else if (s.role === 'admin_wilayah') counts.wilayah++;
        else if (s.role === 'petugas_keliling') counts.keliling++;
        else if (s.role === 'petugas_pil') counts.pil++;
    });

    document.getElementById('count-superadmin').innerText = counts.superadmin;
    document.getElementById('count-wilayah').innerText = counts.wilayah;
    document.getElementById('count-keliling').innerText = counts.keliling;
    document.getElementById('count-pil').innerText = counts.pil;
}

window.loadKCs = async function() {
    try {
        const res = await window.axios.get('master/kantor-cabangs');
        allKCs = res.data.data;
        const sel = document.getElementById('staffKC');
        sel.innerHTML = '<option value="">Pilih Kantor Cabang...</option>';
        allKCs.forEach(kc => {
            sel.innerHTML += `<option value="${kc.id}">${kc.name}</option>`;
        });
    } catch (e) {
        console.error('Load KCs Error:', e);
    }
}

window.handleKCChange = function() {
    const kcId = document.getElementById('staffKC').value;
    const kwInput = document.getElementById('staffKW');
    if (!kcId) {
        kwInput.value = '';
        return;
    }
    const selected = allKCs.find(k => k.id == kcId);
    if (selected && selected.kedeputian_wilayah_id) {
        // Since the API returned full rel, let's look for it
        // Or if not, we might need another call but typically first list has it
        fetchKWName(selected.kedeputian_wilayah_id);
    }
}

async function fetchKWName(kwId) {
    try {
        const res = await window.axios.get('master/kedeputian-wilayahs');
        const kw = res.data.data.find(k => k.id == kwId);
        if (kw) {
            document.getElementById('staffKW').value = kw.name;
            document.getElementById('staffKW_id').value = kw.id;
        }
    } catch (e) {}
}

window.openAddModal = function() {
    editingId = null;
    document.getElementById('modalTitle').innerText = 'Tambah Petugas Baru';
    document.getElementById('passwordLabel').innerText = 'Kata Sandi';
    document.getElementById('passwordNote').style.display = 'none';
    document.getElementById('staffPassword').required = true;
    document.getElementById('staffForm').reset();
    document.getElementById('staffModal').style.display = 'flex';
}

window.openEditStaff = function(staff) {
    editingId = staff.id;
    document.getElementById('modalTitle').innerText = 'Edit Data Petugas';
    document.getElementById('passwordLabel').innerText = 'Ganti Kata Sandi';
    document.getElementById('passwordNote').style.display = 'block';
    document.getElementById('staffPassword').required = false;

    document.getElementById('staffName').value = staff.name;
    document.getElementById('staffUsername').value = staff.username;
    document.getElementById('staffRole').value = staff.role;
    document.getElementById('staffKC').value = staff.kantor_cabang_id || '';
    window.handleKCChange();

    document.getElementById('staffModal').style.display = 'flex';
}

window.closeModal = function() {
    document.getElementById('staffModal').style.display = 'none';
}

window.submitStaff = async function() {
    const btn = document.getElementById('btnSubmitStaff');
    const originalText = btn.innerText;
    
    const payload = {
        name: document.getElementById('staffName').value,
        username: document.getElementById('staffUsername').value,
        role: document.getElementById('staffRole').value,
        kantor_cabang_id: document.getElementById('staffKC').value,
        password: document.getElementById('staffPassword').value || undefined
    };

    btn.disabled = true;
    btn.innerText = 'Menyimpan...';

    try {
        if (editingId) {
            payload.source = rawStaff.find(s => s.id == editingId)?.source || 'asli';
            await window.axios.put(`admin/staff/${editingId}`, payload);
            showToast('Data petugas diperbarui', 'success');
        } else {
            await window.axios.post('admin/staff', payload);
            showToast('Petugas berhasil didaftarkan', 'success');
        }
        window.closeModal();
        window.fetchStaff();
    } catch (e) {
        console.error('Submit Error:', e.response?.data);
        const msg = e.response?.data?.message || 'Gagal menyimpan data';
        showToast(msg, 'error');
    } finally {
        btn.disabled = false;
        btn.innerText = originalText;
    }
}

window.deleteStaff = async function(id) {
    if(!confirm('Hapus petugas ini?')) return;
    try {
        await window.axios.delete(`admin/staff/${id}`);
        showToast('Petugas berhasil dihapus', 'success');
        window.fetchStaff();
    } catch (e) {
        showToast(e.response?.data?.message || 'Gagal menghapus', 'error');
    }
}
