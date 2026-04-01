<x-admin-layout title="Manajemen Jadwal - PIL">
    <div class="justify-between items-end mb-4 flex">
        <div>
            <h1 class="topbar-title" style="font-size: 1.75rem;">Jadwal Penyuluhan / PIL</h1>
            <p class="text-muted" style="margin-top: 4px;">Manajemen pelaksanaan kegiatan Program Informasi Langsung (PIL).</p>
        </div>
        <div class="flex gap-2">
            <a href="/admin/pil/dashboard" class="btn btn-secondary">Lihat Dashboard</a>
            <button class="btn btn-primary" id="btn-add" style="padding: 12px 24px;">+ Agenda PIL Baru</button>
        </div>
    </div>

    <div class="table-card p-4">
        <div class="justify-between items-center mb-4 flex">
            <h3 class="modal-title">Daftar Kegiatan</h3>
            <div class="flex gap-2">
                <select id="filter-status" class="form-input" style="width:auto;">
                    <option value="">Semua Status</option>
                    <option value="scheduled">Terjadwal</option>
                    <option value="ongoing">Berlangsung</option>
                    <option value="completed">Selesai</option>
                    <option value="cancelled">Dibatalkan</option>
                </select>
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Kegiatan & Waktu</th>
                        <th>Lokasi</th>
                        <th>Status & Laporan</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    <tr><td colspan="4" class="text-center text-muted p-4">Memuat data...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    @include('admin.pil.modals')

    @push('scripts')
        @vite(['resources/js/pages/admin_pil_index.js'])
    @endpush
</x-admin-layout>
