    let currentData = null;

    document.addEventListener('DOMContentLoaded', async () => {
        fetchProfile();
        fetchInformations();
        const dateEl = document.getElementById('date-now') || document.getElementById('topbarDate');
        if (dateEl) dateEl.innerText = new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        
        // Handle URL hash navigation (e.g. /member/profile#survey from settings sidebar)
        const hash = window.location.hash.replace('#', '');
        const validSections = ['profil', 'informasi', 'pembayaran', 'laporan', 'survey'];
        if (hash && validSections.includes(hash)) {
            // Wait a tiny tick for DOM to be ready
            setTimeout(() => switchSection(hash), 50);
        }

        // Activity form handler
        const actForm = document.getElementById('activityForm');
        if (actForm) {
            actForm.addEventListener('submit', (e) => {
                e.preventDefault();
                if(typeof showToast !== 'undefined') showToast('Laporan kegiatan berhasil disimpan!', 'success');
                e.target.reset();
            });
        }

        // Survey form handler
        const srvForm = document.getElementById('surveyForm');
        if (srvForm) {
            srvForm.addEventListener('submit', (e) => {
                e.preventDefault();
                if(typeof showToast !== 'undefined') showToast('Terima kasih! Survey Anda telah kami terima.', 'success');
                e.target.reset();
            });
        }
    });

    async function fetchInformations() {
        try {
            const res = await axios.get('member/informations');
            renderInformations(res.data.data);
        } catch (e) {
            console.error(e);
            const infoCont = document.getElementById('infoList');
            if (infoCont) infoCont.innerHTML = '<div style="grid-column: 1/-1; text-align: center; color: #64748b;">Gagal memuat informasi.</div>';
        }
    }

    function renderInformations(items) {
        const container = document.getElementById('infoList');
        if (!container) return;
        
        if (items.length === 0) {
            container.innerHTML = `
                <div style="grid-column: 1/-1; text-align: center; padding: 80px 40px; background: white; border-radius: 24px; border: 1px dashed #e2e8f0;">
                    <div style="margin-bottom: 20px; color: #cbd5e1;"><i data-lucide="info" style="width: 48px; height: 48px; margin: 0 auto;"></i></div>
                    <h3 style="font-weight: 800; color: #1e293b; margin-bottom: 8px;">Belum Ada Informasi</h3>
                    <p style="color: #94a3b8; font-size: 0.9rem;">Dapatkan pembaruan terkini seputar program Garda JKN di sini.</p>
                </div>`;
            if(typeof lucide !== 'undefined') lucide.createIcons();
            return;
        }

        container.innerHTML = '';
        items.forEach(item => {
            let visual = '';
            const dateStr = new Date(item.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
            
            if (item.type === 'image' && item.attachment_url) {
                visual = `
                    <div style="position: relative; height: 180px; overflow: hidden; border-radius: 14px; margin-bottom: 16px;">
                        <img src="${item.attachment_url}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;">
                        <div style="position: absolute; top: 12px; left: 12px; background: rgba(0,0,0,0.4); backdrop-filter: blur(4px); color: white; padding: 4px 12px; border-radius: 50px; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em;">FOTO</div>
                    </div>`;
            } else if (item.type === 'pdf') {
                visual = `
                    <div style="height: 180px; background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border-radius: 14px; margin-bottom: 16px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #b91c1c; gap: 12px;">
                        <i data-lucide="file-text" style="width: 48px; height: 48px;"></i>
                        <div style="background: white; color: #b91c1c; padding: 4px 12px; border-radius: 50px; font-size: 0.65rem; font-weight: 800; text-transform: uppercase;">DOKUMEN PDF</div>
                    </div>`;
            } else {
                visual = `
                    <div style="height: 180px; background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border-radius: 14px; margin-bottom: 16px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #1d4ed8; gap: 12px;">
                        <i data-lucide="bell" style="width: 48px; height: 48px; opacity: 0.5;"></i>
                        <div style="background: white; color: #1d4ed8; padding: 4px 12px; border-radius: 50px; font-size: 0.65rem; font-weight: 800; text-transform: uppercase;">PENGUMUMAN</div>
                    </div>`;
            }

            container.innerHTML += `
                <div class="info-card-premium" onclick="showInfoDetail(${item.id})" style="background: white; border: 1px solid #f1f5f9; border-radius: 24px; padding: 20px; cursor: pointer; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); height: 100%; display: flex; flex-direction: column;">
                    ${visual}
                    <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 700; text-transform: uppercase; margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
                        <i data-lucide="calendar" style="width: 14px; height: 14px;"></i> ${dateStr}
                    </div>
                    <div style="font-weight: 800; color: #1e293b; font-size: 1.1rem; margin-bottom: 10px; line-height: 1.3; letter-spacing: -0.01em;">${item.title}</div>
                    <div style="font-size: 0.9rem; color: #64748b; line-height: 1.6; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; margin-bottom: 20px; flex: 1;">${item.content || ''}</div>
                    <div style="margin-top: auto; display: flex; align-items: center; color: var(--primary); font-size: 0.85rem; font-weight: 800; gap: 6px;">
                        Selengkapnya <i data-lucide="arrow-right" style="width: 16px; height: 16px;"></i>
                    </div>
                </div>
            `;
        });

        // Add Hover styles to the document if not present
        if (!document.getElementById('info-card-styles')) {
            const style = document.createElement('style');
            style.id = 'info-card-styles';
            style.innerHTML = `
                .info-card-premium:hover {
                    transform: translateY(-8px);
                    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
                    border-color: var(--primary-light, #e0e7ff);
                }
                .info-card-premium:hover img {
                    transform: scale(1.05);
                }
            `;
            document.head.appendChild(style);
        }

        if(typeof lucide !== 'undefined') lucide.createIcons();
    }

    window.showInfoDetail = async function(id) {
        try {
            const res = await axios.get(`member/informations/${id}`);
            const item = res.data.data;
            
            let attachmentHtml = '';
            if (item.type === 'image' && item.attachment_url) {
                attachmentHtml = `<img src="${item.attachment_url}" style="width: 100%; border-radius: 12px; margin-top: 16px;">`;
            } else if (item.type === 'pdf' && item.attachment_url) {
                attachmentHtml = `
                    <div style="margin-top: 20px; padding: 16px; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 40px; height: 40px; background: #fee2e2; color: #b91c1c; border-radius: 10px; display: flex; align-items: center; justify-content: center;"><i data-lucide="file-text" style="width: 20px; height: 20px;"></i></div>
                            <div style="font-weight: 700; color: #1e293b; font-size: 0.85rem;">Dokumen Lampiran (PDF)</div>
                        </div>
                        <a href="${item.attachment_url}" target="_blank" class="btn btn-primary" style="padding: 8px 16px; font-size: 0.75rem;">Buka Berkas</a>
                    </div>
                `;
            }

            const modalHtml = `
                <div id="infoDetailModal" style="position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15,23,42,0.65); z-index:1100; display:flex; align-items:center; justify-content:center; backdrop-filter: blur(12px); padding: 20px; animation: fadeIn 0.3s ease;">
                    <div style="background: white; width:100%; max-width: 700px; padding:0; border-radius: 32px; overflow:hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.3); animation: slideUp 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);">
                        <div style="padding:24px 32px; border-bottom:1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center; background: #fff;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 40px; height: 40px; background: rgba(0, 74, 173, 0.08); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--primary);">
                                    <i data-lucide="info" style="width: 20px; height: 20px;"></i>
                                </div>
                                <h3 style="font-size:1.15rem; font-weight:800; color:#1e293b; margin:0; letter-spacing: -0.01em;">Detail Informasi</h3>
                            </div>
                            <button onclick="const m=document.getElementById('infoDetailModal'); m.style.opacity='0'; setTimeout(()=>m.remove(), 300)" style="background: #f1f5f9; border:none; width: 44px; height: 44px; border-radius: 14px; color:#64748b; font-size:1.5rem; cursor:pointer; display: flex; align-items: center; justify-content: center; transition: 0.2s;">&times;</button>
                        </div>
                        <div style="padding:40px; max-height: 80vh; overflow-y: auto;">
                            <div style="display: inline-flex; align-items: center; gap: 8px; font-size: 0.75rem; color: #94a3b8; font-weight: 800; text-transform: uppercase; margin-bottom: 16px; background: #f8fafc; padding: 6px 16px; border-radius: 50px;">
                                <i data-lucide="calendar" style="width: 14px; height: 14px;"></i> 
                                Diterbitkan: ${new Date(item.created_at).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'})}
                            </div>
                            <h2 style="font-size: 1.75rem; font-weight: 800; color: #1e293b; margin-bottom: 24px; line-height: 1.3; letter-spacing: -0.02em;">${item.title}</h2>
                            <div style="font-size: 1.05rem; color: #475569; line-height: 1.8; white-space: pre-wrap; margin-bottom: 32px;">${item.content || ''}</div>
                            ${attachmentHtml}
                        </div>
                    </div>
                </div>
            `;
            
            const div = document.createElement('div');
            div.innerHTML = modalHtml;
            document.body.appendChild(div.firstElementChild);
            if(typeof lucide !== 'undefined') lucide.createIcons();
        } catch (e) {
            if(typeof showToast !== 'undefined') showToast('Gagal memuat detail informasi.', 'error');
        }
    }

    const sectionTitles = {
        'profil': 'Profil Saya',
        'informasi': 'Pusat Informasi',
        'pembayaran': 'Riwayat Pembayaran',
        'laporan': 'Laporan Kegiatan',
        'survey': 'Survey'
    };

    // Logic for direct hash navigation
    window.addEventListener('hashchange', () => {
        const h = window.location.hash.replace('#', '');
        if (h && ['profil', 'informasi', 'pembayaran', 'laporan', 'survey'].includes(h)) {
            switchSection(h);
        }
    });

    window.switchSection = function(sectionId, btn) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
        const target = document.getElementById('section-' + sectionId);
        if (!target) return;
        target.classList.add('active');

        // Update active sidebar link — support both btn param and hash-based nav
        document.querySelectorAll('.sb-link').forEach(el => el.classList.remove('active'));
        if (btn) {
            btn.classList.add('active');
        } else {
            // Find the matching sidebar link by ID
            const matchingLink = document.getElementById('nav-' + sectionId);
            if (matchingLink) matchingLink.classList.add('active');
        }

        // Update topbar title
        const topTitle = document.getElementById('topbarTitle') || document.querySelector('.topbar-title');
        if (topTitle) topTitle.innerText = sectionTitles[sectionId] || sectionId;

        // Clean up URL hash without triggering reload
        if (window.location.hash !== '#' + sectionId) {
            history.replaceState(null, '', '#' + sectionId);
        }

        if(typeof lucide !== 'undefined') lucide.createIcons();
    }

      async function fetchProfile() {
        try {
            console.log('Fetching profile data...');
            const res = await axios.get('member/profile');
            if (res.data && res.data.data) {
                currentData = res.data.data;
                updateUI(currentData);
                if(typeof lucide !== 'undefined') {
                    try { lucide.createIcons(); } catch(le) { console.warn('Lucide icon creation failed', le); }
                }
            } else {
                throw new Error('Data profil kosong atau format tidak sesuai');
            }
        } catch (e) {
            console.error('Error fetching/updating profile:', e);
            if (e.response?.status === 403) {
                const role = localStorage.getItem('user_role');
                if (role === 'admin' || role === 'administrator') {
                    if(typeof showToast !== 'undefined') showToast('Admin tidak memiliki profil member.', 'warning');
                } else {
                    if(typeof showToast !== 'undefined') showToast('Akses ditolak. Silakan login kembali.', 'error');
                }
            } else {
                if(typeof showToast !== 'undefined') showToast('Gagal memuat profil. Silakan coba lagi.', 'error');
            }
        }
    }

    function updateUI(d) {
        if (!d) return;
        console.log('Updating UI with member data:', d.name);

        const setSafeText = (id, val) => {
            const el = document.getElementById(id);
            if (el) el.innerText = val || '-';
        };

        const setSafeHtml = (id, val) => {
            const el = document.getElementById(id);
            if (el) el.innerHTML = val || '';
        };

        setSafeText('nameDisplay', d.name);
        setSafeText('nikDisplay', d.nik);
        setSafeText('jknDisplay', d.jkn_number);
        setSafeText('phoneDisplay', d.phone);
        setSafeText('birthDateDisplay', d.birth_date);
        
        const genderEl = document.getElementById('genderDisplay');
        if (genderEl) genderEl.innerText = d.gender === 'L' ? 'Laki-laki' : (d.gender === 'P' ? 'Perempuan' : '-');
        
        setSafeText('educationDisplay', d.education);
        setSafeText('occupationDisplay', d.occupation);
        setSafeText('addressDetail', d.address_detail);
        
        const regionEl = document.getElementById('regionDisplay');
        if (regionEl && d.district && d.city && d.province) {
            regionEl.innerText = `${d.district.name}, ${d.city.name}, ${d.province.name}`;
        }
        
        // Photo or Initials — hero & sidebar
        const initials = d.name ? d.name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2) : 'U';
        const avatarHero = document.getElementById('avatarContainer');
        const avatarSidebar = document.getElementById('sb-avatar-wrap');
        const topInitials = document.getElementById('user-initials');

        if (d.photo_path || d.photo_url) {
            const photoUrl = d.photo_url || (d.photo_path ? `/storage/${d.photo_path}` : null);
            if (photoUrl) {
                const imgTag = `<img src="${photoUrl}" style="width:100%;height:100%;object-fit:cover;object-position:top;" alt="${d.name}">`;
                if (avatarHero) avatarHero.innerHTML = imgTag;
                if (avatarSidebar) avatarSidebar.innerHTML = imgTag;
            }
        } else {
            if (avatarHero) avatarHero.innerHTML = `<span style="font-weight:800;color:white;font-size:2rem;">${initials}</span>`;
            if (avatarSidebar) avatarSidebar.innerHTML = `<span style="font-weight:800;color:white;font-size:1.5rem;">${initials}</span>`;
        }
        
        if (topInitials) topInitials.innerText = initials;

        // Update sidebar info
        setSafeText('sidebarName', d.name);
        setSafeText('sidebarNik', 'NIK: ' + (d.nik || '-'));

        const sbBadge = document.getElementById('sidebarBadgeWrap');
        if (sbBadge) {
            if (d.role === 'pengurus' || d.status_pengurus === 'aktif') {
                sbBadge.innerHTML = `
                    <div style="background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.2); color: #3b82f6; display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 50px; font-size: 0.65rem; font-weight: 800; text-transform: uppercase;">
                        <span style="width: 6px; height: 6px; background: #3b82f6; border-radius: 50%; box-shadow: 0 0 8px #3b82f6;"></span>
                        PENGURUS AKTIF
                    </div>`;
            } else {
                sbBadge.innerHTML = `
                    <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: #10b981; display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 50px; font-size: 0.65rem; font-weight: 800; text-transform: uppercase;">
                        <span style="width: 6px; height: 6px; background: #10b981; border-radius: 50%; box-shadow: 0 0 8px #10b981;"></span>
                        ANGGOTA AKTIF
                    </div>`;
            }
        }

        // Pengurus Logic in Main Content
        const pSection = document.getElementById('pengurus-section');
        const psSection = document.getElementById('pengurus-status-section');
        const statusBadge = document.getElementById('statusPengurusBadge');
        const roleDisplay = document.getElementById('memberRoleDisplay');

        if (d.status_pengurus === 'tidak_mendaftar') {
            if (pSection) pSection.style.display = 'block';
            if (psSection) psSection.style.display = 'none';
        } else if(d.status_pengurus) {
            if (pSection) pSection.style.display = 'none';
            if (psSection) psSection.style.display = 'block';
            if (roleDisplay) roleDisplay.innerText = d.role === 'pengurus' ? 'PENGURUS GARDA JKN' : 'ANGGOTA BIASA';
            
            let badgeHtml = '';
            if (d.status_pengurus === 'pendaftaran_diterima') {
                badgeHtml = '<span class="status-badge" style="background:#fffbeb; color:#92400e; border:1px solid #fde68a; border-radius: 50px; padding: 4px 14px; font-weight: 700; font-size: 0.75rem;">MENUNGGU VERIFIKASI</span>';
            } else if (d.status_pengurus === 'aktif') {
                badgeHtml = '<span class="status-badge" style="background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; border-radius: 50px; padding: 4px 14px; font-weight: 700; font-size: 0.75rem;">KEPENGURUSAN AKTIF</span>';
            } else {
                badgeHtml = `<span class="status-badge" style="border-radius: 50px; padding: 4px 14px; font-weight: 700; font-size: 0.75rem; background: #f1f5f9; color: #475569;">${d.status_pengurus.toString().toUpperCase()}</span>`;
            }
            if (statusBadge) statusBadge.innerHTML = badgeHtml;
        }
    }

    // --- Pengurus Modal Logic ---
    window.openPengurusModal = function() {
        showPengurusStep(1);
        document.getElementById('pengurusModal').style.display = 'flex';
    }

    window.closePengurusModal = function() {
        document.getElementById('pengurusModal').style.display = 'none';
    }

    window.showPengurusStep = function(step) {
        document.getElementById('pengurusStep1').style.display = step === 1 ? 'block' : 'none';
        document.getElementById('pengurusStep2').style.display = step === 2 ? 'block' : 'none';
        document.getElementById('pengurusStep3').style.display = step === 3 ? 'block' : 'none';
    }

    window.submitPengurusInterest = async function(interested, hasOrg = false) {
        const btn = event?.currentTarget;
        const originalText = btn ? btn.innerText : 'Kirim';
        
        const payload = {
            is_interested_pengurus: interested,
            has_org_experience: hasOrg
        };

        if (interested && hasOrg) {
            payload.org_count = document.getElementById('appOrgCount').value;
            payload.org_name = document.getElementById('appOrgName').value;
            payload.pengurus_reason = document.getElementById('appReason').value;

            if (!payload.org_count || !payload.org_name || !payload.pengurus_reason) {
                if(typeof showToast !== 'undefined') showToast('Mohon lengkapi semua data pendaftaran.', 'warning');
                return;
            }
        }

        if (btn) {
            btn.disabled = true;
            btn.innerText = 'Mengirim...';
        }

        try {
            await axios.post('member/apply-pengurus', payload);
            if(typeof showToast !== 'undefined') showToast('Data kepengurusan berhasil disimpan!', 'success');
            closePengurusModal();
            fetchProfile(); // Refresh UI
        } catch (e) {
            console.error(e);
            let msg = 'Gagal menyimpan data.';
            if (e.response?.data?.errors) {
                msg = Object.values(e.response.data.errors).flat().join(' ');
            } else if (e.response?.data?.message) {
                msg = e.response.data.message;
            }
            if(typeof showToast !== 'undefined') showToast(msg, 'error');
        } finally {
            if (btn) {
                btn.disabled = false;
                btn.innerText = originalText;
            }
        }
    }

    // --- Modal Logic ---
    window.openEditModal = async function() {
        if(!currentData) return;
        
        document.getElementById('editName').value = currentData.name || '';
        document.getElementById('editJknNumber').value = currentData.jkn_number || '';
        document.getElementById('editPhone').value = currentData.phone || '';
        document.getElementById('editBirthDate').value = currentData.birth_date || '';
        document.getElementById('editGender').value = currentData.gender || 'L';
        document.getElementById('editEducation').value = currentData.education || 'SMA';
        document.getElementById('editOccupation').value = currentData.occupation || 'LAINNYA';
        document.getElementById('editAddressDetail').value = currentData.address_detail || '';
        document.getElementById('editDomisiliDetail').value = currentData.domisili_detail || '';
        
        if (currentData.photo_url) {
            document.getElementById('editPhotoPreview').src = currentData.photo_url;
        }
        document.getElementById('editPhoto').value = '';
        
        document.getElementById('editModal').style.display = 'flex';
        
        // Populate regions (KTP)
        await loadProvinces(currentData.province_id, 'editProvince');
        await loadCities(currentData.province_id, currentData.city_id, 'editCity', 'editDistrict');
        await loadDistricts(currentData.city_id, currentData.district_id, 'editDistrict');

        // Populate regions (Domisili)
        await loadProvinces(currentData.dom_province_id, 'editDomProvince');
        await loadCities(currentData.dom_province_id, currentData.dom_city_id, 'editDomCity', 'editDomDistrict');
        await loadDistricts(currentData.dom_city_id, currentData.dom_district_id, 'editDomDistrict');
    }

    window.closeEditModal = function() { document.getElementById('editModal').style.display = 'none'; }

    window.loadProvinces = async function(selectedId = null, targetId = 'editProvince') {
        const res = await axios.get('master/provinces');
        const sel = document.getElementById(targetId);
        if(!sel) return;
        sel.innerHTML = '<option value="">Pilih...</option>';
        res.data.data.forEach(p => {
            sel.innerHTML += `<option value="${p.id}" ${p.id == selectedId ? 'selected' : ''}>${p.name}</option>`;
        });
    }

    window.loadCities = async function(provId, selectedId = null, targetId = 'editCity', nextTargetId = 'editDistrict') {
        const sel = document.getElementById(targetId);
        const distSel = document.getElementById(nextTargetId);
        
        // Reset both child dropdowns
        if(sel) sel.innerHTML = '<option value="">Pilih...</option>';
        if(distSel) distSel.innerHTML = '<option value="">Pilih...</option>';
        
        if(!provId) return;

        const res = await axios.get(`master/cities?province_id=${provId}`);
        res.data.data.forEach(c => {
            const prefix = c.type === 'KOTA' ? 'KOTA ' : 'KAB. ';
            sel.innerHTML += `<option value="${c.id}" ${c.id == selectedId ? 'selected' : ''}>${prefix}${c.name}</option>`;
        });
    }

    window.loadDistricts = async function(cityId, selectedId = null, targetId = 'editDistrict') {
        const sel = document.getElementById(targetId);
        if(sel) sel.innerHTML = '<option value="">Pilih...</option>';
        
        if(!cityId) return;

        const res = await axios.get(`master/districts?city_id=${cityId}`);
        res.data.data.forEach(d => {
            sel.innerHTML += `<option value="${d.id}" ${d.id == selectedId ? 'selected' : ''}>${d.name}</option>`;
        });
    }

    window.submitUpdate = async function(event) {
        if (event) event.preventDefault();
        
        const getVal = (id) => {
            const el = document.getElementById(id);
            return el ? el.value : '';
        };

        const formData = new FormData();
        formData.append('_method', 'PUT'); 
        
        formData.append('name', getVal('editName'));
        const jknVal = getVal('editJknNumber');
        if (jknVal) formData.append('jkn_number', jknVal.replace(/\D/g, ''));
        formData.append('phone', getVal('editPhone').replace(/\D/g, ''));
        formData.append('birth_date', getVal('editBirthDate'));
        formData.append('gender', getVal('editGender'));
        formData.append('education', getVal('editEducation'));
        formData.append('occupation', getVal('editOccupation'));
        
        const provId = getVal('editProvince');
        const cityId = getVal('editCity');
        const distId = getVal('editDistrict');
        if (provId) formData.append('province_id', provId);
        if (cityId) formData.append('city_id', cityId);
        if (distId) formData.append('district_id', distId);
        formData.append('address_detail', getVal('editAddressDetail'));

        const domProvId = getVal('editDomProvince');
        const domCityId = getVal('editDomCity');
        const domDistId = getVal('editDomDistrict');
        if (domProvId) formData.append('dom_province_id', domProvId);
        if (domCityId) formData.append('dom_city_id', domCityId);
        if (domDistId) formData.append('dom_district_id', domDistId);
        formData.append('domisili_detail', getVal('editDomisiliDetail'));

        const photoInput = document.getElementById('editPhoto');
        if (photoInput && photoInput.files[0]) {
            formData.append('photo', photoInput.files[0]);
        }

        const btn = event ? event.currentTarget : document.querySelector('button[onclick^="window.submitUpdate"]');
        const originalText = btn ? btn.innerText : 'Simpan';
        if (btn) {
            btn.disabled = true;
            btn.innerText = 'Menyimpan...';
        }

        try {
            await axios.post('member/profile', formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            });
            if(typeof showToast !== 'undefined') showToast('Profil berhasil diperbarui!', 'success');
            closeEditModal();
            fetchProfile(); 
        } catch (e) {
            console.error(e);
            let msg = 'Gagal memperbarui profil.';
            if (e.response?.data?.errors) {
                const errs = e.response.data.errors;
                msg = Object.values(errs).flat()[0] || msg;
            } else if (e.response?.data?.message) {
                msg = e.response.data.message;
            }
            if(typeof showToast !== 'undefined') showToast(msg, 'error');
        } finally {
            if (btn) {
                btn.disabled = false;
                btn.innerText = originalText;
            }
        }
    }

    window.logout = function() { localStorage.clear(); window.location.href = '/login'; }
