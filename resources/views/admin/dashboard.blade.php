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
        @media (max-width: 640px) { .v-grid-4 { grid-template-columns: 1fr; } }

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
            gap: 0.375rem;
            text-decoration: none;
        }
        .btn-compact:hover { background: #333; }
    </style>

    <!-- Sleek Header -->
    <div class="v-flex v-justify-between v-items-center" style="margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 900; letter-spacing: -0.02em; color: var(--v-black); margin: 0;">Command Hub</h1>
            <p style="font-size: 0.875rem; color: var(--v-gray-500); margin-top: 2px;">Selamat datang, <span style="color: var(--v-black); font-weight: 700;">Administrator</span>.</p>
        </div>
        <div class="v-flex v-gap-2">
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
        <div style="width: 1px; height: 32px; background: rgba(255,255,255,0.1);"></div>
        <div class="v-summary-item">
            <span class="v-label-caps" style="color: var(--v-gray-400);">Sesi Lapangan</span>
            <div class="v-summary-val" id="hub-total-sesi">0</div>
        </div>
        <div style="width: 1px; height: 32px; background: rgba(255,255,255,0.1);"></div>
        <div class="v-summary-item">
            <span class="v-label-caps" style="color: var(--v-gray-400);">Total Impact</span>
            <div class="v-summary-val" id="hub-total-dampak">0</div>
        </div>
    </div>

    <!-- Modular Workspace Grid -->
    <div class="v-grid v-grid-4 v-gap-4" style="margin-bottom: 4rem;">
        
        <a href="/admin/pil/dashboard" class="v-card-nav">
            <div class="v-card-icon"><i data-lucide="bar-chart-2" style="width: 18px; height: 18px;"></i></div>
            <div>
                <span class="v-label-caps">Analytics</span>
                <h3 class="v-card-title">Dashboard PIL</h3>
            </div>
        </a>

        <a href="/admin/bpjs-keliling/dashboard" class="v-card-nav">
            <div class="v-card-icon"><i data-lucide="container" style="width: 18px; height: 18px;"></i></div>
            <div>
                <span class="v-label-caps">Field Ops</span>
                <h3 class="v-card-title">BPJS Keliling</h3>
            </div>
        </a>

        <a href="/admin/members" class="v-card-nav">
            <div class="v-card-icon"><i data-lucide="hard-drive" style="width: 18px; height: 18px;"></i></div>
            <div>
                <span class="v-label-caps">Database</span>
                <h3 class="v-card-title">Basis Data</h3>
            </div>
        </a>

        <a href="/admin/approvals" class="v-card-nav">
            <div class="v-card-icon"><i data-lucide="key-round" style="width: 18px; height: 18px;"></i></div>
            <div>
                <span class="v-label-caps">Workforce</span>
                <h3 class="v-card-title">Pemeriksaan</h3>
            </div>
        </a>

        <a href="/admin/staff" class="v-card-nav">
            <div class="v-card-icon"><i data-lucide="users-2" style="width: 18px; height: 18px;"></i></div>
            <div>
                <span class="v-label-caps">Governance</span>
                <h3 class="v-card-title">Manajemen Staff</h3>
            </div>
        </a>

        <a href="/admin/informations" class="v-card-nav">
            <div class="v-card-icon"><i data-lucide="newspaper" style="width: 18px; height: 18px;"></i></div>
            <div>
                <span class="v-label-caps">Broadcast</span>
                <h3 class="v-card-title">Informasi Publik</h3>
            </div>
        </a>

        <a href="/admin/audit-logs" class="v-card-nav">
            <div class="v-card-icon"><i data-lucide="fingerprint" style="width: 18px; height: 18px;"></i></div>
            <div>
                <span class="v-label-caps">Security</span>
                <h3 class="v-card-title">Audit Log</h3>
            </div>
        </a>

        <a href="/admin/settings" class="v-card-nav">
            <div class="v-card-icon"><i data-lucide="sliders" style="width: 18px; height: 18px;"></i></div>
            <div>
                <span class="v-label-caps">Account</span>
                <h3 class="v-card-title">Setting Profil</h3>
            </div>
        </a>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            function fetchHubData() {
                window.axios.get('admin/dashboard/hub-data')
                    .then(res => {
                        const d = res.data.data;
                        document.getElementById('hub-total-anggota').innerText = d.total_members.toLocaleString();
                        document.getElementById('hub-total-sesi').innerText = d.total_sessions.toLocaleString();
                        document.getElementById('hub-total-dampak').innerText = d.total_impact.toLocaleString();
                    })
                    .catch(err => console.error("Hub data fail:", err));
            }
            
            fetchHubData();
            if(window.lucide) window.lucide.createIcons();
        });
    </script>
    @endpush
</x-admin-layout>
