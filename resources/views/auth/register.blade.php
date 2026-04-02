@extends('layouts.app')

@section('title', 'Daftar Anggota Baru - Garda JKN')

@section('content')
<div class="split-layout">
    <!-- Left Side: Visual & Marketing -->
    <div class="brand-side">
        <div class="brand-header">
            <div class="brand-logo">G</div>
            <div class="brand-name">Garda JKN</div>
        </div>

        <div class="brand-content">
            <div style="font-size: 0.85rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.2em; color: rgba(255,255,255,0.6); margin-bottom: 12px;">Selamat Datang</div>
            <h1 class="brand-title">Registrasi Keanggotaan Nasional</h1>
            <p class="brand-subtitle">Bergabunglah dengan ribuan anggota Garda JKN lainnya untuk berkontribusi dalam pengawasan dan layanan JKN yang lebih baik.</p>
        </div>

        <div class="brand-footer features">
            <div class="feature-item">
                <div class="feature-icon"><i data-lucide="shield-check"></i></div>
                <div>Akses Data Terverifikasi & Aman</div>
            </div>
            <div class="feature-item">
                <div class="feature-icon"><i data-lucide="bar-chart-3"></i></div>
                <div>Visualisasi Laporan Real-time</div>
            </div>
            <div class="feature-item">
                <div class="feature-icon"><i data-lucide="users"></i></div>
                <div>Kolaborasi Antar Pengurus & Anggota</div>
            </div>
        </div>
    </div>

    <!-- Right Side: Registration Form -->
    <div class="form-side" style="padding-top: 60px;">
        <div class="form-container register">
            <div class="welcome-text">
                <h2>Buka Akun Baru</h2>
                <p>Lengkapi formulir di bawah ini untuk memulai registrasi Anggota.</p>
            </div>

            <form id="registerForm">
                <div class="section-title">Data Identitas Utama</div>
                <div class="input-grid">
                    <div class="form-group">
                        <label class="form-label">NIK (16 Digit)</label>
                        <input type="tel" id="nik" class="form-control" placeholder="Sesuai KTP" required minlength="16" maxlength="16" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16);">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nomor Kartu JKN</label>
                        <input type="text" id="jkn_number" class="form-control" placeholder="Opsional / No. BPJS">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" id="name" class="form-control" placeholder="Sesuai KTP (Tanpa Gelar)" required>
                </div>

                <div class="input-grid">
                    <div class="form-group">
                        <label class="form-label">WhatsApp (Aktif)</label>
                        <input type="text" id="phone" class="form-control" placeholder="0812..." required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" id="birth_date" class="form-control" required>
                    </div>
                </div>

                <div class="section-title">Profil Bio & Pendidikan</div>
                <div class="input-grid">
                    <div class="form-group">
                        <label class="form-label">Jenis Kelamin</label>
                        <select id="gender" class="form-control" required>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pendidikan Terakhir</label>
                        <select id="education" class="form-control" required>
                            <option value="SD">SD</option>
                            <option value="SMP">SMP</option>
                            <option value="SMA">SMA</option>
                            <option value="Diploma">Diploma (D1/D2/D3)</option>
                            <option value="S1/D4">S1 / D4</option>
                            <option value="S2">S2</option>
                            <option value="S3">S3</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Jenis Pekerjaan</label>
                    <select id="occupation" class="form-control" required>
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

                <div class="section-title">Domisili Sesuai KTP</div>
                <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px;">
                    <div>
                        <label class="form-label">Provinsi</label>
                        <select id="province" class="form-control" style="padding: 14px 10px;" onchange="window.loadCities(this.value, 'city')" required>
                            <option value="">Pilih...</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Kota/Kab</label>
                        <select id="city" class="form-control" style="padding: 14px 10px;" onchange="window.loadDistricts(this.value, 'district')" required disabled>
                            <option value="">Pilih...</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Kecamatan</label>
                        <select id="district" class="form-control" style="padding: 14px 10px;" required disabled>
                            <option value="">Pilih...</option>
                        </select>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 24px;">
                    <label class="form-label">Alamat Lengkap (KTP)</label>
                    <textarea id="address" class="form-control" rows="2" style="resize: none;" placeholder="Contoh: Jl. Sudirman No. 123, RT 01/RW 02, Desa Makmur" required></textarea>
                </div>

                <!-- Toggle Domisili -->
                <div class="form-group" style="background: #f8fafc; padding: 16px; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 32px;">
                    <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; user-select: none;">
                        <input type="checkbox" id="same_as_ktp" onchange="toggleDomisili(this.checked)" style="width: 18px; height: 18px; cursor: pointer;">
                        <span style="font-size: 0.9rem; font-weight: 700; color: #1e293b;">Alamat Domisili saat ini SAMA dengan KTP</span>
                    </label>
                </div>

                <div id="domisili_section">
                    <div class="section-title">Domisili Tempat Tinggal Sekarang</div>
                    <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px;">
                        <div>
                            <label class="form-label">Provinsi</label>
                            <select id="dom_province" class="form-control" style="padding: 14px 10px;" onchange="window.loadCities(this.value, 'dom_city')" required>
                                <option value="">Pilih...</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Kota/Kab</label>
                            <select id="dom_city" class="form-control" style="padding: 14px 10px;" onchange="window.loadDistricts(this.value, 'dom_district')" required disabled>
                                <option value="">Pilih...</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Kecamatan</label>
                            <select id="dom_district" class="form-control" style="padding: 14px 10px;" required disabled>
                                <option value="">Pilih...</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Alamat Lengkap (Sekarang)</label>
                        <textarea id="dom_address" class="form-control" rows="2" style="resize: none;" placeholder="Masukkan alamat tempat tinggal Anda saat ini" required></textarea>
                    </div>
                </div>

                <div class="section-title">Keamanan Akun</div>
                <div class="input-grid">
                    <div class="form-group">
                        <label class="form-label">Kata Sandi (Password)</label>
                        <div class="input-group-password">
                            <input type="password" id="password" class="form-control" placeholder="Min. 8 Karakter" required>
                            <button type="button" class="password-toggle-btn" onclick="togglePassword('password')" tabindex="-1">
                                <span id="icon-password"><i data-lucide="eye"></i></span>
                            </button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Ulangi Kata Sandi</label>
                        <div class="input-group-password">
                            <input type="password" id="password_confirmation" class="form-control" placeholder="Konfirmasi Password" required>
                            <button type="button" class="password-toggle-btn" onclick="togglePassword('password_confirmation')" tabindex="-1">
                                <span id="icon-password_confirmation"><i data-lucide="eye"></i></span>
                            </button>
                        </div>
                    </div>
                </div>

                <div style="margin-top: 40px;">
                    <button type="submit" class="submit-btn" id="btn-register">
                        <span>Lanjutkan Registrasi</span> <i data-lucide="arrow-right" style="width: 18px;"></i>
                    </button>
                </div>

                <div class="footer-links">
                    <span>Sudah memiliki akun?</span>
                    <a href="{{ route('login') }}">Masuk Sekarang</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@vite(['resources/css/auth.css', 'resources/js/pages/auth_register.js'])

