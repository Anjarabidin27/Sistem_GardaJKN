// resources/js/pages/pengurus_informations.js

const token = localStorage.getItem('auth_token');
const role = localStorage.getItem('user_role');
if (!token || (role !== 'pengurus' && role !== 'admin')) window.location.href = '/login';

document.addEventListener('DOMContentLoaded', () => {
    fetchData();
});

async function fetchData(page = 1) {
    try {
        const res = await window.axios.get(`member/manage/informations?page=${page}`);
        renderTable(res.data.data.data);
        renderPagination(res.data.data);
    } catch (e) {
        window.showToast('Gagal memuat data', 'error');
    }
}

function renderTable(items) {
    const body = document.getElementById('infoTableBody');
    if (!body) return;
    body.innerHTML = '';
    if (!items || items.length === 0) {
        body.innerHTML = '<tr><td colspan="5" style="text-align:center; padding: 40px; color: #64748b; font-size: 0.875rem;">Belum ada informasi.</td></tr>';
        return;
    }
    items.forEach(item => {
        const typeBadge = { text: '#6366f1', image: '#f59e0b', pdf: '#ef4444' }[item.type] || '#64748b';
        body.innerHTML += `
            <tr style="border-bottom: 1px solid #f1f5f9;">
                <td style="padding: 14px 24px; font-size: 0.8rem; color: #64748b; white-space: nowrap;">${new Date(item.created_at).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric'})}</td>
                <td style="padding: 14px 24px;">
                    <div style="font-weight: 700; color: #0f172a; font-size: 0.875rem;">${item.title}</div>
                    <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 2px;">${item.attachment_path ? 'Ada Lampiran' : 'Teks Saja'}</div>
                </td>
                <td style="padding: 14px 24px;">
                    <span style="background: ${typeBadge}15; color: ${typeBadge}; border: 1px solid ${typeBadge}30; padding: 3px 10px; border-radius: 6px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase;">${item.type}</span>
                </td>
                <td style="padding: 14px 24px;">
                    <span style="background: ${item.is_active ? '#ecfdf5' : '#f1f5f9'}; color: ${item.is_active ? '#10b981' : '#64748b'}; padding: 3px 10px; border-radius: 6px; font-size: 0.7rem; font-weight: 800;">${item.is_active ? 'AKTIF' : 'DRAFT'}</span>
                </td>
                <td style="padding: 14px 24px; text-align: right;">
                    <button onclick="openEditModal(${item.id})" style="background: #f1f5f9; border: 1px solid #e2e8f0; padding: 6px 14px; border-radius: 8px; font-size: 0.75rem; font-weight: 700; cursor: pointer; color: #374151; transition: all 0.2s;">
                        <i data-lucide="pencil" style="width: 12px; height: 12px;"></i> Edit
                    </button>
                </td>
            </tr>
        `;
    });
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

function renderPagination(meta) {
    const container = document.getElementById('paginationContainer');
    if (!container) return;
    container.innerHTML = `<div style="font-size: 0.8rem; color: #64748b;">Halaman ${meta.current_page} dari ${meta.last_page} &bull; ${meta.total} total data</div>`;
}

window.openAddModal = function() {
    document.getElementById('infoId').value = '';
    document.getElementById('infoForm').reset();
    document.getElementById('modalTitle').innerText = 'Tambah Informasi';
    document.getElementById('textField').style.display = 'block';
    document.getElementById('attachmentField').style.display = 'none';
    document.getElementById('is_active').checked = true;
    document.getElementById('infoModal').style.display = 'flex';
    if (typeof lucide !== 'undefined') lucide.createIcons();
};

window.openEditModal = async function(id) {
    try {
        const res = await window.axios.get(`member/manage/informations/${id}`);
        const item = res.data.data;
        document.getElementById('infoId').value = item.id;
        document.getElementById('title').value = item.title;
        document.getElementById('type').value = item.type;
        document.getElementById('content').value = item.content || '';
        document.getElementById('is_active').checked = item.is_active;
        document.getElementById('modalTitle').innerText = 'Edit Informasi';
        toggleAttachmentField();
        document.getElementById('infoModal').style.display = 'flex';
        if (typeof lucide !== 'undefined') lucide.createIcons();
    } catch (e) {
        window.showToast('Gagal memuat detail informasi', 'error');
    }
};

window.toggleAttachmentField = function() {
    const type = document.getElementById('type').value;
    document.getElementById('textField').style.display = type === 'text' ? 'block' : 'none';
    document.getElementById('attachmentField').style.display = type !== 'text' ? 'block' : 'none';
};

window.submitForm = async function(e) {
    e.preventDefault();
    const id = document.getElementById('infoId').value;
    const btn = document.getElementById('btnSubmit');
    btn.disabled = true; btn.innerText = 'Menyimpan...';

    const formData = new FormData();
    formData.append('title', document.getElementById('title').value);
    formData.append('type', document.getElementById('type').value);
    formData.append('content', document.getElementById('content').value);
    formData.append('is_active', document.getElementById('is_active').checked ? '1' : '0');
    const attachment = document.getElementById('attachment').files[0];
    if (attachment) formData.append('attachment', attachment);
    if (id) formData.append('_method', 'PUT');

    try {
        const url = id ? `member/manage/informations/${id}` : 'member/manage/informations';
        await window.axios.post(url, formData, { headers: { 'Content-Type': 'multipart/form-data' } });
        window.showToast('Informasi berhasil disimpan!', 'success');
        document.getElementById('infoModal').style.display = 'none';
        fetchData();
    } catch (err) {
        window.showToast('Gagal menyimpan. Cek kembali data Anda.', 'error');
    } finally {
        btn.disabled = false; btn.innerText = 'Simpan Informasi';
    }
};