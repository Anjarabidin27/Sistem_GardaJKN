// resources/js/pages/auth_register.js

document.addEventListener('DOMContentLoaded', () => {
    // Tunggu sampai window.axios siap sebelum mulai
    initRegistration();
});

function initRegistration() {
    if (typeof window.axios !== 'undefined') {
        loadProvinces();
        setupNikAutoCheck();
    } else {
        // Cek lagi dalam 50 milidetik jika belum siap
        setTimeout(initRegistration, 50);
    }
}

function setupNikAutoCheck() {
    const nikInput = document.getElementById('nik');
    if (!nikInput) return;

    let timeoutId;
    nikInput.addEventListener('input', () => {
        // Hapus pesan error saat user mengetik
        const errEl = document.getElementById('nik-error');
        if (errEl) errEl.style.display = 'none';
        nikInput.style.borderColor = '';

        clearTimeout(timeoutId);
        
        // Hanya cek jika tepat 16 digit
        if (nikInput.value.length === 16) {
            timeoutId = setTimeout(async () => {
                try {
                    const res = await window.axios.post('member/check-nik', { nik: nikInput.value });
                    if (!res.data.available) {
                        if (errEl) {
                            const errText = document.getElementById('nik-error-text');
                            if (errText) errText.innerText = 'NIK ini sudah terdaftar di sistem. Silakan gunakan NIK lain.';
                            errEl.style.display = 'block';
                            if (typeof lucide !== 'undefined') lucide.createIcons();
                        }
                        nikInput.style.borderColor = '#EF4444';
                    }
                } catch (e) {
                    console.error('Auto-check NIK failed', e);
                }
            }, 500); // Debounce 500ms
        }
    });
}