<script>
    function toggleDomisili(isSame) {
        const section = document.getElementById('domisili_section');
        const inputs = section.querySelectorAll('input, select, textarea');
        
        if (isSame) {
            section.style.opacity = '0.5';
            section.style.pointerEvents = 'none';
            inputs.forEach(i => i.removeAttribute('required'));
        } else {
            section.style.opacity = '1';
            section.style.pointerEvents = 'auto';
            inputs.forEach(i => i.setAttribute('required', ''));
        }
    }

    // Fitur Auto-Save (Draft on Refresh)
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('registerForm');
        if (!form) return;

        // Restore data dari localStorage
        const draft = JSON.parse(localStorage.getItem('reg_draft') || '{}');
        Object.keys(draft).forEach(key => {
            const el = document.getElementById(key);
            if (el) {
                el.value = draft[key];
                // Trigger change event for selects to load children
                if(el.tagName === 'SELECT' && el.value) {
                    const event = new Event('change');
                    el.dispatchEvent(event);
                }
            }
        });

        // Simpan setiap kali ada perubahan input
        form.addEventListener('input', () => {
            const currentData = {};
            form.querySelectorAll('input, select, textarea').forEach(el => {
                if(el.id && el.type !== 'password' && el.type !== 'file') {
                    currentData[el.id] = el.value;
                }
            });
            localStorage.setItem('reg_draft', JSON.stringify(currentData));
        });

        // Hapus draft saat pendaftaran berhasil
        window.clearRegDraft = () => {
            localStorage.removeItem('reg_draft');
            showToast('Draft berhasil dibersihkan.', 'info');
            setTimeout(() => window.location.reload(), 1000);
        };
    });
</script>
@endpush
