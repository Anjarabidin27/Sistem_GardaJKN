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
<div class="page-wrapper" style="padding: 60px 20px;">
    <div class="profile-card" style="max-width: 800px; margin: 0 auto; background: white; border-radius: 20px; border: none; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02); overflow: hidden;">
        <!-- Premium Header Context -->
        <div style="height: 140px; background: linear-gradient(135deg, #004aad 0%, #002d6a 100%); position: relative;">
            <div style="position: absolute; bottom: -40px; left: 32px; width: 100px; height: 100px; background: white; border-radius: 20px; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); border: 5px solid #fff; overflow: hidden;" id="avatarContainer">
                <i data-lucide="user" style="width: 40px; height: 40px; color: #cbd5e1;"></i>
            </div>
            <div style="position: absolute; top: 20px; right: 24px;">
                <a href="/member/informations" style="background: rgba(255,255,255,0.15); color: white; border: 1px solid rgba(255,255,255,0.3); backdrop-filter: blur(8px); text-decoration: none; padding: 6px 14px; font-size: 0.75rem; border-radius: 50px; display: flex; align-items: center; gap: 8px; font-weight: 600;">
                    <i data-lucide="megaphone" style="width: 14px; height: 14px;"></i> Pusat Informasi
                </a>
            </div>
        </div>

        <div style="padding: 60px 40px 40px 40px;">
            <!-- Header Identity -->
            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 40px; padding-bottom: 32px; border-bottom: 1px solid #f1f5f9;">
                <div>
                    <h1 id="nameDisplay" style="font-size: 1.8rem; font-weight: 800; color: #1e293b; margin: 0 0 8px 0; letter-spacing: -0.025em;">...</h1>
                    <div style="display: flex; align-items: center; gap: 16px; font-size: 0.9rem;">
                        <span style="color: #64748b; font-weight: 500;">NIK: <span id="nikDisplay" style="color: #1e293b; font-weight: 700;">...</span></span>
                        <div style="width: 1px; height: 16px; background: #e2e8f0;"></div>
                        <span style="color: #64748b; font-weight: 500;">No. Kartu JKN: <span id="jknDisplay" style="color: #1e293b; font-weight: 700;">-</span></span>
                    </div>
                </div>
                <div style="display: flex; gap: 10px;">
                    <button class="btn btn-primary" onclick="openEditModal()" style="padding: 10px 24px; background: #004aad; color: white; border: none; border-radius: 12px; font-weight: 700; box-shadow: 0 4px 14px rgba(0, 74, 173, 0.25);">Edit Profil</button>
                    <a href="/settings" class="btn" style="padding: 10px; background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; border-radius: 12px; display: flex; align-items: center; justify-content: center;"><i data-lucide="settings" style="width: 20px; height: 20px;"></i></a>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 60px;">
                <!-- Left Details -->
                <div style="display: flex; flex-direction: column; gap: 32px;">
                    <h3 style="font-size: 0.8rem; font-weight: 800; color: #1e293b; text-transform: uppercase; letter-spacing: 0.1em; margin: 0; display: flex; align-items: center; gap: 10px;">
                        <i data-lucide="contact" style="width: 18px; height: 18px; color: #64748b;"></i> Informasi Pribadi
                    </h3>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px;">
                        <div class="data-row" style="grid-column: span 2;">
                            <div style="font-size: 0.7rem; color: #94a3b8; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">WhatsApp</div>
                            <div id="phoneDisplay" style="font-size: 1.15rem; font-weight: 800; color: #1e293b; letter-spacing: 0.02em;">...</div>
                        </div>
                        <div class="data-row">
                            <div style="font-size: 0.7rem; color: #94a3b8; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">Tanggal Lahir</div>
                            <div id="birthDateDisplay" style="font-size: 1.05rem; font-weight: 700; color: #1e293b;">...</div>
                        </div>
                        <div class="data-row">
                            <div style="font-size: 0.7rem; color: #94a3b8; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">Jenis Kelamin</div>
                            <div id="genderDisplay" style="font-size: 1.05rem; font-weight: 700; color: #1e293b;">...</div>
                        </div>
                    </div>
                </div>

                <!-- Right Details -->
                <div style="display: flex; flex-direction: column; gap: 32px;">
                    <h3 style="font-size: 0.8rem; font-weight: 800; color: #1e293b; text-transform: uppercase; letter-spacing: 0.1em; margin: 0; display: flex; align-items: center; gap: 10px;">
                        <i data-lucide="id-card" style="width: 18px; height: 18px; color: #64748b;"></i> Pekerjaan & Alamat
                    </h3>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px;">
                        <div class="data-row">
                            <div style="font-size: 0.7rem; color: #94a3b8; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">Pekerjaan</div>
                            <div id="occupationDisplay" style="font-size: 1.05rem; font-weight: 800; color: #1e293b;">...</div>
                        </div>
                        <div class="data-row">
                            <div style="font-size: 0.7rem; color: #94a3b8; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">Pendidikan</div>
                            <div id="educationDisplay" style="font-size: 1.05rem; font-weight: 700; color: #1e293b;">...</div>
                        </div>
                        <div class="data-row" style="grid-column: span 2; background: #f8fafc; padding: 20px; border-radius: 16px; border: 1px solid #f1f5f9;">
                            <div style="font-size: 0.7rem; color: #94a3b8; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">Wilayah Domisili</div>
                            <div id="regionDisplay" style="font-weight: 800; color: #1e293b; font-size: 1rem; line-height: 1.4; margin-bottom: 6px;">...</div>
                            <div id="addressDetail" style="font-size: 0.85rem; color: #64748b; font-weight: 500; line-height: 1.6;">...</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Status Section -->
            <div style="margin-top: 48px; padding-top: 32px; border-top: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 10px; background: #f0fdf4; padding: 8px 16px; border-radius: 50px; border: 1px solid #bbf7d0;">
                    <div style="width: 8px; height: 8px; background: #10b981; border-radius: 50%; box-shadow: 0 0 10px rgba(16, 185, 129, 0.4);"></div>
                    <span style="font-size: 0.85rem; color: #166534; font-weight: 800; text-transform: uppercase; letter-spacing: 0.025em;">Anggota Aktif</span>
                </div>
                <button onclick="logout()" style="background: none; border: none; color: #1e293b; font-size: 0.9rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                    <i data-lucide="log-out" style="width: 18px; height: 18px;"></i> Keluar Sesi
                </button>
            </div>

            <!-- Advanced Info (Neutral Colors) -->
            <div id="pengurus-section" style="display:none; margin-top: 32px; padding: 24px; background: #f8fafc; border-radius: 16px; border: 1px solid #e2e8f0;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="font-size: 0.95rem; color: #1e293b; font-weight: 600;">Bergabung menjadi <span style="font-weight: 800;">Pengurus Garda JKN</span>?</div>
                    <button onclick="openPengurusModal()" style="padding: 10px 20px; background: #1e293b; color: white; border: none; border-radius: 10px; font-weight: 700; cursor: pointer;">Daftar Sekarang</button>
                </div>
            </div>

            <div id="pengurus-status-section" style="display:none; margin-top: 32px; padding: 24px; background: #f8fafc; border-radius: 16px; border: 1px solid #e2e8f0;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; flex-direction: column; gap: 2px;">
                        <span style="font-size: 0.7rem; color: #64748b; font-weight: 700; text-transform: uppercase;">Peran Organisasi</span>
                        <div id="memberRoleDisplay" style="font-size: 1.1rem; color: #1e293b; font-weight: 800;">...</div>
                    </div>
                    <div id="statusPengurusBadge"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pendaftaran Pengurus -->
