<x-admin-layout title="Command Hub - Garda JKN">
    <style>
        :root {
            --v-black: #000;
            --v-white: #fff;
            --v-gray-50: #fbfbfc;
            --v-gray-100: #f3f4f6;
            --v-gray-200: #e5e7eb;
            --v-gray-400: #9ca3af;
            --v-gray-500: #6b7280;
            --v-emerald-500: #10b981;
            --v-blue-600: #2ea0fb;
        }

        body { background-color: #f8f9fa; }

        .v-flex { display: flex; }
        .v-items-center { align-items: center; }
        .v-justify-between { justify-content: space-between; }
        
        .v-grid { display: grid; }
        .v-grid-4 { grid-template-columns: repeat(4, 1fr); }
        
        @media (max-width: 1200px) { .v-grid-4 { grid-template-columns: repeat(3, 1fr); } }
        @media (max-width: 1024px) { .v-grid-4 { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 640px) { 
            .v-grid-4 { grid-template-columns: repeat(2, 1fr); gap: 0.75rem; } 
            .v-card-nav { padding: 1rem !important; gap: 0.75rem !important; }
            .v-card-nav .v-card-icon { width: 32px !important; height: 32px !important; }
            .v-card-nav .v-card-icon i { width: 14px !important; height: 14px !important; }
            .v-card-nav .v-card-title { font-size: 0.8rem !important; }
        }

        .v-card-nav {
            background: var(--v-white);
            padding: 1.5rem;
            border-radius: 1rem;
            border: 1px solid var(--v-gray-100);
            text-decoration: none;
            color: inherit;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 1.25rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }
        .v-card-nav:hover {
            border-color: var(--v-black);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(0,0,0,0.05);
        }

        .v-card-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.75rem;
            background: var(--v-gray-50);
            flex-shrink: 0;
            transition: all 0.2s;
        }
        .v-card-nav:hover .v-card-icon {
            background: var(--v-black);
            color: var(--v-white);
        }

        .v-label-caps {
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--v-gray-400);
            display: block;
            margin-bottom: 2px;
        }

        .v-card-title {
            font-size: 0.95rem;
            font-weight: 800;
            color: var(--v-black);
            margin: 0;
        }

        .v-summary-bar {
            background: var(--v-black);
            color: var(--v-white);
            padding: 1.5rem 2rem;
            border-radius: 1.25rem;
            margin-bottom: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: space-around;
            box-shadow: 0 20px 40px -10px rgba(0,0,0,0.15);
        }
        .v-summary-item { text-align: center; }
        .v-summary-val { font-size: 1.75rem; font-weight: 900; letter-spacing: -0.02em; line-height: 1; }
        
        .btn-compact {
            background: var(--v-black);
            color: var(--v-white);
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 700;
            font-size: 0.75rem;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.375rem;
            text-decoration: none;
        }
        .btn-compact:hover { background: #333; }

        /* Mobile Responsiveness - Compact Design */
        @media (max-width: 768px) {
            .v-summary-bar {
                display: grid !important;
                grid-template-columns: repeat(3, 1fr);
                gap: 0.5rem !important;
                padding: 1rem !important;
                text-align: center;
            }
            .v-summary-item {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 0.25rem !important;
            }
            .v-summary-val {
                font-size: 1.75rem !important; /* Smaller text for compact fit */
            }
            .v-label-caps {
                font-size: 0.5rem !important;
                letter-spacing: 0.05em !important;
            }
            .v-summary-divider {
                display: none !important; /* Hide dividers in grid */
            }
            .v-flex-desktop {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 0.75rem !important;
                margin-bottom: 1.5rem !important;
            }
            .v-sidebar-action {
                width: 100% !important;
                padding: 1.5rem !important;
            }
            .header-actions {
                width: 100%;
                display: grid !important;
                grid-template-columns: 1fr 1fr;
                gap: 0.5rem !important;
                margin-top: 0.5rem;
            }
            .header-actions .btn-compact {
                width: 100%;
            }
            h1 { font-size: 1.25rem !important; }
        }
    </style>

    <!-- Sleek Header -->
    <div class="v-flex v-flex-desktop v-justify-between v-items-center" style="margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 900; letter-spacing: -0.02em; color: var(--v-black); margin: 0;">
                @if(optional(auth('admin')->user())->role === 'admin_wilayah')
                    Regional Command
                @else
                    Command Hub
                @endif
            </h1>
            <p style="font-size: 0.875rem; color: var(--v-gray-500); margin-top: 2px;">
                @if(optional(auth('admin')->user())->role === 'admin_wilayah')
                    Wilayah: <span style="color: var(--v-emerald-500); font-weight: 800;">{{ optional(auth('admin')->user())->kedeputian_wilayah }}</span>
                @else
                    Selamat datang, <span style="color: var(--v-black); font-weight: 700;">{{ optional(auth('admin')->user())->name }}</span>.
                @endif
            </p>
        </div>
        <div class="v-flex v-gap-2 header-actions">
            <a href="/admin/audit-logs" class="btn-compact" style="background: white; color: black; border: 1px solid var(--v-gray-200);">
                <i data-lucide="shield" style="width: 14px; height: 14px;"></i> Activity
            </a>
            <button class="btn-compact" onclick="location.reload()">
                <i data-lucide="zap" style="width: 14px; height: 14px;"></i> Sync
            </button>
        </div>
    </div>

    <!-- Compact Summary Bar -->
    <div class="v-summary-bar">
        <div class="v-summary-item">
            <span class="v-label-caps" style="color: var(--v-gray-400);">Anggota JKN</span>
            <div class="v-summary-val" id="hub-total-anggota">0</div>
        </div>
        <div class="v-summary-divider" style="width: 1px; height: 32px; background: rgba(255,255,255,0.1);"></div>
        <div class="v-summary-item">
            <span class="v-label-caps" style="color: var(--v-gray-400);">Sesi Lapangan</span>
            <div class="v-summary-val" id="hub-total-sesi">0</div>
        </div>
        <div class="v-summary-divider" style="width: 1px; height: 32px; background: rgba(255,255,255,0.1);"></div>
        <div class="v-summary-item">
            <span class="v-label-caps" style="color: var(--v-gray-400);">Total Impact</span>
            <div class="v-summary-val" id="hub-total-dampak">0</div>
        </div>
    </div>

    <!-- Modular Workspace Grid -->
    <div class="v-grid v-grid-4 v-gap-4" style="margin-bottom: 2.5rem;">
        <a href="/admin/pil/dashboard" class="v-card-nav">
            <div class="v-card-icon"><i data-lucide="bar-chart-2" style="width: 18px; height: 18px;"></i></div>
            <div><span class="v-label-caps">Analytics</span><h3 class="v-card-title">Dashboard PIL</h3></div>
        </a>
        <a href="/admin/bpjs-keliling/dashboard" class="v-card-nav">
            <div class="v-card-icon"><i data-lucide="container" style="width: 18px; height: 18px;"></i></div>
            <div><span class="v-label-caps">Field Ops</span><h3 class="v-card-title">BPJS Keliling</h3></div>
        </a>
        <a href="/admin/members" class="v-card-nav">
            <div class="v-card-icon"><i data-lucide="hard-drive" style="width: 18px; height: 18px;"></i></div>
            <div><span class="v-label-caps">Database</span><h3 class="v-card-title">Basis Data</h3></div>
        </a>
        @if(in_array(optional(auth('admin')->user())->role, ['superadmin', 'admin_wilayah']))
        <a href="/admin/staff" class="v-card-nav">
            <div class="v-card-icon"><i data-lucide="users-2" style="width: 18px; height: 18px;"></i></div>
            <div><span class="v-label-caps">Governance</span><h3 class="v-card-title">Manajemen Staff</h3></div>
        </a>
        @endif
    </div>

    @if(in_array(optional(auth('admin')->user())->role, ['superadmin', 'admin_wilayah']))
    <div class="v-flex v-flex-desktop v-gap-6" style="margin-bottom: 4rem;">
        <div style="flex: 1; background: white; padding: 2rem; border-radius: 1.25rem; border: 1px solid var(--v-gray-200); width: 100%; box-sizing: border-box;">
            <div class="v-flex v-flex-desktop v-justify-between v-items-center" style="margin-bottom: 1.5rem;">
                <div>
                    <h3 style="font-weight: 900; letter-spacing: -0.01em; margin: 0;" id="regional-title">National Overview</h3>
                    <p style="font-size: 0.75rem; color: var(--v-gray-400); margin: 0; font-weight: 700;" id="regional-subtitle">SEBARAN ANGGOTA PER REGIONAL</p>
                </div>
                
                @if(optional(auth('admin')->user())->role === 'superadmin')
                <select id="filter-kw" class="form-select" style="font-size: 0.75rem; font-weight: 800; border-radius: 0.5rem; padding: 0.4rem 2rem 0.4rem 0.75rem; border-color: var(--v-gray-200); max-width: 100%;">
                    <option value="">Seluruh Wilayah Nasional</option>
                    @foreach(\App\Models\KedeputianWilayah::orderBy('id')->get() as $kw)
                        <option value="{{ $kw->name }}">{{ $kw->name }}</option>
                    @endforeach
                </select>
                @endif
            </div>
            <div id="branch-performance-list" style="max-height: 400px; overflow-y: auto; padding-right: 10px;">
                <div style="padding: 2rem; text-align: center; color: var(--v-gray-400);">Memuat data regional...</div>
            </div>
        </div>
        <div class="v-sidebar-action" style="width: 320px; background: var(--v-black); color: white; padding: 2rem; border-radius: 1.25rem; display: flex; flex-direction: column; justify-content: center; box-sizing: border-box;">
            <h3 style="font-weight: 800; font-size: 1.1rem; margin-bottom: 0.75rem;">Quick Action</h3>
            <p style="font-size: 0.875rem; color: #888; margin-bottom: 1.5rem; line-height: 1.5;">Periksa pendaftaran pengurus baru di wilayah Anda.</p>
            <a href="/admin/members?status=pending" class="btn-compact" style="width: 100%; justify-content: center; background: white; color: black; padding: 0.875rem; border-radius: 0.75rem;">
                Buka Verifikasi
            </a>
        </div>
    </div>
    @endif

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Check for unauthorized access error in URL
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('error') === 'unauthorized_staff_access') {
                if (window.showToast) {
                    window.showToast('Akses Ditolak: Anda tidak memiliki izin untuk mengakses halaman tersebut.', 'error');
                } else {
                    alert('Akses Ditolak: Anda tidak memiliki izin untuk mengakses halaman tersebut.');
                }
                
                // Clean URL
                window.history.replaceState({}, document.title, window.location.pathname);
            }

            const filterKW = document.getElementById('filter-kw');

            function fetchHubData(kw = '') {
                window.axios.get(`admin/dashboard/hub-data?kedeputian_wilayah=${kw}`)
                    .then(res => {
                        const d = res.data.data;
                        document.getElementById('hub-total-anggota').innerText = d.total_members.toLocaleString();
                        document.getElementById('hub-total-sesi').innerText = d.total_sessions.toLocaleString();
                        document.getElementById('hub-total-dampak').innerText = d.total_impact.toLocaleString();
                    })
                    .catch(err => console.error("Hub data fail:", err));
            }

            function fetchRegionalStats(kw = '') {
                const container = document.getElementById('branch-performance-list');
                container.innerHTML = '<div style="padding: 2rem; text-align: center; color: var(--v-gray-400);">Memperbarui data...</div>';

                window.axios.get(`admin/dashboard?range=1&kedeputian_wilayah=${kw}`)
                    .then(res => {
                        const branches = res.data.data.distribution.branches || [];
                        
                        if (branches.length === 0) {
                            container.innerHTML = '<div style="padding: 2rem; text-align: center; color: var(--v-gray-400);">Belum ada data cabang di wilayah ini.</div>';
                            return;
                        }

                        let html = '';
                        const max = Math.max(...branches.map(b => b.total), 1);

                        branches.forEach(b => {
                            const percent = (b.total / max) * 100;
                            html += `
                                <div style="margin-bottom: 1.25rem;">
                                    <div class="v-flex v-justify-between v-items-center" style="margin-bottom: 0.5rem;">
                                        <span style="font-size: 0.875rem; font-weight: 700;">KC ${b.branch_name}</span>
                                        <span style="font-size: 0.875rem; font-weight: 800; color: var(--v-blue-600);">${b.total.toLocaleString()}</span>
                                    </div>
                                    <div style="height: 6px; background: var(--v-gray-100); border-radius: 10px; overflow: hidden;">
                                        <div style="width: ${percent}%; height: 100%; background: var(--v-black); border-radius: 10px;"></div>
                                    </div>
                                </div>
                            `;
                        });
                        container.innerHTML = html;
                    })
                    .catch(err => console.error("Regional stats fail:", err));
            }

            if (filterKW) {
                filterKW.addEventListener('change', (e) => {
                    const kw = e.target.value;
                    const title = document.getElementById('regional-title');
                    const subtitle = document.getElementById('regional-subtitle');

                    if (kw) {
                        title.innerText = 'Regional Performance';
                        subtitle.innerText = 'STATISTIK PER KANTOR CABANG';
                    } else {
                        title.innerText = 'National Overview';
                        subtitle.innerText = 'SEBARAN ANGGOTA PER REGIONAL';
                    }

                    fetchHubData(kw);
                    fetchRegionalStats(kw);
                });
            }

            // Initial Load
            fetchHubData();
            @if(in_array(optional(auth('admin')->user())->role, ['superadmin', 'admin_wilayah']))
                fetchRegionalStats();
            @endif
            
            if(window.lucide) window.lucide.createIcons();
        });
    </script>
    @endpush
</x-admin-layout>
