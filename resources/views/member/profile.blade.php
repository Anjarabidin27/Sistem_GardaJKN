<x-member-layout title="Profil Saya - Garda JKN">
    <div id="section-profil" class="tab-content active">
        <!-- Unified Profile Card -->
        <div class="table-card" style="border: none; background: white; padding: 0; border-radius: 28px; margin-bottom: 32px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 20px 25px -5px rgba(0,0,0,0.1); overflow: hidden;">
            <!-- Header: Blue Strip -->
            <div class="profile-header-strip" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%); color: white; padding: 40px; position: relative;">
                <div style="position: absolute; right: -40px; top: -40px; width: 220px; height: 220px; background: rgba(255,255,255,0.08); border-radius: 50%;"></div>
                
                <div class="flex items-center gap-8 profile-header-container" style="position: relative; z-index: 2;">
                    <div id="avatarContainer" class="overflow-hidden" style="width: 100px; height: 100px; background: rgba(255,255,255,0.15); border: 3px solid rgba(255,255,255,0.3); border-radius: 22px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 8px 16px rgba(0,0,0,0.1); transition: all 0.3s ease;">
                        <i data-lucide="user" style="width: 48px; height: 48px; opacity: 0.8; transition: all 0.3s ease;"></i>
                    </div>
                    <div class="profile-header-text" style="flex: 1;">
                        <h1 id="nameDisplay" style="color: white; font-size: 1.85rem; font-weight: 800; margin: 0; letter-spacing: -0.01em;">Memuat...</h1>
                        <div class="flex gap-6 mt-2 profile-info-row" style="font-size: 0.95rem; opacity: 0.85; font-weight: 500;">
                            <span>NIK: <strong id="nikDisplay" style="font-weight: 700;">—</strong></span>
                            <span class="info-separator" style="opacity: 0.4;">|</span>
                            <span>No. JKN: <strong id="jknDisplay" style="font-weight: 700;">—</strong></span>
                        </div>
                    </div>
                    <button class="btn btn-edit-profile" onclick="window.openEditModal()" style="background: rgba(255,255,255,0.12); color: white; border: 1.5px solid rgba(255,255,255,0.25); border-radius: 14px; padding: 12px 24px; font-size: 0.875rem; font-weight: 700; backdrop-filter: blur(8px); transition: all 0.3s ease;">
                        <i data-lucide="edit-3" style="width: 16px; height: 16px; margin-right: 8px;"></i> <span>Edit Profil</span>
                    </button>
                </div>
            </div>

            <!-- Body: Data Grid -->
            <div class="profile-body-grid" style="padding: 40px; display: grid; grid-template-columns: repeat(3, 1fr); gap: 40px; background: white;">
                <div>
                    <div style="font-size: 0.75rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 24px; display: flex; align-items: center; gap: 8px; letter-spacing: 0.05em;">
                        <i data-lucide="phone" style="width: 14px; opacity: 0.7;"></i> Kontak
                    </div>
                    <div style="margin-bottom: 20px;"><div class="data-label" style="font-size: 0.7rem; font-weight: 700; color: #cbd5e1; text-transform: uppercase; margin-bottom: 4px;">No. WhatsApp</div><div class="data-value" id="phoneDisplay" style="font-size: 1.15rem; font-weight: 800; color: #1e293b; word-break: break-all;">—</div></div>
                    <div class="mobile-grid-2">
                        <div style="margin-bottom: 20px;"><div class="data-label" style="font-size: 0.7rem; font-weight: 700; color: #cbd5e1; text-transform: uppercase; margin-bottom: 4px;">Tanggal Lahir</div><div class="data-value" id="birthDateDisplay" style="font-size: 1.15rem; font-weight: 800; color: #1e293b;">—</div></div>
                        <div><div class="data-label" style="font-size: 0.7rem; font-weight: 700; color: #cbd5e1; text-transform: uppercase; margin-bottom: 4px;">Jenis Kelamin</div><div class="data-value" id="genderDisplay" style="font-size: 1.15rem; font-weight: 800; color: #1e293b;">—</div></div>
                    </div>
                </div>
                <div>
                    <div style="font-size: 0.75rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 24px; display: flex; align-items: center; gap: 8px; letter-spacing: 0.05em;">
                        <i data-lucide="briefcase" style="width: 14px; opacity: 0.7;"></i> Pekerjaan
                    </div>
                    <div style="margin-bottom: 24px;"><div class="data-label" style="font-size: 0.7rem; font-weight: 700; color: #cbd5e1; text-transform: uppercase; margin-bottom: 4px;">Jenis Pekerjaan</div><div class="data-value" id="occupationDisplay" style="font-size: 1.15rem; font-weight: 800; color: #1e293b; word-break: break-word;">—</div></div>
                    <div><div class="data-label" style="font-size: 0.7rem; font-weight: 700; color: #cbd5e1; text-transform: uppercase; margin-bottom: 4px;">Pendidikan Terakhir</div><div class="data-value" id="educationDisplay" style="font-size: 1.15rem; font-weight: 800; color: #1e293b; word-break: break-word;">—</div></div>
                </div>
                <div>
                    <div style="font-size: 0.75rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 24px; display: flex; align-items: center; gap: 8px; letter-spacing: 0.05em;">
                        <i data-lucide="map-pin" style="width: 14px; opacity: 0.7;"></i> Domisili
                    </div>
                    <div style="margin-bottom: 24px;"><div class="data-label" style="font-size: 0.7rem; font-weight: 700; color: #cbd5e1; text-transform: uppercase; margin-bottom: 4px;">Wilayah</div><div class="data-value" id="regionDisplay" style="font-size: 1.15rem; font-weight: 800; color: #1e293b; line-height: 1.3;">—</div></div>
                    <div><div class="data-label" style="font-size: 0.7rem; font-weight: 700; color: #cbd5e1; text-transform: uppercase; margin-bottom: 4px;">Alamat Lengkap</div><div class="data-value" id="addressDetail" style="font-size: 1.15rem; font-weight: 800; color: #1e293b; line-height: 1.4; word-break: break-word;">—</div></div>
                </div>
            </div>
        </div>

        <!-- Pengurus Banner -->
        <div id="pengurus-section" class="pengurus-banner" style="display:none; margin-bottom: 32px; border: 1px solid rgba(0, 74, 173, 0.1); background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%); padding: 24px 32px; border-radius: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02), 0 2px 4px -1px rgba(0,0,0,0.02); border-left: 5px solid var(--primary);">
            <div class="flex justify-between items-center gap-6 pengurus-flex">
                <div class="pengurus-info" style="display: flex; align-items: center; gap: 20px;">
                    <div class="pengurus-icon" style="width: 52px; height: 52px; background: rgba(0, 74, 173, 0.08); border-radius: 16px; display: flex; align-items: center; justify-content: center; color: var(--primary); flex-shrink: 0;">
                        <i data-lucide="award" style="width: 26px; height: 26px;"></i>
                    </div>
                    <div class="pengurus-text">
                        <div class="pengurus-title" style="font-weight: 800; font-size: 1.1rem; color: #1e293b; letter-spacing: -0.01em;">Ingin jadi Pengurus Garda JKN?</div>
                        <div class="pengurus-desc" style="font-size: 0.875rem; color: #64748b; margin-top: 4px; line-height: 1.5;">Berkontribusi lebih bagi anggota dengan menjadi bagian dari kepengurusan formal kami.</div>
                    </div>
                </div>
                <button class="btn btn-primary btn-daftar" onclick="window.openPengurusModal()" style="padding: 12px 32px; font-size: 0.9rem; border-radius: 14px; box-shadow: 0 4px 12px rgba(0, 74, 173, 0.2); flex-shrink: 0;">Daftar Sekarang</button>
            </div>
        </div>

        <div id="pengurus-status-section" style="display:none; background: white; border-radius: 16px; border: 1px solid #e5eaf2; padding: 24px; margin-bottom: 32px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);" class="table-card pengurus-status-card">
            <div class="flex justify-between items-center pengurus-status-flex">
                <div>
                    <div style="font-size: 0.7rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.05em;">PERAN ORGANISASI</div>
                    <div id="memberRoleDisplay" style="font-size: 1.35rem; font-weight: 800; color: #1e293b;">—</div>
                    <p id="statusHelpText" style="font-size: 0.85rem; color: #64748b; margin-top: 4px; line-height: 1.4;">Memuat informasi peran...</p>
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
        <div class="table-card" style="margin: 0; padding: 0; border-radius: 20px; overflow: hidden; border: 1px solid #e5eaf2; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
            <div style="background: #f8fafc; padding: 24px; border-bottom: 1px solid #e5eaf2; text-align: center;">
                <i data-lucide="clipboard-check" style="width: 32px; height: 32px; color: var(--primary); margin-bottom: 12px;"></i>
                <h3 style="font-size: 1.2rem; font-weight: 800; color: #1e293b; margin: 0;">Survey Kepuasan</h3>
                <p style="color: #64748b; font-size: 0.8rem; margin-top: 4px;">Bantu kami meningkatkan layanan Garda JKN.</p>
            </div>
            <form id="surveyForm" style="padding: 24px;">
                <div class="form-group" style="margin-bottom: 24px;">
                    <label class="form-label" style="font-weight: 700; color: #475569; margin-bottom: 12px; display: block; font-size: 0.9rem;">1. Bagaimana penilaian Anda terhadap kecepatan respon pengurus?</label>
                    <div class="grid" style="grid-template-columns: repeat(2, 1fr); gap: 8px; display: grid;">
                         <label class="btn-pill" style="cursor:pointer; padding: 10px; border: 1.5px solid #e2e8f0; border-radius: 10px; text-align: center; font-weight: 700; font-size: 0.8rem;"><input type="radio" name="q1" value="5" style="opacity:0; position:absolute; z-index:-1;" required> Sangat Puas</label>
                         <label class="btn-pill" style="cursor:pointer; padding: 10px; border: 1.5px solid #e2e8f0; border-radius: 10px; text-align: center; font-weight: 700; font-size: 0.8rem;"><input type="radio" name="q1" value="4" style="opacity:0; position:absolute; z-index:-1;"> Puas</label>
                         <label class="btn-pill" style="cursor:pointer; padding: 10px; border: 1.5px solid #e2e8f0; border-radius: 10px; text-align: center; font-weight: 700; font-size: 0.8rem;"><input type="radio" name="q1" value="3" style="opacity:0; position:absolute; z-index:-1;"> Cukup</label>
                         <label class="btn-pill" style="cursor:pointer; padding: 10px; border: 1.5px solid #e2e8f0; border-radius: 10px; text-align: center; font-weight: 700; font-size: 0.8rem;"><input type="radio" name="q1" value="2" style="opacity:0; position:absolute; z-index:-1;"> Buruk</label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-weight: 700; color: #475569; font-size: 0.9rem;">2. Apa saran perbaikan utama Anda?</label>
                    <textarea class="form-input" rows="2" style="resize: none; padding: 12px; border-radius: 10px; margin-top: 8px; font-size: 0.85rem;" placeholder="Tuliskan saran singkat anda..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-full mt-6" style="padding: 12px; border-radius: 12px; font-weight: 800; font-size: 0.9rem;">Kirim Survey</button>
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
    <div id="pengurusModal" class="modal-overlay" style="display:none; backdrop-filter: blur(8px); background: rgba(15, 23, 42, 0.6);">
        <div class="modal-content" style="max-width: 480px; padding: 40px 32px; border-radius: 24px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
            <button class="modal-close" onclick="window.closePengurusModal()" style="position: absolute; top: 20px; right: 24px; background: #f1f5f9; border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; color: #64748b; transition: all 0.3s ease; border: none; cursor: pointer;">&times;</button>
            <div class="modal-body" style="padding: 0;">
                <!-- Step 1: Interest Inquiry -->
                <div id="pengurusStep1" style="animation: slideUp 0.4s ease-out;">
                    <div class="text-center">
                        <div style="width: 96px; height: 96px; background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; box-shadow: inset 0 4px 6px rgba(255,255,255,0.5), 0 10px 15px -3px rgba(37, 99, 235, 0.1);">
                            <i data-lucide="award" style="width: 48px; height: 48px; color: #2563eb;"></i>
                        </div>
                        <h3 style="font-weight: 800; font-size: 1.5rem; color: #0f172a; margin-bottom: 12px; letter-spacing: -0.02em;">Pendaftaran Pengurus</h3>
                        <p style="color: #475569; margin-bottom: 32px; line-height: 1.6; font-size: 0.95rem;">Sebagai pengurus, Anda akan memiliki peran aktif dan eksklusif dalam mengkoordinasikan program Garda JKN di wilayah Anda.</p>
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            <button class="btn btn-primary" onclick="window.showPengurusStep(2)" style="width: 100%; padding: 14px; font-size: 1rem; border-radius: 14px; box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3);">Ya, Saya Tertarik Bergabung</button>
                            <button class="btn btn-secondary" onclick="window.submitPengurusInterest(false)" style="width: 100%; padding: 14px; font-size: 1rem; border-radius: 14px; background: #f8fafc; border-color: #e2e8f0; color: #64748b;">Mungkin Nanti Saja</button>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Experience Inquiry -->
                <div id="pengurusStep2" style="display:none; animation: slideUp 0.4s ease-out;">
                    <div class="text-center">
                        <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
                            <i data-lucide="briefcase" style="width: 36px; height: 36px; color: #64748b;"></i>
                        </div>
                        <h3 style="font-weight: 800; font-size: 1.35rem; color: #0f172a; margin-bottom: 12px; letter-spacing: -0.02em;">Pengalaman Organisasi</h3>
                        <p style="color: #475569; margin-bottom: 32px; line-height: 1.6; font-size: 0.95rem;">Apakah Anda memiliki riwayat aktif dalam organisasi, komunitas, atau lembaga sebelumnya?</p>
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            <button class="btn btn-primary" onclick="window.showPengurusStep(3)" style="width: 100%; padding: 14px; font-size: 1rem; border-radius: 14px;">Ya, Saya Punya Pengalaman</button>
                            <button class="btn btn-secondary" onclick="window.submitPengurusInterest(true, false)" style="width: 100%; padding: 14px; font-size: 1rem; border-radius: 14px; background: #f8fafc; border-color: #e2e8f0; color: #64748b;">Belum Ada Pengalaman</button>
                        </div>
                        <button style="margin-top: 24px; background: none; border: none; color: #94a3b8; font-weight: 600; font-size: 0.85rem; cursor: pointer; transition: color 0.2s;" onclick="window.showPengurusStep(1)" onmouseover="this.style.color='#64748b'" onmouseout="this.style.color='#94a3b8'">← Kembali ke awal</button>
                    </div>
                </div>

                <!-- Step 3: Detailed Experience -->
                <div id="pengurusStep3" style="display:none; animation: slideUp 0.4s ease-out;">
                    <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px;">
                        <button style="background: #f1f5f9; border: none; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #64748b; flex-shrink: 0;" onclick="window.showPengurusStep(2)"><i data-lucide="arrow-left" style="width: 18px; height: 18px;"></i></button>
                        <h3 style="font-weight: 800; font-size: 1.25rem; color: #0f172a; margin: 0;">Detail Pengalaman</h3>
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label class="form-label" style="color: #475569; font-weight: 600;">Nama Organisasi / Lembaga</label>
                        <input type="text" id="appOrgName" class="form-input" placeholder="Contoh: Karang Taruna, dsb" style="padding: 14px; border-radius: 12px; border: 1px solid #cbd5e1; background: #f8fafc;">
                    </div>
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label class="form-label" style="color: #475569; font-weight: 600;">Lama Aktif (Tahun)</label>
                        <input type="number" id="appOrgCount" class="form-input" placeholder="Berapa lama Anda aktif?" style="padding: 14px; border-radius: 12px; border: 1px solid #cbd5e1; background: #f8fafc;">
                    </div>
                    <div class="form-group" style="margin-bottom: 32px;">
                        <label class="form-label" style="color: #475569; font-weight: 600;">Motivasi Bergabung</label>
                        <textarea id="appReason" class="form-input" rows="3" style="resize:none; padding: 14px; border-radius: 12px; border: 1px solid #cbd5e1; background: #f8fafc;" placeholder="Ceritakan motivasi Anda secara singkat..."></textarea>
                    </div>
                    
                    <button class="btn btn-primary" onclick="window.submitPengurusInterest(true, true)" style="width: 100%; padding: 14px; font-size: 1rem; border-radius: 14px; box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3);">Kirim Pendaftaran Pengurus</button>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .tab-content { display: none; width: 100%; animation: fadeIn 0.4s ease-out; }
        .tab-content.active { display: block; }
        
        @keyframes fadeIn { 
            from { opacity: 0; transform: translateY(16px); } 
            to { opacity: 1; transform: translateY(0); } 
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(32px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        @keyframes pulse {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(245, 158, 11, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
        }

        .border-top { border-top: 1px solid var(--border); }
        .px-8 { padding-left: 2rem; padding-right: 2rem; }
        .btn-pill:has(input:checked) {
            border-color: var(--primary) !important;
            background: rgba(0, 74, 173, 0.05);
            color: var(--primary);
        }

        /* Mobile Responsiveness Premium */
        @media (max-width: 768px) {
            /* Header ID Card */
            .profile-header-container {
                flex-direction: row !important;
                align-items: center !important;
                text-align: left !important;
                gap: 16px !important;
            }
            #avatarContainer {
                width: 64px !important;
                height: 64px !important;
                border-radius: 16px !important;
            }
            #avatarContainer i {
                width: 32px !important;
                height: 32px !important;
            }
            .profile-header-text h1 {
                font-size: 1.25rem !important;
                margin-bottom: 2px !important;
            }
            .profile-info-row {
                flex-direction: column !important;
                gap: 2px !important;
                font-size: 0.8rem !important;
            }
            .profile-info-row .info-separator {
                display: none !important;
            }
            
            /* Tombol Edit Melayang */
            .btn-edit-profile {
                position: absolute !important;
                top: 0px !important;
                right: 0px !important;
                width: 40px !important;
                height: 40px !important;
                padding: 0 !important;
                border-radius: 50% !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
            }
            .btn-edit-profile span {
                display: none !important;
            }
            .btn-edit-profile i {
                margin: 0 !important;
            }
            .profile-header-strip {
                padding: 24px 20px !important;
            }

            /* Body Grid */
            .profile-body-grid {
                grid-template-columns: 1fr !important;
                gap: 24px !important;
                padding: 24px 20px !important;
            }
            .mobile-grid-2 {
                display: grid !important;
                grid-template-columns: 1fr 1fr !important;
                gap: 16px !important;
            }
            .data-label {
                font-size: 0.65rem !important;
            }
            .data-value {
                font-size: 0.95rem !important;
            }

            /* Pengurus Banner */
            .pengurus-banner {
                padding: 16px !important;
                border-radius: 16px !important;
            }
            .pengurus-flex {
                flex-direction: row !important;
                text-align: left !important;
                gap: 16px !important;
                flex-wrap: wrap !important;
            }
            .pengurus-info {
                flex-direction: row !important;
                gap: 12px !important;
                width: 100% !important;
            }
            .pengurus-icon {
                width: 44px !important;
                height: 44px !important;
                border-radius: 12px !important;
            }
            .pengurus-icon i {
                width: 22px !important;
                height: 22px !important;
            }
            .pengurus-title {
                font-size: 1rem !important;
            }
            .pengurus-desc {
                font-size: 0.8rem !important;
                margin-top: 2px !important;
            }
            .btn-daftar {
                width: 100% !important;
                padding: 12px !important;
            }
            .pengurus-status-flex {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 16px;
            }
        }
    </style>
    @endpush

    @push('scripts')
        @vite(['resources/js/pages/member.js'])
    @endpush
</x-member-layout>

