@extends('layouts.app')

@section('title', 'Informasi & Pengumuman - Garda JKN')

@section('content')
<div class="page-wrapper" style="font-family: 'Inter', sans-serif; background: #f8fafc; min-height: 100vh; padding: 60px 20px;">
    <div style="max-width: 1000px; margin: 0 auto;">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2" style="font-size: 0.8rem; font-weight: 600;">
                        <li class="breadcrumb-item"><a href="/member/profile" class="text-decoration-none text-muted">Portal Anggota</a></li>
                        <li class="breadcrumb-item active text-primary" aria-current="page">Pusat Informasi</li>
                    </ol>
                </nav>
                <h1 class="h2 font-weight-bold text-dark mb-1">Pusat Informasi</h1>
                <p class="text-muted mb-0">Pengumuman dan berita terbaru untuk mendukung aktivitas Anda.</p>
            </div>
            <div class="d-none d-md-block">
                <a href="/member/profile" class="btn btn-secondary px-4 py-2 border-0 shadow-sm" style="background: white; color: #475569; font-weight: 600; border-radius: 10px;">
                    <i class="bi bi-person-circle me-2"></i> Profil Saya
                </a>
            </div>
        </div>

        <!-- Featured / Search (Optional Visual) -->
        <div class="mb-4">
            <div class="input-group input-group-lg shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                <span class="input-group-text bg-white border-0 ps-4"><i class="bi bi-search text-muted"></i></span>
                <input type="text" class="form-control border-0 py-3" placeholder="Cari pengumuman..." style="font-size: 1rem;" onkeyup="searchInformations(this.value)">
                <button class="btn btn-primary px-4" type="button">Cari</button>
            </div>
        </div>

        <div id="infoList" class="row g-4">
            <!-- Informations will be loaded here -->
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                    <span class="sr-only">Loading...</span>
                </div>
                <p class="mt-3 text-muted font-weight-500">Menyiapkan informasi terbaru...</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTitle">Detail Informasi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div id="modalContent" class="mb-4"></div>
                <div id="modalAttachment" class="text-center"></div>
            </div>
            <div class="modal-footer border-0 pb-4 pe-4">
                <button type="button" class="btn btn-secondary px-4 py-2" data-bs-dismiss="modal" style="border-radius: 10px;">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
        fetchInformations();
    });

    let allInformations = [];

    async function fetchInformations() {
        try {
            const res = await axios.get('member/informations');
            allInformations = res.data.data;
            renderInformations(allInformations);
        } catch (e) {
            console.error(e);
            document.getElementById('infoList').innerHTML = `
                <div class="col-12 text-center py-5">
                    <div class="text-danger mb-3">
                        <i data-lucide="alert-circle" style="width: 48px; height: 48px;"></i>
                    </div>
                    <h5>Gagal Memuat Informasi</h5>
                    <p class="text-muted">Terjadi kesalahan teknis. Silakan coba login ulang.</p>
                </div>
            `;
            lucide.createIcons();
        }
    }

    function searchInformations(val) {
        const filtered = allInformations.filter(item => 
            item.title.toLowerCase().includes(val.toLowerCase()) || 
            (item.content && item.content.toLowerCase().includes(val.toLowerCase()))
        );
        renderInformations(filtered);
    }

    function renderInformations(items) {
        const container = document.getElementById('infoList');
        if (items.length === 0) {
            container.innerHTML = `
                <div class="col-12 text-center py-5">
                    <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-state-2130362-1800926.png" style="width: 200px; opacity: 0.5;">
                    <h5 class="mt-3 text-muted">Belum ada informasi terbaru</h5>
                </div>
            `;
            return;
        }

        container.innerHTML = '';
        items.forEach(item => {
            let previewHtml = '';
            if (item.type === 'image' && item.attachment_url) {
                previewHtml = `
                    <div class="position-relative overflow-hidden" style="height: 180px;">
                        <img src="${item.attachment_url}" class="w-100 h-100 card-img-preview" style="object-fit: cover; object-position: center;">
                        <div class="position-absolute top-0 end-0 p-3">
                            <span class="badge bg-dark bg-opacity-50 blur-sm text-white border-0 py-1 px-2" style="font-size: 0.65rem; backdrop-filter: blur(4px);">
                                <i class="bi bi-image me-1"></i> FOTO
                            </span>
                        </div>
                    </div>
                `;
            } else if (item.type === 'pdf' && item.attachment_url) {
                previewHtml = `
                    <div class="d-flex align-items-center justify-content-center bg-light border-bottom-light" style="height: 180px;">
                        <div class="text-center p-4">
                            <div class="icon-box bg-danger bg-opacity-10 text-danger rounded-circle p-3 mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                                <i class="bi bi-file-earmark-pdf fs-1"></i>
                            </div>
                            <div class="small text-muted font-weight-bold letter-spacing-1">DOKUMEN PDF</div>
                        </div>
                    </div>
                `;
            }

            const card = `
                <div class="col-md-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm hover-up transition-all overflow-hidden" onclick="showDetail(${item.id})" style="cursor: pointer; border-radius: 16px;">
                        ${previewHtml}
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <small class="text-muted font-weight-500">${formatDateShort(item.created_at)}</small>
                            </div>
                            <h4 class="h5 font-weight-bold text-dark mb-3 line-clamp-2" style="line-height: 1.4;">${item.title}</h4>
                            <p class="text-muted small mb-0 line-clamp-3" style="line-height: 1.6;">
                                ${item.content || 'Lihat detail untuk informasi selengkapnya.'}
                            </p>
                        </div>
                        <div class="card-footer bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center border-top-light">
                            <span class="text-primary small font-weight-bold">Baca Selengkapnya</span>
                            <i class="bi bi-chevron-right text-primary small"></i>
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', card);
        });
        lucide.createIcons();
    }

    async function showDetail(id) {
        const modalEl = document.getElementById('detailModal');
        const modal = new bootstrap.Modal(modalEl);
        
        try {
            const res = await axios.get(`member/informations/${id}`);
            const item = res.data.data;

            document.getElementById('modalTitle').innerText = item.title;
            document.getElementById('modalContent').innerHTML = item.content ? `<div class="p-2" style="white-space: pre-wrap; line-height: 1.8; color: #334155; font-size: 1.05rem;">${item.content}</div>` : '';
            
            const attachmentContainer = document.getElementById('modalAttachment');
            attachmentContainer.innerHTML = '';

            if (item.type === 'image' && item.attachment_url) {
                attachmentContainer.innerHTML = `
                    <div class="mt-3 p-2 bg-light rounded" style="border: 1px dashed #cbd5e1;">
                        <img src="${item.attachment_url}" class="img-fluid rounded shadow-sm" style="max-height: 500px">
                    </div>
                `;
            } else if (item.type === 'pdf' && item.attachment_url) {
                attachmentContainer.innerHTML = `
                    <div class="alert alert-light border shadow-sm d-flex align-items-center justify-content-between p-4" style="border-radius: 12px;">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-box bg-danger text-white rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="bi bi-file-earmark-pdf fs-4"></i>
                            </div>
                            <div class="text-start">
                                <strong class="d-block text-dark">Berkas Pengumuman</strong>
                                <small class="text-muted">Format PDF terlampir</small>
                            </div>
                        </div>
                        <a href="${item.attachment_url}" target="_blank" class="btn btn-primary px-4" style="border-radius: 8px;">
                            Buka Berkas
                        </a>
                    </div>
                `;
            }

            modal.show();
        } catch (e) {
            showToast('Gagal memuat detail informasi', 'error');
        }
    }

    function getTypeIcon(type) {
        switch(type) {
            case 'text': return '<i class="bi bi-chat-left-text"></i>';
            case 'image': return '<i class="bi bi-image"></i>';
            case 'pdf': return '<i class="bi bi-file-earmark-pdf"></i>';
            default: return '<i class="bi bi-megaphone"></i>';
        }
    }

    function getTypeBadgeClass(type) {
        switch(type) {
            case 'text': return 'bg-primary-subtle text-primary border border-primary-subtle';
            case 'image': return 'bg-success-subtle text-success border border-success-subtle';
            case 'pdf': return 'bg-danger-subtle text-danger border border-danger-subtle';
            default: return 'bg-secondary-subtle text-secondary border border-secondary';
        }
    }

    function formatDateShort(dateStr) {
        const d = new Date(dateStr);
        return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
    }
</script>

<style>
    .hover-up:hover {
        transform: translateY(-8px);
        box-shadow: 0 1rem 3rem rgba(0, 74, 173, 0.1) !important;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .transition-all {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .border-top-light {
        border-top: 1px solid #f1f5f9;
    }
    
    /* Premium Badge Colors */
    .bg-primary-subtle { background-color: #e0f2fe !important; color: #0369a1 !important; border: 1px solid #bae6fd !important; }
    .bg-success-subtle { background-color: #dcfce7 !important; color: #15803d !important; border: 1px solid #bbf7d0 !important; }
    .bg-danger-subtle { background-color: #fee2e2 !important; color: #b91c1c !important; border: 1px solid #fecaca !important; }
    .bg-secondary-subtle { background-color: #f1f5f9 !important; color: #475569 !important; border: 1px solid #e2e8f0 !important; }
    
    .blur-sm { backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px); }
    .letter-spacing-1 { letter-spacing: 0.05em; }
    .border-bottom-light { border-bottom: 1px solid #f1f5f9; }
    
    .card-img-preview { 
        transition: transform 0.5s ease;
    }
    .card:hover .card-img-preview {
        transform: scale(1.08);
    }

    .breadcrumb-item + .breadcrumb-item::before {
        content: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 8 8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%2394a3b8'/%3E%3C/svg%3E");
        padding-right: 0.5rem;
    }

    .icon-box {
        transition: all 0.3s ease;
    }
    .card:hover .icon-box {
        transform: scale(1.1);
    }
</style>
@endpush
