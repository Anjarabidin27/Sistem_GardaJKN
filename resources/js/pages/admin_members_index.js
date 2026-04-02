let currentPage = 1;
let editingId = null;

console.log('Admin Members JS Loaded');

document.addEventListener('DOMContentLoaded', () => {
    console.log('Admin Members DOM Ready');
    
    // Load initial state from URL
    loadParamsFromUrl();

    // Attach listeners
    const btnOpenAdd = document.getElementById('btnOpenAddMemberModal');
    if (btnOpenAdd) btnOpenAdd.addEventListener('click', () => window.openAddModal());

    const btnPrev = document.getElementById('btn-prev');
    if (btnPrev) btnPrev.addEventListener('click', () => window.prevPage());

    const btnNext = document.getElementById('btn-next');
    if (btnNext) btnNext.addEventListener('click', () => window.nextPage());

    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', debounce(() => {
            currentPage = 1;
            window.fetchData();
        }, 500));
    }

    const provinceFilter = document.getElementById('provinceFilter');
    if (provinceFilter) {
        provinceFilter.addEventListener('change', () => {
            currentPage = 1;
            window.fetchData();
        });
    }

    const statusFilter = document.getElementById('statusFilter');
    if (statusFilter) {
        statusFilter.addEventListener('change', () => {
            currentPage = 1;
            window.fetchData();
        });
    }

    // Modal close listeners
    const btnCloseAdd = document.querySelector('#addModal .modal-close');
    if (btnCloseAdd) btnCloseAdd.addEventListener('click', () => window.closeAddModal());

    const btnCloseEdit = document.querySelector('#editModal .modal-close');
    if (btnCloseEdit) btnCloseEdit.addEventListener('click', () => window.closeEditModal());

    loadProvinces();
    window.fetchData();
});

function loadParamsFromUrl() {
    const params = new URLSearchParams(window.location.search);
    currentPage = parseInt(params.get('page')) || 1;
    
    if (params.has('search')) {
        const el = document.getElementById('searchInput');
        if (el) el.value = params.get('search');
    }
    
    if (params.has('province_id')) {
        const el = document.getElementById('provinceFilter');
        if (el) el.dataset.pendingValue = params.get('province_id'); // Set temp for loadProvinces to use
    }
    
    if (params.has('only_deleted')) {
        const el = document.getElementById('statusFilter');
        if (el) el.value = params.get('only_deleted');
    }
}

function syncUrlWithParams(search, province, status) {
    const params = new URLSearchParams(window.location.search);
    if (currentPage > 1) params.set('page', currentPage); else params.delete('page');
    if (search) params.set('search', search); else params.delete('search');
    if (province) params.set('province_id', province); else params.delete('province_id');
    if (status !== 'false') params.set('only_deleted', status); else params.delete('only_deleted');
    
    const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
    window.history.replaceState({}, '', newUrl);
}

window.fetchData = async function() {
    const searchInput = document.getElementById('searchInput');
    const provinceFilter = document.getElementById('provinceFilter');
    const statusFilter = document.getElementById('statusFilter');
    
    const search = searchInput ? searchInput.value : '';
    const province = provinceFilter ? provinceFilter.value : '';
    const status = statusFilter ? statusFilter.value : 'false';
    
    // Sync URL
    syncUrlWithParams(search, province, status);
    
    try {
        const res = await window.axios.get(`admin/members?page=${currentPage}&search=${search}&province_id=${province}&only_deleted=${status}`);
        renderTable(res.data.data.data);
        updatePagination(res.data.data);
    } catch (e) { 
        console.error('Fetch Members Error:', e); 
    }
}

