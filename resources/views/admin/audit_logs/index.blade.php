<script>
    const userRole = localStorage.getItem('user_role');
    const allowedRoles = ['superadmin', 'administrator'];
    
    if (!userRole || !allowedRoles.includes(userRole)) {
        window.location.href = '/admin/dashboard?error=unauthorized_audit_access';
    }
</script>
<x-admin-layout title="Audit Logs - Garda JKN">
    <div class="mb-4 flex justify-between items-end" id="title-section">
        <div>
            <h1 class="modal-title" style="font-size: 1.75rem;">Log Audit Sistem</h1>
            <p class="text-muted" style="margin-top: 4px;">Riwayat aktivitas dan perubahan data kependudukan.</p>
        </div>
        <div class="text-muted font-bold" style="font-size: 0.8rem; background: #f1f5f9; padding: 8px 16px; border-radius: 8px;">
            <i data-lucide="calendar" style="width:14px; display:inline; margin-right:4px;"></i> <span id="date-now">...</span>
        </div>
    </div>

    <style>
        .filter-bar {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            align-items: center;
        }
        .filter-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .filter-item label {
            font-size: 0.7rem;
            font-weight: 700;
            color: #64748b;
            margin-bottom: 0;
            text-transform: uppercase;
        }
        .filter-bar .form-input {
            height: 38px;
            font-size: 0.8rem;
            padding: 0 12px;
            border-radius: 8px;
            width: 180px;
        }
        @media (max-width: 1024px) {
            .filter-bar .form-input { width: 150px; }
        }
        @media (max-width: 768px) {
            #title-section { flex-direction: column; align-items: flex-start !important; gap: 8px; margin-bottom: 12px !important; }
            #title-section p { font-size: 0.75rem; }
            
            .filter-bar { 
                display: grid !important;
                grid-template-columns: 1fr 1fr; 
                gap: 10px 8px; 
                padding: 12px;
                margin-bottom: 16px;
            }
            .filter-item { gap: 2px; }
            .filter-item label { font-size: 0.6rem; color: #94a3b8; }
            .filter-bar .form-input { 
                height: 34px; 
                font-size: 0.75rem; 
                width: 100% !important;
                padding: 0 8px;
            }
            .filter-bar .ms-auto { 
                grid-column: 1 / -1; 
                margin-left: 0 !important;
                margin-top: 4px;
            }
            .filter-bar .btn { height: 34px !important; font-size: 0.75rem; }
        }

        /* Mobile Table Optimization */
        @media (max-width: 768px) {
            .data-table thead th { 
                font-size: 0.65rem !important; 
                padding: 8px !important; 
                text-transform: uppercase;
                background: #f8fafc;
            }
            .data-table tbody td { 
                font-size: 0.7rem !important; 
                padding: 8px !important; 
            }
            .action-badge {
                padding: 2px 6px;
                font-size: 0.6rem;
            }
            .change-item { gap: 4px; margin-bottom: 2px; }
            .change-label { min-width: 60px; font-size: 0.65rem; }
        }

        .action-badge {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 800;
            white-space: nowrap;
        }
        .bg-login { background: #dcfce7; color: #166534; }
        .bg-logout { background: #fef2f2; color: #991b1b; }
        .bg-update { background: #fef9c3; color: #854d0e; }
        .bg-delete { background: #fee2e2; color: #991b1b; }
        .bg-reset { background: #e0f2fe; color: #075985; }

        .change-item {
            display: flex;
            gap: 8px;
            font-size: 0.75rem;
            margin-bottom: 4px;
            line-height: 1.4;
        }
        .change-label { color: #64748b; font-weight: 700; min-width: 80px; }
        .value-old { color: #94a3b8; text-decoration: line-through; }
        .value-new { color: #0f172a; font-weight: 600; }
        .change-arrow { color: #94a3b8; font-size: 0.7rem; }
    </style>

    <div class="filter-bar">
        <div class="filter-item">
            <label>Aktor/ID</label>
            <input type="text" id="filter-actor" class="form-input" placeholder="Nama/ID...">
        </div>
        <div class="filter-item">
            <label>Jenis Aksi</label>
            <select id="filter-action" class="form-input">
                <option value="">Semua Aksi</option>
                <option value="login">Login</option>
                <option value="logout">Logout</option>
                <option value="create">Tambah</option>
                <option value="update">Update</option>
                <option value="delete">Hapus</option>
                <option value="reset">Reset</option>
            </select>
        </div>
        <div class="filter-item">
            <label>Dari Tanggal</label>
            <input type="date" id="filter-start" class="form-input">
        </div>
        <div class="filter-item">
            <label>Hingga</label>
            <input type="date" id="filter-end" class="form-input">
        </div>
        <div class="flex gap-2 ms-auto" style="align-self: flex-end;">
            <button class="btn btn-secondary" onclick="resetFilter()" title="Reset" style="width: 38px; height: 38px; padding: 0; display: flex; align-items: center; justify-content: center;">
                <i data-lucide="rotate-ccw" style="width:16px;"></i>
            </button>
            <button class="btn btn-primary" onclick="window.fetchLogs(1)" style="height: 38px; padding: 0 20px;">
                <i data-lucide="filter" style="width:14px; margin-right:6px;"></i> Terapkan
            </button>
        </div>
    </div>

    <div class="table-card">
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 200px;">Waktu & Tanggal</th>
                        <th>Aktor Pelaksana</th>
                        <th>Jenis Log</th>
                        <th>Entitas Target</th>
                        <th>Metadata Perubahan</th>
                    </tr>
                </thead>
                <tbody id="logTableBody">
                    <!-- Data loaded via JS -->
                </tbody>
            </table>
        </div>
        <div class="table-footer" id="pagination">
            <!-- Pagination info -->
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/pages/admin_audit_logs_index.js'])
    @endpush
</x-admin-layout>

@push("scripts")
<script>
    window.sessionSuccess = "{{ session("success") }}";
    window.sessionError = "{{ session("error") }}";
</script>
@endpush
