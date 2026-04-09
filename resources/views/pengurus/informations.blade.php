@extends('layouts.app')

@section('title', 'Manajemen Informasi - Pengurus Garda JKN')

@section('content')

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
            <a href="/pengurus/dashboard" class="sb-link"><i data-lucide="layout-dashboard" style="width:16px;height:16px;"></i> Dashboard</a>
            <a href="/pengurus/members" class="sb-link"><i data-lucide="users" style="width:16px;height:16px;"></i> Anggota Wilayah</a>
            <a href="/pengurus/informations" class="sb-link"><i data-lucide="megaphone" style="width:16px;height:16px;"></i> Informasi</a>
        </nav>
        <div class="sb-footer">
            <div class="sb-section-label" style="margin-top:0;margin-bottom:8px;">Pengaturan</div>
            <a href="/pengurus/settings" class="sb-link {{ request()->is('pengurus/settings') ? 'active' : '' }}"><i data-lucide="settings" style="width:16px;height:16px;"></i> Pengaturan Akun</a>
            <a href="javascript:void(0)" onclick="logout()" class="sb-link logout-link">
                <i data-lucide="log-out" class="sb-link-icon"></i> <span>Keluar Sesi</span>
            </a>
        </div>
    </aside>

    <main class="main-body">
        <header class="top-header">
            <div style="font-weight: 600; color: #1e293b; font-size: 1rem;">Pusat Informasi & Pengumuman Wilayah</div>
            <div id="user-info-header" style="display: flex; align-items: center; gap: 12px;">
                <span id="date-now" style="font-size: 0.75rem; color: #94a3b8; font-weight: 500;"></span>
                <div id="user-initials" style="width: 32px; height: 32px; background: #eff6ff; color: #004aad; border: 1px solid #dbeafe; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem;">...</div>
            </div>
        </header>

        <div class="view-container">
            <div class="row mb-4 align-items-end">
                <div class="col-md-6">
                    <h1 class="h3 font-weight-bold text-dark mb-1">Manajemen Informasi</h1>
                    <p class="text-muted mb-0">Kelola pengumuman untuk anggota di wilayah Anda</p>
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

<!-- Modal Tab (Add/Edit) - Identical to Admin -->
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
                        <label class="form-label font-weight-bold">Isi Informasi</label>
                        <textarea id="content" class="form-control" rows="5" placeholder="Ketik informasi di sini..."></textarea>
                    </div>

                    <div class="mb-3 d-none" id="attachmentField">
                        <label class="form-label font-weight-bold" id="attachmentLabel">Lampiran File</label>
                        <input type="file" id="attachment" name="attachment" class="form-control">
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
@vite(['resources/css/pages/pengurus_informations.css', 'resources/js/pages/pengurus_informations.js'])


@endpush

@push("scripts")
<script>
    window.sessionSuccess = "{{ session("success") }}";
    window.sessionError = "{{ session("error") }}";
</script>
@endpush