function renderTable(members) {
    const tbody = document.getElementById('memberTableBody');
    if (!tbody) return;
    
    const statusFilter = document.getElementById('statusFilter');
    const isTrash = statusFilter && statusFilter.value === 'true';
    
    tbody.innerHTML = '';
    members.forEach(m => {
        let actionButtons = '';
        if (isTrash) {
            actionButtons = `
                <div class="btn-actions-group">
                    <button class="btn-icon-square btn-restore" title="Pulihkan Data" onclick="window.restoreMember(${m.id})"><i data-lucide="rotate-ccw"></i></button>
                    <button class="btn-icon-square btn-delete" title="Hapus Permanen" onclick="window.permanentlyDeleteMember(${m.id})"><i data-lucide="x-circle"></i></button>
                </div>
            `;
        } else {
            actionButtons = `
                <div class="btn-actions-group">
                    <button class="btn-icon-square btn-edit" title="Detail/Edit" onclick="window.openEdit(${m.id})"><i data-lucide="edit-3"></i></button>
                    <button class="btn-icon-square btn-delete" title="Hapus" onclick="window.deleteMember(${m.id})"><i data-lucide="trash-2"></i></button>
                </div>
            `;
        }

        tbody.innerHTML += `
            <tr>
                <td>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 40px; height: 40px; background: #eff6ff; color: #1d4ed8; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.8rem; border: 1px solid #dbeafe;">${m.name.charAt(0)}</div>
                        <div>
                            <div style="font-weight:700; color:#0f172a;">${m.name}</div>
                            <div style="font-size:0.75rem; color:#64748b; font-weight: 500;">NIK: ${m.nik}</div>
                        </div>
                    </div>
                </td>
                <td><div style="font-weight: 600; color: #334155;">${m.phone}</div></td>
                <td>
                    <div style="font-weight:700; color: #334155;">${m.city?.name || '-'}</div>
                    <div style="font-size:0.75rem; color:#64748b; font-weight: 500;">${m.province?.name || '-'}</div>
                </td>
                <td><span class="badge badge-blue">${m.occupation}</span></td>
                <td style="text-align: right;">${actionButtons}</td>
            </tr>
        `;
    });
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

window.deleteMember = async function(id) {
    if (typeof showConfirm === 'undefined') {
        if (!confirm('Arsip Anggota?')) return;
    } else {
        const ok = await showConfirm('Arsip Anggota?', 'Data anggota ini akan dipindahkan ke arsip.', { type: 'danger', confirmText: 'Ya, Arsipkan', icon: 'trash-2' });
        if (!ok) return;
    }
    
    try {
        await window.axios.delete(`admin/members/${id}`);
        window.fetchData();
        if (typeof showToast !== 'undefined') showToast('Data berhasil diarsipkan', 'success');
    } catch(e) { 
        if (typeof showToast !== 'undefined') showToast('Gagal menghapus data.', 'error'); 
    }
}

window.restoreMember = async function(id) {
    if (typeof showConfirm !== 'undefined') {
        const ok = await showConfirm('Pulihkan Anggota?', 'Kembalikan data anggota ini ke dalam daftar aktif.', { type: 'info', confirmText: 'Ya, Pulihkan', icon: 'rotate-ccw' });
        if (!ok) return;
    }
    try {
        await window.axios.post(`admin/members/${id}/restore`);
        window.fetchData();
        if (typeof showToast !== 'undefined') showToast('Data berhasil dipulihkan', 'success');
    } catch(e) { 
        if (typeof showToast !== 'undefined') showToast('Gagal memulihkan data.', 'error'); 
    }
}

window.permanentlyDeleteMember = async function(id) {
    if (typeof showConfirm !== 'undefined') {
        const ok = await showConfirm('Hapus Permanen?', 'Data ini akan dihapus secara permanen dan TIDAK DAPAT dipulihkan. Lanjutkan?', { type: 'danger', confirmText: 'Ya, Hapus Permanen', icon: 'x-circle' });
        if (!ok) return;
    }
    try {
        await window.axios.delete(`admin/members/${id}/permanently-delete`);
        window.fetchData();
        if (typeof showToast !== 'undefined') showToast('Data berhasil dihapus permanen', 'success');
    } catch(e) { 
        if (typeof showToast !== 'undefined') showToast('Gagal menghapus data secara permanen.', 'error'); 
    }
}

window.openEdit = async function(id) {
    editingId = id;
    try {
        const res = await window.axios.get(`admin/members/${id}`);
        const m = res.data.data;
        
        document.getElementById('editNik').value = m.nik;
        document.getElementById('editJknNumber').value = m.jkn_number || '';
        document.getElementById('editName').value = m.name;
        document.getElementById('editPhone').value = m.phone;
        document.getElementById('editBirthDate').value = m.birth_date || '';
        document.getElementById('editGender').value = m.gender;
        document.getElementById('editEducation').value = m.education;
        document.getElementById('editOccupation').value = m.occupation;
        document.getElementById('editAddress').value = m.address_detail || '';
        
        await window.loadEditProvinces();
        document.getElementById('editProvince').value = m.province_id || '';
        
        await window.loadEditCities(m.province_id || '');
        document.getElementById('editCity').value = m.city_id || '';
        
        await window.loadEditDistricts(m.city_id || '');
        document.getElementById('editDistrict').value = m.district_id || '';

        const modal = document.getElementById('editModal');
        if (modal) {
            modal.style.display = 'flex';
            modal.classList.remove('hide');
        }
    } catch (e) {
        console.error('Open Edit Error:', e);
    }
}

window.loadEditProvinces = async function() {
    const res = await window.axios.get('master/provinces');
    const sel = document.getElementById('editProvince');
    if (!sel) return;
    sel.innerHTML = '<option value="">Pilih...</option>';
    res.data.data.forEach(p => { sel.innerHTML += `<option value="${p.id}">${p.name}</option>`; });
}

window.loadEditCities = async function(provId) {
    const sel = document.getElementById('editCity');
    const distSel = document.getElementById('editDistrict');
    if (!sel || !distSel) return;
    sel.innerHTML = '<option value="">Pilih...</option>';
    distSel.innerHTML = '<option value="">Pilih...</option>';
    if(!provId) return;
    const res = await window.axios.get(`master/cities?province_id=${provId}`);
    res.data.data.forEach(c => { 
        sel.innerHTML += `<option value="${c.id}">${c.type === 'KOTA' ? 'KOTA ' : 'KAB. '}${c.name}</option>`; 
    });
}

window.loadEditDistricts = async function(cityId) {
    const sel = document.getElementById('editDistrict');
    if (!sel) return;
    sel.innerHTML = '<option value="">Pilih...</option>';
    if(!cityId) return;
    const res = await window.axios.get(`master/districts?city_id=${cityId}`);
    res.data.data.forEach(d => { sel.innerHTML += `<option value="${d.id}">${d.name}</option>`; });
}

window.closeEditModal = function() { 
    const modal = document.getElementById('editModal');
    if (modal) {
        modal.classList.add('hide');
        setTimeout(() => { modal.style.display = 'none'; }, 300);
    }
}
window.closeAddModal = function() { 
    const modal = document.getElementById('addModal');
    if (modal) {
        modal.classList.add('hide');
        setTimeout(() => { modal.style.display = 'none'; }, 300);
    }
}

window.togglePassword = function(id) {
    const input = document.getElementById(id);
    const btnId = 'icon-' + id;
    const btn = document.getElementById(btnId);
    if (!input || !btn) return;
    if (input.type === 'password') {
        input.type = 'text';
        btn.innerHTML = '<i data-lucide="eye-off" style="width: 18px; height: 18px; color: #64748b;"></i>';
    } else {
        input.type = 'password';
        btn.innerHTML = '<i data-lucide="eye" style="width: 18px; height: 18px; color: #64748b;"></i>';
    }
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

window.submitEdit = async function() {
    const phone = document.getElementById('editPhone').value.replace(/\D/g, '');
    const jkn = document.getElementById('editJknNumber').value.replace(/\D/g, '');

    const payload = {
        name: document.getElementById('editName').value,
        phone: phone,
        jkn_number: jkn,
        birth_date: document.getElementById('editBirthDate').value,
        gender: document.getElementById('editGender').value,
        education: document.getElementById('editEducation').value,
        occupation: document.getElementById('editOccupation').value,
        province_id: document.getElementById('editProvince').value,
        city_id: document.getElementById('editCity').value,
        district_id: document.getElementById('editDistrict').value,
        address_detail: document.getElementById('editAddress').value,
    };

    const btn = document.getElementById('btnSubmitEdit');
    if (!btn) return;
    const originalText = btn.innerText;
    btn.disabled = true;
    btn.innerText = 'Menyimpan...';

    try {
        await window.axios.put(`admin/members/${editingId}`, payload);
        if (typeof showToast !== 'undefined') showToast('Data berhasil diperbarui', 'success');
        window.closeEditModal();
        window.fetchData();
    } catch(e) { 
        console.error('Update Error Detail:', e.response?.data);
        let msg = 'Gagal memperbarui data.';
        if (e.response?.data?.errors) {
            const errs = e.response.data.errors;
            msg = Object.values(errs).flat().find(m => m) || msg;
        } else if (e.response?.data?.message) {
            msg = e.response.data.message;
        }
        if (typeof showToast !== 'undefined') showToast(msg, 'error'); 
    } finally {
        btn.disabled = false;
        btn.innerText = originalText;
    }
}

window.resetPassword = async function() {
    if (typeof showConfirm !== 'undefined') {
        const ok = await showConfirm('Reset Password?', 'Password anggota akan dikembalikan ke pengaturan default: GardaJKN2026!', { type: 'danger', confirmText: 'Reset Sekarang', icon: 'key' });
        if (!ok) return;
    }
    try {
        await window.axios.post(`admin/members/${editingId}/reset-password`);
        if (typeof showToast !== 'undefined') showToast('Password telah di-reset.', 'success');
        window.closeEditModal();
    } catch(e) { if (typeof showToast !== 'undefined') showToast('Gagal reset password', 'error'); }
}

window.openAddModal = async function() {
    // Reset Form Fields
    if (document.getElementById('addNik')) document.getElementById('addNik').value = '';
    if (document.getElementById('addJknNumber')) document.getElementById('addJknNumber').value = '';
    if (document.getElementById('addName')) document.getElementById('addName').value = '';
    if (document.getElementById('addPhone')) document.getElementById('addPhone').value = '';
    if (document.getElementById('addBirthDate')) document.getElementById('addBirthDate').value = '';
    if (document.getElementById('addPassword')) document.getElementById('addPassword').value = '';
    if (document.getElementById('addGender')) document.getElementById('addGender').value = 'L';
    if (document.getElementById('addEducation')) document.getElementById('addEducation').value = 'SMA';
    if (document.getElementById('addOccupation')) document.getElementById('addOccupation').value = 'LAINNYA';
    if (document.getElementById('addProvince')) document.getElementById('addProvince').value = '';
    if (document.getElementById('addCity')) document.getElementById('addCity').innerHTML = '<option value="">Pilih...</option>';
    if (document.getElementById('addDistrict')) document.getElementById('addDistrict').innerHTML = '<option value="">Pilih...</option>';
    if (document.getElementById('addAddress')) document.getElementById('addAddress').value = '';
    
    // Ensure Password Field defaults to normal
    if (document.getElementById('addPassword')) document.getElementById('addPassword').type = 'password';
    if (document.getElementById('icon-addPassword')) document.getElementById('icon-addPassword').innerHTML = '<i data-lucide="eye" style="width: 18px; height: 18px; color: #64748b;"></i>';
    if (typeof lucide !== 'undefined') lucide.createIcons();

    const modal = document.getElementById('addModal');
    if (modal) {
        modal.style.display = 'flex';
        modal.classList.remove('hide');
    }
    window.loadAddProvinces();
}

window.loadAddProvinces = async function() {
    const res = await window.axios.get('master/provinces');
    const sel = document.getElementById('addProvince');
    if (!sel) return;
    sel.innerHTML = '<option value="">Pilih...</option>';
    res.data.data.forEach(p => { sel.innerHTML += `<option value="${p.id}">${p.name}</option>`; });
}

window.loadAddCities = async function(provId) {
    const sel = document.getElementById('addCity');
    const distSel = document.getElementById('addDistrict');
    if (!sel || !distSel) return;
    sel.innerHTML = '<option value="">Pilih...</option>';
    distSel.innerHTML = '<option value="">Pilih...</option>';
    if(!provId) return;
    const res = await window.axios.get(`master/cities?province_id=${provId}`);
    res.data.data.forEach(c => { 
        sel.innerHTML += `<option value="${c.id}">${c.type === 'KOTA' ? 'KOTA ' : 'KAB. '}${c.name}</option>`; 
    });
}

window.loadAddDistricts = async function(cityId) {
    const sel = document.getElementById('addDistrict');
    if (!sel) return;
    sel.innerHTML = '<option value="">Pilih...</option>';
    if(!cityId) return;
    const res = await window.axios.get(`master/districts?city_id=${cityId}`);
    res.data.data.forEach(d => { sel.innerHTML += `<option value="${d.id}">${d.name}</option>`; });
}

window.submitAdd = async function() {
    const nikInput = document.getElementById('addNik');
    const phoneInput = document.getElementById('addPhone');
    const jknInput = document.getElementById('addJknNumber');

    const nik = nikInput ? nikInput.value.replace(/\D/g, '') : '';
    const phone = phoneInput ? phoneInput.value.replace(/\D/g, '') : '';
    const jkn = jknInput ? jknInput.value.replace(/\D/g, '') : '';

    const payload = {
        nik: nik,
        jkn_number: jkn,
        name: document.getElementById('addName')?.value || '',
        phone: phone,
        birth_date: document.getElementById('addBirthDate')?.value || '',
        password: document.getElementById('addPassword')?.value || '',
        gender: document.getElementById('addGender')?.value || 'L',
        education: document.getElementById('addEducation')?.value || 'SMA',
        occupation: document.getElementById('addOccupation')?.value || 'LAINNYA',
        province_id: document.getElementById('addProvince')?.value || '',
        city_id: document.getElementById('addCity')?.value || '',
        district_id: document.getElementById('addDistrict')?.value || '',
        address_detail: document.getElementById('addAddress')?.value || '',
    };

    const btn = document.getElementById('btnSubmitAdd');
    if (!btn) return;
    const originalText = btn.innerText;
    btn.disabled = true;
    btn.innerText = 'Mendaftar...';

    try {
        await window.axios.post(`admin/members`, payload);
        if (typeof showToast !== 'undefined') showToast('Anggota baru berhasil didaftarkan', 'success');
        window.closeAddModal();
        window.fetchData();
    } catch (e) { 
        console.error('Registration Error Detail:', e.response?.data);
        let msg = 'Gagal mendaftar.';
        if (e.response?.data?.errors) {
            const errs = e.response.data.errors;
            msg = Object.values(errs).flat().find(m => m) || msg;
        } else if (e.response?.data?.message) {
            msg = e.response.data.message;
        }
        if (typeof showToast !== 'undefined') showToast(msg, 'error'); 
    } finally {
        btn.disabled = false;
        btn.innerText = originalText;
    }
}

async function loadProvinces() {
    try {
        const res = await window.axios.get('master/provinces');
        const sel = document.getElementById('provinceFilter');
        if (!sel) return;
        
        const pendingValue = sel.dataset.pendingValue;
        
        sel.innerHTML = '<option value="">Seluruh Wilayah</option>';
        res.data.data.forEach(p => { 
            const selected = p.id == pendingValue ? 'selected' : '';
            sel.innerHTML += `<option value="${p.id}" ${selected}>${p.name}</option>`; 
        });
        
        delete sel.dataset.pendingValue;
    } catch (e) {
        console.error('Failed to load provinces:', e);
    }
}

function updatePagination(meta) {
    const info = document.getElementById('pagination-info');
    const prev = document.getElementById('btn-prev');
    const next = document.getElementById('btn-next');
    if (info) info.innerText = `Menampilkan ${meta.from || 0}-${meta.to || 0} dari ${meta.total} Entri`;
    if (prev) prev.disabled = !meta.prev_page_url;
    if (next) next.disabled = !meta.next_page_url;
}

window.prevPage = function() { if(currentPage > 1) { currentPage--; window.fetchData(); } }
window.nextPage = function() { currentPage++; window.fetchData(); }
function debounce(func, timeout = 300){ let timer; return (...args) => { clearTimeout(timer); timer = setTimeout(() => { func.apply(this, args); }, timeout); }; }