@extends('layouts.app')

@section('title', 'Manajemen Informasi - Admin Garda JKN')

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
            <div style="font-weight: 600; color: #1e293b; font-size: 1rem;">Pusat Informasi & Pengumuman</div>
            <div id="user-info-header" style="display: flex; align-items: center; gap: 12px;">
                <span id="date-now" style="font-size: 0.75rem; color: #94a3b8; font-weight: 500;"></span>
                <div id="user-initials" style="width: 32px; height: 32px; background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem;">...</div>
            </div>
        </header>

        <div class="view-container">
            <div class="row mb-4 align-items-end">
                <div class="col-md-6">
                    <h1 class="h3 font-weight-bold text-dark mb-1">Manajemen Informasi</h1>
                    <p class="text-muted mb-0">Kelola pengumuman dan informasi strategis untuk anggota</p>
                </div>
                <div class="col-md-6 text-end">
                    <button class="btn btn-primary px-4 shadow-sm" onclick="openAddModal()">
                        <i class="bi bi-plus-lg me-2"></i> Buat Informasi Baru
                    </button>
                </div>
            </div>

            <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 12px;">
                <div class="card-header bg-white py-3 border-light">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0 font-weight-bold text-dark">Daftar Pengumuman</h6>
                        </div>
                        <div class="col-auto">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <span class="input-group-text bg-light border-light"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control bg-light border-light" placeholder="Cari judul..." onkeyup="handleSearch(this.value)">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small text-uppercase">
                                <tr>
                                    <th class="ps-4" style="width: 150px;">Tanggal</th>
                                    <th>Informasi</th>
                                    <th style="width: 120px;">Tipe</th>
                                    <th style="width: 100px;">Status</th>
                                    <th class="text-end pe-4" style="width: 120px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="infoTableBody" class="border-top-0">
                                <!-- Content loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-light py-3">
                    <div id="paginationContainer"></div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Modal Tab (Add/Edit) -->
<div class="modal fade" id="infoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <form id="infoForm" onsubmit="submitForm(event)">
                <input type="hidden" id="infoId">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah Informasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Judul</label>
                        <input type="text" id="title" class="form-control" required placeholder="Contoh: Pengumuman Rapat Anggota">
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label font-weight-bold">Tipe Informasi</label>
                            <select id="type" class="form-select" onchange="toggleAttachmentField()">
                                <option value="text">Teks Manual</option>
                                <option value="image">Foto/Gambar</option>
                                <option value="pdf">Dokumen PDF</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label font-weight-bold">Status</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" id="is_active" checked>
                                <label class="form-check-label" for="is_active">Aktif (Tampilkan)</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3" id="textField">
                        <label class="form-label font-weight-bold">Isi Informasi (Opsional jika ada lampiran)</label>
                        <textarea id="content" class="form-control" rows="5" placeholder="Ketik informasi di sini..."></textarea>
                    </div>

                    <div class="mb-3 d-none" id="attachmentField">
                        <label class="form-label font-weight-bold" id="attachmentLabel">Lampiran File</label>
                        <input type="file" id="attachment" name="attachment" class="form-control">
                        <small class="text-muted d-block mt-1" id="attachmentHint">Pilih file (JPG, PNG, atau PDF). Maksimal 5MB.</small>
                        <div id="currentAttachment" class="mt-2"></div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSubmit">Simpan Informasi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection




