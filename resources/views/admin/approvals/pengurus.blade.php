<x-admin-layout title="Persetujuan Pengurus - Admin Panel">
    <style>
        :root {
            --v-black: #000;
            --v-white: #fff;
            --v-gray-50: #f9fafb;
            --v-gray-100: #f3f4f6;
            --v-gray-200: #e5e7eb;
            --v-gray-400: #9ca3af;
            --v-gray-500: #6b7280;
            --v-emerald-500: #10b981;
            --v-blue-600: #2563eb;
        }

        .v-flex { display: flex; }
        .v-items-center { align-items: center; }
        .v-justify-between { justify-content: space-between; }
        .v-gap-2 { gap: 0.5rem; }
        .v-gap-3 { gap: 0.75rem; }
        
        .v-card {
            background: var(--v-white);
            border-radius: 1rem;
            border: 1px solid var(--v-gray-100);
            overflow: hidden;
        }

        .v-filter-bar {
            background: var(--v-white);
            padding: 0.75rem 1.25rem;
            border-bottom: 1px solid var(--v-gray-100);
            display: flex;
            align-items: center;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .v-label-caps {
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--v-gray-400);
            margin-bottom: 2px;
            display: block;
        }

        .v-input {
            border: 1px solid var(--v-gray-200);
            border-radius: 0.5rem;
            padding: 0.4rem 0.75rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--v-black);
            background: var(--v-gray-50);
            transition: all 0.2s;
        }
        .v-input:focus { outline: none; border-color: var(--v-black); background: white; }

        .v-table { width: 100%; border-collapse: collapse; }
        .v-table th { 
            text-align: left; 
            padding: 0.75rem 1.25rem; 
            background: var(--v-gray-50); 
            font-size: 10px; 
            font-weight: 800; 
            text-transform: uppercase; 
            letter-spacing: 0.05em;
            color: var(--v-gray-500);
            border-bottom: 1px solid var(--v-gray-100);
        }
        .v-table td { 
            padding: 0.75rem 1.25rem; 
            border-bottom: 1px solid var(--v-gray-50); 
            font-size: 0.875rem;
            vertical-align: middle;
        }
        .v-table tr:hover { background: #fafafa; }

        .v-btn-action {
            padding: 0.4rem 0.8rem;
            border-radius: 0.5rem;
            font-size: 0.7rem;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            border: 1px solid transparent;
        }
        .v-btn-approve { background: var(--v-black); color: var(--v-white); }
        .v-btn-approve:hover { transform: translateY(-1px); opacity: 0.9; }
        .v-btn-reject { background: white; border-color: #fee2e2; color: #dc2626; }
        .v-btn-reject:hover { background: #fef2f2; }

        .v-badge {
            padding: 2px 8px;
            border-radius: 9999px;
            font-size: 9px;
            font-weight: 900;
            text-transform: uppercase;
        }
    </style>

    <!-- Header Section -->
    <div class="v-flex v-justify-between v-items-center" style="margin-bottom: 1.5rem;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 900; letter-spacing: -0.03em; color: var(--v-black); margin: 0;">Persetujuan Pengurus</h1>
            <p style="font-size: 0.85rem; color: var(--v-gray-500); margin-top: 2px;">Tinjau pengajuan baru dalam antrian sistem.</p>
        </div>
        <div style="background: var(--v-black); color: white; padding: 0.5rem 1rem; border-radius: 0.75rem; font-size: 0.75rem; font-weight: 800;">
            {{ count($applicants) }} Permohonan
        </div>
    </div>

    <div class="v-card">
        <!-- Filter Bar: Date & Search -->
        <div class="v-filter-bar">
            <div>
                <span class="v-label-caps">Periode Daftar</span>
                <div class="v-flex v-items-center v-gap-2">
                    <input type="date" id="filter-dari" class="v-input">
                    <span style="color: var(--v-gray-200);">/</span>
                    <input type="date" id="filter-sampai" class="v-input">
                </div>
            </div>
            <div style="flex: 1; max-width: 300px;">
                <span class="v-label-caps">Pencarian</span>
                <input type="text" id="filter-search" class="v-input" placeholder="Cari NIK atau Nama..." style="width: 100%;">
            </div>
            <div style="margin-left: auto;">
                <button class="v-input" style="cursor:pointer;" id="btn-reset">Reset</button>
            </div>
        </div>

        <!-- Compact Table -->
        <table class="v-table">
            <thead>
                <tr>
                    <th width="25%">Calon Pengurus</th>
                    <th width="20%">Minat Bidang</th>
                    <th width="15%">Kualifikasi</th>
                    <th width="25%">Riwayat & Bukti</th>
                    <th width="15%" style="text-align: right;">Opsi</th>
                </tr>
            </thead>
            <tbody id="applicant-tbody">
                @forelse($applicants as $app)
                    <tr class="app-row" data-date="{{ $app->created_at->format('Y-m-d') }}">
                        <td>
                            <div class="v-flex v-items-center v-gap-3">
                                <div style="width: 32px; height: 32px; background: var(--v-gray-50); border: 1px solid var(--v-gray-100); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 0.7rem; color: var(--v-gray-500);">
                                    {{ substr($app->name, 0, 1) }}
                                </div>
                                <div class="v-flex-col">
                                    <span style="font-weight: 800; color: var(--v-black); display: block;">{{ $app->name }}</span>
                                    <span style="font-size: 10px; color: var(--v-gray-400); font-weight: 700;">{{ $app->nik }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="v-flex v-flex-col v-gap-2">
                                @if($app->interest_pil)
                                    <span class="v-badge" style="background: rgba(37, 99, 235, 0.05); color: var(--v-blue-600); border: 1px solid rgba(37, 99, 235, 0.1);">PIL</span>
                                @endif
                                @if($app->interest_keliling)
                                    <span class="v-badge" style="background: rgba(16, 185, 129, 0.05); color: var(--v-emerald-500); border: 1px solid rgba(16, 185, 129, 0.1);">BPJS Keliling</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($app->has_org_experience)
                                <span style="font-weight: 800; color: var(--v-emerald-500); font-size: 10px;">PRO EXPERT</span>
                            @else
                                <span style="font-weight: 700; color: var(--v-gray-400); font-size: 10px;">GENERAL</span>
                            @endif
                        </td>
                        <td>
                            <div class="v-flex-col">
                                <span style="font-weight: 700; font-size: 0.8rem; color: var(--v-black);">{{ $app->org_name ?: '-' }}</span>
                                @if($app->org_certificate_path)
                                    <a href="{{ asset('storage/' . $app->org_certificate_path) }}" target="_blank" style="font-size: 9px; font-weight: 800; color: var(--v-blue-600); text-decoration: none; margin-top: 2px; display: inline-flex; align-items: center; gap: 4px;">
                                        <i data-lucide="paperclip" style="width: 10px; height: 10px;"></i> LIHAT SERTIFIKAT
                                    </a>
                                @endif
                            </div>
                        </td>
                        <td style="text-align: right;">
                            <div class="v-flex v-items-center v-gap-2" style="justify-content: flex-end;">
                                <form id="approve-form-{{ $app->id }}" action="{{ route('admin.approvals.approve', $app->id) }}" method="POST" style="display:none;">@csrf</form>
                                <form id="reject-form-{{ $app->id }}" action="{{ route('admin.approvals.reject', $app->id) }}" method="POST" style="display:none;">@csrf</form>
                                
                                <button type="button" class="v-btn-action v-btn-approve" onclick="confirmAction('approve-form-{{ $app->id }}', 'Terima pendaftar?', 'success')">
                                    <i data-lucide="check" style="width: 12px; height: 12px;"></i>
                                </button>
                                <button type="button" class="v-btn-action v-btn-reject" onclick="confirmAction('reject-form-{{ $app->id }}', 'Tolak pendaftar?', 'danger')">
                                    <i data-lucide="x" style="width: 12px; height: 12px;"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 4rem; text-align: center;">
                            <i data-lucide="inbox" style="width: 40px; height: 40px; color: var(--v-gray-200); margin-bottom: 1rem;"></i>
                            <p class="v-label-caps">Antrian Kosong</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dari = document.getElementById('filter-dari');
            const sampai = document.getElementById('filter-sampai');
            const search = document.getElementById('filter-search');
            const rows = document.querySelectorAll('.app-row');

            function applyFilters() {
                const q = search.value.toLowerCase();
                const dMin = dari.value ? new Date(dari.value) : null;
                const dMax = sampai.value ? new Date(sampai.value) : null;

                rows.forEach(row => {
                    const txt = row.innerText.toLowerCase();
                    const rowDate = new Date(row.dataset.date);
                    
                    let show = true;
                    if (q && !txt.includes(q)) show = false;
                    if (dMin && rowDate < dMin) show = false;
                    if (dMax && rowDate > dMax) show = false;

                    row.style.display = show ? '' : 'none';
                });
            }

            [dari, sampai, search].forEach(el => el.addEventListener('input', applyFilters));

            document.getElementById('btn-reset').addEventListener('click', () => {
                dari.value = ''; sampai.value = ''; search.value = '';
                applyFilters();
            });

            if(window.lucide) window.lucide.createIcons();
        });
    </script>
    @endpush
</x-admin-layout>
