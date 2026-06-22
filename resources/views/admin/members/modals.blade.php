<!-- Modal Tambah Member -->
<div id="addModal" class="modal-overlay" style="display:none;">
    <div class="modal-content" style="max-width: 1000px;">
        <div class="modal-header">
            <h3 class="modal-title">Registrasi Basis Data Anggota</h3>
            <button onclick="document.getElementById('addModal').style.display='none'" class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <div class="grid-2" style="gap: 24px;">
                <!-- Kolom 1 -->
                <div class="flex-col" style="gap: 12px;">
                    <div class="grid-2" style="gap: 10px;">
                        <div class="form-group">
                            <label class="form-label">NIK (16 Digit)</label>
                            <input type="text" id="addNik" class="form-input" placeholder="16 digit">
                        </div>
                        <div class="form-group">
                            <label class="form-label">No. JKN</label>
                            <input type="text" id="addJknNumber" class="form-input" placeholder="13 digit">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" id="addName" class="form-input">
                    </div>
                    <div class="grid-2" style="gap:10px;">
                        <div class="form-group">
                            <label class="form-label">WhatsApp</label>
                            <input type="text" id="addPhone" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tgl Lahir</label>
                            <input type="date" id="addBirthDate" class="form-input">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kata Sandi</label>
                        <div class="input-group-password">
                            <input type="password" id="addPassword" class="form-input" placeholder="Min. 6 Karakter">
                        </div>
                    </div>
                </div>

                <!-- Kolom 2 -->
                <div class="flex-col" style="gap: 12px;">
                    <div class="grid-2" style="gap: 10px;">
                        <div class="form-group">
                            <label class="form-label">Gender</label>
                            <select id="addGender" class="form-input">
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Pendidikan</label>
                            <select id="addEducation" class="form-input">
                                <option value="SD">SD</option><option value="SMP">SMP</option><option value="SMA">SMA</option>
                                <option value="Diploma">Diploma</option><option value="S1/D4">S1/D4</option><option value="S2">S2</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pekerjaan</label>
                        <select id="addOccupation" class="form-input">
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
                            <option value="BURUH HARIAN LEPAS">BURUH HARIAN LEPAS</option>
                            <option value="LAINNYA">LAINNYA</option>
                        </select>
                    </div>
                    <div class="grid-3" style="gap: 10px;">
                        <div class="form-group"><label class="form-label">Prov</label><select id="addProvince" class="form-input" onchange="window.loadAddCities(this.value)"><option value="">Pilih...</option></select></div>
                        <div class="form-group"><label class="form-label">Kota</label><select id="addCity" class="form-input" onchange="window.loadAddDistricts(this.value)"><option value="">Pilih...</option></select></div>
                        <div class="form-group"><label class="form-label">Kec</label><select id="addDistrict" class="form-input"><option value="">Pilih...</option></select></div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Alamat</label>
                        <textarea id="addAddress" class="form-input" rows="1" style="resize: none;"></textarea>
                    </div>
                </div>
            </div>
        </div> <!-- Close modal-body -->
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="document.getElementById('addModal').style.display='none'">Batal</button>
            <button class="btn btn-primary" id="btnSubmitAdd" onclick="window.submitAdd()">Simpan Anggota</button>
        </div>
    </div>
</div>

<!-- Modal Edit Member -->
<div id="editModal" class="modal-overlay" style="display:none;">
    <div class="modal-content" style="max-width: 1000px;">
        <div class="modal-header">
            <h3 class="modal-title">Edit Data Anggota</h3>
            <button onclick="document.getElementById('editModal').style.display='none'" class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">NIK (16 Digit)</label>
                    <input type="text" id="editNik" class="form-input" readonly title="NIK tidak dapat diubah">
                </div>
                <div class="form-group">
                    <label class="form-label">Nomor Kartu JKN</label>
                    <input type="text" id="editJknNumber" class="form-input" placeholder="Opsional">
                </div>
            </div>
            <div class="grid-3" style="grid-template-columns: 2fr 1fr 1fr;">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" id="editName" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">WhatsApp</label>
                    <input type="text" id="editPhone" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Jenis Kelamin</label>
                    <select id="editGender" class="form-input">
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
            </div>
            <div class="grid-3" style="grid-template-columns: 1fr 1fr 1.5fr;">
                <div class="form-group">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" id="editBirthDate" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Pendidikan</label>
                    <select id="editEducation" class="form-input">
                        <option value="SD">SD</option>
                        <option value="SMP">SMP</option>
                        <option value="SMA">SMA</option>
                        <option value="Diploma">Diploma</option>
                        <option value="S1/D4">S1/D4</option>
                        <option value="S2">S2</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Jenis Pekerjaan</label>
                    <select id="editOccupation" class="form-input">
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
            </div>
            <div class="grid-3">
                <div class="form-group">
                    <label class="form-label">Provinsi</label>
                    <select id="editProvince" class="form-input" onchange="window.loadEditCities(this.value)">
                        <option value="">Pilih...</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Kab/Kota</label>
                    <select id="editCity" class="form-input" onchange="window.loadEditDistricts(this.value)">
                        <option value="">Pilih...</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Kecamatan</label>
                    <select id="editDistrict" class="form-input">
                        <option value="">Pilih...</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Alamat Lengkap</label>
                <textarea id="editAddress" class="form-input" rows="1" style="resize: none;"></textarea>
            </div>

            <!-- Approval Section (Dynamic) -->
            <div id="approvalSection" style="display: none; background: #fff7ed; padding: 16px; border: 1px solid #ffedd5; border-radius: 12px; margin-top: 16px;">
                <div style="display: flex; align-items: start; gap: 12px; margin-bottom: 12px;">
                    <div style="background: #fb923c; padding: 8px; border-radius: 10px; color: #fff;"><i data-lucide="user-check"></i></div>
                    <div>
                        <div style="font-weight: 800; color: #7c2d12; font-size: 0.9rem;">Persetujuan Pendaftaran Pengurus</div>
                        <p style="font-size: 0.75rem; color: #9a3412;">Anggota ini berminat menjadi pengurus lapangan (PIL/BPJS Keliling).</p>
                    </div>
                </div>
                <div id="approvalInfo" style="font-size: 0.75rem; color: #431407; margin-bottom: 12px; background: #fff; padding: 10px; border-radius: 8px; border: 1px dashed #fdba74;">
                    <!-- Details from JS -->
                </div>
                <div class="flex gap-2">
                    <button class="btn btn-primary" onclick="window.processApproval('setujui')" style="background: #10b981; flex: 1;">Setujui</button>
                    <button class="btn btn-secondary" onclick="window.processApproval('tolak')" style="color: #ef4444; border-color: #fca5a5; flex: 1;">Tolak</button>
                </div>
            </div>
        </div>
        <div class="modal-footer" style="justify-content: space-between;">
             <button class="btn" onclick="window.resetPassword()" style="color: var(--danger); background: none; border: 1px solid #fee2e2; padding: 6px 12px; border-radius: 8px;">Reset Kata Sandi</button>
             <div style="display:flex; gap:12px;">
                <button class="btn btn-secondary" onclick="window.closeEditModal()">Batal</button>
                <button class="btn btn-primary" id="btnSubmitEdit" onclick="window.submitEdit()">Simpan</button>
             </div>
        </div>
    </div>
