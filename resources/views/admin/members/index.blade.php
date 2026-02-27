@extends('layouts.app')

@section('title', 'Manajemen Anggota - Garda JKN')

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

    /* Professional Table Styles */
    .table-card { border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; background: white; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .table-header { padding: 16px 24px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; }
    .table-header h2 { font-size: 1rem; font-weight: 700; color: #1e293b; }
    
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th { background: #f8fafc; padding: 10px 16px; text-align: left; font-size: 0.65rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid #e2e8f0; }
    .data-table td { padding: 12px 16px; font-size: 0.875rem; color: #334155; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .data-table tr:hover { background: #f8fafc; }

    .badge { padding: 4px 8px; border-radius: 4px; font-size: 0.65rem; font-weight: 600; text-transform: uppercase; }
    .badge-blue { background: #eff6ff; color: #1e40af; border: 1px solid #dbeafe; }
    
    .pagination { padding: 12px 16px; display: flex; justify-content: space-between; align-items: center; background: white; border-top: 1px solid #f1f5f9; }
    .btn-action { width: 28px; height: 28px; border-radius: 4px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid #e2e8f0; cursor: pointer; transition: 0.15s; background: white; color: #64748b; }
    .btn-action:hover { background: #f1f5f9; border-color: #cbd5e1; color: #1e293b; }
</style>
@endpush

@section('content')
<div class="admin-layout">
    <aside class="sidebar">
        <div class="sb-brand">Garda JKN</div>
        <nav class="sb-menu">
            <a href="/admin/dashboard" class="sb-link"><i data-lucide="layout-dashboard" style="width: 16px; height: 16px;"></i> Dashboard</a>
            <a href="/admin/members" class="sb-link active"><i data-lucide="users" style="width: 16px; height: 16px;"></i> Manajemen Anggota</a>
            <a href="{{ route('admin.approvals.pengurus.index') }}" class="sb-link"><i data-lucide="user-check" style="width: 16px; height: 16px;"></i> Persetujuan Pengurus</a>
            <a href="/admin/informations" class="sb-link"><i data-lucide="megaphone" style="width: 16px; height: 16px;"></i> Informasi</a>
            <a href="/admin/audit-logs" class="sb-link"><i data-lucide="file-clock" style="width: 16px; height: 16px;"></i> Log Audit</a>
            <div style="margin-top: auto; padding-top: 20px;">
                <div style="height: 1px; background: rgba(255,255,255,0.1); margin-bottom: 20px;"></div>
                <a href="/settings" class="sb-link"><i data-lucide="settings" style="width: 16px; height: 16px;"></i> Pengaturan Akun</a>
                <a href="#" class="sb-link" onclick="logout()"><i data-lucide="log-out" style="width: 16px; height: 16px;"></i> Logout</a>
            </div>
        </nav>
    </aside>

    <main class="main-body">
        <header class="top-header">
            <div style="font-weight: 600; color: #1e293b; font-size: 1rem;">Administrasi Keanggotaan Nasional</div>
            <div style="display: flex; align-items: center; gap: 12px;">
                <span id="date-now" style="font-size: 0.75rem; color: #94a3b8; font-weight: 500;"></span>
                <div style="width: 32px; height: 32px; background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem;">AD</div>
            </div>
        </header>

        <div class="view-container">
            <div class="table-card">
                <div class="table-header">
                    <div>
                        <h2>Daftar Anggota Sistem</h2>
                        <p style="font-size: 0.8125rem; color: #64748b; margin-top: 2px;">Data kependudukan terverifikasi nasional.</p>
                    </div>
                    <div style="display: flex; gap: 12px; align-items: center;">
                        <input type="text" id="searchInput" placeholder="Cari Nama/NIK...." class="form-input" style="width: 220px; font-size: 0.8125rem; padding: 8px 12px; border-radius: 6px;">
                        <select id="statusFilter" class="form-input" style="width: 140px; font-size: 0.8125rem; padding: 8px 12px; border-radius: 6px; border: 1px solid #cbd5e1; background: #fff;">
                            <option value="false">Data Aktif</option>
                            <option value="true">Arsip Dihapus</option>
                        </select>
                        <select id="provinceFilter" class="form-input" style="width: 160px; font-size: 0.8125rem; padding: 8px 12px; border-radius: 6px;">
                            <option value="">Seluruh Wilayah</option>
                        </select>
                        <button class="btn btn-primary" onclick="openAddModal()" style="padding: 8px 16px; background: #004aad; color: white; border: none; font-size: 0.8125rem;">+ Registrasi Baru</button>
                    </div>
                </div>

                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Informasi Anggota</th>
                            <th>Kontak Aktif</th>
                            <th>Domisili Wilayah</th>
                            <th>Klasifikasi</th>
                            <th style="text-align: right;">Opsi</th>
                        </tr>
                    </thead>
                    <tbody id="memberTableBody">
                        <!-- Data loaded via JS -->
                    </tbody>
                </table>

                <div class="pagination">
                    <div style="font-size: 0.8125rem; font-weight: 600; color: #64748b;" id="pagination-info">Menampilkan ...</div>
                    <div style="display: flex; gap: 8px;">
                        <button class="btn btn-secondary" id="btn-prev" onclick="prevPage()" style="border-radius: 10px; font-size: 0.8rem;">Sebelumnya</button>
                        <button class="btn btn-secondary" id="btn-next" onclick="nextPage()" style="border-radius: 10px; font-size: 0.8rem;">Selanjutnya</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Modal Add/Edit Templates -->
@include('admin.members.modals')

@endsection

@push('scripts')
<script>
    let currentPage = 1;
    let editingId = null;

    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('date-now').innerText = new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        loadProvinces();
        fetchData();
        lucide.createIcons();
        
        document.getElementById('searchInput').oninput = debounce(() => { currentPage = 1; fetchData(); }, 500);
        document.getElementById('provinceFilter').onchange = () => { currentPage = 1; fetchData(); };
        document.getElementById('statusFilter').onchange = () => { currentPage = 1; fetchData(); };
    });

    async function fetchData() {
        const search = document.getElementById('searchInput').value;
        const province = document.getElementById('provinceFilter').value;
        const status = document.getElementById('statusFilter').value;
        
        try {
            const res = await axios.get(`admin/members?page=${currentPage}&search=${search}&province_id=${province}&only_deleted=${status}`);
            renderTable(res.data.data.data);
            updatePagination(res.data.data);
        } catch (e) { console.error(e); }
    }

    function renderTable(members) {
        const tbody = document.getElementById('memberTableBody');
        const isTrash = document.getElementById('statusFilter').value === 'true';
        tbody.innerHTML = '';
        members.forEach(m => {
            let actionButtons = '';
            if (isTrash) {
                actionButtons = `
                    <button class="btn-action" title="Pulihkan Data" onclick="restoreMember(${m.id})" style="color: #16a34a; border-color: #dcfce7;"><i data-lucide="rotate-ccw" style="width: 14px; height: 14px;"></i></button>
                    <button class="btn-action" title="Hapus Permanen" onclick="permanentlyDeleteMember(${m.id})" style="color: #ef4444; border-color: #fee2e2;"><i data-lucide="x-circle" style="width: 14px; height: 14px;"></i></button>
                `;
            } else {
                actionButtons = `
                    <button class="btn-action" title="Detail/Edit" onclick="openEdit(${m.id})"><i data-lucide="edit-3" style="width: 14px; height: 14px;"></i></button>
                    <button class="btn-action" title="Hapus" onclick="deleteMember(${m.id})" style="color: #ef4444; border-color: #fee2e2;"><i data-lucide="trash-2" style="width: 14px; height: 14px;"></i></button>
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
        lucide.createIcons();
    }

    async function deleteMember(id) {
        const confirm = await showConfirm(
            'Arsip Anggota?', 
            'Data anggota ini akan dipindahkan ke arsip dan tidak muncul di daftar aktif.',
            { type: 'danger', confirmText: 'Ya, Arsipkan', icon: 'trash-2' }
        );
        if(!confirm) return;
        try {
            await axios.delete(`/admin/members/${id}`);
            fetchData();
            showToast('Data berhasil diarsipkan', 'success');
        } catch(e) { showToast('Gagal menghapus data.', 'error'); }
    }

    async function restoreMember(id) {
        const confirm = await showConfirm(
            'Pulihkan Anggota?', 
            'Kembalikan data anggota ini ke dalam daftar aktif sistem.',
            { type: 'info', confirmText: 'Ya, Pulihkan', icon: 'rotate-ccw' }
        );
        if(!confirm) return;
        try {
            await axios.post(`/admin/members/${id}/restore`);
            fetchData();
            showToast('Data berhasil dipulihkan', 'success');
        } catch(e) { showToast('Gagal memulihkan data.', 'error'); }
    }

    async function permanentlyDeleteMember(id) {
        const confirm = await showConfirm(
            'Hapus Permanen?', 
            'Data ini akan dihapus secara permanen dari server dan TIDAK DAPAT dipulihkan kembali. Lanjutkan?',
            { type: 'danger', confirmText: 'Ya, Hapus Permanen', icon: 'x-circle' }
        );
        if(!confirm) return;
        try {
            await axios.delete(`/admin/members/${id}/permanently-delete`);
            fetchData();
            showToast('Data berhasil dihapus permanen', 'success');
        } catch(e) { showToast('Gagal menghapus data secara permanen.', 'error'); }
    }

    async function openEdit(id) {
        editingId = id;
        const res = await axios.get(`/admin/members/${id}`);
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
        
        await loadEditProvinces();
        document.getElementById('editProvince').value = m.province_id || '';
        
        if (m.province_id) {
            await loadEditCities(m.province_id);
            document.getElementById('editCity').value = m.city_id || '';
        }
        
        if (m.city_id) {
            await loadEditDistricts(m.city_id);
            document.getElementById('editDistrict').value = m.district_id || '';
        }

        document.getElementById('editModal').style.display = 'flex';
        document.getElementById('editModal').classList.remove('hide');
    }

    async function loadEditProvinces() {
        const res = await axios.get('/master/provinces');
        const sel = document.getElementById('editProvince');
        sel.innerHTML = '<option value="">Pilih...</option>';
        res.data.data.forEach(p => { sel.innerHTML += `<option value="${p.id}">${p.name}</option>`; });
    }

    async function loadEditCities(provId) {
        const sel = document.getElementById('editCity');
        const distSel = document.getElementById('editDistrict');
        sel.innerHTML = '<option value="">Pilih...</option>';
        distSel.innerHTML = '<option value="">Pilih...</option>';
        if(!provId) return;
        const res = await axios.get(`/master/cities?province_id=${provId}`);
        res.data.data.forEach(c => { 
            sel.innerHTML += `<option value="${c.id}">${c.type === 'KOTA' ? 'KOTA ' : 'KAB. '}${c.name}</option>`; 
        });
    }

    async function loadEditDistricts(cityId) {
        const sel = document.getElementById('editDistrict');
        sel.innerHTML = '<option value="">Pilih...</option>';
        if(!cityId) return;
        const res = await axios.get(`/master/districts?city_id=${cityId}`);
        res.data.data.forEach(d => { sel.innerHTML += `<option value="${d.id}">${d.name}</option>`; });
    }

    function closeEditModal() { 
        const modal = document.getElementById('editModal');
        modal.classList.add('hide');
        setTimeout(() => { modal.style.display = 'none'; }, 300);
    }
    function closeAddModal() { 
        const modal = document.getElementById('addModal');
        modal.classList.add('hide');
        setTimeout(() => { modal.style.display = 'none'; }, 300);
    }

    async function submitEdit() {
        const payload = {
            name: document.getElementById('editName').value,
            phone: document.getElementById('editPhone').value,
            jkn_number: document.getElementById('editJknNumber').value,
            birth_date: document.getElementById('editBirthDate').value,
            gender: document.getElementById('editGender').value,
            education: document.getElementById('editEducation').value,
            occupation: document.getElementById('editOccupation').value,
            province_id: document.getElementById('editProvince').value,
            city_id: document.getElementById('editCity').value,
            district_id: document.getElementById('editDistrict').value,
            address_detail: document.getElementById('editAddress').value,
        };
        try {
            await axios.put(`/admin/members/${editingId}`, payload);
            showToast('Data berhasil diperbarui', 'success');
            closeEditModal();
            fetchData();
        } catch(e) { showToast('Gagal memperbarui data: ' + (e.response?.data?.message || ''), 'error'); }
    }

    async function resetPassword() {
        const confirm = await showConfirm(
            'Reset Password?', 
            'Password anggota akan dikembalikan ke pengaturan default: GardaJKN2026!',
            { type: 'danger', confirmText: 'Reset Sekarang', icon: 'key' }
        );
        if(!confirm) return;
        try {
            await axios.post(`/admin/members/${editingId}/reset-password`);
            showToast('Password telah di-reset.', 'success');
            closeEditModal();
        } catch(e) { showToast('Gagal reset password', 'error'); }
    }

    async function openAddModal() {
        const modal = document.getElementById('addModal');
        modal.style.display = 'flex';
        modal.classList.remove('hide');
        loadAddProvinces();
    }

    async function loadAddProvinces() {
        const res = await axios.get('/master/provinces');
        const sel = document.getElementById('addProvince');
        sel.innerHTML = '<option value="">Pilih...</option>';
        res.data.data.forEach(p => { sel.innerHTML += `<option value="${p.id}">${p.name}</option>`; });
    }

    async function loadAddCities(provId) {
        const sel = document.getElementById('addCity');
        const distSel = document.getElementById('addDistrict');
        sel.innerHTML = '<option value="">Pilih...</option>';
        distSel.innerHTML = '<option value="">Pilih...</option>';
        if(!provId) return;
        const res = await axios.get(`/master/cities?province_id=${provId}`);
        res.data.data.forEach(c => { 
            sel.innerHTML += `<option value="${c.id}">${c.type === 'KOTA' ? 'KOTA ' : 'KAB. '}${c.name}</option>`; 
        });
    }

    async function loadAddDistricts(cityId) {
        const sel = document.getElementById('addDistrict');
        sel.innerHTML = '<option value="">Pilih...</option>';
        if(!cityId) return;
        const res = await axios.get(`/master/districts?city_id=${cityId}`);
        res.data.data.forEach(d => { sel.innerHTML += `<option value="${d.id}">${d.name}</option>`; });
    }

    async function submitAdd() {
        const payload = {
            nik: document.getElementById('addNik').value,
            jkn_number: document.getElementById('addJknNumber').value,
            name: document.getElementById('addName').value,
            phone: document.getElementById('addPhone').value,
            birth_date: document.getElementById('addBirthDate').value,
            password: document.getElementById('addPassword').value,
            gender: document.getElementById('addGender').value,
            education: document.getElementById('addEducation').value,
            occupation: document.getElementById('addOccupation').value,
            province_id: document.getElementById('addProvince').value,
            city_id: document.getElementById('addCity').value,
            district_id: document.getElementById('addDistrict').value,
            address_detail: document.getElementById('addAddress').value,
        };
        try {
            await axios.post('/admin/members', payload);
            showToast('Anggota baru berhasil didaftarkan', 'success');
            closeAddModal();
            fetchData();
        } catch (e) { showToast(e.response?.data?.message || 'Gagal mendaftar', 'error'); }
    }

    async function loadProvinces() {
        const res = await axios.get('/master/provinces');
        const sel = document.getElementById('provinceFilter');
        res.data.data.forEach(p => { sel.innerHTML += `<option value="${p.id}">${p.name}</option>`; });
    }

    function updatePagination(meta) {
        document.getElementById('pagination-info').innerText = `Menampilkan ${meta.from || 0}-${meta.to || 0} dari ${meta.total} Entri`;
        document.getElementById('btn-prev').disabled = !meta.prev_page_url;
        document.getElementById('btn-next').disabled = !meta.next_page_url;
    }

    function prevPage() { if(currentPage > 1) { currentPage--; fetchData(); } }
    function nextPage() { currentPage++; fetchData(); }
    function debounce(func, timeout = 300){ let timer; return (...args) => { clearTimeout(timer); timer = setTimeout(() => { func.apply(this, args); }, timeout); }; }
    function logout() { localStorage.clear(); window.location.href = '/login'; }
</script>
@endpush
