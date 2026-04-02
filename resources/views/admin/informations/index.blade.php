<x-admin-layout title="Manajemen Informasi - Admin Garda JKN">
    <div class="justify-between items-end mb-4 flex">
        <div>
            <h1 class="topbar-title" style="font-size: 1.75rem;">Manajemen Informasi</h1>
            <p class="text-muted" style="margin-top: 4px;">Kelola pengumuman dan informasi strategis untuk anggota</p>
        </div>
        <button class="btn btn-primary" id="btnOpenAddModal">
            <i data-lucide="plus" style="width:18px;height:18px;margin-right:8px;"></i> Buat Informasi Baru
        </button>
    </div>

    <div class="table-card">
        <div class="table-header">
            <h3 class="modal-title">Daftar Pengumuman</h3>
            <div class="flex gap-2">
                <input type="text" id="infoSearchInput" class="form-input" style="width: 250px;" placeholder="Cari judul...">
            </div>
        </div>
        
        <div style="overflow-x: auto;">
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