<div id="pengurusModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15,23,42,0.6); z-index:1001; align-items:center; justify-content:center; backdrop-filter: blur(4px);">
    <div style="background: white; width:500px; padding:0; border-radius: 12px; overflow:hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
        <div style="padding:20px 24px; border-bottom:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center;">
            <h3 style="font-size:1rem; font-weight:700; color:#1e293b; margin:0;">Formulir Calon Pengurus</h3>
            <button onclick="closePengurusModal()" style="background:none; border:none; color:#64748b; font-size:1.25rem; cursor:pointer;">&times;</button>
        </div>
        
        <div id="pengurusStep1" style="padding:32px; text-align:center;">
            <div style="width:64px; height:64px; background:#eff6ff; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 20px;">
                <i data-lucide="help-circle" style="width:32px; height:32px; color:#3b82f6;"></i>
            </div>
            <h4 style="font-size:1.125rem; font-weight:700; color:#1e293b; margin-bottom:12px;">Ingin Jadi Pengurus?</h4>
            <p style="color:#64748b; font-size:0.875rem; margin-bottom:24px;">Apakah anda bersedia berkontribusi lebih sebagai pengurus di Garda JKN?</p>
            <div style="display:flex; gap:12px;">
                <button class="btn btn-secondary" onclick="submitPengurusInterest(false)" style="flex:1; padding:12px;">Tidak Sekarang</button>
                <button class="btn btn-primary" onclick="showPengurusStep(2)" style="flex:1; padding:12px; background:#004aad; border:none;">Ya, Saya Ingin</button>
            </div>
        </div>

        <div id="pengurusStep2" style="padding:32px; text-align:center; display:none;">
            <div style="width:64px; height:64px; background:#f0fdf4; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 20px;">
                <i data-lucide="users" style="width:32px; height:32px; color:#22c55e;"></i>
            </div>
            <h4 style="font-size:1.125rem; font-weight:700; color:#1e293b; margin-bottom:12px;">Pengalaman Organisasi</h4>
            <p style="color:#64748b; font-size:0.875rem; margin-bottom:24px;">Apakah anda pernah memiliki pengalaman berorganisasi sebelumnya?</p>
            <div style="display:flex; gap:12px;">
                <button class="btn btn-secondary" onclick="submitPengurusInterest(true, false)" style="flex:1; padding:12px;">Tidak Ada</button>
                <button class="btn btn-primary" onclick="showPengurusStep(3)" style="flex:1; padding:12px; background:#004aad; border:none;">Ya, Ada</button>
            </div>
        </div>

        <div id="pengurusStep3" style="padding:32px; display:none;">
            <h4 style="font-size:1rem; font-weight:700; color:#1e293b; margin-bottom:20px;">Detail Pengalaman & Motivasi</h4>
            <div style="margin-bottom:16px;">
                <label class="label" style="font-size:0.75rem;">Berapa Organisasi yang Pernah Diikuti?</label>
                <input type="number" id="appOrgCount" class="form-input" placeholder="Contoh: 3" style="width:100%; margin-top:4px;">
            </div>
            <div style="margin-bottom:16px;">
                <label class="label" style="font-size:0.75rem;">Apa Saja Organisasi Tersebut?</label>
                <textarea id="appOrgName" class="form-input" rows="3" placeholder="Sebutkan nama-nama organisasi..." style="width:100%; margin-top:4px; resize:none;"></textarea>
            </div>
            <div style="margin-bottom:24px;">
                <label class="label" style="font-size:0.75rem;">Alasan Ingin Menjadi Pengurus?</label>
                <textarea id="appReason" class="form-input" rows="3" placeholder="Tuliskan motivasi anda..." style="width:100%; margin-top:4px; resize:none;"></textarea>
            </div>
            <div style="display:flex; gap:12px;">
                <button class="btn btn-secondary" onclick="showPengurusStep(2)" style="flex:1; padding:12px;">Kembali</button>
                <button class="btn btn-primary" onclick="submitPengurusInterest(true, true)" style="flex:2; padding:12px; background:#004aad; border:none;">Kirim Pendaftaran</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Profil (Modern) -->
