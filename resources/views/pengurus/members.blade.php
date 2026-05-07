<x-pengurus-layout title="Anggota Wilayah - Garda JKN">
    <style>
        :root {
            --v-black: #000;
            --v-white: #fff;
            --v-gray-50: #fbfbfc;
            --v-gray-100: #f3f4f6;
            --v-gray-200: #e5e7eb;
            --v-gray-400: #9ca3af;
            --v-gray-500: #6b7280;
            --v-blue-600: #2ea0fb;
        }

        .header-row { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; }
        .v-title { font-size: 1.75rem; font-weight: 900; letter-spacing: -0.04em; color: var(--v-black); margin: 0; }
        .v-subtitle { font-size: 0.875rem; color: var(--v-gray-500); margin-top: 4px; }

        .search-box {
            display: flex; align-items: center; background: var(--v-white);
            border: 1.5px solid var(--v-gray-100); border-radius: 12px;
            padding: 4px 12px; gap: 10px; width: 320px; transition: all 0.2s;
        }
        .search-box:focus-within { border-color: var(--v-black); box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .search-box input {
            border: none; background: transparent; padding: 10px 0; font-size: 0.875rem;
            font-weight: 600; color: var(--v-black); width: 100%; outline: none;
        }

        .v-card {
            background: var(--v-white); border-radius: 1.25rem; border: 1px solid var(--v-gray-100);
            overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }
        .v-table { width: 100%; border-collapse: collapse; }
        .v-table th { 
            text-align: left; padding: 1rem 1.5rem; background: var(--v-gray-50);
            font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em;
            color: var(--v-gray-400); border-bottom: 1px solid var(--v-gray-100);
        }
        .v-table td { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--v-gray-50); vertical-align: middle; }
        .v-table tr:last-child td { border-bottom: none; }
        .v-table tr:hover { background: #fafbfc; }

        .v-label-caps {
            font-size: 9px; font-weight: 800; text-transform: uppercase;
            letter-spacing: 0.1em; color: var(--v-gray-400); display: block; margin-bottom: 2px;
        }

        @media (max-width: 768px) {
            .header-row { flex-direction: column; align-items: flex-start; gap: 1rem; }
            .search-box { width: 100%; }
        }
    </style>

    <div class="header-row">
        <div>
            <h1 class="v-title">Anggota Wilayah</h1>
            <p class="v-subtitle">Kelola dan pantau basis data anggota aktif di wilayah koordinasi Anda.</p>
        </div>
        <div class="search-box">
            <i data-lucide="search" style="width: 18px; height: 18px; color: var(--v-gray-400);"></i>
            <input type="text" id="memberSearch" placeholder="Cari Nama atau NIK Anggota...">
        </div>
    </div>

    <div class="v-card">
        <table class="v-table">
            <thead>
                <tr>
                    <th width="35%">Identitas Anggota</th>
                    <th width="20%">Kontak & WhatsApp</th>
                    <th width="25%">Domisili & Wilayah</th>
                    <th width="20%" style="text-align: right;">Status</th>
                </tr>
            </thead>
            <tbody id="memberTableBody">
                <tr>
                    <td colspan="4" style="padding: 4rem; text-align: center;">
                        <span class="loading-spinner"></span>
                        <p style="font-size: 0.75rem; font-weight: 800; color: var(--v-gray-400); margin-top: 1rem; text-transform: uppercase; letter-spacing: 0.1em;">Menghubungkan ke Server...</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <div id="pagination" style="padding: 1.25rem 1.5rem; border-top: 1px solid var(--v-gray-100); background: var(--v-gray-50); display: flex; justify-content: center; gap: 8px;"></div>
    </div>

    @push('scripts')
    @vite(['resources/js/pages/pengurus_members.js'])
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if(window.lucide) window.lucide.createIcons();
        });
    </script>
    @endpush
</x-pengurus-layout>
