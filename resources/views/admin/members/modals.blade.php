<!-- Modal Tambah Member -->
<div id="addModal" class="modal-overlay">
    <div class="modal-content">
        <div style="padding:20px 32px; border-bottom:1px solid #e2e8f0; background:#fff; display:flex; justify-content:space-between; align-items:center;">
            <h3 style="font-size:1rem; font-weight:700; color: #1e293b;">Registrasi Basis Data Anggota</h3>
            <button onclick="closeAddModal()" style="background:none; border:none; color:#94a3b8; cursor:pointer; font-size:1.5rem;">&times;</button>
        </div>
        <div style="padding:32px; max-height: 75vh; overflow-y: auto;">
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px;">
                <div>
                    <label class="label" style="font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 6px; display: block;">NIK (16 Digit)</label>
                    <input type="text" id="addNik" class="form-input" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.875rem;" placeholder="Wajib 16 digit sesuai KTP">
                </div>
                <div>
                    <label class="label" style="font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 6px; display: block;">Nama Lengkap</label>
                    <input type="text" id="addName" class="form-input" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.875rem;">
                </div>
            </div>
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px;">
                <div>
                    <label class="label" style="font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 6px; display: block;">WhatsApp</label>
                    <input type="text" id="addPhone" class="form-input" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.875rem;" placeholder="0812...">
                </div>
                <div>
                    <label class="label" style="font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 6px; display: block;">Kata Sandi</label>
                    <input type="password" id="addPassword" class="form-input" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.875rem;" placeholder="Min. 6 Karakter">
                </div>
            </div>
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px;">
                <div>
                    <label class="label" style="font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 6px; display: block;">Jenis Kelamin</label>
                    <select id="addGender" class="form-input" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.875rem;">
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="label" style="font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 6px; display: block;">Pendidikan Terakhir</label>
                    <select id="addEducation" class="form-input" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.875rem;">
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
                <select id="addOccupation" class="form-input" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.875rem;">
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
            <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:16px; margin-bottom:20px;">
                <div>
                    <label class="label" style="font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 6px; display: block;">Provinsi</label>
                    <select id="addProvince" class="form-input" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.875rem;" onchange="loadAddCities(this.value)">
                        <option value="">Pilih...</option>
                    </select>
                </div>
                <div>
                    <label class="label" style="font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 6px; display: block;">Kab/Kota</label>
                    <select id="addCity" class="form-input" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.875rem;" onchange="loadAddDistricts(this.value)">
                        <option value="">Pilih...</option>
                    </select>
                </div>
                <div>
                    <label class="label" style="font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 6px; display: block;">Kecamatan</label>
                    <select id="addDistrict" class="form-input" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.875rem;">
                        <option value="">Pilih...</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="label" style="font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 6px; display: block;">Alamat Lengkap</label>
                <textarea id="addAddress" class="form-input" rows="2" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.875rem; resize: none;"></textarea>
            </div>
        </div>
        <div style="padding:20px 32px; background:#f8fafc; border-top:1px solid #e2e8f0; display:flex; justify-content:flex-end; gap:12px;">
            <button class="btn btn-secondary" onclick="closeAddModal()" style="padding: 8px 16px; border-radius: 6px; border: 1px solid #e2e8f0; background: white; color: #64748b; font-weight: 500;">Batal</button>
            <button class="btn btn-primary" onclick="submitAdd()" style="padding: 8px 16px; border-radius: 6px; border: none; background: #004aad; color: white; font-weight: 500;">Simpan Anggota</button>
        </div>
    </div>
</div>

<!-- Modal Edit Member -->
<div id="editModal" class="modal-overlay">
    <div class="modal-content">
        <div style="padding:20px 32px; border-bottom:1px solid #e2e8f0; background:#fff; display:flex; justify-content:space-between; align-items:center;">
            <h3 style="font-size:1rem; font-weight:700; color: #1e293b;">Pemutakhiran Data Anggota</h3>
            <button onclick="closeEditModal()" style="background:none; border:none; color:#94a3b8; cursor:pointer; font-size:1.5rem;">&times;</button>
        </div>
        <div style="padding:32px; max-height: 75vh; overflow-y: auto;">
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px;">
                <div>
                    <label class="label" style="font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 6px; display: block;">NIK (16 Digit)</label>
                    <input type="text" id="editNik" class="form-input" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.875rem;" readonly title="NIK tidak dapat diubah">
                </div>
                <div>
                    <label class="label" style="font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 6px; display: block;">Nama Lengkap</label>
                    <input type="text" id="editName" class="form-input" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.875rem;">
                </div>
            </div>
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px;">
                <div>
                    <label class="label" style="font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 6px; display: block;">WhatsApp</label>
                    <input type="text" id="editPhone" class="form-input" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.875rem;">
                </div>
                <div>
                    <label class="label" style="font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 6px; display: block;">Jenis Kelamin</label>
                    <select id="editGender" class="form-input" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.875rem;">
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
            </div>
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px;">
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
                <div>
                    <label class="label" style="font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 6px; display: block;">Pekerjaan</label>
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
            </div>
            <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:16px; margin-bottom:20px;">
                <div>
                    <label class="label" style="font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 6px; display: block;">Provinsi</label>
                    <select id="editProvince" class="form-input" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.875rem;" onchange="loadEditCities(this.value)">
                        <option value="">Pilih...</option>
                    </select>
                </div>
                <div>
                    <label class="label" style="font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 6px; display: block;">Kab/Kota</label>
                    <select id="editCity" class="form-input" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.875rem;" onchange="loadEditDistricts(this.value)">
                        <option value="">Pilih...</option>
                    </select>
                </div>
                <div>
                    <label class="label" style="font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 6px; display: block;">Kecamatan</label>
                    <select id="editDistrict" class="form-input" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.875rem;">
                        <option value="">Pilih...</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="label" style="font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 6px; display: block;">Alamat Lengkap</label>
                <textarea id="editAddress" class="form-input" rows="2" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.875rem; resize: none;"></textarea>
            </div>
        </div>
        <div style="padding:20px 32px; background:#f8fafc; border-top:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center;">
             <button class="btn" onclick="resetPassword()" style="color: #ef4444; background: none; border: 1px solid #fee2e2; font-size: 0.75rem; font-weight: 600; padding: 6px 12px; border-radius: 6px;">Reset Kata Sandi</button>
             <div style="display:flex; gap:12px;">
                <button class="btn btn-secondary" onclick="closeEditModal()" style="padding: 8px 16px; border-radius: 6px; border: 1px solid #e2e8f0; background: white; color: #64748b; font-weight: 500;">Batal</button>
                <button class="btn btn-primary" onclick="submitEdit()" style="padding: 8px 16px; border-radius: 6px; border: none; background: #004aad; color: white; font-weight: 500;">Simpan</button>
             </div>
        </div>
    </div>
</div>
