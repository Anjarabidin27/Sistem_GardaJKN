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
            // Bersihkan nama dari kata KABUPATEN atau KOTA yang sudah ada agar tidak dobel
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
        console.log(`Loading districts for city: ${cityId} to ${targetId}`);
        const res = await window.axios.get(`master/districts?city_id=${cityId}`);
        sel.innerHTML = '<option value="">Pilih...</option>';
        sel.disabled = false;
        if(res.data.data.length === 0) {
            sel.innerHTML = '<option value="">Belum ada data (tunggu sync)...</option>';
            return;
        }
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
        
        const payload = {
            nik: document.getElementById('nik').value,
            jkn_number: document.getElementById('jkn_number').value || null,
            name: document.getElementById('name').value,
            phone: document.getElementById('phone').value,
            birth_date: document.getElementById('birth_date').value,
            password: document.getElementById('password').value,
            password_confirmation: document.getElementById('password_confirmation').value,
            gender: document.getElementById('gender').value,
            education: document.getElementById('education').value,
            occupation: document.getElementById('occupation').value,
            province_id: document.getElementById('province').value,
            city_id: document.getElementById('city').value,
            district_id: document.getElementById('district').value,
            address_detail: document.getElementById('address').value,
            dom_province_id: sameAsKtp ? document.getElementById('province').value : document.getElementById('dom_province').value,
            dom_city_id: sameAsKtp ? document.getElementById('city').value : document.getElementById('dom_city').value,
            dom_district_id: sameAsKtp ? document.getElementById('district').value : document.getElementById('dom_district').value,
            dom_address_detail: sameAsKtp ? document.getElementById('address').value : document.getElementById('dom_address').value,
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
            console.error('Registration Error:', error.response?.data);
            let msg = 'Gagal mendaftar. Cek kembali data Anda.';
            if (error.response?.data?.errors) {
                // Ambil error pertama dari validasi (misal 'NIK sudah terdaftar')
                msg = Object.values(error.response.data.errors).flat()[0];
            } else if (error.response?.data?.message) {
                msg = error.response.data.message;
            }
            window.showToast(msg, 'error');
        }
    });
}