@push('scripts')
<script>
    let currentPage = 1;

    document.addEventListener('DOMContentLoaded', () => { 
        document.getElementById('date-now').innerText = new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        fetchData();
    });

    async function fetchData(page = 1, search = '') {
        currentPage = page;
        try {
            const res = await axios.get(`admin/informations?page=${page}&search=${search}`);
            renderTable(res.data.data.data);
            renderPagination(res.data.data);
        } catch (e) {
            showToast('Gagal memuat data', 'error');
        }
    }

    let searchTimer;
    function handleSearch(val) {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => fetchData(1, val), 500);
    }

    function renderTable(items) {
        const body = document.getElementById('infoTableBody');
        body.innerHTML = '';
        
        if (items.length === 0) {
            body.innerHTML = '<tr><td colspan="5" class="text-center py-5 text-muted">Tidak ada data informasi ditemukan.</td></tr>';
            return;
        }

        items.forEach(item => {
            const row = `
                <tr class="transition-all">
                    <td class="ps-4">
                        <div class="text-dark font-weight-500">${formatDateShort(item.created_at)}</div>
                        <div class="small text-muted">${formatTime(item.created_at)} WIB</div>
                    </td>
                    <td>
                        <div class="font-weight-bold text-dark mb-0" style="font-size: 0.95rem;">${item.title}</div>
                        ${item.attachment_path ? 
                            `<div class="mt-1"><span class="badge bg-light text-primary border border-primary-subtle py-1 ps-1 pe-2" style="font-size: 0.7rem; font-weight: 500;">
                                <i class="bi bi-paperclip me-1"></i>${item.type === 'pdf' ? 'Dokumen PDF' : 'Foto Lampiran'}
                            </span></div>` : 
                            '<small class="text-muted italic">Tidak ada lampiran</small>'}
                    </td>
                    <td>
                        <span class="badge ${getTypeBadgeClass(item.type)} d-inline-flex align-items-center gap-1 py-2 px-2" style="font-size: 0.75rem; border-radius: 6px;">
                            ${getTypeIcon(item.type)} ${item.type.toUpperCase()}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input cursor-pointer" type="checkbox" ${item.is_active ? 'checked' : ''} onchange="toggleStatus(${item.id})">
                                <label class="small ${item.is_active ? 'text-success' : 'text-muted'} mb-0" style="font-weight: 600; font-size: 0.7rem;">
                                    ${item.is_active ? 'PUBLIK' : 'DRAFT'}
                                </label>
                            </div>
                        </div>
                    </td>
                    <td class="text-end pe-4">
                        <div class="d-flex justify-content-end gap-1">
                            <button class="btn btn-icon btn-light-info" onclick="openEditModal(${item.id})" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button class="btn btn-icon btn-light-danger" onclick="deleteInfo(${item.id})" title="Hapus">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            body.insertAdjacentHTML('beforeend', row);
        });
    }

    function renderPagination(meta) {
        const container = document.getElementById('paginationContainer');
        if (meta.last_page <= 1) {
            container.innerHTML = '';
            return;
        }

        let html = `
            <div class="d-flex justify-content-between align-items-center w-100">
                <div class="text-muted small">Menampilkan ${meta.from || 0} sampai ${meta.to || 0} dari ${meta.total} entri</div>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item ${meta.current_page === 1 ? 'disabled' : ''}">
                            <a class="page-link" href="#" onclick="fetchData(${meta.current_page - 1})">Prev</a>
                        </li>
        `;

        for (let i = 1; i <= meta.last_page; i++) {
            if (i === 1 || i === meta.last_page || (i >= meta.current_page - 1 && i <= meta.current_page + 1)) {
                html += `
                    <li class="page-item ${meta.current_page === i ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="fetchData(${i})">${i}</a>
                    </li>
                `;
            } else if (i === meta.current_page - 2 || i === meta.current_page + 2) {
                html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
        }

        html += `
                        <li class="page-item ${meta.current_page === meta.last_page ? 'disabled' : ''}">
                            <a class="page-link" href="#" onclick="fetchData(${meta.current_page + 1})">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        `;
        container.innerHTML = html;
    }

    function toggleAttachmentField() {
        const type = document.getElementById('type').value;
        const attachmentField = document.getElementById('attachmentField');
        const attachmentLabel = document.getElementById('attachmentLabel');
        const attachmentHint = document.getElementById('attachmentHint');
        const attachmentInput = document.getElementById('attachment');

        if (type === 'text') {
            attachmentField.classList.add('d-none');
            attachmentInput.required = false;
        } else {
            attachmentField.classList.remove('d-none');
            attachmentLabel.innerText = type === 'image' ? 'Lampiran Foto/Gambar' : 'Lampiran Dokumen PDF';
            attachmentHint.innerText = type === 'image' ? 'Format: JPG, PNG. Max 5MB' : 'Format: PDF. Max 5MB';
            attachmentInput.accept = type === 'image' ? 'image/*' : '.pdf';
            // Only require on add, not edit
            // attachmentInput.required = !document.getElementById('infoId').value;
        }
    }

    function openAddModal() {
        document.getElementById('infoId').value = '';
        document.getElementById('infoForm').reset();
        document.getElementById('modalTitle').innerText = 'Tambah Informasi';
        document.getElementById('currentAttachment').innerHTML = '';
        toggleAttachmentField();
        new bootstrap.Modal(document.getElementById('infoModal')).show();
    }

    async function openEditModal(id) {
        try {
            const res = await axios.get(`admin/informations/${id}`);
            const item = res.data.data;
            
            document.getElementById('infoId').value = item.id;
            document.getElementById('title').value = item.title;
            document.getElementById('type').value = item.type;
            document.getElementById('content').value = item.content || '';
            document.getElementById('is_active').checked = item.is_active;
            
            toggleAttachmentField();

            if (item.attachment_url) {
                document.getElementById('currentAttachment').innerHTML = `
                    <div class="mt-2 small text-muted">
                        File saat ini: <a href="${item.attachment_url}" target="_blank">Lihat File</a>
                    </div>
                `;
            } else {
                document.getElementById('currentAttachment').innerHTML = '';
            }

            document.getElementById('modalTitle').innerText = 'Edit Informasi';
            new bootstrap.Modal(document.getElementById('infoModal')).show();
        } catch (e) {
            showToast('Gagal memuat detail', 'error');
        }
    }

    async function submitForm(e) {
        e.preventDefault();
        const id = document.getElementById('infoId').value;
        const formData = new FormData();
        
        formData.append('title', document.getElementById('title').value);
        formData.append('type', document.getElementById('type').value);
        formData.append('content', document.getElementById('content').value);
        formData.append('is_active', document.getElementById('is_active').checked ? 1 : 0);
        
        if (id) {
            formData.append('_method', 'PUT');
        }

        const fileInput = document.getElementById('attachment');
        const file = fileInput.files[0];
        
        if (file) {
            console.log('Attaching file:', file.name, file.size, file.type);
            formData.append('attachment', file);
        }

        const btn = document.getElementById('btnSubmit');
        btn.disabled = true;
        btn.innerText = 'Menyimpan...';

        try {
            const config = {
                headers: { 'Content-Type': 'multipart/form-data' }
            };

            if (id) {
                // Use POST with _method=PUT override
                await axios.post(`admin/informations/${id}`, formData, config);
                showToast('Informasi berhasil diupdate');
            } else {
                await axios.post('admin/informations', formData, config);
                showToast('Informasi berhasil dibuat');
            }
            bootstrap.Modal.getInstance(document.getElementById('infoModal')).hide();
            fetchData(currentPage);
        } catch (e) {
            console.error('Submit Error:', e);
            const msg = e.response?.data?.message || e.message || 'Terjadi kesalahan';
            showToast(msg, 'error');
        } finally {
            btn.disabled = false;
            btn.innerText = 'Simpan Informasi';
        }
    }

    async function toggleStatus(id) {
        try {
            await axios.patch(`admin/informations/${id}/toggle-status`);
            showToast('Status berhasil diubah');
        } catch (e) {
            showToast('Gagal mengubah status', 'error');
            fetchData(currentPage); // Reset UI
        }
    }

    async function deleteInfo(id) {
        const ok = await showConfirm(
            'Hapus Informasi?', 
            'Informasi ini akan dihapus secara permanen. Lanjutkan?', 
            { type: 'danger', confirmText: 'Ya, Hapus', icon: 'trash-2' }
        );
        if(!ok) return;
        
        try {
            await axios.delete(`admin/informations/${id}`);
            showToast('Informasi berhasil dihapus');
            fetchData(currentPage);
        } catch (e) {
            showToast('Gagal menghapus informasi', 'error');
        }
    }

    function getTypeIcon(type) {
        switch(type) {
            case 'text': return '<i class="bi bi-chat-left-text"></i>';
            case 'image': return '<i class="bi bi-image"></i>';
            case 'pdf': return '<i class="bi bi-file-earmark-pdf"></i>';
            default: return '<i class="bi bi-info-circle"></i>';
        }
    }

    function getTypeBadgeClass(type) {
        switch(type) {
            case 'text': return 'bg-primary-subtle text-primary border border-primary';
            case 'image': return 'bg-success-subtle text-success border border-success';
            case 'pdf': return 'bg-danger-subtle text-danger border border-danger';
            default: return 'bg-secondary-subtle text-secondary border border-secondary';
        }
    }

    function formatDateShort(dateStr) {
        const d = new Date(dateStr);
        return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
    }

    function formatTime(dateStr) {
        const d = new Date(dateStr);
        return d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    }

    // Global functions will handle initGlobalSidebar and logout from app.blade.php
</script>
@endpush

