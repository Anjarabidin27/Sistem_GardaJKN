// resources/js/pages/admin_bpjs_keliling_laporan.js

document.addEventListener('DOMContentLoaded', () => {
    const token = localStorage.getItem('auth_token');
    if (!token) {
        window.location.href = '/login/admin';
        return;
    }

    window.axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    window.axios.defaults.headers.common['Accept'] = 'application/json';

    // === DOM Elements ===
    const filterForm       = document.getElementById('filterForm');
    const rangeForm        = document.getElementById('rangeForm');
    const kegiatanSelect   = document.getElementById('kegiatan_id');
    const searchInput      = document.getElementById('search_peserta');
    const statusFilter     = document.getElementById('status_filter');
    const tableBody        = document.getElementById('laporanTableBody');
    const tableContainer   = document.getElementById('table-container');
    const statsContainer   = document.getElementById('stats-container');
    const initialState     = document.getElementById('initial-state');
    const colTanggal       = document.getElementById('col-tanggal');
    const tableCtxLabel    = document.getElementById('table-context-label');
    const tableFilterBadge = document.getElementById('table-filter-badge');
    const btnExport        = document.getElementById('btn-export');
    const rangeMetaBar     = document.getElementById('range-meta-bar');
    const btnRangeExport   = document.getElementById('btn-range-export');
    const monthForm        = document.getElementById('monthForm');

    // === State ===
    let allParticipants  = [];
    let currentMode      = 'kegiatan'; // 'kegiatan' | 'range' | 'month'
    let currentRangeData = [];          // Raw data dari /export endpoint
    let currentRangeMeta = null;

    // === Tab Switcher ===
    window.switchTab = (mode) => {
        currentMode = mode;
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.getElementById(`tab-${mode}`).classList.add('active');

        document.getElementById('panel-kegiatan').style.display = mode === 'kegiatan' ? 'block' : 'none';
        document.getElementById('panel-range').style.display     = mode === 'range'    ? 'block' : 'none';
        document.getElementById('panel-month').style.display     = mode === 'month'    ? 'block' : 'none';

        // Reset display
        resetDisplay();
        if (window.lucide) window.lucide.createIcons();
    };

    function resetDisplay() {
        tableContainer.style.display = 'none';
        statsContainer.style.display = 'none';
        initialState.style.display   = 'block';
        if (rangeMetaBar) rangeMetaBar.style.display = 'none';
        disableExportBtn();
        allParticipants  = [];
        currentRangeData = [];
        currentRangeMeta = null;
        if (tableBody) tableBody.innerHTML = '';
    }

    function enableExportBtn()  { btnExport.disabled = false; btnExport.style.opacity = '1'; btnExport.style.cursor = 'pointer'; }
    function disableExportBtn() { btnExport.disabled = true;  btnExport.style.opacity = '0.5'; btnExport.style.cursor = 'not-allowed'; }

    // === Range Validation ===
    window.validateRange = () => {
        const dari   = document.getElementById('range_dari').value;
        const sampai = document.getElementById('range_sampai').value;
        const warning = document.getElementById('range-warning');
        const info    = document.getElementById('range-info');

        if (!dari || !sampai) { warning.style.display = 'none'; info.innerText = ''; return; }

        const d1 = new Date(dari);
        const d2 = new Date(sampai);
        const diff = Math.round((d2 - d1) / (1000 * 60 * 60 * 24));

        if (diff < 0) {
            warning.innerText = 'Tanggal selesai tidak boleh sebelum tanggal mulai.';
            warning.style.display = 'block';
            info.innerText = '';
            return;
        }
        if (diff > 31) {
            warning.style.display = 'block';
            if (window.lucide) window.lucide.createIcons();
            info.innerText = '';
        } else {
            warning.style.display = 'none';
            info.innerHTML = `<span style="color: #059669; font-weight: 700;">✓ Rentang: ${diff} hari</span>`;
        }
    };

    // === Load Kegiatan List ===
    function loadKegiatan() {
        window.axios.get('admin/bpjs-keliling')
            .then(res => {
                const activities = res.data.data;
                kegiatanSelect.innerHTML = '<option value="">-- Pilih Kegiatan --</option>';
                activities.forEach(ev => {
                    const date = new Date(ev.tanggal).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
                    kegiatanSelect.innerHTML += `<option value="${ev.id}">[${date}] ${ev.judul}</option>`;
                });
            })
            .catch(() => {
                kegiatanSelect.innerHTML = '<option value="">Gagal memuat daftar.</option>';
            });
    }

    // === Per-Kegiatan Filter Submit ===
    if (filterForm) {
        filterForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const kegId = kegiatanSelect.value;
            if (!kegId) return;

            const submitBtn = filterForm.querySelector('button[type="submit"]');
            submitBtn.disabled = true; submitBtn.innerText = 'Memuat...';

            window.axios.get(`admin/bpjs-keliling/${kegId}/participants`)
                .then(res => {
                    allParticipants = res.data.data;

                    // Tampilkan kolom kegiatan: TIDAK (mode per-kegiatan, judul sudah jelas)
                    if (colTanggal) colTanggal.style.display = 'none';
                    tableCtxLabel.innerText = `Peserta: ${kegiatanSelect.options[kegiatanSelect.selectedIndex].text}`;

                    renderLaporan();
                    initialState.style.display = 'none';
                    tableContainer.style.display = 'block';
                    statsContainer.style.display = 'grid';
                    if (rangeMetaBar) rangeMetaBar.style.display = 'none';
                    enableExportBtn();
                })
                .catch(() => window.showToast('Gagal mengambil data laporan.', 'error'))
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Tampilkan';
                    if (window.lucide) window.lucide.createIcons();
                });
        });
    }

    // === Month Filter Submit (Preview) ===
    if (monthForm) {
        monthForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const bulan = document.getElementById('filter_bulan').value;
            const tahun = document.getElementById('filter_tahun').value;

            // Hitung tanggal awal dan akhir bulan
            const dari   = `${tahun}-${bulan}-01`;
            const terakhir = new Date(tahun, bulan, 0).getDate(); // Mendapatkan tanggal terakhir bulan tersebut
            const sampai = `${tahun}-${bulan}-${terakhir}`;

            const btn = monthForm.querySelector('button[type="submit"]');
            btn.disabled = true; btn.innerText = 'Memuat...';

            try {
                const res = await window.axios.get(`admin/bpjs-keliling/export?dari=${dari}&sampai=${sampai}`);
                currentRangeData = res.data.data;
                currentRangeMeta = res.data.meta;

                // Update meta bar (sama seperti range)
                const monthName = document.getElementById('filter_bulan').options[document.getElementById('filter_bulan').selectedIndex].text;
                document.getElementById('range-meta-text').innerText = `${monthName} ${tahun}`;
                document.getElementById('range-meta-kegiatan').innerText = `${currentRangeMeta.total_kegiatan} kegiatan`;
                document.getElementById('range-meta-peserta').innerText = `${currentRangeMeta.total_peserta} peserta`;
                rangeMetaBar.style.display = 'block';

                if (colTanggal) colTanggal.style.display = 'table-cell';
                tableCtxLabel.innerText = `Rekap Peserta: ${monthName} ${tahun}`;

                renderRangeLaporan(currentRangeData);

                initialState.style.display = 'none';
                tableContainer.style.display = 'block';
                statsContainer.style.display = 'grid';
                updateStats(currentRangeData);

                enableExportBtn();

                window.showToast(`Data bulan ${monthName} berhasil dimuat.`, 'success');
            } catch (err) {
                const msg = err.response?.data?.message || 'Gagal memuat data bulanan.';
                window.showToast(msg, 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i data-lucide="eye" style="width:14px;height:14px;display:inline;vertical-align:-2px;margin-right:4px;"></i> Pratinjau';
                if (window.lucide) window.lucide.createIcons();
            }
        });
    }

    // === Render Laporan (Mode Per-Kegiatan) ===
    function renderLaporan() {
        const query  = searchInput?.value?.toLowerCase() || '';
        const status = statusFilter?.value || '';

        const filtered = allParticipants.filter(p => {
            const matchSearch = p.nik.toLowerCase().includes(query) ||
                                p.jenis_layanan.toLowerCase().includes(query) ||
                                (p.transaksi_layanan && p.transaksi_layanan.toLowerCase().includes(query));
            const matchStatus = status ? p.status === status : true;
            return matchSearch && matchStatus;
        });

        tableBody.innerHTML = '';
        if (filtered.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="6" style="text-align:center; padding: 40px; color: #64748b; font-size: 0.875rem;">Data tidak ditemukan.</td></tr>';
            updateStats([]);
            return;
        }

        filtered.forEach(p => {
            const statusClr = p.status === 'Berhasil' ? '#10B981' : '#EF4444';
            const puasClr   = p.suara_pelanggan === 'Puas' ? '#3B82F6' : '#EF4444';
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td style="padding: 12px 16px; border-bottom: 1px solid #F1F5F9; display: none;"></td>
                <td style="padding: 12px 16px; border-bottom: 1px solid #F1F5F9;">
                    <div style="font-weight:700; color: #0F172A; font-size: 0.85rem;">${p.jam_mulai ?? '-'}</div>
                    <div style="font-size: 0.7rem; color: #64748B;">s/d ${p.jam_selesai ?? '-'}</div>
                </td>
                <td style="padding: 12px 16px; border-bottom: 1px solid #F1F5F9;">
                    <div style="font-weight:700; color: #0F172A; font-size: 0.85rem; font-family: 'Outfit', monospace;">${p.nik}</div>
                    <div style="font-size: 0.7rem; color: #64748B;">${p.segmen_peserta ?? '-'}</div>
                </td>
                <td style="padding: 12px 16px; border-bottom: 1px solid #F1F5F9;">
                    <div style="font-weight:700; color: #3B82F6; font-size: 0.85rem;">${p.jenis_layanan}</div>
                    <div style="font-size: 0.7rem; color: #64748B;">${p.transaksi_layanan || '-'}</div>
                </td>
                <td style="padding: 12px 16px; border-bottom: 1px solid #F1F5F9;">
                    <div style="display: flex; align-items: center; gap: 6px; font-weight:700; color:${puasClr}; font-size: 0.8rem;">
                        <i data-lucide="${p.suara_pelanggan === 'Puas' ? 'smile' : 'frown'}" style="width:14px; height:14px;"></i>
                        ${p.suara_pelanggan || '-'}
                    </div>
                </td>
                <td style="padding: 12px 16px; border-bottom: 1px solid #F1F5F9; text-align: center;">
                    <span style="display: inline-block; padding: 4px 12px; border-radius: 20px; background: ${statusClr}15; color: ${statusClr}; font-size: 0.7rem; font-weight: 800; text-transform: uppercase;">
                        ${p.status}
                    </span>
                </td>
            `;
            tableBody.appendChild(tr);
        });

        updateStats(filtered);
        if (window.lucide) window.lucide.createIcons();
    }

    // === Render Laporan (Mode Range) ===
    function renderRangeLaporan(data) {
        tableBody.innerHTML = '';
        if (!data || data.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="6" style="text-align:center; padding: 40px; color: #64748b; font-size: 0.875rem;">Tidak ada data peserta dalam rentang ini.</td></tr>';
            return;
        }

        data.forEach(p => {
            const statusClr = p.status === 'Berhasil' ? '#10B981' : '#EF4444';
            const puasClr   = p.suara_pelanggan === 'Puas' ? '#3B82F6' : '#EF4444';
            const tgl       = new Date(p.tanggal).toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td style="padding: 10px 16px; border-bottom: 1px solid #F1F5F9; min-width: 160px;">
                    <div style="font-size: 0.72rem; font-weight: 800; color: #3b82f6;">${tgl}</div>
                    <div style="font-size: 0.72rem; color: #0f172a; font-weight: 600; margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 150px;" title="${p.judul_kegiatan}">${p.judul_kegiatan}</div>
                </td>
                <td style="padding: 10px 16px; border-bottom: 1px solid #F1F5F9;">
                    <div style="font-weight:700; color: #0F172A; font-size: 0.82rem;">${p.jam_mulai ?? '-'}</div>
                    <div style="font-size: 0.7rem; color: #64748B;">s/d ${p.jam_selesai ?? '-'}</div>
                </td>
                <td style="padding: 10px 16px; border-bottom: 1px solid #F1F5F9;">
                    <div style="font-weight:700; color: #0F172A; font-size: 0.82rem; font-family: 'Outfit', monospace;">${p.nik}</div>
                    <div style="font-size: 0.7rem; color: #64748B;">${p.segmen_peserta ?? '-'}</div>
                </td>
                <td style="padding: 10px 16px; border-bottom: 1px solid #F1F5F9;">
                    <div style="font-weight:700; color: #3B82F6; font-size: 0.82rem;">${p.jenis_layanan}</div>
                    <div style="font-size: 0.7rem; color: #64748B;">${p.transaksi_layanan || '-'}</div>
                </td>
                <td style="padding: 10px 16px; border-bottom: 1px solid #F1F5F9;">
                    <div style="display: flex; align-items: center; gap: 6px; font-weight:700; color:${puasClr}; font-size: 0.78rem;">
                        <i data-lucide="${p.suara_pelanggan === 'Puas' ? 'smile' : 'frown'}" style="width:13px; height:13px;"></i>
                        ${p.suara_pelanggan || '-'}
                    </div>
                </td>
                <td style="padding: 10px 16px; border-bottom: 1px solid #F1F5F9; text-align: center;">
                    <span style="display: inline-block; padding: 3px 10px; border-radius: 20px; background: ${statusClr}15; color: ${statusClr}; font-size: 0.68rem; font-weight: 800; text-transform: uppercase;">
                        ${p.status}
                    </span>
                </td>
            `;
            tableBody.appendChild(tr);
        });

        if (window.lucide) window.lucide.createIcons();
    }

    // === Update Stats ===
    function updateStats(data) {
        const total    = data.length;
        const berhasil = data.filter(p => p.status === 'Berhasil').length;
        const gagal    = data.filter(p => p.status === 'Tidak Berhasil').length;
        const puas     = data.filter(p => p.suara_pelanggan === 'Puas').length;
        const ratePuas = total > 0 ? Math.round((puas / total) * 100) : 0;

        document.getElementById('stat-total').innerText    = total.toLocaleString('id-ID');
        document.getElementById('stat-berhasil').innerText = berhasil.toLocaleString('id-ID');
        document.getElementById('stat-gagal').innerText    = gagal.toLocaleString('id-ID');
        document.getElementById('stat-puas').innerText     = ratePuas + '%';
    }

    // === Export Logic ===
    window.exportExcel = () => {
        let dataSource = [];
        let filename   = '';

        if ((currentMode === 'range' || currentMode === 'month') && currentRangeData.length > 0) {
            // Mode rentang atau bulan — data dari endpoint /export
            const { dari, sampai } = currentRangeMeta;
            filename = `Export_BPJS_Keliling_${dari}_sd_${sampai}.xlsx`;

            dataSource = currentRangeData.map(p => ({
                'Tanggal'          : p.tanggal,
                'Judul Kegiatan'   : p.judul_kegiatan,
                'Jenis Kegiatan'   : p.jenis_kegiatan,
                'Kuadran'          : p.kuadran,
                'Kantor Cabang'    : p.kantor_cabang,
                'Kota/Kabupaten'   : p.kota,
                'Desa/Kelurahan'   : p.nama_desa,
                'Jam Mulai'        : p.jam_mulai,
                'Jam Selesai'      : p.jam_selesai,
                'NIK'              : p.nik,
                'Nama'             : p.name,
                'Segmen Peserta'   : p.segmen_peserta,
                'Jenis Layanan'    : p.jenis_layanan,
                'Transaksi Layanan': p.transaksi_layanan,
                'Status'           : p.status,
                'Keterangan Gagal' : p.keterangan_gagal,
                'Suara Pelanggan'  : p.suara_pelanggan,
            }));
        } else if (currentMode === 'kegiatan' && allParticipants.length > 0) {
            // Mode per-kegiatan
            const kegLabel = kegiatanSelect?.options[kegiatanSelect?.selectedIndex]?.text || 'export';
            filename = `Laporan_BPJS_Keliling_${new Date().toISOString().slice(0, 10)}.xlsx`;

            dataSource = allParticipants.map(p => ({
                'Waktu'            : `${p.jam_mulai} - ${p.jam_selesai}`,
                'NIK'              : p.nik,
                'Nama'             : p.name ?? '-',
                'Segmen'           : p.segmen_peserta,
                'Jenis Layanan'    : p.jenis_layanan,
                'Transaksi'        : p.transaksi_layanan || '-',
                'Status'           : p.status,
                'Suara Pelanggan'  : p.suara_pelanggan || '-',
            }));
        } else {
            window.showToast('Tidak ada data untuk di-export. Tampilkan data terlebih dahulu.', 'warning');
            return;
        }

        const ws = XLSX.utils.json_to_sheet(dataSource);

        // Auto-width columns
        const colWidths = Object.keys(dataSource[0] || {}).map(k => ({
            wch: Math.max(k.length, ...dataSource.map(r => String(r[k] ?? '').length)) + 2
        }));
        ws['!cols'] = colWidths;

        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'Laporan Peserta');
        XLSX.writeFile(wb, filename);
        window.showToast(`File "${filename}" berhasil diunduh!`, 'success');
    };

    // === Print ===
    window.printReport = () => window.print();

    // === Live Filter (Per-Kegiatan Mode) ===
    if (searchInput) searchInput.addEventListener('input', () => { if (currentMode === 'kegiatan') renderLaporan(); });
    if (statusFilter) statusFilter.addEventListener('change', () => { if (currentMode === 'kegiatan') renderLaporan(); });

    // === Init ===
    loadKegiatan();
});
