// resources/js/pages/admin_bpjs_keliling_index.js

document.addEventListener('DOMContentLoaded', () => {
    const token = localStorage.getItem('auth_token');
    if (!token) {
        window.location.href = '/login/admin';
        return;
    }

    // Set Authorization Header globally for this page
    window.axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    window.axios.defaults.headers.common['Accept'] = 'application/json';

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

    // --- Master Data wilayah (Contextual Loading) ---
    // --- Master Data wilayah (Contextual Loading) ---
    async function loadProvinces() {
        try {
            const ctxRes = await window.axios.get('master/get-context');
            const ctx = ctxRes.data.data;
            
            // 1. Context Display
            const kwName = document.getElementById('ui-kw-name');
            const kcName = document.getElementById('ui-kc-name');
            if (ctx) {
                if (kwName) kwName.innerText = ctx.kantor_cabang?.kedeputian_wilayah || ctx.unit_name || '-';
                if (kcName) kcName.innerText = ctx.kantor_cabang?.name || ctx.unit_name || '-';
            }

            // 2. Load API
            const provRes = await window.axios.get('master/provinces');
            if(provSelect) {
                provSelect.disabled = false;
                provSelect.innerHTML = '<option value="">Pilih Provinsi...</option>';
                provRes.data.data.forEach(p => {
                    provSelect.innerHTML += `<option value="${p.id}">${p.name}</option>`;
                });

                // 3. Auto-fill logic
                if (ctx.kantor_cabang && ctx.kantor_cabang.province_id) {
                    provSelect.value = ctx.kantor_cabang.province_id;
                    provSelect.disabled = true; // Lock it
                    
                    // Trigger cities loading
                    komaSelectReset();
                    const cityRes = await window.axios.get(`master/cities?province_id=${ctx.kantor_cabang.province_id}`);
                    if(kotaSelect) {
                        kotaSelect.innerHTML = '<option value="">Kota/Kab...</option>';
                        cityRes.data.data.forEach(c => {
                            kotaSelect.innerHTML += `<option value="${c.id}">${c.name}</option>`;
                        });

                        if (ctx.kantor_cabang.city_id) {
                            kotaSelect.value = ctx.kantor_cabang.city_id;
                            kotaSelect.disabled = true; // Lock it
                            
                            // Trigger districts
                            kecSelectReset();
                            const distRes = await window.axios.get(`master/districts?city_id=${ctx.kantor_cabang.city_id}`);
                            if(kecSelect) {
                                kecSelect.disabled = false;
                                distRes.data.data.forEach(d => {
                                    kecSelect.innerHTML += `<option value="${d.id}">${d.name}</option>`;
                                });
                            }
                        }
                    }
                }
            }
        } catch (err) {
            console.error("Gagal load context wilayah BPJS Keliling", err);
        }
    }

    if(provSelect) {
        provSelect.addEventListener('change', (e) => {
            komaSelectReset();
            kecSelectReset();
            if(!e.target.value) return;
            window.axios.get(`master/cities?province_id=${e.target.value}`)
                .then(res => {
                    if(kotaSelect) {
                        kotaSelect.disabled = false;
                        res.data.data.forEach(c => kotaSelect.innerHTML += `<option value="${c.id}">${c.name}</option>`);
                    }
                });
        });
    }

    if(kotaSelect) {
        kotaSelect.addEventListener('change', (e) => {
            kecSelectReset();
            if(!e.target.value) return;
            window.axios.get(`master/districts?city_id=${e.target.value}`)
                .then(res => {
                    if(kecSelect) {
                        kecSelect.disabled = false;
                        res.data.data.forEach(d => kecSelect.innerHTML += `<option value="${d.id}">${d.name}</option>`);
                    }
                });
        });
    }

    function komaSelectReset() { if(kotaSelect) { kotaSelect.innerHTML = '<option value="">Kota/Kab...</option>'; kotaSelect.disabled = true; } }
    function kecSelectReset() { if(kecSelect) { kecSelect.innerHTML = '<option value="">Kecamatan...</option>'; kecSelect.disabled = true; } }

    // --- Load Data Jadwal ---
    function loadData() {
        if(!tableBody) return;
        const params = new URLSearchParams(window.location.search);
        const statusVal = params.get('status') || (filterStatus ? filterStatus.value : '') || '';
        
        // Update filter UI to match URL if different
        if (statusVal && filterStatus && filterStatus.value !== statusVal) {
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
        if(!tableBody) return;
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
            const statusText = event.status_label;
            if (statusText === 'Terjadwal') statusBadge = '<span class="status-badge badge-info text-uppercase">Terjadwal</span>';
            else if (statusText === 'Berlangsung') statusBadge = '<span class="status-badge badge-warning text-uppercase">Berlangsung</span>';
            else if (statusText === 'Selesai') statusBadge = '<span class="status-badge badge-success text-uppercase">Selesai</span>';
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
                    <div class="text-muted" style="font-size:0.8rem;">${new Date(event.tanggal).toLocaleDateString('id-ID', {day:'numeric', month:'long', year:'numeric'})} ${event.jam_mulai ? '| '+event.jam_mulai.slice(0,5) : ''}</div>
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

    if(filterStatus) filterStatus.addEventListener('change', loadData);

    // --- Modal Kegiatan ---
    if(btnAdd) {
        btnAdd.addEventListener('click', () => {
            if(bpjsForm) bpjsForm.reset();
            if(bpjsId) bpjsId.value = '';
            komaSelectReset();
            kecSelectReset();
            if(modalTitle) modalTitle.innerText = "Tambah Jadwal Kegiatan";
            if(bpjsModal) bpjsModal.style.display = 'flex';
        });
    }

    window.editEvent = (id) => {
        const ev = eventsData.find(e => e.id === id);
        if(!ev) return;
        if(bpjsForm) bpjsForm.reset();
        
        if(bpjsId) bpjsId.value = ev.id;
        if(document.getElementById('jenis_kegiatan')) document.getElementById('jenis_kegiatan').value = ev.jenis_kegiatan;
        if(document.getElementById('judul')) document.getElementById('judul').value = ev.judul;
        if(document.getElementById('kuadran')) document.getElementById('kuadran').value = ev.kuadran || '';
        if(document.getElementById('nama_frontliner')) document.getElementById('nama_frontliner').value = ev.nama_frontliner || '';
        if(document.getElementById('tanggal')) document.getElementById('tanggal').value = ev.tanggal ? ev.tanggal.split('T')[0] : '';
        if(document.getElementById('jam_mulai')) document.getElementById('jam_mulai').value = ev.jam_mulai ? ev.jam_mulai.slice(0,5) : '';
        if(document.getElementById('jam_selesai')) document.getElementById('jam_selesai').value = ev.jam_selesai ? ev.jam_selesai.slice(0,5) : '';
        if(document.getElementById('nama_desa')) document.getElementById('nama_desa').value = ev.nama_desa || '';
        if(document.getElementById('lokasi_kegiatan')) document.getElementById('lokasi_kegiatan').value = ev.lokasi_kegiatan || '';
        if(document.getElementById('lokasi_detail')) document.getElementById('lokasi_detail').value = ev.lokasi_detail || '';
        if(document.getElementById('jumlah_petugas')) document.getElementById('jumlah_petugas').value = ev.jumlah_petugas;
        if(document.getElementById('status')) document.getElementById('status').value = ev.status;
        
        // Regions
        komaSelectReset();
        kecSelectReset();
        if(ev.provinsi_id && provSelect) {
            provSelect.value = ev.provinsi_id;
            window.axios.get(`master/cities?province_id=${ev.provinsi_id}`).then(res => {
                if(kotaSelect) {
                    kotaSelect.disabled = false;
                    res.data.data.forEach(c => kotaSelect.innerHTML += `<option value="${c.id}">${c.name}</option>`);
                    if(ev.kota_id) {
                        kotaSelect.value = ev.kota_id;
                        window.axios.get(`master/districts?city_id=${ev.kota_id}`).then(r2 => {
                            if(kecSelect) {
                                kecSelect.disabled = false;
                                r2.data.data.forEach(d => kecSelect.innerHTML += `<option value="${d.id}">${d.name}</option>`);
                                if(ev.kecamatan_id) kecSelect.value = ev.kecamatan_id;
                            }
                        });
                    }
                }
            });
        }
        
        if(modalTitle) modalTitle.innerText = "Edit Jadwal Kegiatan";
        if(bpjsModal) bpjsModal.style.display = 'flex';
    };

    if(bpjsForm) {
        bpjsForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const id = bpjsId ? bpjsId.value : '';
            const formData = new FormData(bpjsForm);
            const data = Object.fromEntries(formData.entries());

            const saveBtn = document.getElementById('btn-save');
            if(saveBtn) { saveBtn.disabled = true; saveBtn.innerText = 'Menyimpan...'; }

            let req = id ? window.axios.put(`admin/bpjs-keliling/${id}`, data) 
                         : window.axios.post(`admin/bpjs-keliling`, data);

            req.then(res => {
                window.showToast(res.data.message, 'success');
                if(bpjsModal) bpjsModal.style.display = 'none';
                loadData();
            }).catch(err => {
                window.showToast("Validasi gagal, cek isian Anda.", "error");
                console.error(err);
            }).finally(() => {
                if(saveBtn) { saveBtn.disabled = false; saveBtn.innerText = 'Simpan Jadwal'; }
            });
        });
    }

    window.deleteEvent = (id) => {
        window.showConfirm("Hapus kegiatan?", "Data laporan (jika ada) juga akan terhapus.", {type:'danger'})
            .then(res => {
                if(res) {
                    window.axios.delete(`admin/bpjs-keliling/${id}`)
                        .then(r => { window.showToast('Dihapus', 'success'); loadData(); });
                }
            });
    }

    // --- Modal Laporan / Entry ---
    const entryPesertaModal = document.getElementById('entryPesertaModal');
    const pesertaForm = document.getElementById('pesertaForm');
    const entryKegiatanId = document.getElementById('entry_kegiatan_id');
    const pesertaListWrap = document.getElementById('peserta-list');
    const filterPesertaInput = document.getElementById('filter-peserta');

    const mainContentArea = document.getElementById('main-content-area');
    const commandCenterUI = document.getElementById('command-center-ui');
    const activeKegiatanTitle = document.getElementById('active-kegiatan-title');

    let currentParticipants = [];
    const jenisLayananSelect = document.getElementById('jenis_layanan');
    const wrapTransaksi = document.getElementById('wrap_transaksi_layanan');
    const transaksiSelect = document.getElementById('transaksi_layanan');
    
    if(jenisLayananSelect) {
        jenisLayananSelect.addEventListener('change', (e) => {
            if (e.target.value === 'Administrasi') {
                if(wrapTransaksi) wrapTransaksi.style.display = 'block';
                if(transaksiSelect) transaksiSelect.required = true;
            } else {
                if(wrapTransaksi) wrapTransaksi.style.display = 'none';
                if(transaksiSelect) { transaksiSelect.required = false; transaksiSelect.value = ''; }
            }
        });
    }

    const statusLayananSelect = document.getElementById('status_layanan');
    const wrapKeterangan = document.getElementById('wrap_keterangan_gagal');
    const keteranganSelect = document.getElementById('keterangan_gagal');
    
    if(statusLayananSelect) {
        statusLayananSelect.addEventListener('change', (e) => {
            if (e.target.value === 'Tidak Berhasil') {
                if(wrapKeterangan) wrapKeterangan.style.display = 'block';
                if(keteranganSelect) keteranganSelect.required = true;
            } else {
                if(wrapKeterangan) wrapKeterangan.style.display = 'none';
                if(keteranganSelect) { keteranganSelect.required = false; keteranganSelect.value = ''; }
            }
        });
    }

    window.handleLaporan = (id) => {
        const ev = eventsData.find(e => e.id === id);
        if(!ev) return;

        if(pesertaForm) pesertaForm.reset();
        if(entryKegiatanId) entryKegiatanId.value = ev.id;
        if(activeKegiatanTitle) activeKegiatanTitle.innerText = ev.judul;
        
        // Reset dynamic fields
        if(wrapTransaksi) wrapTransaksi.style.display = 'none';
        if(transaksiSelect) transaksiSelect.required = false;
        if(wrapKeterangan) wrapKeterangan.style.display = 'none';
        if(keteranganSelect) keteranganSelect.required = false;

        // Pre-fill participant times from the master activity times
        const defMulai = ev.jam_mulai ? ev.jam_mulai.slice(0,5) : '';
        const defSelesai = ev.jam_selesai ? ev.jam_selesai.slice(0,5) : '';
        if(document.getElementById('peserta_jam_mulai')) document.getElementById('peserta_jam_mulai').value = defMulai;
        if(document.getElementById('peserta_jam_selesai')) document.getElementById('peserta_jam_selesai').value = defSelesai;

        loadParticipants(ev.id);
        
        if(mainContentArea) mainContentArea.style.display = 'none';
        if(commandCenterUI) commandCenterUI.style.display = 'block';
        if(window.lucide) window.lucide.createIcons();
    };

    window.exitCommandCenter = () => {
        if(commandCenterUI) commandCenterUI.style.display = 'none';
        if(mainContentArea) mainContentArea.style.display = 'block';
        loadData(); // Refresh table status
    };

    function loadParticipants(id) {
        if(!pesertaListWrap) return;
        pesertaListWrap.innerHTML = '<div class="text-center text-muted" style="padding:10px;">Loading...</div>';
        window.axios.get(`admin/bpjs-keliling/${id}/participants`)
            .then(res => {
                currentParticipants = res.data.data;
                renderParticipantsList(currentParticipants);
                updateLiveStats(currentParticipants);
            }).catch(e => {
                pesertaListWrap.innerHTML = '<div class="text-danger text-center">Gagal memuat peserta</div>';
            });
    }

    function updateLiveStats(data) {
        const total = data.length;
        const berhasil = data.filter(p => p.status === 'Berhasil').length;
        const gagal = data.filter(p => p.status === 'Tidak Berhasil').length;

        const elTotal = document.getElementById('stat-total');
        const elB = document.getElementById('stat-berhasil');
        const elG = document.getElementById('stat-gagal');

        if(elTotal) elTotal.innerText = total;
        if(elB) elB.innerText = berhasil;
        if(elG) elG.innerText = gagal;
    }

    if(filterPesertaInput) {
        filterPesertaInput.addEventListener('input', (e) => {
            const val = e.target.value.toLowerCase();
            const filtered = currentParticipants.filter(p => 
                p.nik.toLowerCase().includes(val) || 
                (p.transaksi_layanan && p.transaksi_layanan.toLowerCase().includes(val)) ||
                p.jenis_layanan.toLowerCase().includes(val)
            );
            renderParticipantsList(filtered);
        });
    }

    function renderParticipantsList(data) {
        if(!pesertaListWrap) return;
        pesertaListWrap.innerHTML = '';
        if(data.length === 0) {
            pesertaListWrap.innerHTML = '<div class="text-muted text-center" style="padding: 20px; font-size: 0.85rem;">Belum ada peserta</div>';
            return;
        }

        data.forEach(p => {
            let infoStr = p.jenis_layanan;
            let statusClr = p.status === 'Berhasil' ? 'var(--success)' : 'var(--danger)';
            pesertaListWrap.innerHTML += `
                <div style="border:1px solid var(--border); border-radius:6px; padding:10px; background:#fff; position: relative;">
                    <div style="font-weight:700; font-size:0.85rem;">NIK: ${p.nik}</div>
                    <div style="font-size:0.75rem; color:var(--text-muted); margin-bottom: 5px;">${p.segmen_peserta} | ${p.jam_mulai} - ${p.jam_selesai}</div>
                    <div style="font-size:0.75rem; font-weight:600; color:var(--primary);">${infoStr}</div>
                    ${p.transaksi_layanan ? `<div style="font-size:0.7rem;">${p.transaksi_layanan}</div>` : ''}
                    <div style="font-size:0.75rem; font-weight:700; color:${statusClr}; margin-top:5px;">${p.status}</div>
                    <button type="button" onclick="deleteParticipant(${p.id})" style="position: absolute; right: 10px; top: 10px; background:none; border:none; color:var(--danger); cursor:pointer;"><i data-lucide="trash-2" style="width:14px;height:14px"></i></button>
                </div>
            `;
        });
        if(window.lucide) window.lucide.createIcons();
    }

    if(pesertaForm) {
        pesertaForm.addEventListener('submit', (e) => {
            e.preventDefault();
            document.getElementById('feedback-overlay').style.display = 'flex';
            if(window.lucide) window.lucide.createIcons();
        });
    }

    window.submitWithFeedback = (rating) => {
        const elSuara = document.getElementById('suara_pelanggan');
        if(elSuara) elSuara.value = rating;
        
        const id = entryKegiatanId ? entryKegiatanId.value : '';
        const data = Object.fromEntries(new FormData(pesertaForm).entries());

        const btn = document.getElementById('btn-save-peserta');
        const overlay = document.getElementById('feedback-overlay');
        
        if(btn) { btn.disabled = true; btn.innerText = 'Menyimpan...'; }
        if(overlay) overlay.style.display = 'none';

        window.axios.post(`admin/bpjs-keliling/${id}/participants`, data)
            .then(res => {
                window.showToast(res.data.message, 'success');
                
                // Maintain activity times after reset
                const curMulai = document.getElementById('peserta_jam_mulai')?.value;
                const curSelesai = document.getElementById('peserta_jam_selesai')?.value;
                
                if(pesertaForm) pesertaForm.reset();
                
                if(curMulai) document.getElementById('peserta_jam_mulai').value = curMulai;
                if(curSelesai) document.getElementById('peserta_jam_selesai').value = curSelesai;

                if(wrapTransaksi) wrapTransaksi.style.display = 'none';
                if(wrapKeterangan) wrapKeterangan.style.display = 'none';
                window.scrollTo({top:0, behavior:'smooth'});
                if(document.getElementById('nik')) document.getElementById('nik').focus();
                
                loadParticipants(id);
                loadData();
            })
            .catch(err => window.showToast("Gagal simpan peserta. Cek form.", 'error'))
            .finally(() => { 
                if(btn) {
                    btn.disabled = false; 
                    btn.innerHTML = '<i data-lucide="save"></i> Simpan & Entry Baru'; 
                }
                if(window.lucide) window.lucide.createIcons();
            });
    };

    window.deleteParticipant = (p_id) => {
        if(!confirm("Hapus peserta ini?")) return;
        const keg_id = entryKegiatanId ? entryKegiatanId.value : '';
        window.axios.delete(`admin/bpjs-keliling/${keg_id}/participants/${p_id}`)
            .then(res => {
                window.showToast("Dihapus", 'success');
                loadParticipants(keg_id);
                loadData();
            });
    }

    const btnRefreshPeserta = document.getElementById('btn-refresh-peserta');
    if(btnRefreshPeserta) {
        btnRefreshPeserta.addEventListener('click', () => {
            if(entryKegiatanId) loadParticipants(entryKegiatanId.value);
        });
    }

    const btnFinishKegiatan = document.getElementById('btn-finish-kegiatan');
    if(btnFinishKegiatan) {
        btnFinishKegiatan.addEventListener('click', () => {
            window.showConfirm('Selesaikan Laporan', 'Apakah Anda yakin ingin menyelesaikan laporan ini dan kembali ke daftar?', { type: 'success' })
                .then(ok => {
                    if(ok) {
                        const id = entryKegiatanId ? entryKegiatanId.value : '';
                        window.axios.post(`admin/bpjs-keliling/${id}/finish`)
                            .then(res => {
                                window.showToast(res.data.message, 'success');
                                window.exitCommandCenter();
                            });
                    }
                });
        });
    }

    // --- GPS & Time Utils ---
    window.detectGPS = () => {
        if (!navigator.geolocation) {
            window.showToast("GPS tidak didukung browser ini", "error");
            return;
        }
        window.showToast("Mendeteksi lokasi presisi (GPS)...", "info");
        navigator.geolocation.getCurrentPosition((pos) => {
            const { latitude, longitude, accuracy } = pos.coords;
            const detailInput = document.getElementById('lokasi_detail');
            if(detailInput) {
                const accStr = accuracy < 100 ? `(Akurasi: ${Math.round(accuracy)}m)` : `(Akurasi Rendah: ${Math.round(accuracy)}m)`;
                detailInput.value = `📍 [${latitude.toFixed(6)}, ${longitude.toFixed(6)}] ${accStr} ` + detailInput.value;
            }
            if (accuracy > 100) window.showToast("Lokasi didapat, namun akurasi rendah.", "warning");
            else window.showToast("Lokasi presisi berhasil dikunci!", "success");
        }, (err) => {
            window.showToast("Gagal mengambil GPS.", "error");
        }, { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 });
    };

    window.setParticipantTime = (type) => {
        const now = new Date();
        const timeStr = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
        if (type === 'mulai') {
            const el = document.getElementById('peserta_jam_mulai');
            if(el) el.value = timeStr;
        } else {
            const el = document.getElementById('peserta_jam_selesai');
            if(el) el.value = timeStr;
        }
    };

    const nikInput = document.getElementById('nik');
    if (nikInput) {
        nikInput.addEventListener('focus', () => {
            const startInput = document.getElementById('peserta_jam_mulai');
            if(startInput && !startInput.value) window.setParticipantTime('mulai');
        }, { once: true });
    }

    // --- Auto Status Logic ---
    function updateStatusAuto() {
        const dateInput = document.getElementById('tanggal');
        const startInput = document.getElementById('jam_mulai');
        const endInput = document.getElementById('jam_selesai');
        const statusSelect = document.getElementById('status');
        const autoBadge = document.getElementById('status-auto-badge');

        if (!dateInput || !statusSelect || !dateInput.value) return;

        const now = new Date();
        const selectedDate = new Date(dateInput.value);
        const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
        const targetDate = new Date(selectedDate.getFullYear(), selectedDate.getMonth(), selectedDate.getDate());

        let suggested = 'scheduled';
        if (targetDate < today) {
            suggested = 'completed';
        } else if (targetDate > today) {
            suggested = 'scheduled';
        } else {
            const currentTotal = now.getHours() * 60 + now.getMinutes();
            if (startInput && startInput.value) {
                const [sh, sm] = startInput.value.split(':').map(Number);
                if (currentTotal < (sh * 60 + sm)) {
                    suggested = 'scheduled';
                } else {
                    suggested = 'ongoing';
                    if (endInput && endInput.value) {
                        const [eh, em] = endInput.value.split(':').map(Number);
                        if (currentTotal > (eh * 60 + em)) suggested = 'completed';
                    }
                }
            } else {
                suggested = 'ongoing';
            }
        }

        statusSelect.value = suggested;
        if(autoBadge) {
            autoBadge.style.display = 'inline-block';
            autoBadge.style.borderColor = 'var(--primary)';
            setTimeout(() => autoBadge.style.borderColor = 'var(--border)', 1000);
        }
    }

    ['tanggal', 'jam_mulai', 'jam_selesai'].forEach(id => {
        const el = document.getElementById(id);
        if(el) el.addEventListener('change', updateStatusAuto);
    });

    loadProvinces();
    loadData();
});
