@extends('layouts.app')

@section('title', 'Daftar Anggota Baru - Garda JKN')

@section('content')
<style>
    .page-wrapper { min-height: 100vh; background: #f1f5f9; padding: 60px 20px; display: flex; align-items: center; justify-content: center; }
    .auth-card { background: white; width: 100%; max-width: 640px; border-radius: 20px; border: none; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.1); overflow: hidden; }
    
    .auth-header { 
        padding: 40px; 
        background: linear-gradient(135deg, #004aad 0%, #002d6a 100%); 
        color: white; 
        text-align: center;
        position: relative;
    }
    .auth-header::after {
        content: '';
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 40px;
        background: white;
        clip-path: ellipse(50% 100% at 50% 100%);
    }
    
    .auth-header h2 { font-size: 1.8rem; font-weight: 800; margin-bottom: 8px; letter-spacing: -0.025em; }
    .auth-header p { opacity: 0.8; font-size: 0.95rem; font-weight: 500; }
    
    .auth-body { padding: 40px; }
    
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px; }
    .form-group { margin-bottom: 20px; }
    .label { display: block; font-size: 0.75rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px; }

    @media (max-width: 640px) {
        .page-wrapper { padding: 0; background: white; }
        .auth-card { border-radius: 0; box-shadow: none; }
        .auth-body { padding: 32px 24px; }
        .form-grid { grid-template-columns: 1fr; }
        .auth-header { padding: 40px 24px; }
    }
</style>

