// resources/js/pages/admin_bpjs_keliling_index.js

document.addEventListener('DOMContentLoaded', () => {
    const token = localStorage.getItem('auth_token');
    if (!token) {
        window.location.href = '/login/admin';
        return;
    }

    const tableBody = document.getElementById('table-body');
    const filterStatus = document.getElementById('filter-status');
    const btnAdd = document.getElementById('btn-add');
    
    // BPJS Modal (Kegiatan)
    const bpjsModal = document.getElementById('bpjsModal');
    const bpjsForm = document.getElementById('bpjsForm');
    const bpjsId = document.getElementById('bpjs_id');
    const modalTitle = document.getElementById('modal-title');

    // Laporan Modal
    const laporanModal = document.getElementById('laporanModal');
    const laporanForm = document.getElementById('laporanForm');
    const lapKegiatanId = document.getElementById('lap_kegiatan_id');

    // Master Region elements
    const provSelect = document.getElementById('provinsi_id');
    const kotaSelect = document.getElementById('kota_id');
    const kecSelect = document.getElementById('kecamatan_id');

    let eventsData = [];

    // --- Master Data wilayah (sama spt Member) ---
    function loadProvinces() {
        window.axios.get('master/provinces')
            .then(res => {
                const data = res.data.data;
                provSelect.innerHTML = '<option value="">Pilih Provinsi...</option>';
                data.forEach(p => {
                    provSelect.innerHTML += `<option value="${p.id}">${p.name}</option>`;
                });
            });
    }

    provSelect.addEventListener('change', (e) => {
        komaSelectReset();
        kecSelectReset();
        if(!e.target.value) return;
        window.axios.get(`master/cities?province_id=${e.target.value}`)
            .then(res => {
                kotaSelect.disabled = false;
                res.data.data.forEach(c => kotaSelect.innerHTML += `<option value="${c.id}">${c.name}</option>`);
            });
    });

    kotaSelect.addEventListener('change', (e) => {
        kecSelectReset();
        if(!e.target.value) return;
        window.axios.get(`master/districts?city_id=${e.target.value}`)
            .then(res => {
                kecSelect.disabled = false;
                res.data.data.forEach(d => kecSelect.innerHTML += `<option value="${d.id}">${d.name}</option>`);
            });
    });

    function komaSelectReset() { kotaSelect.innerHTML = '<option value="">Kota/Kab...</option>'; kotaSelect.disabled = true; }
    function kecSelectReset() { kecSelect.innerHTML = '<option value="">Kecamatan...</option>'; kecSelect.disabled = true; }

    // --- Load Data Jadwal ---
    function loadData() {
        const params = new URLSearchParams(window.location.search);
        const statusVal = params.get('status') || filterStatus.value || '';
        
        // Update filter UI to match URL if different
        if (statusVal && filterStatus.value !== statusVal) {
            filterStatus.value = statusVal;
        }

        let qs = statusVal ? `?status=${statusVal}` : '';
        
        // Update URL
        const newUrl = window.location.pathname + (qs ? qs : '');
        window.history.replaceState({}, '', newUrl);

        window.axios.get('admin/bpjs-keliling' + qs)
            .then(res => {
                eventsData = res.data.data;
                renderTable();
            })
            .catch(err => console.error("Gagal load jadwal", err));
    }

    function renderTable() {
        tableBody.innerHTML = '';
        if (eventsData.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="4" class="text-center p-4 text-muted">Belum ada agenda kegiatan.</td></tr>';
            return;
        }

        const mapJenis = {
            'goes_to_village': 'Goes To Village',
            'around_city': 'Around City',
            'goes_to_office': 'Goes To Office',
            'institusi': 'Institusi',
            'pameran': 'Pameran',
            'other': 'Lainnya'
        };

        eventsData.forEach(event => {
            const hasLaporan = (event.jumlah_peserta > 0 || event.layanan_informasi > 0) || event.status === 'completed';
            const isCompleted = event.status === 'completed';
            const jns = mapJenis[event.jenis_kegiatan] || event.jenis_kegiatan;
            
            // Lokasi string
            let locParts = [];
            if(event.nama_desa) locParts.push(event.nama_desa);
            if(event.kota) locParts.push(event.kota.name);
            let locStr = locParts.length > 0 ? locParts.join(', ') : 'Belum di set';

            let statusBadge = '';
            if (event.status === 'scheduled') statusBadge = '<span class="status-badge badge-info text-uppercase">Terjadwal</span>';
            else if (event.status === 'ongoing') statusBadge = '<span class="status-badge badge-warning text-uppercase">Berlangsung</span>';
            else if (event.status === 'completed') statusBadge = '<span class="status-badge badge-success text-uppercase">Selesai</span>';
            else statusBadge = '<span class="status-badge badge-danger text-uppercase">Dibatalkan</span>';

            let lapInfo = '';
            if (hasLaporan && isCompleted) {
                lapInfo = `<div style="font-size: 0.70rem; margin-top: 6px; color:#10b981; font-weight:700;"><i data-lucide="check-circle" style="width:12px; height:12px; display:inline"></i> Laporan Masuk: ${event.jumlah_peserta} Peserta</div>`;
            } else if (isCompleted) {
                lapInfo = `<div style="font-size: 0.70rem; margin-top: 6px; color:#f59e0b; font-weight:700;"><i data-lucide="alert-circle" style="width:12px; height:12px; display:inline"></i> Menunggu Laporan</div>`;
            }

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <div class="font-bold text-dark" style="font-size:1rem;">${event.judul}</div>
                    <div class="text-muted" style="font-size:0.8rem;">${event.tanggal} ${event.jam_mulai ? '| '+event.jam_mulai : ''}</div>
                    <div style="font-size:0.75rem; font-weight:700; color:var(--primary); margin-top:4px;">${jns}</div>
                </td>
                <td>
                    <div style="font-size:0.85rem; font-weight:600;">${locStr}</div>
                    <div class="text-muted" style="font-size:0.75rem;">${event.lokasi_detail || '-'}</div>
                </td>
                <td>
                    ${statusBadge}
                    ${lapInfo}
                </td>
                <td class="text-right">
                    <div class="flex gap-2 justify-end">
                        <button class="btn btn-secondary" onclick="editEvent(${event.id})" style="padding: 6px 10px; font-size: 0.75rem;">Edit</button>
                        <button class="btn ${isCompleted ? 'btn-secondary' : 'btn-primary'}" onclick="handleLaporan(${event.id})" style="padding: 6px 10px; font-size: 0.75rem;">
                            ${isCompleted ? 'Lihat/Edit Laporan' : '+ Input Laporan'}
                        </button>
                        <button class="btn btn-danger" onclick="deleteEvent(${event.id})" style="padding: 6px 10px; font-size: 0.75rem;"><i data-lucide="trash-2" style="width:14px; height:14px"></i></button>
                    </div>
                </td>
            `;
            tableBody.appendChild(tr);
        });

        if (window.lucide) window.lucide.createIcons();
    }

    filterStatus.addEventListener('change', loadData);

    // --- Modal Kegiatan ---
    btnAdd.addEventListener('click', () => {
        bpjsForm.reset();
        bpjsId.value = '';
        komaSelectReset();
        kecSelectReset();
        modalTitle.innerText = "Tambah Jadwal Kegiatan";
        bpjsModal.style.display = 'flex';
    });

    window.editEvent = (id) => {
        const ev = eventsData.find(e => e.id === id);
        if(!ev) return;
        bpjsForm.reset();
        
        bpjsId.value = ev.id;
        document.getElementById('jenis_kegiatan').value = ev.jenis_kegiatan;
        document.getElementById('judul').value = ev.judul;
        document.getElementById('tanggal').value = ev.tanggal ? ev.tanggal.split('T')[0] : '';
        document.getElementById('jam_mulai').value = ev.jam_mulai ? ev.jam_mulai.slice(0,5) : '';
        document.getElementById('jam_selesai').value = ev.jam_selesai ? ev.jam_selesai.slice(0,5) : '';
        document.getElementById('nama_desa').value = ev.nama_desa || '';
        document.getElementById('lokasi_detail').value = ev.lokasi_detail || '';
        document.getElementById('jumlah_petugas').value = ev.jumlah_petugas;
        document.getElementById('status').value = ev.status;
        
        // Regions
        komaSelectReset();
        kecSelectReset();
        if(ev.provinsi_id) {
            provSelect.value = ev.provinsi_id;
            window.axios.get(`master/cities?province_id=${ev.provinsi_id}`).then(res => {
                kotaSelect.disabled = false;
                res.data.data.forEach(c => kotaSelect.innerHTML += `<option value="${c.id}">${c.name}</option>`);
                if(ev.kota_id) {
                    kotaSelect.value = ev.kota_id;
                    window.axios.get(`master/districts?city_id=${ev.kota_id}`).then(r2 => {
                        kecSelect.disabled = false;
                        r2.data.data.forEach(d => kecSelect.innerHTML += `<option value="${d.id}">${d.name}</option>`);
                        if(ev.kecamatan_id) kecSelect.value = ev.kecamatan_id;
                    });
                }
            });
        }
        
        modalTitle.innerText = "Edit Jadwal Kegiatan";
        bpjsModal.style.display = 'flex';
    };

    bpjsForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const id = bpjsId.value;
        const formData = new FormData(bpjsForm);
        const data = Object.fromEntries(formData.entries());

        const saveBtn = document.getElementById('btn-save');
        saveBtn.disabled = true; saveBtn.innerText = 'Menyimpan...';

        let req = id ? window.axios.put(`admin/bpjs-keliling/${id}`, data) 
                     : window.axios.post(`admin/bpjs-keliling`, data);

        req.then(res => {
            window.showToast(res.data.message, 'success');
            bpjsModal.style.display = 'none';
            loadData();
        }).catch(err => {
            window.showToast("Validasi gagal, cek isian Anda.", "error");
            console.error(err);
        }).finally(() => {
            saveBtn.disabled = false; saveBtn.innerText = 'Simpan Jadwal';
        });
    });

    window.deleteEvent = (id) => {
        window.showConfirm("Hapus kegiatan?", "Data laporan (jika ada) juga akan terhapus.", {type:'danger'})
            .then(res => {
                if(res) {
                    window.axios.delete(`admin/bpjs-keliling/${id}`)
                        .then(r => { window.showToast('Dihapus', 'success'); loadData(); });
                }
            });
    }

    // --- Modal Laporan ---
    window.handleLaporan = (id) => {
        const ev = eventsData.find(e => e.id === id);
        if(!ev) return;

        laporanForm.reset();
        lapKegiatanId.value = ev.id;

        if(ev.jumlah_peserta > 0 || ev.layanan_informasi > 0) {
            document.getElementById('layanan_informasi').value = ev.layanan_informasi;
            document.getElementById('layanan_administrasi').value = ev.layanan_administrasi;
            document.getElementById('layanan_pengaduan').value = ev.layanan_pengaduan;
            document.getElementById('transaksi_berhasil').value = ev.transaksi_berhasil || 0;
            document.getElementById('transaksi_gagal').value = ev.transaksi_gagal || 0;
            document.getElementById('jumlah_peserta').value = ev.jumlah_peserta;
            document.getElementById('kepuasan_puas').value = ev.kepuasan_puas;
            document.getElementById('kepuasan_tidak_puas').value = ev.kepuasan_tidak_puas;
            document.getElementById('catatan').value = ev.catatan || '';
        }

        laporanModal.style.display = 'flex';
    };

    laporanForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const id = lapKegiatanId.value;
        const data = Object.fromEntries(new FormData(laporanForm).entries());

        const btn = document.getElementById('btn-save-laporan');
        btn.disabled = true; btn.innerText = 'Menyimpan...';

        window.axios.post(`admin/bpjs-keliling/${id}/laporan`, data)
            .then(res => {
                window.showToast(res.data.message, 'success');
                laporanModal.style.display = 'none';
                loadData();
            })
            .catch(err => window.showToast("Gagal simpan laporan", 'error'))
            .finally(() => { btn.disabled = false; btn.innerText = 'Simpan Laporan'; });
    });


    // INIT
    loadProvinces();
    loadData();
});
