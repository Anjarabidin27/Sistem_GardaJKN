// resources/js/pages/admin_bpjs_keliling_laporan.js

document.addEventListener('DOMContentLoaded', () => {
    const token = localStorage.getItem('auth_token');
    if (!token) {
        window.location.href = '/login/admin';
        return;
    }

    const filterForm = document.getElementById('filterForm');
    const kegiatanSelect = document.getElementById('kegiatan_id');
    const searchInput = document.getElementById('search_peserta');
    const statusFilter = document.getElementById('status_filter');
    const tableBody = document.getElementById('laporanTableBody');
    const tableContainer = document.getElementById('table-container');
    const statsContainer = document.getElementById('stats-container');
    const initialState = document.getElementById('initial-state');

    let allParticipants = [];

    // 1. Load BPJS Keliling Activities for Dropdown
    function loadKegiatan() {
        window.axios.get('admin/bpjs-keliling')
            .then(res => {
                const activities = res.data.data;
                kegiatanSelect.innerHTML = '<option value="">Pilih Kegiatan...</option>';
                
                activities.forEach(ev => {
                    const date = new Date(ev.tanggal).toLocaleDateString('id-ID', {day:'numeric', month:'short'});
                    kegiatanSelect.innerHTML += `<option value="${ev.id}">${date} - ${ev.judul}</option>`;
                });
            })
            .catch(err => {
                kegiatanSelect.innerHTML = '<option value="">Gagal memuat daftar.</option>';
                console.error(err);
            });
    }

    // 2. Load Participants for selected activity
    filterForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const kegId = kegiatanSelect.value;
        if (!kegId) return;

        const submitBtn = filterForm.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerText = 'Memuat...';

        window.axios.get(`admin/bpjs-keliling/${kegId}/participants`)
            .then(res => {
                allParticipants = res.data.data;
                renderLaporan();
                
                // Show UI
                initialState.style.display = 'none';
                tableContainer.style.display = 'block';
                statsContainer.style.display = 'grid';
            })
            .catch(err => {
                window.showToast("Gagal mengambil data laporan.", "error");
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i data-lucide="search"></i> Tampilkan';
                if(window.lucide) window.lucide.createIcons();
            });
    });

    function renderLaporan() {
        const query = searchInput.value.toLowerCase();
        const status = statusFilter.value;

        // Filter local data
        const filtered = allParticipants.filter(p => {
            const matchSearch = p.nik.toLowerCase().includes(query) || 
                               p.jenis_layanan.toLowerCase().includes(query) ||
                               (p.transaksi_layanan && p.transaksi_layanan.toLowerCase().includes(query));
            const matchStatus = status ? p.status === status : true;
            return matchSearch && matchStatus;
        });

        tableBody.innerHTML = '';
        if (filtered.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="5" class="text-center p-8 text-muted">Data tidak ditemukan.</td></tr>';
            updateStats(filtered);
            return;
        }

        filtered.forEach(p => {
            const tr = document.createElement('tr');
            const statusClr = p.status === 'Berhasil' ? '#10B981' : '#EF4444';
            const puasClr = p.suara_pelanggan === 'Puas' ? '#3B82F6' : '#EF4444';

            tr.innerHTML = `
                <td style="padding: 12px 16px; border-bottom: 1px solid #F1F5F9;">
                    <div style="font-weight:700; color: #0F172A; font-size: 0.85rem;">${p.jam_mulai}</div>
                    <div style="font-size: 0.7rem; color: #64748B;">Selesai: ${p.jam_selesai}</div>
                </td>
                <td style="padding: 12px 16px; border-bottom: 1px solid #F1F5F9;">
                    <div style="font-weight:700; color: #0F172A; font-size: 0.85rem;">${p.nik}</div>
                    <div style="font-size: 0.7rem; color: #64748B;">${p.segmen_peserta}</div>
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
        if(window.lucide) window.lucide.createIcons();
    }

    // Sidebar folding logic
    document.querySelectorAll('.sb-folding-header').forEach(header => {
        header.addEventListener('click', () => {
            header.parentElement.classList.toggle('active');
        });
    });

    function updateStats(data) {
        const total = data.length;
        const berhasil = data.filter(p => p.status === 'Berhasil').length;
        const gagal = data.filter(p => p.status === 'Tidak Berhasil').length;
        const puas = data.filter(p => p.suara_pelanggan === 'Puas').length;
        const ratePuas = total > 0 ? Math.round((puas / total) * 100) : 0;

        document.getElementById('stat-total').innerText = total;
        document.getElementById('stat-berhasil').innerText = berhasil;
        document.getElementById('stat-gagal').innerText = gagal;
        document.getElementById('stat-puas').innerText = ratePuas + '%';
    }

    // --- Export Logic ---
    window.exportExcel = () => {
        if (allParticipants.length === 0) {
            window.showToast("Tidak ada data untuk di-export.", "warning");
            return;
        }

        const dataToExport = allParticipants.map(p => ({
            'Waktu': `${p.jam_mulai} - ${p.jam_selesai}`,
            'NIK': p.nik,
            'Segmen': p.segmen_peserta,
            'Jenis Layanan': p.jenis_layanan,
            'Transaksi': p.transaksi_layanan || '-',
            'Status': p.status,
            'Suara Pelanggan': p.suara_pelanggan || '-'
        }));

        const ws = XLSX.utils.json_to_sheet(dataToExport);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Laporan Peserta");
        XLSX.writeFile(wb, `Laporan_BPJS_Keliling_${new Date().toISOString().slice(0,10)}.xlsx`);
    };

    window.printReport = () => {
        window.print();
    };

    // Live filtering
    searchInput.addEventListener('input', renderLaporan);
    statusFilter.addEventListener('change', renderLaporan);

    // Initial Load
    loadKegiatan();
});
