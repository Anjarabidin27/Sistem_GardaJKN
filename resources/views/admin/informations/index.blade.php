<x-admin-layout title="Manajemen Informasi - Admin Garda JKN">
    <div class="justify-between items-end mb-4 flex" id="title-section">
        <div>
            <h1 class="topbar-title" style="font-size: 1.75rem;">Manajemen Informasi</h1>
            <p class="text-muted" style="margin-top: 4px;">Kelola pengumuman dan informasi strategis untuk anggota</p>
        </div>
        <button class="btn btn-primary" id="btnOpenAddModal" style="padding: 12px 24px;">
            <i data-lucide="plus" style="width:18px;height:18px;margin-right:8px;"></i> Buat Informasi Baru
        </button>
    </div>

    <style>
        /* Mobile Compact Layout */
        @media (max-width: 768px) {
            #title-section {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 8px;
                margin-bottom: 12px !important;
            }
            #title-section h1 { font-size: 1.25rem !important; }
            #title-section p { font-size: 0.75rem; }
            
            #title-section .btn {
                width: 100% !important;
                padding: 10px !important;
                font-size: 0.75rem !important;
                justify-content: center;
                text-align: center;
            }
            
            .table-card > .justify-between.items-center {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 12px;
            }
            .chip { padding: 6px 12px; font-size: 0.7rem; }
            .toggle-btn { padding: 4px 8px; }
        }

        /* Drive Style Components */
        .filter-chips {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            padding: 4px 0 12px;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .filter-chips::-webkit-scrollbar { display: none; }
        
        .chip {
            padding: 8px 16px;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #64748b;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.2s;
        }
        .chip.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .view-toggles {
            display: flex;
            background: #f1f5f9;
            padding: 4px;
            border-radius: 8px;
            gap: 4px;
        }
        .toggle-btn {
            padding: 6px 10px;
            border-radius: 6px;
            cursor: pointer;
            color: #64748b;
            transition: all 0.2s;
            display: flex;
            align-items: center;
        }
        .toggle-btn.active {
            background: white;
            color: var(--primary);
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .drive-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 16px;
            padding-top: 10px;
        }
        @media (max-width: 480px) {
            .drive-grid { grid-template-columns: 1fr; }
        }

        .drive-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
            position: relative;
            transition: transform 0.2s, box-shadow 0.2s;
            overflow: hidden;
        }
        .drive-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border-color: var(--primary);
        }
        .card-dots {
            position: absolute;
            top: 12px;
            right: 12px;
            padding: 4px;
            border-radius: 50%;
            cursor: pointer;
            color: #94a3b8;
            z-index: 10;
        }
        .card-dots:hover { background: #f1f5f9; color: var(--primary); }

        .context-menu {
            position: absolute;
            top: 40px;
            right: 12px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            z-index: 50;
            width: 180px;
            display: none;
            overflow: hidden;
        }
        .menu-item {
            padding: 10px 14px;
            font-size: 0.85rem;
            color: #475569;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }
        .menu-item:hover { background: #f8fafc; color: var(--primary); }
        .menu-item.danger { color: #ef4444; }
        .menu-item.danger:hover { background: #fef2f2; }

        .info-preview {
            width: 100%;
            height: 120px;
            background: #f1f5f9;
            border-radius: 8px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            overflow: hidden;
        }
        .info-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>

    <div class="table-card">
        <div class="p-4">
            <div class="justify-between items-center mb-4 flex">
                <div class="filter-chips" id="status-chips">
                    <div class="chip active" data-type="">Semua</div>
                    <div class="chip" data-type="text">Teks</div>
                    <div class="chip" data-type="image">Gambar</div>
                    <div class="chip" data-type="pdf">Dokumen</div>
                </div>
                <div class="view-toggles">
                    <div class="toggle-btn" id="toggle-grid" title="Grid View">
                        <i data-lucide="layout-grid" style="width:18px; height:18px;"></i>
                    </div>
                    <div class="toggle-btn active" id="toggle-list" title="List View">
                        <i data-lucide="list" style="width:18px; height:18px;"></i>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <input type="text" id="infoSearchInput" class="form-input" style="width: 100%;" placeholder="Cari judul informasi...">
            </div>

            <div id="grid-view" class="drive-grid" style="display: none;">
                <!-- Grid items rendered by JS -->
            </div>

            <div id="list-view" style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 150px;">Tanggal</th>
                        <th>Informasi</th>
                        <th style="width: 120px;">Tipe</th>
                        <th style="width: 100px;">Status</th>
                        <th class="text-right" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="infoTableBody">
                    <!-- Content loaded via AJAX -->
                </tbody>
            </table>
        </div>
        <div class="table-footer" id="paginationContainer">
            <!-- Pagination loaded via JS -->
        </div>
        </div>
    </div>

    <!-- Modal Tab (Add/Edit) -->
    <div id="infoModal" class="modal-overlay">
        <div class="modal-content">
            <form id="infoForm">
                <input type="hidden" id="infoId">
                <div class="modal-header">
                    <h3 class="modal-title" id="modalTitle">Tambah Informasi</h3>
                    <button type="button" class="modal-close" onclick="closeModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Judul</label>
                        <input type="text" id="title" class="form-input" required placeholder="Contoh: Pengumuman Rapat Anggota">
                    </div>
                    
                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Tipe Informasi</label>
                            <select id="type" class="form-input" onchange="toggleAttachmentField()">
                                <option value="text">Teks Manual</option>
                                <option value="image">Foto/Gambar</option>
                                <option value="pdf">Dokumen PDF</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <div class="flex items-center gap-2" style="margin-top: 10px;">
                                <input type="checkbox" id="is_active" checked style="width: 18px; height: 18px; cursor: pointer;">
                                <label for="is_active" class="text-muted font-bold" style="font-size: 0.85rem; cursor: pointer;">Aktif (Tampilkan)</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" id="textField">
                        <label class="form-label">Isi Informasi (Opsional jika ada lampiran)</label>
                        <textarea id="content" class="form-input" rows="5" placeholder="Ketik informasi di sini..." style="resize: none;"></textarea>
                    </div>

                    <div class="form-group" id="attachmentField" style="display: none;">
                        <label class="form-label" id="attachmentLabel">Lampiran File</label>
                        <input type="file" id="attachment" name="attachment" class="form-input">
                        <small class="text-muted font-bold" id="attachmentHint" style="display: block; margin-top: 8px; font-size: 0.7rem;">Pilih file (JPG, PNG, atau PDF). Maksimal 5MB.</small>
                        <div id="currentAttachment" class="mt-4"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSubmit">Simpan Informasi</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/pages/admin_informations_index.js'])
    @endpush
</x-admin-layout>

@push("scripts")
<script>
    window.sessionSuccess = "{{ session("success") }}";
    window.sessionError = "{{ session("error") }}";
</script>
@endpush
