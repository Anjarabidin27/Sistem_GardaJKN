@extends('layouts.app')

@section('title', 'Manajemen Informasi - Admin Garda JKN')

@section('content')
<div class="admin-layout">
    <aside class="sidebar">
        <div class="sb-brand">Garda JKN</div>
        <nav class="sb-menu">
            <a href="/admin/dashboard" class="sb-link"><i data-lucide="layout-dashboard" style="width: 16px; height: 16px;"></i> Dashboard</a>
            <a href="/admin/members" class="sb-link"><i data-lucide="users" style="width: 16px; height: 16px;"></i> Manajemen Anggota</a>
            <a href="/admin/informations" class="sb-link active"><i data-lucide="megaphone" style="width: 16px; height: 16px;"></i> Informasi</a>
            <a href="/admin/audit-logs" class="sb-link"><i data-lucide="file-clock" style="width: 16px; height: 16px;"></i> Log Audit</a>
            <div style="margin-top: auto; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.05);">
                <a href="#" class="sb-link" onclick="logout()"><i data-lucide="log-out" style="width: 16px; height: 16px;"></i> Logout</a>
            </div>
        </nav>
    </aside>

    <main class="main-body">
        <header class="top-header">
            <div style="font-weight: 600; color: #1e293b; font-size: 1rem;">Pusat Informasi & Pengumuman</div>
            <div style="display: flex; align-items: center; gap: 12px;">
                <span id="date-now-header" style="font-size: 0.75rem; color: #94a3b8; font-weight: 500;"></span>
                <div style="width: 32px; height: 32px; background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem;">AD</div>
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


@push('styles')
<style>
    .admin-layout { display: flex; min-height: 100vh; background: #f8fafc; }
    .sidebar { width: 260px; background: #004aad; color: white; display: flex; flex-direction: column; position: fixed; height: 100vh; z-index: 1050; }
    .sb-brand { padding: 24px 32px; font-size: 1.1rem; font-weight: 700; border-bottom: 1px solid rgba(255,255,255,0.1); }
    .sb-menu { padding: 20px 12px; flex: 1; }
    .sb-link { display: flex; align-items: center; padding: 10px 16px; color: rgba(255,255,255,0.7); text-decoration: none; border-radius: 6px; font-weight: 500; font-size: 0.875rem; margin-bottom: 4px; transition: 0.15s; gap: 12px; }
    .sb-link:hover, .sb-link.active { background: rgba(255,255,255,0.1); color: white; }
    
    .main-body { margin-left: 260px; flex: 1; width: calc(100% - 260px); }
    .top-header { height: 61px; background: white; border-bottom: 1px solid #e2e8f0; padding: 0 32px; display: flex; align-items: center; justify-content: space-between; }
    .view-container { padding: 32px; }

    .btn-icon { width: 34px; height: 34px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; transition: all 0.2s; border: none; }
    .btn-light-info { background: #e0f2fe; color: #0ea5e9; }
    .btn-light-info:hover { background: #0ea5e9; color: white; }
    .btn-light-danger { background: #fee2e2; color: #ef4444; }
    .btn-light-danger:hover { background: #ef4444; color: white; }
    
    .cursor-pointer { cursor: pointer; }
    .font-weight-500 { font-weight: 500; }
    .bg-primary-subtle { background-color: #e0f2fe !important; color: #0369a1 !important; border: 1px solid #bae6fd !important; }
    .bg-success-subtle { background-color: #dcfce7 !important; color: #15803d !important; border: 1px solid #bbf7d0 !important; }
    .bg-danger-subtle { background-color: #fee2e2 !important; color: #b91c1c !important; border: 1px solid #fecaca !important; }
    .italic { font-style: italic; }
    .transition-all { transition: all 0.2s ease; }
    tr.transition-all:hover { background-color: #f8fafc !important; }
    .form-switch .form-check-input { width: 2.5em; height: 1.25em; cursor: pointer; }
</style>
@endpush

@push('scripts')
<script>
    let currentPage = 1;

    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('date-now-header').innerText = new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        lucide.createIcons();
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
        if (!confirm('Yakin ingin menghapus informasi ini secara permanen?')) return;
        
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

    function logout() {
        localStorage.clear();
        window.location.href = '/login';
    }
</script>
@endpush
