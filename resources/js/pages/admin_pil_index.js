// resources/js/pages/admin_pil_index.js

document.addEventListener('DOMContentLoaded', () => {
    const token = localStorage.getItem('auth_token');
    if (!token) {
        window.location.href = '/login/admin';
        return;
    }

    const tableBody = document.getElementById('table-body');
    const filterStatus = document.getElementById('filter-status');
    const btnAdd = document.getElementById('btn-add');
    
    // PIL Modal (Kegiatan)
    const pilModal = document.getElementById('pilModal');
    const pilForm = document.getElementById('pilForm');
    const pilId = document.getElementById('pil_id');
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

    // --- Master Data wilayah ---
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
        let qs = filterStatus.value ? `?status=${filterStatus.value}` : '';
        window.axios.get('admin/pil' + qs)
            .then(res => {
                eventsData = res.data.data;
                renderTable();
            })
            .catch(err => console.error("Gagal load jadwal", err));
    }

    function renderTable() {
        tableBody.innerHTML = '';
        if (eventsData.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="4" class="text-center p-4 text-muted">Belum ada agenda kegiatan PIL.</td></tr>';
            return;
        }

        eventsData.forEach(event => {
            const hasLaporan = event.jumlah_peserta > 0;
            const isCompleted = event.status === 'completed';
            
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
            if (hasLaporan) {
                lapInfo = `<div style="font-size: 0.70rem; margin-top: 6px; color:#10b981; font-weight:700;"><i data-lucide="check-circle" style="width:12px; height:12px; display:inline"></i> Evaluasi Selesai (${event.jumlah_peserta} psrt)</div>`;
            } else if (isCompleted) {
                lapInfo = `<div style="font-size: 0.70rem; margin-top: 6px; color:#f59e0b; font-weight:700;"><i data-lucide="alert-circle" style="width:12px; height:12px; display:inline"></i> Menunggu Evaluasi</div>`;
            }

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <div class="font-bold text-dark" style="font-size:1rem;">${event.judul}</div>
                    <div class="text-muted" style="font-size:0.8rem;">${event.tanggal} ${event.jam_mulai ? '| '+event.jam_mulai.slice(0,5) : ''}</div>
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
                        <button class="btn ${hasLaporan ? 'btn-secondary' : 'btn-primary'}" onclick="handleLaporan(${event.id})" style="padding: 6px 10px; font-size: 0.75rem;">
                            ${hasLaporan ? 'Lihat/Edit Hasil' : '+ Input Laporan'}
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
        pilForm.reset();
        pilId.value = '';
        komaSelectReset();
        kecSelectReset();
        modalTitle.innerText = "Tambah Agenda Penyuluhan";
        pilModal.style.display = 'flex';
    });

    window.editEvent = (id) => {
        const ev = eventsData.find(e => e.id === id);
        if(!ev) return;
        pilForm.reset();
        
        pilId.value = ev.id;
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
        
        modalTitle.innerText = "Edit Jadwal PIL";
        pilModal.style.display = 'flex';
    };

    pilForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const id = pilId.value;
        const formData = new FormData(pilForm);
        const data = Object.fromEntries(formData.entries());

        const saveBtn = document.getElementById('btn-save');
        saveBtn.disabled = true; saveBtn.innerText = 'Menyimpan...';

        let req = id ? window.axios.put(`admin/pil/${id}`, data) 
                     : window.axios.post(`admin/pil`, data);

        req.then(res => {
            window.showToast(res.data.message, 'success');
            pilModal.style.display = 'none';
            loadData();
        }).catch(err => {
            window.showToast("Validasi gagal, cek isian Anda.", "error");
            console.error(err);
        }).finally(() => {
            saveBtn.disabled = false; saveBtn.innerText = 'Simpan Jadwal';
        });
    });

    window.deleteEvent = (id) => {
        window.showConfirm("Hapus kegiatan PIL?", "Data laporan (jika ada) juga akan terhapus secara permanen.", {type:'danger'})
            .then(res => {
                if(res) {
                    window.axios.delete(`admin/pil/${id}`)
                        .then(r => { window.showToast('Berhasil dihapus', 'success'); loadData(); });
                }
            });
    }

    // --- Modal Laporan ---
    window.handleLaporan = (id) => {
        const ev = eventsData.find(e => e.id === id);
        if(!ev) return;

        laporanForm.reset();
        lapKegiatanId.value = ev.id;

        if(ev.jumlah_peserta > 0) {
            document.getElementById('jumlah_peserta').value = ev.jumlah_peserta;
            document.getElementById('rata_uji_pemahaman').value = ev.rata_uji_pemahaman || 0;
            document.getElementById('efek_ketertarikan_jkn').value = ev.efek_ketertarikan_jkn || 1;
            document.getElementById('efek_rekomendasi_jkn').value = ev.efek_rekomendasi_jkn || 1;
            document.getElementById('efek_rekomendasi_bpjs').value = ev.efek_rekomendasi_bpjs || 1;
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

        window.axios.post(`admin/pil/${id}/laporan`, data)
            .then(res => {
                window.showToast(res.data.message, 'success');
                laporanModal.style.display = 'none';
                loadData();
                
                // auto update status ke completed
                const ev = eventsData.find(e => e.id == id);
                if(ev && ev.status !== 'completed') {
                    window.axios.put(`admin/pil/${id}`, {...ev, status: 'completed'}).then(console.log).finally(loadData);
                }
            })
            .catch(err => window.showToast("Penilaian gagal, pastikan angka valid", 'error'))
            .finally(() => { btn.disabled = false; btn.innerText = 'Simpan Laporan'; });
    });


    // INIT
    loadProvinces();
    loadData();
});
