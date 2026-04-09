document.addEventListener('DOMContentLoaded', () => {
    if(typeof lucide !== 'undefined') lucide.createIcons();
});

function goToStep(s) {
    const target = (typeof s === 'string') ? document.getElementById('step' + s) : document.getElementById('step' + s);
    
    document.querySelectorAll('.step-content').forEach(el => el.classList.remove('active'));
    
    if (s === '1b') {
        document.getElementById('step1b').classList.add('active');
    } else {
        document.getElementById('step' + s).classList.add('active');
    }
    
    // Update dots (1b is still part of major step 1)
    const dotS = (s === '1b') ? 1 : s;
    document.querySelectorAll('.step-item').forEach(el => el.classList.remove('active'));
    for(let i=1; i<=dotS; i++) {
        document.getElementById('dot' + i).classList.add('active');
    }
    
    if(typeof lucide !== 'undefined') lucide.createIcons();
}

window.goToStep = goToStep;

function selectInterest(val) {
    document.getElementById('is_interested_pengurus').value = val ? "1" : "0";
    if (val) {
        goToStep('1b');
    } else {
        // Direct Submit for "BELUM SAATNYA" if needed, or redirect
        window.location.href = "/member/profile";
    }
}

window.selectInterest = selectInterest;

function toggleRole(id) {
    const checkbox = document.getElementById(id);
    const card = document.getElementById('card-' + id.split('_')[1]);
    
    checkbox.checked = !checkbox.checked;
    if (checkbox.checked) {
        card.classList.add('active');
    } else {
        card.classList.remove('active');
    }
}

window.toggleRole = toggleRole;

function validateRoleSelection() {
    const k = document.getElementById('interest_keliling').checked;
    const p = document.getElementById('interest_pil').checked;

    if (!k && !p) {
        if(typeof showToast !== 'undefined') {
            showToast('Silakan pilih minimal satu bagian pelayanan.', 'error');
        } else {
            alert('Silakan pilih minimal satu bagian pelayanan.');
        }
        return;
    }
    goToStep(2);
}

window.validateRoleSelection = validateRoleSelection;

function selectExperience(val) {
    document.getElementById('has_org_experience').value = val ? "1" : "0";
    if (val) {
        goToStep(3);
    } else {
        if(typeof showToast !== 'undefined') showToast('Memproses pendaftaran...', 'info');
        document.getElementById('applyForm').submit();
    }
}

window.selectExperience = selectExperience;