// Global scope for navigation
window.nextStep = async function(step) {
    // Validate current step before proceeding
    const currentStep = document.querySelector('.form-step.active');
    const inputs = currentStep.querySelectorAll('input[required], select[required], textarea[required]');
    
    // Check validity
    let isValid = true;
    inputs.forEach(input => {
        if (!input.checkValidity()) {
            input.reportValidity();
            isValid = false;
        }
    });

    const isMovingForward = step > parseInt(currentStep.id.split('-')[1]);
    if (!isValid && isMovingForward) return;

    // AJAX Check NIK khusus untuk Step 1 -> Step 2
    if (isMovingForward && currentStep.id === 'step-1') {
        const nikInput = document.getElementById('nik');
        if (nikInput && nikInput.value) {
            // Wajib Mutlak 16 Digit
            if (nikInput.value.length < 16) {
                const errEl = document.getElementById('nik-error');
                const errText = document.getElementById('nik-error-text');
                if (errText) errText.innerText = 'NIK wajib diisi lengkap 16 digit angka.';
                if (errEl) {
                    errEl.style.display = 'block';
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }
                nikInput.style.borderColor = '#EF4444';
                window.showToast('NIK wajib diisi lengkap 16 digit angka.', 'error');
                return; // Blokir
            }

            try {
                // Tampilkan indikator loading di tombol Lanjut (opsional)
                const btn = currentStep.querySelector('button[onclick="nextStep(2)"]');
                const oldText = btn ? btn.innerHTML : '';
                if (btn) { btn.disabled = true; btn.innerHTML = 'Mengecek NIK...'; }

                const res = await window.axios.post('member/check-nik', { nik: nikInput.value });
                
                if (btn) { btn.disabled = false; btn.innerHTML = oldText; }

                if (!res.data.available) {
                    const errEl = document.getElementById('nik-error');
                    const errText = document.getElementById('nik-error-text');
                    if (errText) errText.innerText = 'NIK ini sudah terdaftar di sistem. Silakan gunakan NIK lain.';
                    if (errEl) {
                        errEl.style.display = 'block';
                        if (typeof lucide !== 'undefined') lucide.createIcons();
                    }
                    nikInput.style.borderColor = '#EF4444';
                    window.showToast('NIK ini sudah terdaftar di sistem. Silakan gunakan NIK lain.', 'error');
                    return; // Hentikan agar tidak pindah ke Step 2
                } else {
                    const errEl = document.getElementById('nik-error');
                    if (errEl) errEl.style.display = 'none';
                    nikInput.style.borderColor = '';
                }
            } catch (err) {
                // Jika error server/jaringan, biarkan lanjut
                const btn = currentStep.querySelector('button[onclick="nextStep(2)"]');
                if (btn) { btn.disabled = false; btn.innerHTML = 'Selanjutnya <i data-lucide="arrow-right"></i>'; }
                console.error('Gagal mengecek NIK:', err);
            }
        }
    }

    // Hide all steps
    document.querySelectorAll('.form-step').forEach(s => s.classList.remove('active'));
    document.querySelectorAll('.step-header').forEach(h => h.classList.remove('active'));
    
    // Show target step
    document.getElementById('step-' + step).classList.add('active');
    
    // Update headers
    for (let i = 1; i <= 3; i++) {
        const header = document.getElementById('header-' + i);
        if (i < step) {
            header.classList.add('completed');
            header.classList.remove('active');
        } else if (i === step) {
            header.classList.add('active');
            header.classList.remove('completed');
        } else {
            header.classList.remove('active', 'completed');
        }
    }

    // Scroll top of form
    document.querySelector('.form-side').scrollTo({ top: 0, behavior: 'smooth' });

    // Refresh Lucide Icons
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

async function loadProvinces() {
    try {
        const res = await window.axios.get('master/provinces');
        if (!res.data || !res.data.data) return;
        const items = res.data.data;
        
        ['province', 'dom_province'].forEach(id => {
            const el = document.getElementById(id);
            if (!el) return;
            el.innerHTML = '<option value="">Pilih...</option>';
            items.forEach(p => { 
                el.innerHTML += `<option value="${p.id}">${p.name}</option>`; 
            });
        });
    } catch (e) {
        console.error('Cant load provinces', e);
    }
}

async function loadCities(provId, targetId) {
    const sel = document.getElementById(targetId);
    if (!sel) return;

    sel.innerHTML = '<option value="">Memuat...</option>';
    sel.disabled = true;

    if(!provId) {
        sel.innerHTML = '<option value="">Pilih...</option>';
        return;
    }
    
    try {
        const res = await window.axios.get(`master/cities?province_id=${provId}`);
        sel.innerHTML = '<option value="">Pilih...</option>';
        sel.disabled = false;
        res.data.data.forEach(c => { 
            let cleanName = c.name.replace(/^(KABUPATEN|KOTA|KAB\.?)\s+/i, '');
            sel.innerHTML += `<option value="${c.id}">${c.type === 'KOTA' ? 'KOTA ' : 'KAB. '}${cleanName}</option>`; 
        });
    } catch (e) {
        console.error('Cant load cities', e);
    }
}

async function loadDistricts(cityId, targetId) {
    const sel = document.getElementById(targetId);
    if (!sel) return;
    
    sel.innerHTML = '<option value="">Memuat...</option>';
    sel.disabled = true;

    if(!cityId) {
        sel.innerHTML = '<option value="">Pilih...</option>';
        return;
    }
    
    try {
        const res = await window.axios.get(`master/districts?city_id=${cityId}`);
        sel.innerHTML = '<option value="">Pilih...</option>';
        sel.disabled = false;
        res.data.data.forEach(d => { 
            sel.innerHTML += `<option value="${d.id}">${d.name}</option>`; 
        });
    } catch (e) {
        console.error('Cant load districts', e);
        sel.innerHTML = '<option value="">Gagal memuat!</option>';
    }
}

window.loadCities = loadCities;
window.loadDistricts = loadDistricts;

// Registration submit
const regForm = document.getElementById('registerForm');
if (regForm) {
    regForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const sameAsKtp = document.getElementById('same_as_ktp').checked;
        
        const getValue = (id) => {
            const el = document.getElementById(id);
            return el ? el.value.trim() : '';
        };

        const payload = {
            nik: getValue('nik'),
            jkn_number: getValue('jkn_number'),
            name: getValue('name'),
            phone: getValue('phone'),
            birth_date: getValue('birth_date'),
            password: getValue('password'),
            password_confirmation: getValue('password_confirmation'),
            gender: getValue('gender'),
            education: getValue('education'),
            occupation: getValue('occupation'),
            province_id: getValue('province'),
            city_id: getValue('city'),
            district_id: getValue('district'),
            address_detail: getValue('address'),
            dom_province_id: sameAsKtp ? getValue('province') : getValue('dom_province'),
            dom_city_id: sameAsKtp ? getValue('city') : getValue('dom_city'),
            dom_district_id: sameAsKtp ? getValue('district') : getValue('dom_district'),
            dom_address_detail: sameAsKtp ? getValue('address') : getValue('dom_address'),
        };

        if (!payload.address_detail) {
            window.showToast('Alamat KTP wajib diisi.', 'error');
            return;
        }

        if (!payload.dom_address_detail) {
            window.showToast('Alamat domisili wajib diisi.', 'error');
            return;
        }

        if (payload.password !== payload.password_confirmation) {
            window.showToast('Konfirmasi kata sandi tidak cocok.', 'error');
            return;
        }

        const btn = document.getElementById('btn-register');
        const oldText = btn.innerHTML;
        btn.disabled = true; btn.innerHTML = 'Memproses... <span class="spinner-small"></span>';

        // Set a timeout guard to reset button if server is too slow
        const timeoutGuard = setTimeout(() => {
            if (btn.disabled) {
                btn.disabled = false;
                btn.innerHTML = oldText;
                window.showToast('Server merespon terlalu lama. Coba lagi.', 'warning');
            }
        }, 20000); // 20 Seconds

        try {
            const res = await window.axios.post('member/register', payload, {
                timeout: 15000 // 15 Seconds axios timeout
            });
            clearTimeout(timeoutGuard);
            
            if(res.data.status === 'success') {
                window.showToast('Pendaftaran Berhasil! Silakan Login.', 'success');
                setTimeout(() => { window.location.href = '/login'; }, 2000);
            }
        } catch (error) {
            clearTimeout(timeoutGuard);
            btn.disabled = false; btn.innerHTML = oldText;
            
            if (error.code === 'ECONNABORTED') {
                window.showToast('Koneksi terputus atau server sibuk. Silakan coba lagi.', 'error');
                return;
            }

            console.error('Registration Error Details:', error.response?.data || error.message);
            
            let msg = 'Gagal mendaftar. Cek kembali data Anda.';
            if (error.response?.data?.errors) {
                const firstErr = Object.values(error.response.data.errors).flat()[0];
                msg = firstErr;
            } else if (error.response?.data?.message) {
                msg = error.response.data.message;
            }
            window.showToast(msg, 'error');
        }
    });
}