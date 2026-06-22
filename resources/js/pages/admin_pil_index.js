// resources/js/pages/admin_pil_index.js

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
    const btnAdd = document.getElementById('btn-add');
    
    // Drive UI Elements
    const gridView = document.getElementById('grid-view');
    const listView = document.getElementById('list-view');
    const toggleGrid = document.getElementById('toggle-grid');
    const toggleList = document.getElementById('toggle-list');
    const statusChips = document.getElementById('status-chips');

    let currentViewMode = localStorage.getItem('pil_view_mode') || 'list';
    let selectedStatus = '';
    
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

    // --- Master Data wilayah (Contextual Loading) ---
    let userContext = null;

    // --- Master Data wilayah (Contextual Loading) ---
    async function initRegionContext() {
        try {
            const ctxRes = await window.axios.get('master/get-context');
            userContext = ctxRes.data.data;
            
            // Context Display in UI
            const kwName = document.getElementById('ui-kw-name');
            const kcName = document.getElementById('ui-kc-name');
            if (userContext) {
                if (kwName) kwName.innerText = userContext.kantor_cabang?.kedeputian_wilayah || userContext.unit_name || '-';
                if (kcName) kcName.innerText = userContext.kantor_cabang?.name || userContext.unit_name || '-';
            }

            await loadProvinces();
        } catch (err) {
            console.error("Gagal load context wilayah", err);
        }
    }

    async function loadProvinces() {
        try {
            const provRes = await window.axios.get('master/provinces');
            if(provSelect) {
                provSelect.innerHTML = '<option value="">Pilih Provinsi...</option>';
                provRes.data.data.forEach(p => {
                    provSelect.innerHTML += `<option value="${p.id}">${p.name}</option>`;
                });

                const scopeType = userContext?.scope_type;
                // Lock province for Admin Wilayah (kenwil) and Admin Cabang (branch)
                if ((scopeType === 'kenwil' || scopeType === 'branch') && provRes.data.data.length >= 1) {
                    provSelect.value = provRes.data.data[0].id;
                    provSelect.disabled = true;
                    await loadCities(provSelect.value);
                }
            }
        } catch (err) {
            console.error("Gagal load provinces", err);
        }
    }

    async function loadCities(provinceId) {
        if (!provinceId || !kotaSelect) return;
        try {
            const res = await window.axios.get(`master/cities?province_id=${provinceId}`);
            kotaSelect.innerHTML = '<option value="">Kota/Kab...</option>';
            res.data.data.forEach(c => {
                kotaSelect.innerHTML += `<option value="${c.id}">${c.name}</option>`;
            });
            kotaSelect.disabled = false;

            // If only 1 city — lock (Admin Cabang)
            if (res.data.data.length === 1) {
                kotaSelect.value = res.data.data[0].id;
                kotaSelect.disabled = true;
                await loadDistricts(kotaSelect.value);
            }
        } catch (err) {
            console.error("Gagal load cities", err);
        }
    }

    async function loadDistricts(cityId) {
        if (!cityId || !kecSelect) return;
        try {
            const res = await window.axios.get(`master/districts?city_id=${cityId}`);
            kecSelect.innerHTML = '<option value="">Kecamatan...</option>';
            res.data.data.forEach(d => {
                kecSelect.innerHTML += `<option value="${d.id}">${d.name}</option>`;
            });
            kecSelect.disabled = false;
        } catch (err) {
            console.error("Gagal load districts", err);
        }
    }

    if(provSelect) {
        provSelect.addEventListener('change', (e) => {
            komaSelectReset();
            kecSelectReset();
            if(e.target.value) loadCities(e.target.value);
        });
    }

    if(kotaSelect) {
        kotaSelect.addEventListener('change', (e) => {
            kecSelectReset();
            if(e.target.value) loadDistricts(e.target.value);
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

    const filterJudul = document.getElementById('filter-judul');
    if(filterJudul) {
        let typingTimer;
        filterJudul.addEventListener('input', (e) => {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                loadData();
            }, 500);
        });
    }

    const filterJam = document.getElementById('filter-jam');
    if(filterJam) filterJam.addEventListener('change', loadData);

    const filterDari = document.getElementById('filter-dari');
    if(filterDari) filterDari.addEventListener('change', loadData);

    const filterSampai = document.getElementById('filter-sampai');
    if(filterSampai) filterSampai.addEventListener('change', loadData);

    function loadData() {
        const params = new URLSearchParams(window.location.search);
        
        const statusVal = selectedStatus || (document.getElementById('filter-status') ? document.getElementById('filter-status').value : '') || params.get('status') || '';
        const judulVal = document.getElementById('filter-judul') ? document.getElementById('filter-judul').value : '';
        const jamVal = document.getElementById('filter-jam') ? document.getElementById('filter-jam').value : '';
        const dariVal = document.getElementById('filter-dari') ? document.getElementById('filter-dari').value : '';
        const sampaiVal = document.getElementById('filter-sampai') ? document.getElementById('filter-sampai').value : '';

        // Sync Chips UI
        if (statusChips) {
            statusChips.querySelectorAll('.chip').forEach(c => {
                c.classList.toggle('active', c.dataset.status === statusVal);
            });
        }

        const queryParams = new URLSearchParams();
        if (statusVal) queryParams.set('status', statusVal);
        if (judulVal) queryParams.set('judul', judulVal);
        if (jamVal) queryParams.set('jam_mulai', jamVal);
        if (dariVal) queryParams.set('dari', dariVal);
        if (sampaiVal) queryParams.set('sampai', sampaiVal);

        const qs = queryParams.toString() ? '?' + queryParams.toString() : '';
        console.log("PIL LoadData Query:", qs);
        
        // Update URL
        const newUrl = window.location.pathname + qs;
        window.history.replaceState({}, '', newUrl);

        window.axios.get('admin/pil' + qs)
            .then(res => {
                console.log("PIL API Response:", res.data);
                eventsData = res.data.data || [];
                console.log("Items to render:", eventsData.length);

                // Render Autocomplete Suggestions for Index
                const datalist = document.getElementById('judul-list-index');
                if (datalist && eventsData.length > 0) {
                    const titles = [...new Set(eventsData.map(e => e.judul))].filter(Boolean);
                    datalist.innerHTML = '';
                    titles.forEach(t => datalist.innerHTML += `<option value="${t}">`);
                }

                renderData();
            })
            .catch(err => console.error("Gagal load jadwal", err));
    }

    // View Toggling Logic
    function setViewMode(mode) {
        currentViewMode = mode;
        localStorage.setItem('pil_view_mode', mode);
        
        if (mode === 'grid') {
            if (gridView) gridView.style.display = 'grid';
            if (listView) listView.style.display = 'none';
            if (toggleGrid) toggleGrid.classList.add('active');
            if (toggleList) toggleList.classList.remove('active');
        } else {
            if (gridView) gridView.style.display = 'none';
            if (listView) listView.style.display = 'block';
            if (toggleGrid) toggleGrid.classList.remove('active');
            if (toggleList) toggleList.classList.add('active');
        }
        renderData();
    }

    if (toggleGrid) toggleGrid.addEventListener('click', () => setViewMode('grid'));
    if (toggleList) toggleList.addEventListener('click', () => setViewMode('list'));

    if (statusChips) {
        statusChips.querySelectorAll('.chip').forEach(chip => {
            chip.addEventListener('click', () => {
                selectedStatus = chip.dataset.status;
                loadData();
            });
        });
    }

    function renderData() {
        if (currentViewMode === 'grid') {
            renderGridView();
        } else {
            renderTableView();
        }
    }

    function renderTableView() {
        if(!tableBody) return;
        tableBody.innerHTML = '';
        if (eventsData.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="4" class="text-center p-4 text-muted">Belum ada agenda kegiatan PIL.</td></tr>';
            return;
        }

        eventsData.forEach(event => {
            const hasLaporan = event.jumlah_peserta > 0;
            const isCompleted = event.status === 'completed';
            
            let locParts = [];
            if(event.lokasi_kegiatan) locParts.push(event.lokasi_kegiatan);
            if(event.kecamatan?.name) locParts.push(event.kecamatan.name);
            else if(event.nama_desa) locParts.push(event.nama_desa);
            
            if(event.kota?.name && locParts.length < 2) locParts.push(event.kota.name);
            
            let locStr = locParts.length > 0 ? locParts.join(', ') : 'Belum di set';

            let statusBadge = '';
            const statusText = event.status_label;
            if (statusText === 'Terjadwal') statusBadge = '<span class="status-badge badge-info text-uppercase">Terjadwal</span>';
            else if (statusText === 'Berlangsung') statusBadge = '<span class="status-badge badge-warning text-uppercase">Berlangsung</span>';
            else if (statusText === 'Selesai') statusBadge = '<span class="status-badge badge-success text-uppercase">Selesai</span>';
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
                    <div class="text-muted" style="font-size:0.8rem;">${new Date(event.tanggal).toLocaleDateString('id-ID', {day:'numeric', month:'long', year:'numeric'})} ${event.jam_mulai ? '| '+event.jam_mulai.slice(0,5) : ''}</div>
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

    function renderGridView() {
        if(!gridView) return;
        gridView.innerHTML = '';
        if (eventsData.length === 0) {
            gridView.innerHTML = '<div class="text-center p-8 text-muted" style="grid-column: 1/-1;">Belum ada agenda kegiatan PIL.</div>';
            return;
        }

        eventsData.forEach(event => {
            const hasLaporan = event.jumlah_peserta > 0;
            const isCompleted = event.status === 'completed';
            
            let locParts = [];
            if(event.lokasi_kegiatan) locParts.push(event.lokasi_kegiatan);
            if(event.kecamatan?.name) locParts.push(event.kecamatan.name);
            else if(event.nama_desa) locParts.push(event.nama_desa);
            
            let locStr = locParts.length > 0 ? locParts.join(', ') : 'Lokasi Belum di set';

            let statusColor = '#3b82f6';
            const statusText = event.status_label;
            if (statusText === 'Berlangsung') statusColor = '#f59e0b';
            else if (statusText === 'Selesai') statusColor = '#10b981';
            else if (statusText === 'Dibatalkan') statusColor = '#ef4444';

            const card = document.createElement('div');
            card.className = 'drive-card';
            card.innerHTML = `
                <div class="card-dots" onclick="toggleContextMenu(event, ${event.id})">
                    <i data-lucide="more-vertical" style="width:20px; height:20px;"></i>
                </div>
                
                <div class="context-menu" id="ctx-${event.id}">
                    <div class="menu-item" onclick="editEvent(${event.id})">
                        <i data-lucide="edit-2" style="width:14px;"></i> Edit Jadwal
                    </div>
                    <div class="menu-item" onclick="handleLaporan(${event.id})">
                        <i data-lucide="clipboard-list" style="width:14px;"></i> ${hasLaporan ? 'Lihat Hasil' : 'Input Laporan'}
                    </div>
                    <div class="menu-item danger" onclick="deleteEvent(${event.id})">
                        <i data-lucide="trash-2" style="width:14px;"></i> Hapus Jadwal
                    </div>
                </div>

                <div class="flex items-start gap-3 mb-3">
                    <div style="background: ${statusColor}15; color: ${statusColor}; padding: 10px; border-radius: 10px;">
                        <i data-lucide="award" style="width:24px; height:24px;"></i>
                    </div>
                    <div style="flex: 1; padding-right: 20px;">
                        <div style="font-size: 0.95rem; font-weight: 800; color: #0f172a; margin-bottom: 2px; line-height:1.2;">${event.judul}</div>
                        <div style="font-size: 0.75rem; color: #64748b; font-weight: 600;">Penyuluhan PIL</div>
                    </div>
                </div>

                <div style="background: #f8fafc; border-radius: 10px; padding: 12px; margin-bottom: 12px;">
                    <div class="flex items-center gap-2 mb-2">
                        <i data-lucide="map-pin" style="width:14px; color: #94a3b8;"></i>
                        <span style="font-size: 0.8rem; font-weight: 600; color: #475569;">${locStr}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i data-lucide="clock" style="width:14px; color: #94a3b8;"></i>
                        <span style="font-size: 0.8rem; font-weight: 600; color: #475569;">
                            ${new Date(event.tanggal).toLocaleDateString('id-ID', {day:'numeric', month:'short'})} 
                            ${event.jam_mulai ? ' • ' + event.jam_mulai.slice(0,5) : ''}
                        </span>
                    </div>
                </div>

                <div class="flex justify-between items-center">
                    <span style="background: ${statusColor}15; color: ${statusColor}; padding: 4px 10px; border-radius: 20px; font-size: 0.65rem; font-weight: 800; text-transform: uppercase;">
                        ${statusText}
                    </span>
                    ${hasLaporan ? `
                        <span style="font-size: 0.7rem; font-weight: 700; color: #10b981;">
                            ${event.jumlah_peserta} Peserta
                        </span>
                    ` : ''}
                </div>
            `;
            gridView.appendChild(card);
        });

        if (window.lucide) window.lucide.createIcons();
    }

    window.toggleContextMenu = (e, id) => {
        e.stopPropagation();
        const allMenus = document.querySelectorAll('.context-menu');
        const targetMenu = document.getElementById(`ctx-${id}`);
        
        const isAlreadyOpen = targetMenu.style.display === 'block';
        
        allMenus.forEach(m => m.style.display = 'none');
        if (!isAlreadyOpen) {
            targetMenu.style.display = 'block';
        }
    };

    document.addEventListener('click', () => {
        document.querySelectorAll('.context-menu').forEach(m => m.style.display = 'none');
    });

    // Initial View Mode
    setViewMode(currentViewMode);

    // --- Modal Kegiatan ---
    if(btnAdd) {
        btnAdd.addEventListener('click', () => {
            if(pilForm) pilForm.reset();
            if(pilId) pilId.value = '';
            
            // Re-apply context loading to ensure locked fields are restored
            loadProvinces(); 

            if(modalTitle) modalTitle.innerText = "Tambah Agenda Penyuluhan";
            if(pilModal) pilModal.style.display = 'flex';
        });
    }

    window.editEvent = (id) => {
        const ev = eventsData.find(e => e.id === id);
        if(!ev) return;
        if(pilForm) pilForm.reset();
        
        if(pilId) pilId.value = ev.id;
        if(document.getElementById('judul')) document.getElementById('judul').value = ev.judul;
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
            window.axios.get(`/api/master/cities?province_id=${ev.provinsi_id}`).then(res => {
                if(kotaSelect) {
                    kotaSelect.disabled = false;
                    res.data.data.forEach(c => kotaSelect.innerHTML += `<option value="${c.id}">${c.name}</option>`);
                    if(ev.kota_id) {
                        kotaSelect.value = ev.kota_id;
                        window.axios.get(`/api/master/districts?city_id=${ev.kota_id}`).then(r2 => {
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
        
        if(modalTitle) modalTitle.innerText = "Edit Jadwal PIL";
        if(pilModal) pilModal.style.display = 'flex';
    };

    if(pilForm) {
        pilForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const id = pilId ? pilId.value : '';
            const formData = new FormData(pilForm);
            const data = Object.fromEntries(formData.entries());

            const saveBtn = document.getElementById('btn-save');
            if(saveBtn) { saveBtn.disabled = true; saveBtn.innerText = 'Menyimpan...'; }

            let req = id ? window.axios.put(`admin/pil/${id}`, data) 
                         : window.axios.post(`admin/pil`, data);

            req.then(res => {
                window.showToast(res.data.message, 'success');
                if(pilModal) pilModal.style.display = 'none';
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
        window.showConfirm("Hapus kegiatan PIL?", "Data laporan (jika ada) juga akan terhapus secara permanen.", {type:'danger'})
            .then(res => {
                if(res) {
                    window.axios.delete(`admin/pil/${id}`)
                        .then(r => { window.showToast('Berhasil dihapus', 'success'); loadData(); });
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

    window.handleLaporan = (id) => {
        const ev = eventsData.find(e => e.id === id);
        if(!ev) return;

        if(pesertaForm) pesertaForm.reset();
        if(entryKegiatanId) entryKegiatanId.value = ev.id;
        if(activeKegiatanTitle) activeKegiatanTitle.innerText = ev.judul;
        if(filterPesertaInput) filterPesertaInput.value = '';

        // Pre-fill participant times from the master activity times
        const defMulai = ev.jam_mulai ? ev.jam_mulai.slice(0,5) : '';
        const defSelesai = ev.jam_selesai ? ev.jam_selesai.slice(0,5) : '';
        if(document.getElementById('pil_jam_mulai')) document.getElementById('pil_jam_mulai').value = defMulai;
        if(document.getElementById('pil_jam_selesai')) document.getElementById('pil_jam_selesai').value = defSelesai;
        
        loadParticipants(ev.id);
        
        if(mainContentArea) mainContentArea.style.display = 'none';
        if(commandCenterUI) commandCenterUI.style.display = 'block';
        
        // Hide sidebar for focus mode
        document.querySelector('.app-layout')?.classList.add('sidebar-hidden');

        // Show back button at top, hide other title btns
        if(document.getElementById('btn-back-top')) document.getElementById('btn-back-top').style.display = 'flex';
        if(document.getElementById('btn-to-dashboard')) document.getElementById('btn-to-dashboard').style.display = 'none';
        if(btnAdd) btnAdd.style.display = 'none';

        if(window.lucide) window.lucide.createIcons();
    };

    window.exitCommandCenter = () => {
        if(commandCenterUI) commandCenterUI.style.display = 'none';
        if(mainContentArea) mainContentArea.style.display = 'block';

        // Show sidebar back
        document.querySelector('.app-layout')?.classList.remove('sidebar-hidden');

        // Restore title btns
        if(document.getElementById('btn-back-top')) document.getElementById('btn-back-top').style.display = 'none';
        if(document.getElementById('btn-to-dashboard')) document.getElementById('btn-to-dashboard').style.display = 'inline-flex';
        if(btnAdd) btnAdd.style.display = 'inline-flex';

        loadData(); // Refresh table status
    };

    function loadParticipants(id) {
        if(!pesertaListWrap) return;
        pesertaListWrap.innerHTML = '<div class="text-center text-muted" style="padding:10px;">Loading...</div>';
        window.axios.get(`admin/pil/${id}/participants`)
            .then(res => {
                currentParticipants = res.data.data;
                renderParticipantsList(currentParticipants);
            }).catch(e => {
                pesertaListWrap.innerHTML = '<div class="text-danger text-center">Gagal memuat peserta</div>';
            });
    }

    if(filterPesertaInput) {
        filterPesertaInput.addEventListener('input', (e) => {
            const val = e.target.value.toLowerCase();
            const filtered = currentParticipants.filter(p => {
                const nikStr = p.nik ? String(p.nik).toLowerCase() : '';
                const segStr = p.segmen_peserta ? String(p.segmen_peserta).toLowerCase() : '';
                return nikStr.includes(val) || segStr.includes(val);
            });
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
            const nikDisplay = p.nik || 'NIK Tidak Tersedia';
            const segmen = p.segmen_peserta || '-';
            const jamMulai = p.jam_sosialisasi_mulai ? p.jam_sosialisasi_mulai.slice(0,5) : '--:--';
            const jamSelesai = p.jam_sosialisasi_selesai ? p.jam_sosialisasi_selesai.slice(0,5) : '--:--';

            pesertaListWrap.innerHTML += `
                <div style="border:1.5px solid #e2e8f0; border-radius:12px; padding:12px; background:#fff; position: relative; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                    <div style="font-weight:800; font-size:0.9rem; color: #0f172a; margin-bottom: 4px;">NIK: ${nikDisplay}</div>
                    <div style="font-size:0.75rem; color: #64748b; font-weight: 600; margin-bottom: 8px;">
                        <span style="background: #f1f5f9; padding: 2px 6px; border-radius: 4px;">${segmen}</span>
                        <span style="margin-left: 5px;">${jamMulai} - ${jamSelesai}</span>
                    </div>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <div style="font-size:0.75rem; font-weight:700; color:var(--primary); background: rgba(0, 74, 173, 0.05); padding: 4px 8px; border-radius: 6px;">
                           Pemahaman: ${p.nilai_pemahaman}%
                        </div>
                    </div>
                    <button type="button" onclick="deleteParticipant(${p.id})" style="position: absolute; right: 12px; top: 12px; background:#fff1f2; border:none; color:#e11d48; cursor:pointer; width:28px; height:28px; border-radius:8px; display:flex; align-items:center; justify-content:center; transition: 0.2s;">
                        <i data-lucide="trash-2" style="width:14px;height:14px"></i>
                    </button>
                </div>
            `;
        });
        if(window.lucide) window.lucide.createIcons();
    }

    if(pesertaForm) {
        pesertaForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const id = entryKegiatanId ? entryKegiatanId.value : '';
            const data = Object.fromEntries(new FormData(pesertaForm).entries());

            const btn = document.getElementById('btn-save-peserta');
            if(btn) { btn.disabled = true; btn.innerText = 'Menyimpan...'; }

            window.axios.post(`admin/pil/${id}/participants`, data)
                .then(res => {
                    window.showToast(res.data.message, 'success');
                    
                    // Maintain activity times after reset
                    const curMulai = document.getElementById('pil_jam_mulai')?.value;
                    const curSelesai = document.getElementById('pil_jam_selesai')?.value;
                    
                    pesertaForm.reset();
                    
                    if(curMulai) document.getElementById('pil_jam_mulai').value = curMulai;
                    if(curSelesai) document.getElementById('pil_jam_selesai').value = curSelesai;

                    window.scrollTo({top:0, behavior:'smooth'});
                    if(document.getElementById('nik')) document.getElementById('nik').focus();
                    
                    loadParticipants(id);
                    loadData(); // Memperbarui daftar utama
                })
                .catch(err => {
                    let msg = "Gagal simpan peserta.";
                    if (err.response && err.response.data) {
                        if (err.response.data.errors) {
                            msg = Object.values(err.response.data.errors).flat()[0];
                        } else if (err.response.data.message) {
                            msg = err.response.data.message;
                        }
                    }
                    window.showToast(msg, 'error');
                })
                .finally(() => { 
                    if(btn) {
                        btn.disabled = false; 
                        btn.innerHTML = '<i data-lucide="check-circle"></i> Simpan Evaluasi & Entry Baru'; 
                    }
                    if(window.lucide) window.lucide.createIcons();
                });
        });
    }

    window.deleteParticipant = (p_id) => {
        if(!confirm("Hapus peserta ini?")) return;
        const keg_id = entryKegiatanId ? entryKegiatanId.value : '';
        window.axios.delete(`admin/pil/${keg_id}/participants/${p_id}`)
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
            window.showConfirm('Selesaikan Agenda PIL', 'Apakah Anda yakin ingin menyelesaikan agenda ini dan kembali ke daftar?', { type: 'success' })
                .then(ok => {
                    if(ok) {
                        const id = entryKegiatanId ? entryKegiatanId.value : '';
                        window.axios.post(`admin/pil/${id}/finish`)
                            .then(res => {
                                window.showToast(res.data.message, 'success');
                                window.exitCommandCenter();
                            });
                    }
                });
        });
    }


    // --- PIL Time Utils ---
    window.setPilTime = (type) => {
        const now = new Date();
        const timeStr = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
        if (type === 'mulai') {
            const el = document.getElementById('pil_jam_mulai');
            if(el) el.value = timeStr;
        } else {
            const el = document.getElementById('pil_jam_selesai');
            if(el) el.value = timeStr;
        }
    };

    // Auto-fill start time on NIK focus
    const nikInput = document.getElementById('nik');
    if (nikInput) {
        nikInput.addEventListener('focus', () => {
            const startInput = document.getElementById('pil_jam_mulai');
            if(startInput && !startInput.value) window.setPilTime('mulai');
        }, { once: true });
    }

    // INIT
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

            // Auto-detect Province and City using Reverse Geocoding
            fetch(`https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${latitude}&longitude=${longitude}&localityLanguage=id`)
                .then(res => res.json())
                .then(async data => {
                    let provName = "";
                    let cityName = "";
                    let kecName = "";

                    if (data.localityInfo && data.localityInfo.administrative) {
                        const p = data.localityInfo.administrative.find(a => a.adminLevel === 4);
                        if (p) provName = p.name;
                        const c = data.localityInfo.administrative.find(a => a.adminLevel === 5);
                        if (c) cityName = c.name;
                        const k = data.localityInfo.administrative.find(a => a.adminLevel === 6);
                        if (k) kecName = k.name;
                    }
                    if (!provName && data.principalSubdivision) provName = data.principalSubdivision;
                    if (!cityName && data.city) cityName = data.city;
                    if (!kecName && data.locality) kecName = data.locality;

                    const normalizeName = (n) => n ? n.toUpperCase().replace(/PROVINSI |KOTA |KABUPATEN |KECAMATAN /g, '').trim() : "";
                    provName = normalizeName(provName);
                    cityName = normalizeName(cityName);
                    kecName = normalizeName(kecName);

                    if (provName) {
                        const provSelect = document.getElementById('provinsi_id');
                        if (provSelect) {
                            const normProv = normalizeName(provSelect.options[provSelect.selectedIndex]?.text || '');

                            // If province is already locked/selected, check if it matches GPS
                            if (provSelect.disabled && provSelect.value) {
                                if (normProv === provName) {
                                    window.showToast(`Wilayah terdeteksi: ${provName}`, "info");
                                    await loadCities(provSelect.value);
                                    await matchCityPIL(cityName, kecName);
                                } else {
                                    window.showToast(`Anda berada di ${provName}, namun akun Anda terkunci di ${normProv}.`, "warning");
                                }
                            } else {
                                let provMatched = false;
                                for (let i = 0; i < provSelect.options.length; i++) {
                                    const optName = normalizeName(provSelect.options[i].text);
                                    if (optName === provName) {
                                        provSelect.value = provSelect.options[i].value;
                                        provMatched = true;
                                        window.showToast(`Wilayah terdeteksi: ${provName}`, "info");
                                        await loadCities(provSelect.value);
                                        await matchCityPIL(cityName, kecName);
                                        break;
                                    }
                                }
                                if (!provMatched) {
                                    window.showToast(`Anda berada di ${provName}, namun wilayah ini tidak tersedia di pilihan Anda.`, "warning");
                                }
                            }
                        }
                    }
                })
                .catch(err => console.error("Gagal reverse geocoding:", err));
        }, (err) => {
            window.showToast("Gagal mengambil GPS.", "error");
        }, { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 });
    };

    async function matchCityPIL(cityName, kecName) {
        const normalizeName = (n) => n ? n.toUpperCase().replace(/PROVINSI |KOTA |KABUPATEN |KECAMATAN /g, '').trim() : "";
        const kotaSelect = document.getElementById('kota_id');
        if (!cityName || !kotaSelect) return;

        if (kotaSelect.disabled && kotaSelect.value) {
            await loadDistricts(kotaSelect.value);
            await matchKecamatanPIL(kecName);
            return;
        }

        for (let j = 0; j < kotaSelect.options.length; j++) {
            if (normalizeName(kotaSelect.options[j].text) === cityName) {
                kotaSelect.value = kotaSelect.options[j].value;
                await loadDistricts(kotaSelect.value);
                await matchKecamatanPIL(kecName);
                break;
            }
        }
    }

    async function matchKecamatanPIL(kecName) {
        const normalizeName = (n) => n ? n.toUpperCase().replace(/PROVINSI |KOTA |KABUPATEN |KECAMATAN /g, '').trim() : "";
        if (!kecName) return;
        const kecSelect = document.getElementById('kecamatan_id');
        if (!kecSelect) return;
        for (let k = 0; k < kecSelect.options.length; k++) {
            if (normalizeName(kecSelect.options[k].text) === kecName) {
                kecSelect.value = kecSelect.options[k].value;
                break;
            }
        }
    }

    initRegionContext();
    loadData();
});
