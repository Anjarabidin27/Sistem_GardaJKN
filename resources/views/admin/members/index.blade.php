<x-admin-layout title="Manajemen Anggota - Garda JKN">
    <div class="table-card" 
         id="memberContext" 
         data-role="{{ auth('admin')->user()->role }}" 
         data-region="{{ auth('admin')->user()->kedeputian_wilayah }}">
        <div class="table-header">
            <div>
                <h2 class="modal-title">Daftar Anggota Sistem</h2>
                <p class="text-muted" style="font-size: 0.85rem; margin-top: 4px;">Data kependudukan terverifikasi nasional.</p>
            </div>
            <div class="header-actions flex gap-2">
                <input type="text" id="searchInput" placeholder="Cari Nama/NIK...." class="form-input" style="width: 200px;">
                <div class="status-tabs" style="display: flex; background: #f1f5f9; padding: 4px; border-radius: 8px; gap: 4px;">
                    <button class="tab-btn active" data-val="false" style="padding: 6px 12px; border: none; background: white; border-radius: 6px; font-size: 0.85rem; font-weight: 600; cursor: pointer; box-shadow: 0 1px 3px rgba(0,0,0,0.1); color: #0f172a;">Data Aktif</button>
                    <button class="tab-btn" data-val="pending" style="padding: 6px 12px; border: none; background: transparent; border-radius: 6px; font-size: 0.85rem; font-weight: 600; cursor: pointer; color: #64748b;">Menunggu Persetujuan</button>
                    <button class="tab-btn" data-val="true" style="padding: 6px 12px; border: none; background: transparent; border-radius: 6px; font-size: 0.85rem; font-weight: 600; cursor: pointer; color: #64748b;">Data Arsip</button>
                </div>
                <input type="hidden" id="statusFilter" value="false">
                <select id="provinceFilter" class="form-input" style="width: auto;">
                    <option value="">Seluruh Wilayah</option>
                </select>
                <button class="btn btn-primary" id="btnOpenAddMemberModal">+ Registrasi Baru</button>
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Informasi Anggota</th>
                        <th>Kontak Aktif</th>
                        <th>Domisili Wilayah</th>
                        <th>Klasifikasi</th>
                        <th>Role/Status</th>
                        <th class="text-right">Opsi</th>
                    </tr>
                </thead>
                <tbody id="memberTableBody">
                    <!-- Data loaded via JS -->
                </tbody>
            </table>
        </div>

        <div class="table-footer">
            <div class="text-muted" id="pagination-info" style="font-size: 0.85rem;">Menampilkan ...</div>
            <div class="flex gap-2">
                <button class="btn btn-secondary" id="btn-prev">Sebelumnya</button>
                <button class="btn btn-secondary" id="btn-next">Selanjutnya</button>
            </div>
        </div>
    </div>

    <!-- Modal Add/Edit Templates -->
    @include('admin.members.modals')

    @push('scripts')
        @vite(['resources/js/pages/admin_members_index.js'])
    @endpush
</x-admin-layout>
