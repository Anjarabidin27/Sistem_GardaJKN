<x-pengurus-layout title="Manajemen Informasi - Garda JKN">
    <style>
        :root {
            --v-black: #000;
            --v-white: #fff;
            --v-gray-50: #fbfbfc;
            --v-gray-100: #f3f4f6;
            --v-gray-200: #e5e7eb;
            --v-gray-400: #9ca3af;
            --v-gray-500: #6b7280;
            --v-blue-600: #2ea0fb;
        }

        .header-row { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; }
        .v-title { font-size: 1.75rem; font-weight: 900; letter-spacing: -0.04em; color: var(--v-black); margin: 0; }
        .v-subtitle { font-size: 0.875rem; color: var(--v-gray-500); margin-top: 4px; }

        .btn-primary {
            background: var(--v-black); color: var(--v-white);
            padding: 12px 24px; border-radius: 12px; border: none;
            font-size: 0.875rem; font-weight: 800; cursor: pointer;
            display: flex; align-items: center; gap: 8px; transition: all 0.2s;
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 20px -5px rgba(0,0,0,0.1); }

        .filter-section { display: flex; gap: 8px; margin-bottom: 1.5rem; overflow-x: auto; padding-bottom: 8px; scrollbar-width: none; }
        .filter-section::-webkit-scrollbar { display: none; }
        .chip {
            padding: 8px 16px; background: var(--v-white); border: 1px solid var(--v-gray-200);
            border-radius: 999px; font-size: 0.8rem; font-weight: 700; color: var(--v-gray-500);
            cursor: pointer; white-space: nowrap; transition: all 0.2s;
        }
        .chip.active { background: var(--v-black); color: var(--v-white); border-color: var(--v-black); }

        .v-card {
            background: var(--v-white); border-radius: 1.25rem; border: 1px solid var(--v-gray-100);
            overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }
        .v-table { width: 100%; border-collapse: collapse; }
        .v-table th { 
            text-align: left; padding: 1rem 1.5rem; background: var(--v-gray-50);
            font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em;
            color: var(--v-gray-400); border-bottom: 1px solid var(--v-gray-100);
        }
        .v-table td { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--v-gray-50); vertical-align: middle; }
        .v-table tr:last-child td { border-bottom: none; }

        @media (max-width: 768px) {
            .header-row { flex-direction: column; align-items: flex-start; gap: 1rem; }
            .btn-primary { width: 100%; justify-content: center; }
        }
    </style>

    <div class="header-row">
        <div>
            <h1 class="v-title">Manajemen Informasi</h1>
            <p class="v-subtitle">Kelola pengumuman untuk anggota di wilayah koordinasi Anda.</p>
        </div>
        <button class="btn-primary" onclick="openAddModal()">
            <i data-lucide="plus" style="width: 18px; height: 18px;"></i> Buat Informasi
        </button>
    </div>

    <div class="filter-section">
        <div class="chip active">Semua</div>
        <div class="chip">Teks Saja</div>
        <div class="chip">Gambar</div>
        <div class="chip">Dokumen PDF</div>
    </div>

    <div class="v-card">
        <table class="v-table">
            <thead>
                <tr>
                    <th width="15%">Tanggal</th>
                    <th width="45%">Judul & Konten</th>
                    <th width="15%">Tipe</th>
                    <th width="15%">Status</th>
                    <th width="10%" style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody id="infoTableBody">
                <tr>
                    <td colspan="5" style="padding: 4rem; text-align: center;">
                        <span class="loading-spinner"></span>
                        <p style="font-size: 0.75rem; font-weight: 800; color: var(--v-gray-400); margin-top: 1rem; text-transform: uppercase; letter-spacing: 0.1em;">Memuat Data Informasi...</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <div id="paginationContainer" style="padding: 1.25rem 1.5rem; border-top: 1px solid var(--v-gray-100); background: var(--v-gray-50);"></div>
    </div>

    <!-- Modal Form -->
    <div id="infoModal" class="modal-overlay" style="display:none;">
        <div class="modal-content" style="max-width: 600px;">
            <form id="infoForm" onsubmit="submitForm(event)">
                <div class="modal-header">
                    <h3 id="modalTitle" style="font-weight: 800; color: #0f172a; font-size: 1.1rem;">Tambah Informasi</h3>
                    <button type="button" class="close-btn" onclick="document.getElementById('infoModal').style.display='none'">&times;</button>
                </div>
                <div class="modal-body" style="padding: 24px;">
                    <input type="hidden" id="infoId">
                    <div style="margin-bottom: 20px;">
                        <label class="form-label" style="font-size: 0.7rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; display: block; margin-bottom: 8px;">Judul Informasi</label>
                        <input type="text" id="title" class="form-input" placeholder="Contoh: Jadwal BPJS Keliling April" required style="width: 100%; padding: 12px; border-radius: 12px; border: 1.5px solid #f1f5f9; background: #fbfbfc; font-weight: 600;">
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                        <div>
                            <label class="form-label" style="font-size: 0.7rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; display: block; margin-bottom: 8px;">Tipe Konten</label>
                            <select id="type" class="form-input" onchange="toggleAttachmentField()" style="width: 100%; padding: 12px; border-radius: 12px; border: 1.5px solid #f1f5f9; background: #fbfbfc; font-weight: 600;">
                                <option value="text">Teks Saja</option>
                                <option value="image">Gambar (Poster)</option>
                                <option value="pdf">Dokumen (PDF)</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label" style="font-size: 0.7rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; display: block; margin-bottom: 8px;">Status Publikasi</label>
                            <div style="display: flex; align-items: center; gap: 12px; height: 48px;">
                                <input type="checkbox" id="is_active" checked style="width: 20px; height: 20px; accent-color: var(--v-black);">
                                <span style="font-size: 0.875rem; font-weight: 700; color: #334155;">Aktifkan Sekarang</span>
                            </div>
                        </div>
                    </div>
                    <div id="textField">
                        <label class="form-label" style="font-size: 0.7rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; display: block; margin-bottom: 8px;">Isi Pengumuman</label>
                        <textarea id="content" class="form-input" rows="5" placeholder="Tuliskan detail informasi di sini..." style="width: 100%; padding: 12px; border-radius: 12px; border: 1.5px solid #f1f5f9; background: #fbfbfc; font-weight: 600; font-family: inherit; resize: none;"></textarea>
                    </div>
                    <div id="attachmentField" style="display:none;">
                        <label class="form-label" style="font-size: 0.7rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; display: block; margin-bottom: 8px;">Pilih Berkas</label>
                        <input type="file" id="attachment" class="form-input" style="width: 100%; padding: 10px; border-radius: 12px; border: 1.5px solid #f1f5f9; background: #fbfbfc; font-weight: 600;">
                        <p style="font-size: 0.65rem; color: #94a3b8; margin-top: 8px; font-weight: 600;">* Format: JPG, PNG, atau PDF (Maks 2MB)</p>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 20px 24px; background: #fbfbfc; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 12px;">
                    <button type="button" class="btn-cancel" onclick="document.getElementById('infoModal').style.display='none'" style="background: none; border: none; font-size: 0.875rem; font-weight: 700; color: #64748b; cursor: pointer; padding: 10px 20px;">Batal</button>
                    <button type="submit" id="btnSubmit" class="btn-primary" style="padding: 10px 28px;">Simpan Informasi</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    @vite(['resources/js/pages/pengurus_informations.js'])
    @endpush
</x-pengurus-layout>