<div class="page-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <h2>Registrasi Anggota</h2>
            <p>Database Keanggotaan Nasional Garda JKN</p>
        </div>

        <div class="auth-body">
            <form id="registerForm">
                <div class="form-grid">
                    <div>
                        <label class="label">NIK (16 Digit)</label>
                        <input type="text" id="nik" class="form-input" placeholder="Sesuai KTP" required style="border-radius: 10px;">
                    </div>
                    <div>
                        <label class="label">Nomor Kartu JKN</label>
                        <input type="text" id="jkn_number" class="form-input" placeholder="Opsional" style="border-radius: 10px;">
                    </div>
                </div>

                <div class="form-group">
                    <label class="label">Nama Lengkap</label>
                    <input type="text" id="name" class="form-input" placeholder="Nama lengkap sesuai KTP" required style="border-radius: 10px;">
                </div>

                <div class="form-grid">
                    <div>
                        <label class="label">WhatsApp</label>
                        <input type="text" id="phone" class="form-input" placeholder="0812..." required style="border-radius: 10px;">
                    </div>
                    <div>
                        <label class="label">Tanggal Lahir</label>
                        <input type="date" id="birth_date" class="form-input" required style="border-radius: 10px;">
                    </div>
                </div>

                <div class="form-grid">
                    <div>
                        <label class="label">Jenis Kelamin</label>
                        <select id="gender" class="form-input" required style="border-radius: 10px;">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="label">Pendidikan</label>
                        <select id="education" class="form-input" required style="border-radius: 10px;">
                            <option value="SD">SD</option>
                            <option value="SMP">SMP</option>
                            <option value="SMA">SMA</option>
                            <option value="Diploma">Diploma</option>
                            <option value="S1/D4">S1/D4</option>
                            <option value="S2">S2</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="label">Jenis Pekerjaan</label>
                    <select id="occupation" class="form-input" required style="border-radius: 10px;">
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
                        <option value="TENAGA MEDIS">TENAGA MEDIS</option>
                        <option value="LAINNYA">LAINNYA</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="label">Kata Sandi</label>
                    <input type="password" id="password" class="form-input" placeholder="Minimal 6 karakter" required style="border-radius: 10px;">
                </div>

                <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:12px; margin-bottom:20px;">
                    <div>
                        <label class="label">Provinsi</label>
                        <select id="province" class="form-input" onchange="loadCities(this.value)" required style="border-radius: 10px;">
                            <option value="">Pilih...</option>
                        </select>
                    </div>
                    <div>
                        <label class="label">Kota</label>
                        <select id="city" class="form-input" onchange="loadDistricts(this.value)" required style="border-radius: 10px;">
                            <option value="">Pilih...</option>
                        </select>
                    </div>
                    <div>
                        <label class="label">Kecamatan</label>
                        <select id="district" class="form-input" required style="border-radius: 10px;">
                            <option value="">Pilih...</option>
                        </select>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 32px;">
                    <label class="label">Alamat Lengkap (Domisili)</label>
                    <textarea id="address" class="form-input" rows="2" style="resize: none; border-radius: 10px;" placeholder="Contoh: Dk. Dermayu Ds. Bumiharjo" required></textarea>
                </div>

                <button type="submit" class="btn" style="width: 100%; background: linear-gradient(135deg, #004aad 0%, #002d6a 100%); color: white; padding: 14px; border-radius: 10px; border: none; font-weight: 800; font-size: 1rem; box-shadow: 0 10px 15px -3px rgba(0, 74, 173, 0.3); transition: 0.3s;">
                    Daftar Sekarang
                </button>

                <div style="margin-top: 32px; text-align: center; font-size: 0.9rem;">
                    <span style="color: #64748b; font-weight: 500;">Sudah punya akun?</span>
                    <a href="{{ route('login') }}" style="color: #1e293b; font-weight: 800; text-decoration: underline; margin-left: 6px;">Masuk Sekarang</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        loadProvinces();
    });

    async function loadProvinces() {
        try {
            const res = await axios.get('master/provinces');
            const sel = document.getElementById('province');
            sel.innerHTML = '<option value="">Pilih...</option>';
            res.data.data.forEach(p => { 
                sel.innerHTML += `<option value="${p.id}">${p.name}</option>`; 
            });
        } catch (e) {
            console.error('Gagal mengambil data provinsi', e);
        }
    }

    async function loadCities(provId) {
        const sel = document.getElementById('city');
        const distSel = document.getElementById('district');
        sel.innerHTML = '<option value="">Pilih...</option>';
        distSel.innerHTML = '<option value="">Pilih...</option>';
        if(!provId) return;
        try {
            const res = await axios.get(`master/cities?province_id=${provId}`);
            res.data.data.forEach(c => { 
                sel.innerHTML += `<option value="${c.id}">${c.type === 'KOTA' ? 'KOTA ' : 'KAB. '}${c.name}</option>`; 
            });
        } catch (e) {
            console.error('Gagal mengambil data kota', e);
        }
    }

    async function loadDistricts(cityId) {
        const sel = document.getElementById('district');
        sel.innerHTML = '<option value="">Pilih...</option>';
        if(!cityId) return;
        try {
            const res = await axios.get(`master/districts?city_id=${cityId}`);
            res.data.data.forEach(d => { 
                sel.innerHTML += `<option value="${d.id}">${d.name}</option>`; 
            });
        } catch (e) {
            console.error('Gagal mengambil data kecamatan', e);
        }
    }

    document.getElementById('registerForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const payload = {
            nik: document.getElementById('nik').value,
            jkn_number: document.getElementById('jkn_number').value,
            name: document.getElementById('name').value,
            phone: document.getElementById('phone').value,
            birth_date: document.getElementById('birth_date').value,
            password: document.getElementById('password').value,
            gender: document.getElementById('gender').value,
            education: document.getElementById('education').value,
            occupation: document.getElementById('occupation').value,
            province_id: document.getElementById('province').value,
            city_id: document.getElementById('city').value,
            district_id: document.getElementById('district').value,
            address_detail: document.getElementById('address').value,
        };

        try {
            const res = await axios.post('member/register', payload);
            if(res.data.success) {
                showToast('Pendaftaran berhasil! Silakan login.', 'success');
                setTimeout(() => {
                    window.location.href = '/login';
                }, 2000);
            }
        } catch (error) {
            showToast(error.response?.data?.message || 'Gagal mendaftar. Silakan periksa kembali data Anda.', 'error');
        }
    });
</script>
@endpush
