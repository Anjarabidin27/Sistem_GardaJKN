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

    <div id="main-content-area">
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
                <table class="data-table" id="main-table">
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
    </div>

    <!-- COMMAND CENTER UI PIL -->
    <div id="command-center-ui" style="display:none;">
        <div class="command-center">
            <!-- Pane 1: Context -->
            <div class="command-pane">
                <div class="pane-header"><i data-lucide="award"></i> Konteks PIL</div>
                <div class="pane-body">
                    <div class="context-card" style="background: #f0fdf4; border-color: #dcfce7;">
                        <div class="context-label" style="color: #166534;">Jenis Modul</div>
                        <div class="context-value">Program Informasi Langsung</div>
                    </div>
                    <div class="context-card">
                        <div class="context-label">Kantor Cabang</div>
                        <div class="context-value" id="ui-kc-name">{{ optional(auth('admin')->user())->kantor_cabang ?? '-' }}</div>
                    </div>
                    <div class="context-card">
                        <div class="context-label">Wilayah Kerja</div>
                        <div class="context-value" id="ui-kw-name">{{ optional(auth('admin')->user())->kedeputian_wilayah ?? '-' }}</div>
                    </div>
                    
                    <div style="margin-top: 20px;">
                        <button class="btn btn-secondary w-full" onclick="window.exitCommandCenter()">
                            <i data-lucide="arrow-left"></i> Kembali ke Daftar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Pane 2: Entry Form -->
            <div class="command-pane">
                <div class="pane-header">
                    <i data-lucide="clipboard-list"></i> Evaluasi PIL: <span id="active-kegiatan-title" style="margin-left: 5px; color: var(--success);">...</span>
                </div>
                <div class="pane-body form-compact">
                    <form id="pesertaForm">
                        <input type="hidden" id="entry_kegiatan_id">
                        
                        <div class="grid-2">
                            <div class="form-group">
                                <label class="form-label">NIK Peserta</label>
                                <input type="text" id="nik" name="nik" class="form-input" maxlength="16" required placeholder="NIK 16 Digit">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Nomor HP</label>
                                <input type="text" name="phone_number" class="form-input" placeholder="08xxxx">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Segmen Peserta</label>
                            <select id="segmen_peserta" name="segmen_peserta" class="form-input" required>
                                <option value="">- Pilih Segmen -</option>
                                <option value="PBPU">PBPU</option>
                                <option value="BP">BP</option>
                                <option value="PPU BU">PPU BU</option>
                                <option value="PPU Pemerintah">PPU Pemerintah</option>
                                <option value="PBI APBN">PBI APBN</option>
                                <option value="PBI APBD">PBI APBD</option>
                            </select>
                        </div>

                        <div class="grid-2">
                            <div class="form-group">
                                <div class="flex justify-between items-center mb-1">
                                    <label class="form-label mb-0">Jam Mulai Sosialisasi</label>
                                    <button type="button" onclick="window.setPilTime('mulai')" class="btn-text" style="font-size: 10px; color: var(--success);">Set Skg</button>
                                </div>
                                <input type="time" id="pil_jam_mulai" name="jam_sosialisasi_mulai" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <div class="flex justify-between items-center mb-1">
                                    <label class="form-label mb-0">Jam Selesai Sosialisasi</label>
                                    <button type="button" onclick="window.setPilTime('selesai')" class="btn-text" style="font-size: 10px; color: var(--success);">Set Skg</button>
                                </div>
                                <input type="time" id="pil_jam_selesai" name="jam_sosialisasi_selesai" class="form-input" required>
                            </div>
                        </div>

                        <div class="grid-2">
                            <div class="form-group">
                                <label class="form-label">Hasil Pemahaman (Score)</label>
                                <input type="number" name="nilai_pemahaman" class="form-input" min="0" max="100" required placeholder="0-100">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Efektifitas Sosialisasi</label>
                                <select name="efektifitas_sosialisasi" class="form-input" required>
                                    <option value="">- Pilih Efektifitas -</option>
                                    <option value="a. Sangat Tidak Memuaskan">a. Sangat Tidak Memuaskan</option>
                                    <option value="b. Tidak Memuaskan">b. Tidak Memuaskan</option>
                                    <option value="c. Kurang Memuaskan">c. Kurang Memuaskan</option>
                                    <option value="d. Cukup Memuaskan">d. Cukup Memuaskan</option>
                                    <option value="e. Memuaskan">e. Memuaskan</option>
                                    <option value="f. Sangat Memuaskan">f. Sangat Memuaskan</option>
                                </select>
                            </div>
                        </div>

                        <div style="background: #f8fafc; padding: 15px; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 20px;">
                            <label class="form-label" style="text-align: center; display: block; margin-bottom: 5px; font-weight: 800; color: var(--primary);">Net Promoter Score (NPS)</label>
                            <p style="font-size: 10px; text-align: center; color: var(--text-muted); margin-bottom: 15px;">Skala 1 (Sangat Tidak Setuju) s.d 10 (Sangat Setuju)</p>
                            
                            <div class="grid-3" style="gap: 15px;">
                                <div class="form-group mb-0">
                                    <label class="form-label" style="font-size: 11px;">Ketertarikan terhadap Program JKN</label>
                                    <select name="nps_ketertarikan" class="form-input" required>
                                        @for ($i = 1; $i <= 10; $i++) <option value="{{ $i }}" {{ $i == 10 ? 'selected' : '' }}>{{ $i }}</option> @endfor
                                    </select>
                                </div>
                                <div class="form-group mb-0">
                                    <label class="form-label" style="font-size: 11px;">Rekomendasi Program JKN</label>
                                    <select name="nps_rekomendasi_program" class="form-input" required>
                                        @for ($i = 1; $i <= 10; $i++) <option value="{{ $i }}" {{ $i == 10 ? 'selected' : '' }}>{{ $i }}</option> @endfor
                                    </select>
                                </div>
                                <div class="form-group mb-0">
                                    <label class="form-label" style="font-size: 11px;">Rekomendasi BPJS Kesehatan</label>
                                    <select name="nps_rekomendasi_bpjs" class="form-input" required>
                                        @for ($i = 1; $i <= 10; $i++) <option value="{{ $i }}" {{ $i == 10 ? 'selected' : '' }}>{{ $i }}</option> @endfor
                                    </select>
                                </div>
                            </div>
                        </div>

                        <button type="submit" form="pesertaForm" class="btn btn-success w-full" id="btn-save-peserta" style="height: 50px;">
                            <i data-lucide="check-circle"></i> Simpan Evaluasi & Entry Baru
                        </button>
                    </form>
                </div>
            </div>

            <!-- Pane 3: History -->
            <div class="command-pane">
                <div class="pane-header"><i data-lucide="users"></i> Daftar Peserta PIL</div>
                <div class="pane-body">
                    <input type="text" id="filter-peserta" class="form-input mb-3" placeholder="Cari NIK...">
                    <div id="peserta-list" style="display:flex; flex-direction:column; gap:12px;"></div>
                </div>
                <div style="padding: 20px; border-top: 1px solid var(--border);">
                    <button class="btn btn-primary w-full" id="btn-finish-kegiatan">
                        <i data-lucide="flag"></i> Selesaikan Agenda PIL
                    </button>
                </div>
            </div>
        </div>
    </div>

    @include('admin.pil.modals')

    @push('scripts')
        @vite(['resources/js/pages/admin_pil_index.js'])
    @endpush
</x-admin-layout>
