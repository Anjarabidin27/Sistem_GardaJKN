<x-member-layout title="Profil Saya - Garda JKN">
    <div id="section-profile" class="tab-content active">
        <!-- Unified Profile Card -->
        <div class="table-card" style="border: none; background: white; padding: 0; border-radius: 28px; margin-bottom: 32px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 20px 25px -5px rgba(0,0,0,0.1); overflow: hidden;">
            <!-- Header: Blue Strip -->
            <div style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%); color: white; padding: 40px; position: relative;">
                <div style="position: absolute; right: -40px; top: -40px; width: 220px; height: 220px; background: rgba(255,255,255,0.08); border-radius: 50%;"></div>
                
                <div class="flex items-center gap-8" style="position: relative; z-index: 2;">
                    <div id="avatarContainer" class="overflow-hidden" style="width: 100px; height: 100px; background: rgba(255,255,255,0.15); border: 3px solid rgba(255,255,255,0.3); border-radius: 22px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 8px 16px rgba(0,0,0,0.1);">
                        <i data-lucide="user" style="width: 48px; height: 48px; opacity: 0.8;"></i>
                    </div>
                    <div style="flex: 1;">
                        <h1 id="nameDisplay" style="color: white; font-size: 1.85rem; font-weight: 800; margin: 0; letter-spacing: -0.01em;">Memuat...</h1>
                        <div class="flex gap-6 mt-2" style="font-size: 0.95rem; opacity: 0.85; font-weight: 500;">
                            <span>NIK: <strong id="nikDisplay" style="font-weight: 700;">—</strong></span>
                            <span style="opacity: 0.4;">|</span>
                            <span>No. JKN: <strong id="jknDisplay" style="font-weight: 700;">—</strong></span>
                        </div>
                    </div>
                    <button class="btn" onclick="window.openEditModal()" style="background: rgba(255,255,255,0.12); color: white; border: 1.5px solid rgba(255,255,255,0.25); border-radius: 14px; padding: 12px 24px; font-size: 0.875rem; font-weight: 700; backdrop-filter: blur(8px);">
                        <i data-lucide="edit-3" style="width: 16px; height: 16px; margin-right: 10px;"></i> Edit Profil
                    </button>
                </div>
            </div>

            <!-- Body: Data Grid -->
            <div style="padding: 40px; display: grid; grid-template-columns: repeat(3, 1fr); gap: 40px; background: white;">
                <div>
                    <div style="font-size: 0.75rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 24px; display: flex; align-items: center; gap: 8px; letter-spacing: 0.05em;">
                        <i data-lucide="phone" style="width: 14px; opacity: 0.7;"></i> Kontak
                    </div>
                    <div style="margin-bottom: 20px;"><div style="font-size: 0.7rem; font-weight: 700; color: #cbd5e1; text-transform: uppercase; margin-bottom: 4px;">No. WhatsApp</div><div id="phoneDisplay" style="font-size: 1.15rem; font-weight: 800; color: #1e293b;">—</div></div>
                    <div style="margin-bottom: 20px;"><div style="font-size: 0.7rem; font-weight: 700; color: #cbd5e1; text-transform: uppercase; margin-bottom: 4px;">Tanggal Lahir</div><div id="birthDateDisplay" style="font-size: 1.15rem; font-weight: 800; color: #1e293b;">—</div></div>
                    <div><div style="font-size: 0.7rem; font-weight: 700; color: #cbd5e1; text-transform: uppercase; margin-bottom: 4px;">Jenis Kelamin</div><div id="genderDisplay" style="font-size: 1.15rem; font-weight: 800; color: #1e293b;">—</div></div>
                </div>
                <div>
                    <div style="font-size: 0.75rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 24px; display: flex; align-items: center; gap: 8px; letter-spacing: 0.05em;">
                        <i data-lucide="briefcase" style="width: 14px; opacity: 0.7;"></i> Pekerjaan
                    </div>
                    <div style="margin-bottom: 24px;"><div style="font-size: 0.7rem; font-weight: 700; color: #cbd5e1; text-transform: uppercase; margin-bottom: 4px;">Jenis Pekerjaan</div><div id="occupationDisplay" style="font-size: 1.15rem; font-weight: 800; color: #1e293b;">—</div></div>
                    <div><div style="font-size: 0.7rem; font-weight: 700; color: #cbd5e1; text-transform: uppercase; margin-bottom: 4px;">Pendidikan Terakhir</div><div id="educationDisplay" style="font-size: 1.15rem; font-weight: 800; color: #1e293b;">—</div></div>
                </div>
                <div>
                    <div style="font-size: 0.75rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 24px; display: flex; align-items: center; gap: 8px; letter-spacing: 0.05em;">
                        <i data-lucide="map-pin" style="width: 14px; opacity: 0.7;"></i> Domisili
                    </div>
                    <div style="margin-bottom: 24px;"><div style="font-size: 0.7rem; font-weight: 700; color: #cbd5e1; text-transform: uppercase; margin-bottom: 4px;">Wilayah</div><div id="regionDisplay" style="font-size: 1.15rem; font-weight: 800; color: #1e293b; line-height: 1.3;">—</div></div>
                    <div><div style="font-size: 0.7rem; font-weight: 700; color: #cbd5e1; text-transform: uppercase; margin-bottom: 4px;">Alamat Lengkap</div><div id="addressDetail" style="font-size: 1.15rem; font-weight: 800; color: #1e293b; line-height: 1.4;">—</div></div>
                </div>
            </div>
        </div>

        <!-- Pengurus Banner -->
        <div id="pengurus-section" style="display:none; margin-bottom: 32px; border: 1px solid rgba(0, 74, 173, 0.1); background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%); padding: 24px 32px; border-radius: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02), 0 2px 4px -1px rgba(0,0,0,0.02); border-left: 5px solid var(--primary);">
            <div class="flex justify-between items-center gap-6">
                <div style="display: flex; align-items: center; gap: 20px;">
                    <div style="width: 52px; height: 52px; background: rgba(0, 74, 173, 0.08); border-radius: 16px; display: flex; align-items: center; justify-content: center; color: var(--primary); flex-shrink: 0;">
                        <i data-lucide="award" style="width: 26px; height: 26px;"></i>
                    </div>
                    <div>
                        <div style="font-weight: 800; font-size: 1.1rem; color: #1e293b; letter-spacing: -0.01em;">Ingin jadi Pengurus Garda JKN?</div>
                        <div style="font-size: 0.875rem; color: #64748b; margin-top: 4px; line-height: 1.5;">Berkontribusi lebih bagi anggota dengan menjadi bagian dari kepengurusan formal kami.</div>
                    </div>
                </div>
                <button class="btn btn-primary" onclick="window.openPengurusModal()" style="padding: 12px 32px; font-size: 0.9rem; border-radius: 14px; box-shadow: 0 4px 12px rgba(0, 74, 173, 0.2); flex-shrink: 0;">Daftar Sekarang</button>
            </div>
        </div>

        <div id="pengurus-status-section" style="display:none; background: white; border-radius: 16px; border: 1px solid #e5eaf2; padding: 24px; margin-bottom: 32px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);" class="table-card">
            <div class="flex justify-between items-center">
                <div>
                    <div style="font-size: 0.7rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.05em;">PERAN ORGANISASI</div>
                    <div id="memberRoleDisplay" style="font-size: 1.35rem; font-weight: 800; color: #1e293b;">—</div>
                </div>
                <div id="statusPengurusBadge"></div>
            </div>
        </div>
    </div>

    <!-- 2. Section Informasi -->
    <div id="section-informasi" class="tab-content">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="topbar-title">Pusat Informasi</h2>
                <p class="text-muted" style="font-size: 0.85rem;">Berita terkini dan pengumuman resmi Garda JKN.</p>
            </div>
        </div>
        <div id="infoList" class="event-grid">
             <!-- Data from JS -->
        </div>
    </div>

    <!-- 3. Section Pembayaran -->
    <div id="section-pembayaran" class="tab-content">
        <div class="table-card">
            <div class="table-header flex justify-between items-center">
                <div>
                    <h3 class="modal-title">Riwayat Pembayaran</h3>
                    <p class="text-muted" style="font-size: 0.85rem;">Kelola iuran dan riwayat transaksi anda.</p>
                </div>
                <button class="btn btn-primary"><i data-lucide="plus-circle" style="width:18px;"></i> Bayar Iuran</button>
            </div>
            <div class="empty-state">
                <i data-lucide="credit-card" class="empty-icon" style="margin: 0 auto 16px;"></i>
                <h4 class="empty-title">Belum ada riwayat pembayaran</h4>
                <p class="empty-text">Semua transaksi iuran Anda akan muncul secara detail di sini.</p>
            </div>
        </div>
    </div>

    <!-- 4. Section Laporan -->
    <div id="section-laporan" class="tab-content">
        <div class="table-card" style="margin: 0; padding: 0; border-radius: 28px; overflow: hidden; border: 1px solid #e5eaf2; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 20px 25px -5px rgba(0,0,0,0.1);">
            <div style="background: #f8fafc; padding: 40px; border-bottom: 1px solid #e5eaf2;">
                <h3 style="font-size: 1.5rem; font-weight: 800; color: #1e293b; margin: 0;">Laporan Kegiatan</h3>
                <p style="color: #64748b; margin-top: 8px;">Laporkan kegiatan sosial atau pengaduan layanan JKN anda.</p>
            </div>
            <form id="activityForm" style="padding: 40px;">
                <div class="form-group" style="margin-bottom: 24px;">
                    <label class="form-label" style="font-weight: 700; color: #475569;">Subjek Laporan</label>
                    <input type="text" class="form-input" placeholder="Contoh: Kesulitan Pendaftaran Faskes" required style="padding: 12px; border-radius: 12px;">
                </div>
                <div class="form-group" style="margin-bottom: 24px;">
                    <label class="form-label" style="font-weight: 700; color: #475569;">Detail Kegiatan / Masalah</label>
                    <textarea class="form-input" rows="5" style="resize: none; padding: 12px; border-radius: 12px;" placeholder="Ceritakan secara detail..." required></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-weight: 700; color: #475569;">Lampiran Foto (Opsional)</label>
                    <input type="file" class="form-input" accept="image/*" style="padding: 12px; border-radius: 12px;">
                </div>
                <div class="flex justify-end mt-8">
                    <button type="submit" class="btn btn-primary" style="padding: 12px 32px; border-radius: 14px; font-weight: 700;">Kirim Laporan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 5. Section Survey -->
    <div id="section-survey" class="tab-content">
        <div class="table-card" style="margin: 0; padding: 0; border-radius: 28px; overflow: hidden; border: 1px solid #e5eaf2; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 20px 25px -5px rgba(0,0,0,0.1);">
            <div style="background: #f8fafc; padding: 40px; border-bottom: 1px solid #e5eaf2; text-align: center;">
                <i data-lucide="clipboard-check" style="width: 48px; height: 48px; color: var(--primary); margin-bottom: 16px;"></i>
                <h3 style="font-size: 1.5rem; font-weight: 800; color: #1e293b; margin: 0;">Survey Kepuasan Anggota</h3>
                <p style="color: #64748b; margin-top: 8px;">Bantu kami meningkatkan layanan dengan mengisi survey singkat berikut.</p>
            </div>
            <form id="surveyForm" style="padding: 40px;">
                <div class="form-group" style="margin-bottom: 32px;">
                    <label class="form-label" style="font-weight: 700; color: #475569; margin-bottom: 16px; display: block;">1. Bagaimana penilaian Anda terhadap kecepatan respon pengurus?</label>
                    <div class="grid" style="grid-template-columns: repeat(4, 1fr); gap: 12px; display: grid;">
                         <label class="btn-pill" style="cursor:pointer; padding: 12px; border: 2px solid #e2e8f0; border-radius: 12px; text-align: center; font-weight: 600;"><input type="radio" name="q1" value="5" style="opacity:0; position:absolute; z-index:-1;" required> Sangat Puas</label>
                         <label class="btn-pill" style="cursor:pointer; padding: 12px; border: 2px solid #e2e8f0; border-radius: 12px; text-align: center; font-weight: 600;"><input type="radio" name="q1" value="4" style="opacity:0; position:absolute; z-index:-1;"> Puas</label>
                         <label class="btn-pill" style="cursor:pointer; padding: 12px; border: 2px solid #e2e8f0; border-radius: 12px; text-align: center; font-weight: 600;"><input type="radio" name="q1" value="3" style="opacity:0; position:absolute; z-index:-1;"> Cukup</label>
                         <label class="btn-pill" style="cursor:pointer; padding: 12px; border: 2px solid #e2e8f0; border-radius: 12px; text-align: center; font-weight: 600;"><input type="radio" name="q1" value="2" style="opacity:0; position:absolute; z-index:-1;"> Buruk</label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-weight: 700; color: #475569;">2. Apa saran perbaikan utama untuk Garda JKN kedepannya?</label>
                    <textarea class="form-input" rows="4" style="resize: none; padding: 12px; border-radius: 12px; margin-top: 8px;" placeholder="Tuliskan saran anda di sini..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-full mt-8" style="padding: 16px; border-radius: 14px; font-weight: 700;">Kirim Survey</button>
            </form>
        </div>
    </div>

    <!-- Modals (Edit Profile & Pengurus) -->
    <div id="editModal" class="modal-overlay" style="display:none;">
        <div class="modal-content" style="max-width: 800px;">
            <div class="modal-header">
                <h3 class="modal-title">Edit Profil Saya</h3>
                <button class="modal-close" onclick="window.closeEditModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div style="display:flex; gap:24px; margin-bottom: 24px; background: #f8fafc; padding: 20px; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div style="width: 120px; height: 120px; background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <img id="editPhotoPreview" style="width:100%; height:100%; object-fit: cover;" src="https://ui-avatars.com/api/?background=004aad&color=fff&size=200">
                        </div>
                        <div style="flex:1;">
                        <label class="form-label">Foto Profil Baru</label>
                        <input type="file" id="editPhoto" class="form-input" style="padding-top: 8px;" accept="image/*" onchange="const fr = new FileReader(); fr.onload = (e) => document.getElementById('editPhotoPreview').src = e.target.result; fr.readAsDataURL(this.files[0])">
                        <p style="font-size: 0.75rem; color: #64748b; margin-top: 8px;">Format JPG/PNG, Maksimal 2MB. Disarankan rasio 1:1.</p>
                        </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" id="editName" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">WhatsApp</label>
                        <input type="text" id="editPhone" class="form-input">
                    </div>
                </div>
                <div class="grid-3">
                    <div class="form-group">
                        <label class="form-label">Nomor Kartu JKN</label>
                        <input type="text" id="editJknNumber" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" id="editBirthDate" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jenis Kelamin</label>
                        <select id="editGender" class="form-input">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Pendidikan Terakhir</label>
                        <select id="editEducation" class="form-input">
                            <option value="SD">SD</option>
                            <option value="SMP">SMP</option>
                            <option value="SMA">SMA</option>
                            <option value="Diploma">Diploma</option>
                            <option value="S1/D4">S1/D4</option>
                            <option value="S2">S2</option>
                            <option value="S3">S3</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jenis Pekerjaan</label>
                        <select id="editOccupation" class="form-input">
                            <option value="PELAJAR/MAHASISWA">PELAJAR/MAHASISWA</option>
                            <option value="KARYAWAN SWASTA">KARYAWAN SWASTA</option>
                            <option value="WIRASWASTA">WIRASWASTA</option>
                            <option value="PEGAWAI NEGERI SIPIL">PEGAWAI NEGERI SIPIL</option>
                            <option value="TNI/POLRI">TNI / POLRI</option>
                            <option value="LAINNYA">LAINNYA</option>
                        </select>
                    </div>
                </div>
                <div class="grid-3">
                    <div class="form-group">
                        <label class="form-label">Provinsi</label>
                        <select id="editProvince" class="form-input" onchange="window.loadCities(this.value)">
                            <option value="">Pilih...</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kabupaten/Kota</label>
                        <select id="editCity" class="form-input" onchange="window.loadDistricts(this.value)">
                            <option value="">Pilih...</option>
                        </select>
                    </div>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Kecamatan (KTP)</label>
                        <select id="editDistrict" class="form-input">
                            <option value="">Pilih...</option>
                        </select>
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label">Alamat Lengkap KTP (Jalan/RT/RW)</label>
                        <input type="text" id="editAddressDetail" class="form-input" placeholder="Jl. Merdeka No. 10...">
                    </div>
                </div>

                <div style="margin-top: 24px; padding-top: 20px; border-top: 1px dashed #e2e8f0;">
                    <div style="font-weight: 800; color: #1e293b; font-size: 0.9rem; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                        <i data-lucide="home" style="width: 16px; color: var(--primary);"></i> ALAMAT DOMISILI (ALAMAT SAAT INI)
                    </div>
                    <div class="grid-3">
                        <div class="form-group">
                            <label class="form-label">Provinsi Domisili</label>
                            <select id="editDomProvince" class="form-input" onchange="window.loadCities(this.value, null, 'editDomCity', 'editDomDistrict')">
                                <option value="">Pilih...</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kota Domisili</label>
                            <select id="editDomCity" class="form-input" onchange="window.loadDistricts(this.value, null, 'editDomDistrict')">
                                <option value="">Pilih...</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kecamatan Domisili</label>
                            <select id="editDomDistrict" class="form-input">
                                <option value="">Pilih...</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Detail Alamat Domisili</label>
                        <textarea id="editDomisiliDetail" class="form-input" rows="2" style="resize: none;" placeholder="Sama dengan KTP atau isi alamat tempat tinggal sekarang..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="window.closeEditModal()">Batal</button>
                <button class="btn btn-primary" onclick="window.submitUpdate()">Simpan Perubahan</button>
            </div>
        </div>
    </div>

    <!-- Registration Pengurus Modal -->
    <div id="pengurusModal" class="modal-overlay" style="display:none;">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <h3 class="modal-title">Pendaftaran Pengurus</h3>
                <button class="modal-close" onclick="window.closePengurusModal()">&times;</button>
            </div>
            <div class="modal-body">
                <!-- Step 1: Interest Inquiry -->
                <div id="pengurusStep1">
                    <div class="text-center py-6">
                        <i data-lucide="award" style="width: 64px; height: 64px; color: var(--primary); margin-bottom: 20px;"></i>
                        <h4 class="font-bold text-dark mb-4">Ingin berkontribusi sebagai Pengurus?</h4>
                        <p class="text-muted mb-8">Sebagai pengurus, Anda akan memiliki peran aktif dalam mengkoordinasikan program Garda JKN di wilayah Anda.</p>
                        <div class="flex gap-4">
                            <button class="btn btn-primary flex-1" onclick="window.showPengurusStep(2)">Ya, Saya Tertarik</button>
                            <button class="btn btn-secondary flex-1" onclick="window.submitPengurusInterest(false)">Belum Saatnya</button>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Experience Inquiry -->
                <div id="pengurusStep2" style="display:none;">
                    <div class="text-center py-6">
                        <i data-lucide="briefcase" style="width: 48px; height: 48px; color: #64748b; margin-bottom: 16px;"></i>
                        <h4 class="font-bold text-dark mb-4">Pengalaman Organisasi</h4>
                        <p class="text-muted mb-8">Apakah Anda pernah aktif dalam organisasi atau lembaga sebelumnya?</p>
                        <div class="flex gap-4">
                            <button class="btn btn-primary flex-1" onclick="window.showPengurusStep(3)">Punya Pengalaman</button>
                            <button class="btn btn-secondary flex-1" onclick="window.submitPengurusInterest(true, false)">Tidak Punya</button>
                        </div>
                        <button class="btn mt-6" style="background:none; border:none; color:#94a3b8; font-size:0.75rem;" onclick="window.showPengurusStep(1)">Kembali</button>
                    </div>
                </div>

                <!-- Step 3: Detailed Experience -->
                <div id="pengurusStep3" style="display:none;">
                    <h4 class="font-bold text-dark mb-6">Detail Portfolio</h4>
                    <div class="form-group">
                        <label class="form-label">Nama Lembaga (Max 3 Organisasi)</label>
                        <input type="text" id="appOrgName" class="form-input" placeholder="Contoh: BEM Univ, Karang Taruna, dsb">
                    </div>
                    <div class="form-group mt-4">
                        <label class="form-label">Berapa total tahun aktif?</label>
                        <input type="number" id="appOrgCount" class="form-input" placeholder="Misal: 2">
                    </div>
                    <div class="form-group mt-4">
                        <label class="form-label">Alasan ingin bergabung?</label>
                        <textarea id="appReason" class="form-input" rows="3" style="resize:none;" placeholder="Berikan deskripsi singkat ketertarikan Anda..."></textarea>
                    </div>
                    <div class="flex gap-4 mt-8">
                        <button class="btn btn-secondary flex-1" onclick="window.showPengurusStep(2)">Kembali</button>
                        <button class="btn btn-primary flex-1" onclick="window.submitPengurusInterest(true, true)">Kirim Pendaftaran</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .tab-content { display: none; width: 100%; animation: fadeIn 0.3s ease; }
        .tab-content.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .border-top { border-top: 1px solid var(--border); }
        .px-8 { padding-left: 2rem; padding-right: 2rem; }
        .btn-pill:has(input:checked) {
            border-color: var(--primary) !important;
            background: rgba(0, 74, 173, 0.05);
            color: var(--primary);
        }
    </style>
    @endpush

    @push('scripts')
        @vite(['resources/js/pages/member.js'])
    @endpush
</x-member-layout>

