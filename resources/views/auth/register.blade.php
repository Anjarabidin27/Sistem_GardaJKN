@extends('layouts.app')

@section('title', 'Daftar Anggota Baru - Garda JKN')

@section('content')
<style>
    .split-layout {
        display: flex;
        min-height: 100vh;
        background: white;
    }

    /* Left Side: Brand/Visual */
    .brand-side {
        flex: 1;
        background: linear-gradient(135deg, #004aad 0%, #002d6a 100%);
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 80px;
        color: white;
        position: relative;
        overflow: hidden;
    }
    
    .brand-side::before {
        content: '';
        position: absolute;
        top: -10%; right: -10%;
        width: 400px; height: 400px;
        background: rgba(255,255,255,0.03);
        border-radius: 50%;
    }

    .brand-title { font-size: 2.5rem; font-weight: 800; letter-spacing: -1px; margin-bottom: 16px; }
    .brand-subtitle { font-size: 1.125rem; opacity: 0.8; line-height: 1.6; max-width: 480px; }

    /* Right Side: Form (Made wider for the registration form) */
    .form-side {
        width: 700px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 60px 80px;
        background: #fdfdfd;
        overflow-y: auto;
    }

    .form-container { width: 100%; max-width: 540px; margin: 0 auto; }

    .welcome-text { margin-bottom: 32px; }
    .welcome-text h2 { font-size: 1.5rem; font-weight: 700; color: var(--text-title); margin-bottom: 8px; }
    .welcome-text p { color: var(--text-muted); font-size: 0.875rem; }

    .input-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    .label { display: block; font-size: 0.75rem; font-weight: 600; margin-bottom: 6px; color: #64748b; }

    @media (max-width: 1200px) {
        .form-side { width: 600px; padding: 40px; }
    }

    @media (max-width: 1024px) {
        .brand-side { display: none; }
        .form-side { width: 100%; padding: 40px; }
    }
</style>

<div class="split-layout">
    <!-- Left Section -->
    <div class="brand-side">
        <div class="brand-title">Garda JKN</div>
        <p class="brand-subtitle">Bergabunglah dengan ribuan anggota lainnya dalam Sistem Informasi Pengelolaan Database dan Keanggotaan Nasional.</p>
        
        <div style="margin-top: 60px; display: flex; gap: 40px;">
            <div>
                <div style="font-size: 1.5rem; font-weight: 700;">34</div>
                <div style="font-size: 0.875rem; opacity: 0.7;">Provinsi</div>
            </div>
            <div>
                <div style="font-size: 1.5rem; font-weight: 700;">Data</div>
                <div style="font-size: 0.875rem; opacity: 0.7;">Terverifikasi</div>
            </div>
        </div>
    </div>

    <!-- Right Section -->
    <div class="form-side">
        <div class="form-container">
            <div class="welcome-text">
                <h2>Registrasi Anggota</h2>
                <p>Lengkapi formulir di bawah untuk mendaftar sebagai anggota baru.</p>
            </div>

            <form id="registerForm">
                <div class="input-grid">
                    <div>
                        <label class="label">NIK (16 Digit)</label>
                        <input type="text" id="nik" class="form-input" placeholder="Wajib 16 digit sesuai KTP" required>
                    </div>
                    <div>
                        <label class="label">Nama Lengkap</label>
                        <input type="text" id="name" class="form-input" placeholder="Nama sesuai KTP" required>
                    </div>
                </div>

                <div class="input-grid">
                    <div>
                        <label class="label">WhatsApp</label>
                        <input type="text" id="phone" class="form-input" placeholder="0812..." required>
                    </div>
                    <div>
                        <label class="label">Kata Sandi</label>
                        <input type="password" id="password" class="form-input" placeholder="Min. 6 Karakter" required>
                    </div>
                </div>

                <div class="input-grid">
                    <div>
                        <label class="label">Jenis Kelamin</label>
                        <select id="gender" class="form-input" required>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="label">Pendidikan Terakhir</label>
                        <select id="education" class="form-input" required>
                            <option value="SD">SD</option>
                            <option value="SMP">SMP</option>
                            <option value="SMA">SMA</option>
                            <option value="Diploma">Diploma</option>
                            <option value="S1/D4">S1/D4</option>
                            <option value="S2">S2</option>
                        </select>
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label class="label">Sektor Pekerjaan</label>
                    <select id="occupation" class="form-input" required>
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
                        <label class="label">Provinsi</label>
                        <select id="province" class="form-input" onchange="loadCities(this.value)" required>
                            <option value="">Pilih...</option>
                        </select>
                    </div>
                    <div>
                        <label class="label">Kab/Kota</label>
                        <select id="city" class="form-input" onchange="loadDistricts(this.value)" required>
                            <option value="">Pilih...</option>
                        </select>
                    </div>
                    <div>
                        <label class="label">Kecamatan</label>
                        <select id="district" class="form-input" required>
                            <option value="">Pilih...</option>
                        </select>
                    </div>
                </div>

                <div style="margin-bottom: 24px;">
                    <label class="label">Alamat Lengkap</label>
                    <textarea id="address" class="form-input" rows="2" style="resize: none;" placeholder="Jl. Contoh No. 123..." required></textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    Daftar Sekarang
                </button>

                <div style="margin-top: 24px; text-align: center; font-size: 0.875rem;">
                    <span style="color: var(--text-muted);">Sudah punya akun?</span>
                    <a href="{{ route('login') }}" style="color: var(--primary); font-weight: 600; text-decoration: none; margin-left: 4px;">Masuk di sini</a>
                </div>
            </form>

            <div style="margin-top: 40px; text-align: center; font-size: 0.75rem; color: var(--text-muted);">
                &copy; 2026 BPJS Kesehatan Garda JKN. Seluruh Hak Cipta Dilindungi.
            </div>
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
            const res = await axios.get('/master/provinces');
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
            const res = await axios.get(`/master/cities?province_id=${provId}`);
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
            const res = await axios.get(`/master/districts?city_id=${cityId}`);
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
            name: document.getElementById('name').value,
            phone: document.getElementById('phone').value,
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