<div id="editModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15,23,42,0.6); z-index:1000; align-items:center; justify-content:center; backdrop-filter: blur(8px);">
    <div style="background: white; width:640px; padding:0; overflow:hidden; border-radius: 20px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
        <div style="padding:24px 32px; border-bottom:1px solid #f1f5f9; background:#fff; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 36px; height: 36px; background: #eff6ff; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #004aad;">
                    <i data-lucide="user-plus" style="width: 20px; height: 20px;"></i>
                </div>
                <h3 style="font-size:1.1rem; font-weight:800; color: #1e293b; margin: 0;">Pembaruan Profil</h3>
            </div>
            <button onclick="closeEditModal()" style="background: #f1f5f9; border: none; width: 32px; height: 32px; border-radius: 50%; color: #64748b; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: 0.2s;">&times;</button>
        </div>
        <div style="padding:32px; max-height: 75vh; overflow-y: auto;">
            <div style="margin-bottom: 24px;">
                <label class="label" style="font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 8px; display: block;">Foto Profil</label>
                <div style="display: flex; align-items: center; gap: 16px;">
                    <img id="editPhotoPreview" src="" style="width: 72px; height: 72px; border-radius: 12px; object-fit: cover; object-position: top; background: #f1f5f9; border: 2px solid #e2e8f0; padding: 2px;">
                    <div style="flex: 1;">
                        <input type="file" id="editPhoto" accept="image/*" class="form-input" style="width: 100%; padding: 6px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.75rem;">
                        <small style="color: #94a3b8; font-size: 0.7rem; margin-top: 4px; display: block;">Rekomendasi: 400x400px, JPG/PNG. Max 10MB.</small>
                    </div>
                </div>
            </div>
            <div style="margin-bottom: 20px;">
                <label class="label" style="font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 6px; display: block;">Nama Lengkap</label>
                <input type="text" id="editName" class="form-input" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.875rem;">
            </div>
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px;">
                <div>
                    <label class="label" style="font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 6px; display: block;">Nomor Kartu JKN</label>
                    <input type="text" id="editJknNumber" class="form-input" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.875rem;" placeholder="Opsional (13 digit)">
                </div>
                <div>
                    <label class="label" style="font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 6px; display: block;">No. WhatsApp</label>
                    <input type="text" id="editPhone" class="form-input" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.875rem;">
                </div>
            </div>
            <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:20px; margin-bottom:20px;">
                <div>
                    <label class="label" style="font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 6px; display: block;">Tanggal Lahir</label>
                    <input type="date" id="editBirthDate" class="form-input" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.875rem;">
                </div>
                <div>
                    <label class="label" style="font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 6px; display: block;">Jenis Kelamin</label>
                    <select id="editGender" class="form-input" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.875rem;">
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="label" style="font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 6px; display: block;">Pendidikan</label>
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
                <label class="label" style="font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 6px; display: block;">Jenis Pekerjaan (Sesuai Dukcapil)</label>
                <select id="editOccupation" class="form-input" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.875rem;">
                    <option value="BELUM/TIDAK BEKERJA">BELUM/TIDAK BEKERJA</option>
                    <option value="MENGURUS RUMAH TANGGA">MENGURUS RUMAH TANGGA</option>
                    <option value="PELAJAR/MAHASISWA">PELAJAR/MAHASISWA</option>
                    <option value="PENSIUNAN">PENSIUNAN</option>
                    <option value="PEGAWAI NEGERI SIPIL">PEGAWAI NEGERI SIPIL</option>
                    <option value="TNI/POLRI">TNI / POLRI</option>
                    <option value="KARYAWAN SWASTA">KARYAWAN SWASTA</option>
                    <option value="KARYAWAN BUMN/BUMD">KARYAWAN BUMN/BUMD</option>
                    <option value="WIRASWASTA">WIRASWASTA</option>
                    <option value="PETANI/PEKEBUN">PETANI/PEKEBUN</option>
                    <option value="NELAYAN/PERIKANAN">NELAYAN/PERIKANAN</option>
                    <option value="BURUH HARIAN LEPAS">BURUH HARIAN LEPAS</option>
                    <option value="PEDAGANG">PEDAGANG</option>
                    <option value="PERANGKAT DESA">PERANGKAT DESA</option>
                    <option value="TENAGA MEDIS">TENAGA MEDIS (DOKTER/PERAWAT)</option>
                    <option value="LAINNYA">LAINNYA</option>
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
            if (e.response?.status === 403) {
                showToast('Akses ditolak. Halaman ini hanya untuk Anggota.', 'error');
                setTimeout(() => window.location.href = '/login', 2000);
            } else {
                showToast('Gagal memuat profil. Silakan coba lagi.', 'error');
            }
        }
    }

    function updateUI(d) {
        document.getElementById('nameDisplay').innerText = d.name;
        document.getElementById('nikDisplay').innerText = d.nik;
        document.getElementById('jknDisplay').innerText = d.jkn_number || '-';
        document.getElementById('phoneDisplay').innerText = d.phone;
        document.getElementById('birthDateDisplay').innerText = d.birth_date ? d.birth_date : '-';
        document.getElementById('genderDisplay').innerText = d.gender === 'L' ? 'Laki-laki' : 'Perempuan';
        document.getElementById('educationDisplay').innerText = d.education;
        document.getElementById('occupationDisplay').innerText = d.occupation;
        document.getElementById('addressDetail').innerText = d.address_detail;
        document.getElementById('regionDisplay').innerText = `${d.district.name}, ${d.city.name}, ${d.province.name}`;
        
        // Photo or Initials
        if (d.photo_path) {
            document.getElementById('avatarContainer').innerHTML = `<img src="${d.photo_url}" style="width: 100%; height: 100%; object-fit: cover; object-position: top;" alt="${d.name}">`;
        } else {
            const initials = d.name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
            document.getElementById('avatarContainer').innerHTML = `<span style="font-weight: 800; color: #004aad; font-size: 2.5rem;">${initials}</span>`;
        }

        // Pengurus Logic
        const pSection = document.getElementById('pengurus-section');
        const psSection = document.getElementById('pengurus-status-section');
        const statusBadge = document.getElementById('statusPengurusBadge');
        const roleDisplay = document.getElementById('memberRoleDisplay');

        if (d.status_pengurus === 'tidak_mendaftar') {
            pSection.style.display = 'block';
            psSection.style.display = 'none';
        } else {
            pSection.style.display = 'none';
            psSection.style.display = 'block';
            roleDisplay.innerText = d.role === 'pengurus' ? 'PENGURUS GARDA JKN' : 'Anggota Biasa';
            
            let badgeHtml = '';
            if (d.status_pengurus === 'pendaftaran_diterima') {
                badgeHtml = '<span class="status-badge" style="background:#fffbeb; color:#92400e; border:1px solid #fde68a; border-radius: 50px; padding: 4px 14px; font-weight: 700; font-size: 0.75rem;">MENUNGGU VERIFIKASI</span>';
            } else if (d.status_pengurus === 'aktif') {
                badgeHtml = '<span class="status-badge" style="background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; border-radius: 50px; padding: 4px 14px; font-weight: 700; font-size: 0.75rem;">KEPENGURUSAN AKTIF</span>';
            } else {
                badgeHtml = `<span class="status-badge" style="border-radius: 50px; padding: 4px 14px; font-weight: 700; font-size: 0.75rem; background: #f1f5f9; color: #475569;">${d.status_pengurus.toUpperCase()}</span>`;
            }
            statusBadge.innerHTML = badgeHtml;
        }
    }

    // --- Pengurus Modal Logic ---
    function openPengurusModal() {
        showPengurusStep(1);
        document.getElementById('pengurusModal').style.display = 'flex';
    }

    function closePengurusModal() {
        document.getElementById('pengurusModal').style.display = 'none';
    }

    function showPengurusStep(step) {
        document.getElementById('pengurusStep1').style.display = step === 1 ? 'block' : 'none';
        document.getElementById('pengurusStep2').style.display = step === 2 ? 'block' : 'none';
        document.getElementById('pengurusStep3').style.display = step === 3 ? 'block' : 'none';
    }

    async function submitPengurusInterest(interested, hasOrg = false) {
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
                showToast('Mohon lengkapi semua data pendaftaran.', 'warning');
                return;
            }
        }

        if (btn) {
            btn.disabled = true;
            btn.innerText = 'Mengirim...';
        }

        try {
            await axios.post('member/apply-pengurus', payload);
            showToast('Data kepengurusan berhasil disimpan!', 'success');
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
            showToast(msg, 'error');
        } finally {
            if (btn) {
                btn.disabled = false;
                btn.innerText = originalText;
            }
        }
    }

    // --- Modal Logic ---
    async function openEditModal() {
        if(!currentData) return;
        
        document.getElementById('editName').value = currentData.name;
        document.getElementById('editJknNumber').value = currentData.jkn_number || '';
        document.getElementById('editPhone').value = currentData.phone;
        document.getElementById('editBirthDate').value = currentData.birth_date;
        document.getElementById('editGender').value = currentData.gender;
        document.getElementById('editEducation').value = currentData.education;
        document.getElementById('editOccupation').value = currentData.occupation;
        document.getElementById('editAddress').value = currentData.address_detail;
        document.getElementById('editPhotoPreview').src = currentData.photo_url;
        document.getElementById('editPhoto').value = '';
        
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
        const formData = new FormData();
        formData.append('_method', 'PUT'); // Spofing method for multipart data
        formData.append('name', document.getElementById('editName').value);
        formData.append('jkn_number', document.getElementById('editJknNumber').value);
        formData.append('phone', document.getElementById('editPhone').value);
        formData.append('birth_date', document.getElementById('editBirthDate').value);
        formData.append('gender', document.getElementById('editGender').value);
        formData.append('education', document.getElementById('editEducation').value);
        formData.append('occupation', document.getElementById('editOccupation').value);
        const provId = document.getElementById('editProvince').value;
        const cityId = document.getElementById('editCity').value;
        const distId = document.getElementById('editDistrict').value;

        if (provId) formData.append('province_id', provId);
        if (cityId) formData.append('city_id', cityId);
        if (distId) formData.append('district_id', distId);

        formData.append('address_detail', document.getElementById('editAddress').value);

        const photoInput = document.getElementById('editPhoto');
        if (photoInput.files[0]) {
            formData.append('photo', photoInput.files[0]);
        }

        const btn = event.currentTarget;
        const originalText = btn.innerText;
        btn.disabled = true;
        btn.innerText = 'Menyimpan...';

        try {
            await axios.post('member/profile', formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            });
            showToast('Profil berhasil diperbarui!', 'success');
            closeEditModal();
            fetchProfile(); // Refresh UI
        } catch (e) {
            console.error(e);
            showToast(e.response?.data?.message || 'Gagal memperbarui profil.', 'error');
        } finally {
            btn.disabled = false;
            btn.innerText = originalText;
        }
    }

    function logout() { localStorage.clear(); window.location.href = '/login'; }
</script>
@endpush
