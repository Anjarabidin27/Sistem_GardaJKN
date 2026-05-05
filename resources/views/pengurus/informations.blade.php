<x-pengurus-layout title="Manajemen Informasi - Pengurus Garda JKN">

    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 900; letter-spacing: -0.02em; color: #0f172a; margin: 0;">Manajemen Informasi</h1>
            <p style="font-size: 0.875rem; color: #64748b; margin-top: 4px;">Kelola pengumuman untuk anggota di wilayah Anda.</p>
        </div>
        <button onclick="openAddModal()" style="display: inline-flex; align-items: center; gap: 8px; background: #0f172a; color: white; padding: 10px 20px; border-radius: 10px; font-weight: 700; font-size: 0.875rem; border: none; cursor: pointer; transition: all 0.2s;">
            <i data-lucide="plus" style="width: 16px; height: 16px;"></i> Buat Informasi Baru
        </button>
    </div>

    <!-- Table Card -->
    <div style="background: white; border-radius: 1rem; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
        <div style="padding: 16px 24px; border-bottom: 1px solid #f1f5f9;">
            <h6 style="margin: 0; font-weight: 700; color: #0f172a; font-size: 0.875rem;">Daftar Pengumuman</h6>
        </div>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc;">
                        <th style="padding: 12px 24px; text-align: left; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: #64748b; border-bottom: 1px solid #e2e8f0; white-space: nowrap; width: 150px;">Tanggal</th>
                        <th style="padding: 12px 24px; text-align: left; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: #64748b; border-bottom: 1px solid #e2e8f0;">Informasi</th>
                        <th style="padding: 12px 24px; text-align: left; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: #64748b; border-bottom: 1px solid #e2e8f0; width: 120px;">Tipe</th>
                        <th style="padding: 12px 24px; text-align: left; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: #64748b; border-bottom: 1px solid #e2e8f0; width: 100px;">Status</th>
                        <th style="padding: 12px 24px; text-align: right; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: #64748b; border-bottom: 1px solid #e2e8f0; width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="infoTableBody">
                    <!-- Content loaded via AJAX -->
                </tbody>
            </table>
        </div>
        <div style="padding: 16px 24px; background: #f8fafc; border-top: 1px solid #e2e8f0;" id="paginationContainer"></div>
    </div>

    <!-- Modal Add/Edit -->
    <div id="infoModal" class="modal-overlay" style="display:none;">
        <div class="confirm-card" style="max-width: 600px; width: 100%; border-radius: 1.25rem; padding: 0; overflow: hidden;">
            <form id="infoForm" onsubmit="submitForm(event)">
                <input type="hidden" id="infoId">
                <div style="padding: 20px 24px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                    <h5 style="margin: 0; font-weight: 800; font-size: 1rem; color: #0f172a;" id="modalTitle">Tambah Informasi</h5>
                    <button type="button" onclick="document.getElementById('infoModal').style.display='none'" style="background: none; border: none; cursor: pointer; color: #64748b;">
                        <i data-lucide="x" style="width: 20px; height: 20px;"></i>
                    </button>
                </div>
                <div style="padding: 24px;">
                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #374151; margin-bottom: 6px;">Judul</label>
                        <input type="text" id="title" style="width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.875rem; box-sizing: border-box;" required placeholder="Contoh: Pengumuman Rapat Anggota">
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                        <div>
                            <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #374151; margin-bottom: 6px;">Tipe Informasi</label>
                            <select id="type" style="width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.875rem;" onchange="toggleAttachmentField()">
                                <option value="text">Teks Manual</option>
                                <option value="image">Foto/Gambar</option>
                                <option value="pdf">Dokumen PDF</option>
                            </select>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #374151; margin-bottom: 6px;">Status</label>
                            <div style="display: flex; align-items: center; gap: 10px; margin-top: 8px;">
                                <input type="checkbox" id="is_active" checked style="width: 16px; height: 16px; cursor: pointer;">
                                <label for="is_active" style="font-size: 0.875rem; color: #374151; cursor: pointer;">Aktif (Tampilkan)</label>
                            </div>
                        </div>
                    </div>

                    <div style="margin-bottom: 16px;" id="textField">
                        <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #374151; margin-bottom: 6px;">Isi Informasi</label>
                        <textarea id="content" style="width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.875rem; resize: none; box-sizing: border-box;" rows="5" placeholder="Ketik informasi di sini..."></textarea>
                    </div>

                    <div style="margin-bottom: 16px; display: none;" id="attachmentField">
                        <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #374151; margin-bottom: 6px;" id="attachmentLabel">Lampiran File</label>
                        <input type="file" id="attachment" name="attachment" style="width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.875rem; box-sizing: border-box;">
                        <div id="currentAttachment" style="margin-top: 8px;"></div>
                    </div>
                </div>
                <div style="padding: 16px 24px; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 12px;">
                    <button type="button" onclick="document.getElementById('infoModal').style.display='none'" style="padding: 10px 20px; background: #f1f5f9; border: none; border-radius: 8px; font-weight: 600; font-size: 0.875rem; cursor: pointer;">Batal</button>
                    <button type="submit" id="btnSubmit" style="padding: 10px 20px; background: #0f172a; color: white; border: none; border-radius: 8px; font-weight: 700; font-size: 0.875rem; cursor: pointer;">Simpan Informasi</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    @vite(['resources/css/pages/pengurus_informations.css', 'resources/js/pages/pengurus_informations.js'])
    @endpush
</x-pengurus-layout>
