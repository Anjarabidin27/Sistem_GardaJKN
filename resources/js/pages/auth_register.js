// resources/js/pages/auth_register.js

document.addEventListener('DOMContentLoaded', () => {
    // Tunggu sampai window.axios siap sebelum mulai
    initRegistration();
});

function initRegistration() {
    if (typeof window.axios !== 'undefined') {
        loadProvinces();
    } else {
        // Cek lagi dalam 50 milidetik jika belum siap
        setTimeout(initRegistration, 50);
    }
}

// Global scope for navigation
window.nextStep = function(step) {
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

    if (!isValid && step > parseInt(currentStep.id.split('-')[1])) return;

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
        
        const getValue = (id) => document.getElementById(id) ? document.getElementById(id).value : null;
        const isChecked = (id) => document.getElementById(id) ? document.getElementById(id).checked : false;

        const payload = {
            nik: getValue('nik'),
            jkn_number: getValue('jkn_number') || null,
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
            
            // Pengurus Interest
            is_interested_pengurus: isChecked('is_interested_pengurus'),
            interest_pil: isChecked('interest_pil'),
            interest_keliling: isChecked('interest_keliling'),
            has_org_experience: getValue('has_org_experience') === "1",
            org_name: getValue('org_name') || null,
            pengurus_reason: getValue('pengurus_reason') || null,
        };

        if (payload.password !== payload.password_confirmation) {
            window.showToast('Konfirmasi kata sandi tidak cocok.', 'error');
            return;
        }

        const btn = document.getElementById('btn-register');
        const oldText = btn.innerHTML;
        btn.disabled = true; btn.innerHTML = 'Memproses...';

        try {
            const res = await window.axios.post('member/register', payload);
            if(res.data.success) {
                window.showToast('Pendaftaran Berhasil! Silakan Login.', 'success');
                setTimeout(() => { window.location.href = '/login'; }, 2000);
            }
        } catch (error) {
            btn.disabled = false; btn.innerHTML = oldText;
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