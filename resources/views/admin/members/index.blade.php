<x-admin-layout title="Manajemen Anggota - Garda JKN">
    <div class="table-card">
        <div class="table-header">
            <div>
                <h2 class="modal-title">Daftar Anggota Sistem</h2>
                <p class="text-muted" style="font-size: 0.85rem; margin-top: 4px;">Data kependudukan terverifikasi nasional.</p>
            </div>
            <div class="header-actions flex gap-2">
                <input type="text" id="searchInput" placeholder="Cari Nama/NIK...." class="form-input" style="width: 200px;">
                <select id="statusFilter" class="form-input" style="width: auto;">
                    <option value="false">Data Aktif</option>
                    <option value="pending">Menunggu Persetujuan</option>
                    <option value="true">Arsip Dihapus</option>
                </select>
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
