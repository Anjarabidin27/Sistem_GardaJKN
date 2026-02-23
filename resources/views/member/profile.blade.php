@extends('layouts.app')

@section('title', 'Profil Saya - Garda JKN')

@push('styles')
<style>
    .page-wrapper { font-family: 'Inter', sans-serif; background: #f1f5f9; min-height: 100vh; padding: 40px 20px; }
    .profile-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06);
        border: 1px solid #e2e8f0;
    }
    .p-name { font-size: 1.5rem; font-weight: 700; color: #1e293b; }
    .data-label { 
        font-size: 0.7rem; font-weight: 600; color: #64748b; 
        text-transform: uppercase; letter-spacing: 0.025em; margin-bottom: 6px;
        display: flex; align-items: center; gap: 6px;
    }
    .data-value { font-size: 0.95rem; font-weight: 500; color: #1e293b; }
    .btn { transition: all 0.15s ease; cursor: pointer; border-radius: 6px; font-weight: 500; font-size: 0.875rem; }
    .btn:active { transform: translateY(1px); }
    
    .icon-box { color: #94a3b8; width: 16px; height: 16px; }
    .status-badge {
        padding: 4px 10px; border-radius: 4px; font-size: 0.65rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.025em;
    }
</style>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
@endpush

@section('content')
<div class="page-wrapper">
    <div class="profile-card" style="max-width: 900px; margin: 0 auto; overflow: hidden;">
        <!-- Header Section -->
        <div style="height: 120px; background: #004aad; position: relative;">
            <div style="position: absolute; bottom: -40px; left: 32px; width: 96px; height: 96px; background: #f8fafc; border-radius: 8px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); border: 4px solid #fff;" id="avatarContainer">
                <i data-lucide="user" style="width: 40px; height: 40px; color: #64748b;"></i>
            </div>
        </div>

        <div style="padding: 56px 32px 32px 32px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 32px; padding-bottom: 24px; border-bottom: 1px solid #f1f5f9;">
                <div>
                    <h1 class="p-name" id="nameDisplay" style="margin-bottom: 4px;">...</h1>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span id="nikDisplay" style="color: #64748b; font-size: 0.875rem;">NIK: ...</span>
                        <span class="status-badge" style="background: #ecfdf5; color: #065f46; border: 1px solid #d1fae5;">Terverifikasi</span>
                    </div>
                </div>
                <div style="display: flex; gap: 8px;">
                    <button class="btn btn-primary" onclick="openEditModal()" style="padding: 8px 16px; background: #004aad; border: none; color: white;">Edit Profil</button>
                    <button class="btn btn-secondary" onclick="logout()" style="padding: 8px 16px; background: white; border: 1px solid #e2e8f0; color: #64748b;">Keluar Sesi</button>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 48px;">
                <div class="data-section">
                    <h2 style="font-size: 0.875rem; font-weight: 600; color: #1e293b; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                        <i data-lucide="contact" style="width: 16px; height: 16px; color: #004aad;"></i> Informasi Kontak
                    </h2>
                    
                    <div class="data-row" style="margin-bottom: 20px;">
                        <div class="data-label">WhatsApp</div>
                        <div class="data-value" id="phoneDisplay">...</div>
                    </div>
                    
                    <div class="data-row">
                        <div class="data-label">Alamat Domisili</div>
                        <div class="data-value" id="regionDisplay" style="margin-bottom: 4px;">...</div>
                        <div style="font-size: 0.8125rem; color: #64748b; line-height: 1.5;" id="addressDetail">...</div>
                    </div>
                </div>

                <div class="data-section">
                    <h2 style="font-size: 0.875rem; font-weight: 600; color: #1e293b; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                        <i data-lucide="id-card" style="width: 16px; height: 16px; color: #004aad;"></i> Status & Pekerjaan
                    </h2>

                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:24px; margin-bottom:24px;">
                        <div class="data-row">
                            <div class="data-label">Jenis Kelamin</div>
                            <div class="data-value" id="genderDisplay">...</div>
                        </div>
                        <div class="data-row">
                            <div class="data-label">Pendidikan</div>
                            <div class="data-value" id="educationDisplay">...</div>
                        </div>
                    </div>

                    <div class="data-row" style="margin-bottom: 24px;">
                        <div class="data-label">Pekerjaan</div>
                        <div class="data-value" id="occupationDisplay">...</div>
                    </div>

                    <div class="data-row" style="padding: 16px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px;">
                        <div class="data-label">Status Anggota</div>
                        <div style="display: flex; align-items: center; gap: 8px; margin-top: 4px;">
                            <div style="width: 8px; height: 8px; background: #10b981; border-radius: 50%;"></div>
                            <span style="font-weight: 600; color: #1e293b; font-size: 0.875rem;">Aktif Bertugas</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Profil (Modern) -->
<div id="editModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15,23,42,0.6); z-index:1000; align-items:center; justify-content:center; backdrop-filter: blur(4px);">
    <div style="background: white; width:600px; padding:0; overflow:hidden; border-radius: 8px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <div style="padding:20px 32px; border-bottom:1px solid #e2e8f0; background:#fff;">
            <h3 style="font-size:1rem; font-weight:700; color: #1e293b;">Pemutakhiran Profil Anggota</h3>
        </div>
        <div style="padding:32px; max-height: 75vh; overflow-y: auto;">
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px;">
                <div>
                    <label class="label" style="font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 6px; display: block;">Nama Lengkap</label>
                    <input type="text" id="editName" class="form-input" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.875rem;">
                </div>
                <div>
                    <label class="label" style="font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 6px; display: block;">No. WhatsApp</label>
                    <input type="text" id="editPhone" class="form-input" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.875rem;">
                </div>
            </div>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px;">
                <div>
                    <label class="label" style="font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 6px; display: block;">Jenis Kelamin</label>
                    <select id="editGender" class="form-input" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.875rem;">
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="label" style="font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 6px; display: block;">Tingkat Pendidikan</label>
                    <select id="editEducation" class="form-input" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.875rem;">
                        <option value="SD">SD</option>
                        <option value="SMP">SMP</option>
                        <option value="SMA">SMA</option>
                        <option value="Diploma">Diploma</option>
                        <option value="S1/D4">S1/D4</option>
                        <option value="S2">S2</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom:20px;">
                <label class="label" style="font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 6px; display: block;">Sektor Pekerjaan</label>
                <select id="editOccupation" class="form-input" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.875rem;">
                    <option value="Petani">Petani</option>
                    <option value="Pedagang">Pedagang</option>
                    <option value="Nelayan">Nelayan</option>
                    <option value="Wiraswasta">Wiraswasta</option>
                    <option value="Karyawan">Karyawan</option>
                    <option value="PNS">PNS</option>
                    <option value="TNI/POLRI">TNI / POLRI</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px;">
                <div>
                    <label class="label" style="font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 6px; display: block;">Provinsi</label>
                    <select id="editProvince" class="form-input" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.875rem;" onchange="loadCities(this.value)">
                        <option value="">Pilih...</option>
                    </select>
                </div>
                <div>
                    <label class="label" style="font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 6px; display: block;">Kab/Kota</label>
                    <select id="editCity" class="form-input" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.875rem;" onchange="loadDistricts(this.value)">
                        <option value="">Pilih...</option>
                    </select>
                </div>
            </div>
            <div style="margin-bottom:20px;">
                <label class="label" style="font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 6px; display: block;">Kecamatan</label>
                <select id="editDistrict" class="form-input" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.875rem;">
                    <option value="">Pilih...</option>
                </select>
            </div>
            <div>
                <label class="label" style="font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 6px; display: block;">Alamat Lengkap Rumah</label>
                <textarea id="editAddress" class="form-input" rows="2" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.875rem; resize: none;"></textarea>
            </div>
        </div>
        <div style="padding:20px 32px; background:#f8fafc; border-top:1px solid #e2e8f0; display:flex; justify-content:flex-end; gap:12px;">
            <button class="btn btn-secondary" onclick="closeEditModal()" style="padding: 8px 16px; border-radius: 6px; border: 1px solid #e2e8f0; background: white; color: #64748b; font-weight: 500;">Batal</button>
            <button class="btn btn-primary" onclick="submitUpdate()" style="padding: 8px 16px; border-radius: 6px; border: none; background: #004aad; color: white; font-weight: 500;">Simpan Perubahan</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentData = null;

    document.addEventListener('DOMContentLoaded', async () => {
        fetchProfile();
    });

    async function fetchProfile() {
        try {
            const res = await axios.get('member/profile');
            currentData = res.data.data;
            updateUI(currentData);
            lucide.createIcons();
        } catch (e) {
            console.error(e);
        }
    }

    function updateUI(d) {
        document.getElementById('nameDisplay').innerText = d.name;
        document.getElementById('nikDisplay').innerText = `NIK: ${d.nik}`;
        document.getElementById('phoneDisplay').innerText = d.phone;
        document.getElementById('genderDisplay').innerText = d.gender === 'L' ? 'Laki-laki' : 'Perempuan';
        document.getElementById('educationDisplay').innerText = d.education;
        document.getElementById('occupationDisplay').innerText = d.occupation;
        document.getElementById('addressDetail').innerText = d.address_detail;
        document.getElementById('regionDisplay').innerText = `${d.district.name}, ${d.city.name}, ${d.province.name}`;
        
        // Professional Initials Avatar
        const initials = d.name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
        document.getElementById('avatarContainer').innerHTML = `<span style="font-weight: 700; color: #64748b; font-size: 1.5rem;">${initials}</span>`;
    }

    // --- Modal Logic ---
    async function openEditModal() {
        if(!currentData) return;
        
        document.getElementById('editName').value = currentData.name;
        document.getElementById('editPhone').value = currentData.phone;
        document.getElementById('editGender').value = currentData.gender;
        document.getElementById('editEducation').value = currentData.education;
        document.getElementById('editOccupation').value = currentData.occupation;
        document.getElementById('editAddress').value = currentData.address_detail;
        
        document.getElementById('editModal').style.display = 'flex';
        
        // Populate regions
        await loadProvinces(currentData.province_id);
        await loadCities(currentData.province_id, currentData.city_id);
        await loadDistricts(currentData.city_id, currentData.district_id);
    }

    function closeEditModal() { document.getElementById('editModal').style.display = 'none'; }

    async function loadProvinces(selectedId = null) {
        const res = await axios.get('master/provinces');
        const sel = document.getElementById('editProvince');
        sel.innerHTML = '<option value="">Pilih...</option>';
        res.data.data.forEach(p => {
            sel.innerHTML += `<option value="${p.id}" ${p.id == selectedId ? 'selected' : ''}>${p.name}</option>`;
        });
    }

    async function loadCities(provId, selectedId = null) {
        const sel = document.getElementById('editCity');
        const distSel = document.getElementById('editDistrict');
        
        // Reset both child dropdowns
        sel.innerHTML = '<option value="">Pilih...</option>';
        distSel.innerHTML = '<option value="">Pilih...</option>';
        
        if(!provId) return;

        const res = await axios.get(`master/cities?province_id=${provId}`);
        res.data.data.forEach(c => {
            const prefix = c.type === 'KOTA' ? 'KOTA ' : 'KAB. ';
            sel.innerHTML += `<option value="${c.id}" ${c.id == selectedId ? 'selected' : ''}>${prefix}${c.name}</option>`;
        });
    }

    async function loadDistricts(cityId, selectedId = null) {
        const sel = document.getElementById('editDistrict');
        sel.innerHTML = '<option value="">Pilih...</option>';
        
        if(!cityId) return;

        const res = await axios.get(`master/districts?city_id=${cityId}`);
        res.data.data.forEach(d => {
            sel.innerHTML += `<option value="${d.id}" ${d.id == selectedId ? 'selected' : ''}>${d.name}</option>`;
        });
    }

    async function submitUpdate() {
        const payload = {
            name: document.getElementById('editName').value,
            phone: document.getElementById('editPhone').value,
            gender: document.getElementById('editGender').value,
            education: document.getElementById('editEducation').value,
            occupation: document.getElementById('editOccupation').value,
            province_id: document.getElementById('editProvince').value,
            city_id: document.getElementById('editCity').value,
            district_id: document.getElementById('editDistrict').value,
            address_detail: document.getElementById('editAddress').value,
        };

        try {
            await axios.put('member/profile', payload);
            showToast('Profil berhasil diperbarui!', 'success');
            closeEditModal();
            fetchProfile(); // Refresh UI
        } catch (e) {
            showToast(e.response?.data?.message || 'Gagal memperbarui profil.', 'error');
        }
    }

    function logout() { localStorage.clear(); window.location.href = '/login'; }
</script>
@endpush