</div>

<!-- Delete Options Modal -->
<div id="deleteOptionsModal" class="modal-overlay" style="display:none; backdrop-filter: blur(8px); background: rgba(15, 23, 42, 0.6); z-index: 9999;">
    <div class="modal-content" style="max-width: 500px; padding: 32px; border-radius: 24px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
        <div class="modal-header" style="border-bottom: none; padding-bottom: 0;">
            <h3 class="modal-title" style="font-weight: 800; font-size: 1.5rem; color: #0f172a;">Opsi Hapus Anggota</h3>
            <button class="modal-close" onclick="window.closeDeleteOptionsModal()" style="background: #f1f5f9; border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; color: #64748b; border: none; cursor: pointer;">&times;</button>
        </div>
        <div class="modal-body">
            <p style="color: #475569; margin-bottom: 24px; line-height: 1.5;">Pilih metode penghapusan yang Anda inginkan untuk data anggota ini.</p>
            
            <div style="display: flex; flex-direction: column; gap: 16px;">
                <!-- Arsip Sementara -->
                <div style="border: 2px solid #cbd5e1; border-radius: 16px; padding: 16px; cursor: pointer; transition: all 0.2s;" onclick="window.executeDeleteOption('arsip')" onmouseover="this.style.borderColor='#3b82f6'" onmouseout="this.style.borderColor='#cbd5e1'">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                        <i data-lucide="archive" style="width: 24px; height: 24px; color: #3b82f6;"></i>
                        <h4 style="font-weight: 700; color: #0f172a; margin: 0; font-size: 1.1rem;">Arsip Sementara</h4>
                    </div>
                    <p style="font-size: 0.85rem; color: #64748b; margin: 0; line-height: 1.5;">
                        Data dipindahkan ke kotak sampah dan dapat dipulihkan sewaktu-waktu. 
                        <br><span style="color: #ea580c; font-weight: 600;">Risiko:</span> NIK tetap terdaftar di sistem. Jika anggota ingin daftar ulang, data ini harus dihapus permanen terlebih dahulu.
                    </p>
                </div>

                <!-- Hapus Permanen -->
                <div style="border: 2px solid #cbd5e1; border-radius: 16px; padding: 16px; cursor: pointer; transition: all 0.2s;" onclick="window.executeDeleteOption('permanen')" onmouseover="this.style.borderColor='#ef4444'" onmouseout="this.style.borderColor='#cbd5e1'">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                        <i data-lucide="trash-2" style="width: 24px; height: 24px; color: #ef4444;"></i>
                        <h4 style="font-weight: 700; color: #0f172a; margin: 0; font-size: 1.1rem;">Hapus Permanen</h4>
                    </div>
                    <p style="font-size: 0.85rem; color: #64748b; margin: 0; line-height: 1.5;">
                        Data akan dihapus secara fisik dari sistem.
                        <br><span style="color: #ef4444; font-weight: 600;">Risiko:</span> Data <strong>tidak dapat dipulihkan</strong> kembali. NIK tersebut menjadi bebas dan bisa langsung dipakai daftar ulang.
                    </p>
                </div>
            </div>
            <div style="margin-top: 24px;">
                <button class="btn btn-secondary" onclick="window.closeDeleteOptionsModal()" style="width: 100%; border-radius: 12px; padding: 12px; background: #f8fafc; color: #64748b; border-color: #e2e8f0;">Batalkan Penghapusan</button>
            </div>
        </div>
    </div>
</div>
