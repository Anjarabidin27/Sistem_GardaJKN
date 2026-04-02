document.addEventListener('DOMContentLoaded', () => {
    loadData();
    initForms();
});

const getAxios = () => window.axios;

async function loadData() {
    try {
        const res = await getAxios().get('member/pil');
        const items = res.data.data;
        renderTable(items);
        updateStats(items);
    } catch (e) {
        console.error('Failed load PIL', e);
    }
}

function renderTable(items) {
    const tbody = document.getElementById('list-pil');
    if (!tbody) return;
    tbody.innerHTML = '';

    items.forEach(item => {
        const date = new Date(item.tanggal).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
        const avgNps = item.jumlah_peserta > 0 ? ( (parseFloat(item.rata_nps_ketertarikan) + parseFloat(item.rata_nps_rekomendasi_program) + parseFloat(item.rata_nps_rekomendasi_bpjs))/3 ).toFixed(1) : '-';
        
        tbody.innerHTML += `
            <tr>
                <td class="ps-4" style="font-size:0.875rem;">${date}</td>
                <td>
                    <div style="font-weight:600; color:#1e293b;">${item.judul}</div>
                    <div style="font-size:0.75rem; color:#64748b;">${item.kota?.name || '-'}</div>
                </td>
                <td style="font-size:0.875rem; color:#475569;">${item.nama_frontliner}</td>
                <td>
                    <span class="badge rounded-pill bg-light text-dark" style="font-weight:600;">${item.participants?.length || 0} Orang</span>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <i data-lucide="star" style="width:14px; color:#fbbf24; margin-right:5px;"></i>
                        <span style="font-weight:700;">${avgNps}</span>
                    </div>
                </td>
                <td class="text-end pe-4">
                    <button class="btn btn-sm btn-outline-primary" onclick="entryParticipant('${item.id}', '${item.judul}')">
                        <i data-lucide="user-plus" style="width:14px;"></i>
                        <span>Entry Survei</span>
                    </button>
                    <button class="btn btn-sm btn-icon" style="background:#f1f5f9; color:#64748b;">
                        <i data-lucide="eye" style="width:14px;"></i>
                    </button>
                </td>
            </tr>
        `;
    });
    if(typeof lucide !== 'undefined') lucide.createIcons();
}

function updateStats(items) {
    document.getElementById('count-kegiatan').innerText = items.length;
    let totalPeserta = 0;
    let sumNps = 0;
    let totalCount = 0;

    items.forEach(i => {
        totalPeserta += (i.participants?.length || 0);
        if(i.jumlah_peserta > 0) {
            sumNps += (parseFloat(i.rata_nps_ketertarikan) + parseFloat(i.rata_nps_rekomendasi_program) + parseFloat(i.rata_nps_rekomendasi_bpjs))/3;
            totalCount++;
        }
    });

    document.getElementById('count-peserta').innerText = totalPeserta;
    document.getElementById('avg-nps').innerText = totalCount > 0 ? (sumNps/totalCount).toFixed(1) : '-';
}

function initForms() {
    const fKeg = document.getElementById('formKegiatan');
    if (fKeg) {
        fKeg.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = e.target.querySelector('button[type="submit"]');
            btn.disabled = true; btn.innerText = 'Menyimpan...';

            const payload = {
                judul: document.getElementById('judul').value,
                tanggal: document.getElementById('tanggal').value,
                nama_frontliner: document.getElementById('nama_frontliner').value,
                provinsi_id: document.getElementById('provinsi_id').value,
                kota_id: document.getElementById('kota_id').value,
                kecamatan_id: document.getElementById('kecamatan_id').value,
                nama_desa: document.getElementById('nama_desa').value,
            };

            try {
                const res = await getAxios().post('member/pil', payload);
                if (res.data.status === 'success') {
                    window.hideModal('modalKegiatan');
                    loadData();
                    entryParticipant(res.data.data.id, res.data.data.judul);
                }
            } catch (err) {
                console.error(err);
                alert('Gagal memulai sesi PIL. Pastikan data terisi.');
            } finally {
                btn.disabled = false; btn.innerText = 'Mulai Sesi & Input Survei Peserta';
            }
        });
    }

    const fPar = document.getElementById('formParticipant');
    if (fPar) {
        fPar.addEventListener('submit', async (e) => {
            e.preventDefault();
            const id = document.getElementById('p_activity_id').value;
            const btn = e.target.querySelector('button[type="submit"]');
            btn.disabled = true; btn.innerText = 'Menyimpan...';

            const payload = {
                nik: document.getElementById('p_nik').value,
                name: document.getElementById('p_name').value,
                phone_number: document.getElementById('p_phone').value,
                segmen_peserta: document.getElementById('p_segmen').value,
                jam_sosialisasi_mulai: document.getElementById('p_jam_mulai').value,
                jam_sosialisasi_selesai: document.getElementById('p_jam_selesai').value,
                nilai_pemahaman: document.getElementById('p_pemahaman').value,
                efektifitas_sosialisasi: document.getElementById('p_efektifitas').value,
                nps_ketertarikan: document.getElementById('p_nps1').value,
                nps_rekomendasi_program: document.getElementById('p_nps2').value,
                nps_rekomendasi_bpjs: document.getElementById('p_nps3').value,
            };

            try {
                const res = await getAxios().post(`member/pil/${id}/participants`, payload);
                if (res.data.status === 'success') {
                    fPar.reset();
                    document.getElementById('p_activity_id').value = id;
                    // Reset slider visual value (output tag)
                    const outputs = fPar.querySelectorAll('output');
                    outputs.forEach(o => o.value = 5);

                    window.showToast('Data survei tersimpan!', 'success');
                    loadData();
                }
            } catch (err) {
                console.error(err);
                alert('Gagal simpan survei. Cek NIK dan kelengkapan skor.');
            } finally {
                btn.disabled = false; btn.innerText = 'Simpan & Peserta Lainnya';
            }
        });
    }
}

window.entryParticipant = (id, title) => {
    document.getElementById('p_activity_id').value = id;
    window.showModal('modalParticipant');
};

// Modals
window.showModal = (id) => { document.getElementById(id).style.display = 'flex'; };
window.hideModal = (id) => { document.getElementById(id).style.display = 'none'; };
