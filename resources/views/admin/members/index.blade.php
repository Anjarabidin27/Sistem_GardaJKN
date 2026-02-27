@extends('layouts.app')

@section('title', 'Manajemen Anggota - Garda JKN')

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

    /* Table Component */
    .table-card { background: white !important; border: 1px solid #e2e8f0 !important; border-radius: 16px !important; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .table-header { padding: 24px 32px; border-bottom: 1px solid #f1f5f9; display: flex !important; align-items: center !important; justify-content: space-between !important; }
    .data-table { width: 100% !important; border-collapse: collapse !important; }
    .data-table th { background: #f8fafc !important; padding: 16px 32px !important; text-align: left !important; font-size: 0.75rem !important; font-weight: 700 !important; color: #64748b !important; text-transform: uppercase !important; border-bottom: 1px solid #e2e8f0 !important; }
    .data-table td { padding: 16px 32px !important; border-bottom: 1px solid #f1f5f9 !important; font-size: 0.875rem !important; color: #334155 !important; vertical-align: middle !important; }

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
            <a href="/admin/dashboard" class="sb-link"><i data-lucide="layout-dashboard" style="width:16px;height:16px;"></i> Dashboard</a>
            <a href="/admin/members" class="sb-link active"><i data-lucide="users" style="width:16px;height:16px;"></i> Manajemen Anggota</a>
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
            <div style="font-weight: 600; color: #1e293b; font-size: 1rem;">Administrasi Keanggotaan Nasional</div>
            <div id="user-info-header" style="display: flex; align-items: center; gap: 12px;">
                <span id="date-now" style="font-size: 0.75rem; color: #94a3b8; font-weight: 500;"></span>
                <div id="user-initials" style="width: 32px; height: 32px; background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem;">...</div>
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
                    <button class="btn-action" title="Pulihkan Data" onclick="restoreMember(${m.id})" style="color: #16a34a;"><i data-lucide="rotate-ccw" style="width: 16px; height: 16px;"></i></button>
                    <button class="btn-action" title="Hapus Permanen" onclick="permanentlyDeleteMember(${m.id})" style="color: #ef4444;"><i data-lucide="x-circle" style="width: 16px; height: 16px;"></i></button>
                `;
            } else {
                actionButtons = `
                    <button class="btn-action" title="Detail/Edit" onclick="openEdit(${m.id})" style="color: #004aad;"><i data-lucide="edit-3" style="width: 16px; height: 16px;"></i></button>
                    <button class="btn-action" title="Hapus" onclick="deleteMember(${m.id})" style="color: #ef4444;"><i data-lucide="trash-2" style="width: 16px; height: 16px;"></i></button>
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
            await axios.delete(`admin/members/${id}`);
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
            await axios.post(`admin/members/${id}/restore`);
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
            await axios.delete(`admin/members/${id}/permanently-delete`);
            fetchData();
            showToast('Data berhasil dihapus permanen', 'success');
        } catch(e) { showToast('Gagal menghapus data secara permanen.', 'error'); }
    }

    async function openEdit(id) {
        editingId = id;
        const res = await axios.get(`admin/members/${id}`);
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
        const res = await axios.get('master/provinces');
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
        const res = await axios.get(`master/cities?province_id=${provId}`);
        res.data.data.forEach(c => { 
            sel.innerHTML += `<option value="${c.id}">${c.type === 'KOTA' ? 'KOTA ' : 'KAB. '}${c.name}</option>`; 
        });
    }

    async function loadEditDistricts(cityId) {
        const sel = document.getElementById('editDistrict');
        sel.innerHTML = '<option value="">Pilih...</option>';
        if(!cityId) return;
        const res = await axios.get(`master/districts?city_id=${cityId}`);
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

        const btn = document.querySelector('button[onclick="submitEdit()"]');
        const originalText = btn.innerText;
        btn.disabled = true;
        btn.innerText = 'Menyimpan...';

        try {
            await axios.put(`/admin/members/${editingId}`, payload);
            showToast('Data berhasil diperbarui', 'success');
            closeEditModal();
            fetchData();
        } catch(e) { 
            console.error('Update Error Detail:', e.response?.data);
            let msg = 'Gagal memperbarui data.';
            if (e.response?.data?.errors) {
                const errs = e.response.data.errors;
                msg = Object.values(errs).flat().find(m => m) || msg;
            } else if (e.response?.data?.message) {
                msg = e.response.data.message;
            }
            showToast(msg, 'error'); 
        } finally {
            btn.disabled = false;
            btn.innerText = originalText;
        }
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
        const res = await axios.get('master/provinces');
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
        const res = await axios.get(`master/cities?province_id=${provId}`);
        res.data.data.forEach(c => { 
            sel.innerHTML += `<option value="${c.id}">${c.type === 'KOTA' ? 'KOTA ' : 'KAB. '}${c.name}</option>`; 
        });
    }

    async function loadAddDistricts(cityId) {
        const sel = document.getElementById('addDistrict');
        sel.innerHTML = '<option value="">Pilih...</option>';
        if(!cityId) return;
        const res = await axios.get(`master/districts?city_id=${cityId}`);
        res.data.data.forEach(d => { sel.innerHTML += `<option value="${d.id}">${d.name}</option>`; });
    }

    async function submitAdd() {
        const nik = document.getElementById('addNik').value.replace(/\D/g, '');
        const phone = document.getElementById('addPhone').value.replace(/\D/g, '');
        const jkn = document.getElementById('addJknNumber').value.replace(/\D/g, '');

        const payload = {
            nik: nik,
            jkn_number: jkn,
            name: document.getElementById('addName').value,
            phone: phone,
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

        const btn = document.querySelector('button[onclick="submitAdd()"]');
        const originalText = btn.innerText;
        btn.disabled = true;
        btn.innerText = 'Mendaftar...';

        try {
            await axios.post('/admin/members', payload);
            showToast('Anggota baru berhasil didaftarkan', 'success');
            closeAddModal();
            fetchData();
        } catch (e) { 
            console.error('Registration Error Detail:', e.response?.data);
            let msg = 'Gagal mendaftar.';
            if (e.response?.data?.errors) {
                const errs = e.response.data.errors;
                msg = Object.values(errs).flat().find(m => m) || msg;
            } else if (e.response?.data?.message) {
                msg = e.response.data.message;
            }
            showToast(msg, 'error'); 
        } finally {
            btn.disabled = false;
            btn.innerText = originalText;
        }
    }

    async function loadProvinces() {
        try {
            const res = await axios.get('master/provinces');
            const sel = document.getElementById('provinceFilter');
            // Keep only the first option 'Seluruh Wilayah'
            sel.innerHTML = '<option value="">Seluruh Wilayah</option>';
            res.data.data.forEach(p => { 
                sel.innerHTML += `<option value="${p.id}">${p.name}</option>`; 
            });
        } catch (e) {
            console.error('Failed to load provinces:', e);
        }
    }

    function updatePagination(meta) {
        document.getElementById('pagination-info').innerText = `Menampilkan ${meta.from || 0}-${meta.to || 0} dari ${meta.total} Entri`;
        document.getElementById('btn-prev').disabled = !meta.prev_page_url;
        document.getElementById('btn-next').disabled = !meta.next_page_url;
    }

    function prevPage() { if(currentPage > 1) { currentPage--; fetchData(); } }
    function nextPage() { currentPage++; fetchData(); }
    function debounce(func, timeout = 300){ let timer; return (...args) => { clearTimeout(timer); timer = setTimeout(() => { func.apply(this, args); }, timeout); }; }
    // Global functions will handle initGlobalSidebar and logout from app.blade.php
</script>
@endpush

