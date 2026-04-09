<x-admin-layout title="Administrasi Keanggotaan - Garda JKN">
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
        .v-gap-3 { gap: 0.75rem; }
        
        .v-card {
            background: var(--v-white);
            border-radius: 1rem;
            border: 1px solid var(--v-gray-100);
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
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

        .v-input-compact {
            border: 1px solid var(--v-gray-200);
            border-radius: 0.5rem;
            padding: 0.4rem 0.75rem;
            font-size: 0.8rem;
            font-weight: 600;
            background: var(--v-gray-50);
            width: 100%;
            max-width: 280px;
        }
    </style>

    <!-- Sleek Header -->
    <div class="v-flex v-justify-between v-items-center" style="margin-bottom: 1.5rem;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 900; letter-spacing: -0.03em; color: var(--v-black); margin: 0;">Anggota Wilayah</h1>
            <p style="font-size: 0.85rem; color: var(--v-gray-500); margin-top: 2px;">Cari dan kelola basis data anggota di wilayah koordinasi Anda.</p>
        </div>
        <div>
            <input type="text" id="memberSearch" class="v-input-compact" placeholder="Cari Nama / NIK anggota...">
        </div>
    </div>

    <div class="v-card">
        <table class="v-table">
            <thead>
                <tr>
                    <th width="30%">Identitas Anggota</th>
                    <th width="20%">Kontak</th>
                    <th width="25%">Alamat / Wilayah</th>
                    <th width="15%">Klasifikasi</th>
                    <th width="10%" style="text-align: right;">Status</th>
                </tr>
            </thead>
            <tbody id="memberTableBody">
                <!-- Data loaded via JS -->
                <tr>
                    <td colspan="5" style="padding: 4rem; text-align: center;">
                        <span class="loading-spinner"></span>
                        <p class="v-label-caps" style="margin-top: 1rem;">Memuat Data Anggota...</p>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <div id="pagination" style="padding: 1rem 1.25rem; display: flex; justify-content: center; background: var(--v-gray-50); border-top: 1px solid var(--v-gray-100);"></div>
    </div>

    @push('scripts')
    @vite(['resources/js/pages/pengurus_members.js'])
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if(window.lucide) window.lucide.createIcons();
        });
    </script>
    @endpush
</x-admin-layout>
