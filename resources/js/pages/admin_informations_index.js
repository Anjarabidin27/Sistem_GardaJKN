let currentPage = 1;
let searchVal = '';
let currentViewMode = localStorage.getItem('info_view_mode') || 'list';
let selectedType = '';
let informationsData = [];

document.addEventListener('DOMContentLoaded', () => { 
    // Drive UI Elements
    const gridView = document.getElementById('grid-view');
    const listView = document.getElementById('list-view');
    const toggleGrid = document.getElementById('toggle-grid');
    const toggleList = document.getElementById('toggle-list');
    const statusChips = document.getElementById('status-chips');

    if (toggleGrid) toggleGrid.addEventListener('click', () => setViewMode('grid'));
    if (toggleList) toggleList.addEventListener('click', () => setViewMode('list'));

    if (statusChips) {
        statusChips.querySelectorAll('.chip').forEach(chip => {
            chip.addEventListener('click', () => {
                selectedType = chip.dataset.type;
                window.fetchData(1);
            });
        });
    }

    // View Toggling Logic
    function setViewMode(mode) {
        currentViewMode = mode;
        localStorage.setItem('info_view_mode', mode);
        
        if (mode === 'grid') {
            if (gridView) gridView.style.display = 'grid';
            if (listView) listView.style.display = 'none';
            if (toggleGrid) toggleGrid.classList.add('active');
            if (toggleList) toggleList.classList.remove('active');
        } else {
            if (gridView) gridView.style.display = 'none';
            if (listView) listView.style.display = 'block';
            if (toggleGrid) toggleGrid.classList.remove('active');
            if (toggleList) toggleList.classList.add('active');
        }
        renderData();
    }
    
    // Attach listener to Add button
    const btnOpenAdd = document.getElementById('btnOpenAddModal');
    if (btnOpenAdd) {
        btnOpenAdd.addEventListener('click', () => {
            window.openAddModal();
        });
    }

    // Attach listener to Search input
    const searchInput = document.getElementById('infoSearchInput');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            window.handleSearch(e.target.value);
        });
    }

    // Attach listener to Form
    const infoForm = document.getElementById('infoForm');
    if (infoForm) {
        infoForm.addEventListener('submit', (e) => {
            e.preventDefault();
            window.submitForm(e);
        });
    }

    // Attach listener to Type select
    const typeSelect = document.getElementById('type');
    if (typeSelect) {
        typeSelect.addEventListener('change', () => window.toggleAttachmentField());
    }

    // Initial View Mode
    setViewMode(currentViewMode);
    window.fetchData();
});

    window.fetchData = async function(page = 1, search = searchVal) {
    currentPage = page;
    searchVal = search;
    
    // Sync Chips UI
    const statusChips = document.getElementById('status-chips');
    if (statusChips) {
        statusChips.querySelectorAll('.chip').forEach(c => {
            c.classList.toggle('active', c.dataset.type === selectedType);
        });
    }

    try {
        let url = `admin/informations?page=${page}&search=${search}`;
        if (selectedType) url += `&type=${selectedType}`;
        
        const res = await window.axios.get(url);
        informationsData = res.data.data.data;
        renderData();
        renderPagination(res.data.data);
    } catch (e) {
        console.error('Fetch Error:', e);
        if (typeof showToast !== 'undefined') showToast('Gagal memuat data', 'error');
    }
}

let searchTimer;
window.handleSearch = function(val) {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
        searchVal = val;
        window.fetchData(1, val);
    }, 500);
}

function renderData() {
    if (currentViewMode === 'grid') {
        renderGridView();
    } else {
        renderTableView();
    }
}

