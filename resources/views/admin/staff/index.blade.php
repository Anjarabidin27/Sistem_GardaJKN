<x-admin-layout title="Master Petugas - Garda JKN">
    <style>
        .staff-card { background: #fff; border: 1px solid #E2E8F0; border-radius: 12px; padding: 16px; display: flex; align-items: center; gap: 16px; transition: all 0.2s; }
        .staff-card:hover { border-color: #000; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .staff-card-icon { width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1rem; }
        .staff-card-val { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.5rem; font-weight: 800; color: #0F172A; line-height: 1; }
        .staff-card-lbl { font-size: 0.7rem; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 4px; }
        
        .v-table { width: 100%; border-collapse: separate; border-spacing: 0; }
        .v-table th { font-size: 0.7rem; font-weight: 800; color: #64748B; text-transform: uppercase; letter-spacing: 0.08em; padding: 16px; border-bottom: 1px solid #E2E8F0; background: #F8FAFC; text-align: left; }
        .v-table td { padding: 16px; border-bottom: 1px solid #F1F5F9; font-size: 0.85rem; vertical-align: middle; }
        .v-table tr:hover td { background: #FAFBFC; }
        
        .role-badge { padding: 4px 12px; border-radius: 6px; font-size: 0.75rem; font-weight: 800; display: inline-flex; align-items: center; text-transform: uppercase; letter-spacing: 0.02em; }
        .role-superadmin { background: #1E293B; color: #F8FAFC; }
        .role-admin { background: #F0F9FF; color: #0284C7; border: 1px solid #BAE6FD; }
        .role-petugas { background: #F8FAFC; color: #475569; border: 1px solid #E2E8F0; }

        .source-badge { font-size: 10px; font-weight: 900; padding: 2px 8px; border-radius: 4px; text-transform: uppercase; letter-spacing: 0.03em; }
        .source-asli { background: #F1F5F9; color: #64748B; border: 1px solid #E2E8F0; }
        .source-member { background: #ECFDF5; color: #059669; border: 1px solid #D1FAE5; }

        .btn-action { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 8px; border: 1px solid #E2E8F0; background: #fff; color: #64748B; transition: all 0.2s; cursor: pointer; }
        .btn-action:hover { border-color: #000; color: #000; transform: translateY(-1px); box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .btn-action.delete:hover { border-color: #EF4444; color: #EF4444; }
    </style>

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 style="font-size: 1.25rem; font-weight: 800; color: #000; letter-spacing: -0.02em;">Master Data Petugas</h1>
            <p style="font-size: 0.8rem; color: #64748B;">Kelola otentikasi dan penempatan unit kerja staf.</p>
        </div>
        <div class="flex gap-3">
            <select id="filterSource" class="form-input" style="background: #fff; font-size: 0.75rem; font-weight: 700; padding: 8px 12px; border-radius: 6px; border: 1px solid #E2E8F0; width: 140px;" onchange="window.handleFilterChange()">
                <option value="all">Semua Asal</option>
                <option value="asli">Admin / Staff</option>
                <option value="member">Jalur Member</option>
            </select>
            <button class="btn" style="background: #000; color: #fff; font-size: 0.75rem; font-weight: 700; padding: 8px 16px; border-radius: 6px;" id="btnOpenAddStaffModal">
                <i data-lucide="user-plus" style="width: 14px; height: 14px; margin-right: 6px;"></i> Tambah Petugas
            </button>
        </div>
    </div>

    <!-- Counters -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 24px;">
        <div class="staff-card">
            <div class="staff-card-icon" style="background: #000; color: #fff;"><i data-lucide="shield-check" style="width:16px"></i></div>
            <div>
                <div class="staff-card-val" id="count-superadmin">0</div>
                <div class="staff-card-lbl">Super Admin</div>
            </div>
        </div>
        <div class="staff-card">
            <div class="staff-card-icon" style="background: #E0F2FE; color: #0369A1;"><i data-lucide="map" style="width:16px"></i></div>
            <div>
                <div class="staff-card-val" id="count-wilayah">0</div>
                <div class="staff-card-lbl">Admin Wilayah</div>
            </div>
        </div>
        <div class="staff-card">
            <div class="staff-card-icon" style="background: #F1F5F9; color: #475569;"><i data-lucide="truck" style="width:16px"></i></div>
            <div>
                <div class="staff-card-val" id="count-keliling">0</div>
                <div class="staff-card-lbl">Petugas Keliling</div>
            </div>
        </div>
        <div class="staff-card">
            <div class="staff-card-icon" style="background: #F1F5F9; color: #475569;"><i data-lucide="mic" style="width:16px"></i></div>
            <div>
                <div class="staff-card-val" id="count-pil">0</div>
                <div class="staff-card-lbl">Petugas PIL</div>
            </div>
        </div>
    </div>

    <div style="background: #fff; border: 1px solid #E2E8F0; border-radius: 8px; overflow: hidden;">
        <table class="v-table">
            <thead>
                <tr>
                    <th>Informasi Petugas</th>
                    <th>Asal Akun</th>
                    <th>Hak Akses / Role</th>
                    <th>Unit Kerja (KC)</th>
                    <th>Kedeputian Wilayah</th>
                    <th style="text-align: right; width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="staffTableBody">
                <!-- Data loaded via JS -->
            </tbody>
        </table>
    </div>

    <!-- Modal (Vercel Style) -->
    <div id="staffModal" class="modal-overlay" style="display:none; background: rgba(0,0,0,0.4); backdrop-filter: blur(4px);">
        <div class="modal-content" style="max-width: 500px; border-radius:12px; border: 1px solid #E2E8F0; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
            <div style="padding: 24px; border-bottom: 1px solid #F1F5F9;">
                <h3 id="modalTitle" style="font-weight: 800; font-size: 1.1rem; color: #000;">Tambah Petugas</h3>
                <p style="font-size: 0.8rem; color: #64748B;">Pastikan data penempatan unit kerja sudah benar.</p>
            </div>
            <div style="padding: 24px;">
                <form id="staffForm" style="display: flex; flex-direction: column; gap: 16px;">
                    <div style="display: grid; grid-template-columns: 1fr; gap: 12px;">
                        <div>
                            <label class="v-label" style="display:block; margin-bottom: 6px;">Nama Lengkap</label>
                            <input type="text" id="staffName" class="form-input" required style="width:100%; border-radius: 6px; border: 1px solid #E2E8F0; padding: 8px 12px; font-size: 0.85rem;">
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                            <div>
                                <label class="v-label" style="display:block; margin-bottom: 6px;">Username</label>
                                <input type="text" id="staffUsername" class="form-input" required style="width:100%; border-radius: 6px; border: 1px solid #E2E8F0; padding: 8px 12px; font-size: 0.85rem;">
                            </div>
                            <div>
                                <label class="v-label" style="display:block; margin-bottom: 6px;">Role / Akses</label>
                                <select id="staffRole" class="form-input" style="width:100%; border-radius: 6px; border: 1px solid #E2E8F0; padding: 8px 12px; font-size: 0.85rem;">
                                    <option value="superadmin">Super Admin</option>
                                    <option value="administrator">Administrator</option>
                                    <option value="admin_wilayah">Admin Wilayah</option>
                                    <option value="petugas_keliling">Petugas Keliling</option>
                                    <option value="petugas_pil">Petugas PIL</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div style="padding: 16px; background: #F8FAFC; border-radius: 8px; border: 1px dashed #E2E8F0;">
                        <label class="v-label" style="display:block; margin-bottom: 6px;">Penempatan Kantor Cabang</label>
                        <select id="staffKC" class="form-input" required onchange="window.handleKCChange()" style="width:100%; border-radius: 6px; border: 1px solid #E2E8F0; padding: 8px 12px; font-size: 0.85rem;">
                            <option value="">Pilih Kantor Cabang...</option>
                        </select>
                        <div style="margin-top: 10px;">
                            <label class="v-label" style="display:block; margin-bottom: 4px;">Region (Otomatis)</label>
                            <input type="text" id="staffKW" readonly style="width:100%; border:none; background: transparent; font-size: 0.8rem; font-weight: 700; color: #0369A1;">
                        </div>
                    </div>

                    <div>
                        <label id="passwordLabel" class="v-label" style="display:block; margin-bottom: 6px;">Kata Sandi</label>
                        <input type="password" id="staffPassword" class="form-input" style="width:100%; border-radius: 6px; border: 1px solid #E2E8F0; padding: 8px 12px; font-size: 0.85rem;">
                        <p id="passwordNote" style="font-size: 0.65rem; color: #94A3B8; margin-top: 4px;">Kosongkan jika tidak ingin mengubah password.</p>
                    </div>
                </form>
            </div>
            <div style="padding: 16px 24px; background: #F8FAFC; border-top: 1px solid #F1F5F9; border-radius: 0 0 12px 12px; display: flex; justify-content: flex-end; gap: 10px;">
                <button class="btn" style="background: #fff; border: 1px solid #E2E8F0; color: #64748B; font-size: 0.75rem; font-weight: 700; padding: 8px 16px;" onclick="window.closeModal()">Batal</button>
                <button class="btn" id="btnSubmitStaff" style="background: #000; color: #fff; font-size: 0.75rem; font-weight: 700; padding: 8px 16px;" onclick="window.submitStaff()">Simpan Perubahan</button>
            </div>
        </div>
    </div>

    @push('scripts')
    @vite(['resources/js/pages/admin_staff_index.js'])
    @endpush
</x-admin-layout>
