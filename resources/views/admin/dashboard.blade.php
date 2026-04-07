<x-admin-layout title="Admin Dashboard - Garda JKN">
    @php
        $admin = auth('admin')->user();
        $r = $admin ? $admin->role : 'guest';
        if($r === 'petugas_keliling') {
            echo "<script>window.location.href='/admin/bpjs-keliling';</script>";
            exit;
        } elseif($r === 'petugas_pil') {
            echo "<script>window.location.href='/admin/pil';</script>";
            exit;
        }
    @endphp

    <script>
        // Fallback: If PHP session is not detected but localStorage shows special role
        (function() {
            const role = localStorage.getItem('user_role');
            if (role === 'petugas_keliling' && !window.location.pathname.includes('bpjs-keliling')) {
                window.location.href = '/admin/bpjs-keliling';
            } else if (role === 'petugas_pil' && !window.location.pathname.includes('pil')) {
                window.location.href = '/admin/pil';
            }
        })();
    </script>
    <div class="summary-grid">
        <div class="stat-card">
            <div class="stat-label">Basis Anggota</div>
            <div class="stat-value" id="count-total">...</div>
            <div class="text-muted" style="font-size: 0.75rem; font-weight: 600; margin-top: 4px;">Total Data Terdaftar</div>
        </div>
        <div class="stat-card" style="border-left: 3px solid var(--success);">
            <div class="stat-label">Pertumbuhan Bulanan</div>
            <div class="stat-value" id="count-month" style="color: var(--success);">...</div>
            <div class="text-muted" style="font-size: 0.75rem; font-weight: 600; margin-top: 4px;">+ Terverifikasi Bulan Ini</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Cakupan Wilayah</div>
            <div class="stat-value" id="count-provinces">...</div>
            <div class="text-muted" style="font-size: 0.75rem; font-weight: 600; margin-top: 4px;">Provinsi Terdaftar</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Aktivitas Sistem</div>
            <div class="stat-value" id="count-logs">...</div>
            <div class="text-muted" style="font-size: 0.75rem; font-weight: 600; margin-top: 4px;">Log Transaksi</div>
        </div>
    </div>

    <div class="table-card p-4 mb-4">
        <div class="justify-between items-center mb-4 flex">
            <div>
                <h3 class="modal-title" style="font-size: 1.25rem;">Analitik Pertumbuhan Anggota</h3>
                <p class="text-muted" style="font-size: 0.85rem; margin-top: 4px;">Laju pendaftaran berbasis periode waktu global.</p>
            </div>
            <select id="rangeSelector" class="form-input" style="width: auto;" onchange="updateDashboard(this.value)">
                <option value="3">3 Bulan Terakhir</option>
                <option value="6" selected>6 Bulan Terakhir</option>
                <option value="12">1 Tahun Terakhir</option>
            </select>
        </div>
        <div style="position: relative; width: 100%; height: 400px;"><canvas id="mainChart"></canvas></div>
    </div>

    <div class="grid-3">
        <div class="table-card p-4 text-center">
            <h4 class="stat-label" style="text-align: left;">Distribusi Gender</h4>
            <div style="width: 100%; height: 200px;"><canvas id="genderChart"></canvas></div>
        </div>
        <div class="table-card p-4 text-center">
            <h4 class="stat-label" style="text-align: left;">Tingkat Pendidikan</h4>
            <div style="width: 100%; height: 200px;"><canvas id="eduChart"></canvas></div>
        </div>
        <div class="table-card p-4 text-center">
            <h4 class="stat-label" style="text-align: left;">Kelompok Usia</h4>
            <div style="width: 100%; height: 200px;"><canvas id="ageChart"></canvas></div>
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/pages/admin-dashboard.js'])
    @endpush
</x-admin-layout>