function renderTableView() {
    const body = document.getElementById('infoTableBody');
    if (!body) return;
    body.innerHTML = '';
    
    if (informationsData.length === 0) {
        body.innerHTML = '<tr><td colspan="5" class="text-center py-5 text-muted">Tidak ada data informasi ditemukan.</td></tr>';
        return;
    }

    informationsData.forEach(item => {
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
                            <input class="form-check-input cursor-pointer" type="checkbox" ${item.is_active ? 'checked' : ''} onchange="window.toggleStatus(${item.id})">
                            <label class="small ${item.is_active ? 'text-success' : 'text-muted'} mb-0" style="font-weight: 600; font-size: 0.7rem;">
                                ${item.is_active ? 'PUBLIK' : 'DRAFT'}
                            </label>
                        </div>
                    </div>
                </td>
                <td class="text-end pe-4">
                    <div class="d-flex justify-content-end gap-1">
                        <div class="btn-actions-group">
                            <button class="btn-icon-square btn-edit" onclick="window.openEditModal(${item.id})" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button class="btn-icon-square btn-delete" onclick="window.deleteInfo(${item.id})" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </td>
            </tr>
        `;
        body.insertAdjacentHTML('beforeend', row);
    });
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

function renderPagination(meta) {
    const container = document.getElementById('paginationContainer');
    if (!container) return;
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
                        <a class="page-link" href="javascript:void(0)" onclick="window.fetchData(${meta.current_page - 1})">Prev</a>
                    </li>
    `;

    for (let i = 1; i <= meta.last_page; i++) {
        if (i === 1 || i === meta.last_page || (i >= meta.current_page - 1 && i <= meta.current_page + 1)) {
            html += `
                <li class="page-item ${meta.current_page === i ? 'active' : ''}">
                    <a class="page-link" href="javascript:void(0)" onclick="window.fetchData(${i})">${i}</a>
                </li>
            `;
        } else if (i === meta.current_page - 2 || i === meta.current_page + 2) {
            html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
        }
    }

    html += `
                    <li class="page-item ${meta.current_page === meta.last_page ? 'disabled' : ''}">
                        <a class="page-link" href="javascript:void(0)" onclick="window.fetchData(${meta.current_page + 1})">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    `;
    container.innerHTML = html;
}

window.toggleAttachmentField = function() {
    const typeEl = document.getElementById('type');
    if (!typeEl) return;
    const type = typeEl.value;
    const attachmentField = document.getElementById('attachmentField');
    const attachmentLabel = document.getElementById('attachmentLabel');
    const attachmentHint = document.getElementById('attachmentHint');
    const attachmentInput = document.getElementById('attachment');

    if (!attachmentField || !attachmentLabel || !attachmentHint || !attachmentInput) return;

    if (type === 'text') {
        attachmentField.style.display = 'none';
        attachmentInput.required = false;
    } else {
        attachmentField.style.display = 'block';
        attachmentLabel.innerText = type === 'image' ? 'Lampiran Foto/Gambar' : 'Lampiran Dokumen PDF';
        attachmentHint.innerText = type === 'image' ? 'Format: JPG, PNG. Max 5MB' : 'Format: PDF. Max 5MB';
        attachmentInput.accept = type === 'image' ? 'image/*' : '.pdf';
    }
}

window.openAddModal = function() {
    console.log('Opening Add Modal...');
    const form = document.getElementById('infoForm');
    const idInput = document.getElementById('infoId');
    const titleInput = document.getElementById('modalTitle');
    const currentAtt = document.getElementById('currentAttachment');
    const modal = document.getElementById('infoModal');

    if (idInput) idInput.value = '';
    if (form) form.reset();
    if (titleInput) titleInput.innerText = 'Tambah Informasi';
    if (currentAtt) currentAtt.innerHTML = '';
    
    if (modal) {
        modal.style.display = 'flex';
        modal.classList.remove('hide');
    }
    window.toggleAttachmentField();
}

window.openEditModal = async function(id) {
    try {
        const res = await window.axios.get(`admin/informations/${id}`);
        const item = res.data.data;
        
        const idInput = document.getElementById('infoId');
        const titleInput = document.getElementById('title');
        const typeInput = document.getElementById('type');
        const contentInput = document.getElementById('content');
        const activeInput = document.getElementById('is_active');
        const modalTitle = document.getElementById('modalTitle');
        const currentAtt = document.getElementById('currentAttachment');
        const modal = document.getElementById('infoModal');

        if (idInput) idInput.value = item.id;
        if (titleInput) titleInput.value = item.title;
        if (typeInput) typeInput.value = item.type;
        if (contentInput) contentInput.value = item.content || '';
        if (activeInput) activeInput.checked = !!item.is_active;
        
        window.toggleAttachmentField();

        if (item.attachment_url && currentAtt) {
            currentAtt.innerHTML = `
                <div class="mt-2 small text-muted">
                    File saat ini: <a href="${item.attachment_url}" target="_blank">Lihat File</a>
                </div>
            `;
        } else if (currentAtt) {
            currentAtt.innerHTML = '';
        }

        if (modalTitle) modalTitle.innerText = 'Edit Informasi';
        if (modal) {
            modal.style.display = 'flex';
            modal.classList.remove('hide');
        }
    } catch (e) {
        console.error('Edit Load Error:', e);
        if (typeof showToast !== 'undefined') showToast('Gagal memuat detail', 'error');
    }
}

window.submitForm = async function(e) {
    if (e) e.preventDefault();
    const id = document.getElementById('infoId')?.value;
    const formData = new FormData();
    
    formData.append('title', document.getElementById('title')?.value || '');
    formData.append('type', document.getElementById('type')?.value || 'text');
    formData.append('content', document.getElementById('content')?.value || '');
    formData.append('is_active', document.getElementById('is_active')?.checked ? 1 : 0);
    
    if (id) formData.append('_method', 'PUT');

    const fileInput = document.getElementById('attachment');
    if (fileInput && fileInput.files[0]) {
        formData.append('attachment', fileInput.files[0]);
    }

    const btn = document.getElementById('btnSubmit');
    if (!btn) return;
    const originalBtnText = btn.innerText;
    btn.disabled = true;
    btn.innerText = 'Menyimpan...';

    try {
        const url = id ? `admin/informations/${id}` : 'admin/informations';
        await window.axios.post(url, formData, { headers: { 'Content-Type': 'multipart/form-data' } });

        if (typeof showToast !== 'undefined') showToast(id ? 'Informasi berhasil diupdate' : 'Informasi berhasil dibuat', 'success');
        window.closeModal();
        window.fetchData(currentPage);
    } catch (e) {
        console.error('Submit Error:', e);
        const msg = e.response?.data?.message || e.message || 'Terjadi kesalahan';
        if (typeof showToast !== 'undefined') showToast(msg, 'error');
    } finally {
        btn.disabled = false;
        btn.innerText = originalBtnText;
    }
}

window.toggleStatus = async function(id) {
    try {
        await window.axios.patch(`admin/informations/${id}/toggle-status`);
        if (typeof showToast !== 'undefined') showToast('Status berhasil diubah');
    } catch (e) {
        if (typeof showToast !== 'undefined') showToast('Gagal mengubah status', 'error');
        window.fetchData(currentPage);
    }
}

window.deleteInfo = async function(id) {
    if (typeof showConfirm === 'undefined') {
        if (!confirm('Hapus Informasi?')) return;
    } else {
        const ok = await showConfirm('Hapus Informasi?', 'Informasi ini akan dihapus secara permanen. Lanjutkan?', { type: 'danger', confirmText: 'Ya, Hapus', icon: 'trash-2' });
        if (!ok) return;
    }
    
    try {
        await window.axios.delete(`admin/informations/${id}`);
        if (typeof showToast !== 'undefined') showToast('Informasi berhasil dihapus');
        window.fetchData(currentPage);
    } catch (e) {
        if (typeof showToast !== 'undefined') showToast('Gagal menghapus informasi', 'error');
    }
}

window.closeModal = function() {
    const modal = document.getElementById('infoModal');
    if (modal) {
        modal.classList.add('hide');
        setTimeout(() => { modal.style.display = 'none'; }, 300);
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

function renderGridView() {
    const grid = document.getElementById('grid-view');
    if (!grid) return;
    grid.innerHTML = '';

    if (informationsData.length === 0) {
        grid.innerHTML = '<div class="text-center p-8 text-muted" style="grid-column: 1/-1;">Tidak ada data informasi ditemukan.</div>';
        return;
    }

    informationsData.forEach(item => {
        let previewHtml = '';
        if (item.type === 'image' && item.attachment_url) {
            previewHtml = `<img src="${item.attachment_url}" alt="Preview">`;
        } else if (item.type === 'pdf') {
            previewHtml = '<i data-lucide="file-text" style="width:48px; height:48px;"></i>';
        } else {
            previewHtml = '<i data-lucide="align-left" style="width:48px; height:48px;"></i>';
        }

        const card = document.createElement('div');
        card.className = 'drive-card';
        card.innerHTML = `
            <div class="card-dots" onclick="toggleContextMenu(event, ${item.id})">
                <i data-lucide="more-vertical" style="width:20px; height:20px;"></i>
            </div>

            <div class="context-menu" id="ctx-${item.id}">
                <div class="menu-item" onclick="window.openEditModal(${item.id})">
                    <i data-lucide="edit-2" style="width:14px;"></i> Edit Informasi
                </div>
                <div class="menu-item" onclick="window.toggleStatus(${item.id})">
                    <i data-lucide="${item.is_active ? 'eye-off' : 'eye'}" style="width:14px;"></i> ${item.is_active ? 'Sembunyikan' : 'Tampilkan'}
                </div>
                <div class="menu-item danger" onclick="window.deleteInfo(${item.id})">
                    <i data-lucide="trash-2" style="width:14px;"></i> Hapus Permanen
                </div>
            </div>

            <div class="info-preview">
                ${previewHtml}
            </div>

            <div style="padding-right: 24px;">
                <div style="font-size: 0.95rem; font-weight: 800; color: #0f172a; margin-bottom: 4px; line-height: 1.2; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">${item.title}</div>
                <div class="flex items-center gap-2 mb-3">
                    <span style="font-size: 0.7rem; color: #64748b; font-weight: 600;">${formatDateShort(item.created_at)}</span>
                    <span style="width: 4px; height: 4px; background: #cbd5e1; border-radius: 50%;"></span>
                    <span style="font-size: 0.7rem; color: #64748b; font-weight: 600;">${item.type.toUpperCase()}</span>
                </div>
            </div>

            <div class="flex justify-between items-center mt-auto pt-2 border-t border-slate-100">
                <span style="background: ${item.is_active ? '#10b981' : '#64748b'}15; color: ${item.is_active ? '#10b981' : '#64748b'}; padding: 4px 10px; border-radius: 20px; font-size: 0.65rem; font-weight: 800; text-transform: uppercase;">
                    ${item.is_active ? 'PUBLIK' : 'DRAFT'}
                </span>
                ${item.attachment_path ? `<i data-lucide="paperclip" style="width:14px; color: #94a3b8;"></i>` : ''}
            </div>
        `;
        grid.appendChild(card);
    });

    if (typeof lucide !== 'undefined') lucide.createIcons();
}

window.toggleContextMenu = (e, id) => {
    e.stopPropagation();
    const allMenus = document.querySelectorAll('.context-menu');
    const targetMenu = document.getElementById(`ctx-${id}`);
    
    const isAlreadyOpen = targetMenu.style.display === 'block';
    
    allMenus.forEach(m => m.style.display = 'none');
    if (!isAlreadyOpen) {
        targetMenu.style.display = 'block';
    }
};

document.addEventListener('click', () => {
    document.querySelectorAll('.context-menu').forEach(m => m.style.display = 'none');
});