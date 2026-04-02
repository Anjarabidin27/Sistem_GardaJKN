document.addEventListener('DOMContentLoaded', () => {
    loadData();
    initForms();
});

const getAxios = () => window.axios;

async function loadData() {
    try {
        const res = await getAxios().get('member/bpjs-keliling');
        const items = res.data.data;
        renderTable(items);
        updateStats(items);
    } catch (e) {
        console.error('Failed load', e);
    }
}

function renderTable(items) {
    const tbody = document.getElementById('list-bpjs');
    if (!tbody) return;
    tbody.innerHTML = '';

    items.forEach(item => {
        const date = new Date(item.tanggal).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
        tbody.innerHTML += `
            <tr>
                <td class="ps-4" style="font-size:0.875rem;">${date}</td>
                <td>
                    <div style="font-weight:600; color:#1e293b;">${item.judul}</div>
                    <div style="font-size:0.75rem; color:#64748b;">${item.jenis_kegiatan}</div>
                </td>
                <td style="font-size:0.875rem; color:#475569;">${item.kota?.name || '-'}</td>
                <td>
                    <span class="badge rounded-pill bg-light text-dark" style="font-weight:600;">${item.participants?.length || 0} Orang</span>
                </td>
                <td>
                    <span class="badge bg-success-subtle text-success px-3 py-2 border border-success-subtle" style="font-size:0.7rem; text-transform:uppercase;">${item.status}</span>
                </td>
                <td class="text-end pe-4">
                    <button class="btn btn-sm btn-outline-primary" onclick="entryParticipant('${item.id}', '${item.judul}')">
                        <i data-lucide="user-plus" style="width:14px;"></i>
                        <span>Entry Peserta</span>
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
    let totalPuas = 0;
    let totalResp = 0;

    items.forEach(i => {
        totalPeserta += (i.participants?.length || 0);
        i.participants?.forEach(p => {
           if(p.suara_pelanggan === 'Puas') totalPuas++;
           totalResp++;
        });
    });

    document.getElementById('count-peserta').innerText = totalPeserta;
    let percent = totalResp > 0 ? Math.round((totalPuas/totalResp)*100) : 100;
    document.getElementById('count-puas').innerText = percent + '%';
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
                jenis_kegiatan: document.getElementById('jenis_kegiatan').value,
                tanggal: document.getElementById('tanggal').value,
                kuadran: document.getElementById('kuadran').value,
                nama_frontliner: document.getElementById('nama_frontliner').value,
                provinsi_id: document.getElementById('provinsi_id').value,
                kota_id: document.getElementById('kota_id').value,
                kecamatan_id: document.getElementById('kecamatan_id').value,
                nama_desa: document.getElementById('nama_desa').value,
            };

            try {
                const res = await getAxios().post('member/bpjs-keliling', payload);
                if (res.data.status === 'success') {
                    window.hideModal('modalKegiatan');
                    loadData();
                    entryParticipant(res.data.data.id, res.data.data.judul);
                }
            } catch (err) {
                console.error(err);
                alert('Gagal menyimpan kegiatan. Pastikan semua field terisi.');
            } finally {
                btn.disabled = false; btn.innerText = 'Simpan Laporan & Lanjut Isi Peserta';
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
                jam_mulai: document.getElementById('p_jam_mulai').value,
                jam_selesai: document.getElementById('p_jam_selesai').value,
                segmen_peserta: document.getElementById('p_segmen').value,
                jenis_layanan: document.getElementById('p_jenis').value,
                transaksi_layanan: document.getElementById('p_transaksi').value,
                status: document.getElementById('p_status').value,
                keterangan_gagal: document.getElementById('p_keterangan_gagal').value,
                suara_pelanggan: document.getElementById('p_puas').value,
            };

            try {
                const res = await getAxios().post(`member/bpjs-keliling/${id}/participants`, payload);
                if (res.data.status === 'success') {
                    fPar.reset();
                    document.getElementById('p_activity_id').value = id;
                    document.getElementById('div_keterangan_gagal').style.display = 'none';
                    window.showToast('Data peserta tersimpan!', 'success');
                    loadData();
                }
            } catch (err) {
                console.error(err);
                alert('Gagal simpan peserta. Cek NIK (16 digit) dan kelengkapan data.');
            } finally {
                btn.disabled = false; btn.innerText = 'Simpan & Input Peserta Lagi';
            }
        });
    }
}

window.toggleGagal = (status) => {
    document.getElementById('div_keterangan_gagal').style.display = (status === 'Tidak Berhasil' ? 'block' : 'none');
};

window.entryParticipant = (id, title) => {
    document.getElementById('p_activity_id').value = id;
    window.showModal('modalParticipant');
};

window.updateTransaksi = (jenis) => {
    const sel = document.getElementById('p_transaksi');
    sel.innerHTML = '<option value="">Pilih...</option>';
    
    let options = [];
    if (jenis === 'Administrasi') {
        options = [
            '1. Pendaftaran Baru',
            '2. Penambahan Anggota Keluarga',
            '3. Pengaktifan Kembali Status Kepesertaan (Anak >21 Tahun masih Kuliah)',
            '4. Pengaktifan Kembali Status Kepesertaan (Data Ganda dan Rekonsiliasi Data)',
            '5. Pengaktifan Kembali Status Kepesertaan (PBI JK dan PBPU BP Pemda)',
            '6. Pengaktifan Kembali Status Kepesertaan (Registrasi Ulang dan Rekonsiliasi Data)',
            '7. Pengaktifan Kembali Status Kepesertaan (Update VA PBPU)',
            '8. Pengaktifan Kembali Status Kepesertaan (WNI Kembali dari Luar Negeri)',
            '9. Pengantian Kartu Hilang',
            '10. Pengurangan Anggota Keluarga (Pelaporan Peserta Meninggal Dunia dan Rekonsiliasi Data)',
            '11. Pengurangan Anggota Keluarga (Pelaporan WNI pergi keluar Negeri)',
            '12. Peralihan Jenis Kepesertaan',
            '13. Peralihan Jenis Kepesertaan (Tanpa Administrasi 14 Hari)',
            '14. Perubahan/Perbaikan Data FKTP',
            '15. Perubahan/Perbaikan Data Golongan dan Gaji',
            '16. Perubahan/Perbaikan Data Identitas (NIK, No KK, Nama, Tanggal Lahir, Jenis Kelamin, Alamat)',
            '17. Perubahan/Perbaikan Data Kelas Rawat',
            '18. Perubahan/Perbaikan Data Nomor Handphone',
            '19. Perubahan/Perbaikan Data Pembaharuan KK (Gabung/Pisah KK)',
            '20. Rekonsiliasi Iuran (Refund Iuran)',
            '21. Rekonsiliasi Iuran (VA to VA)'
        ];
    } else if (jenis === 'Informasi') {
        options = ['Cek Iuran', 'Cek Status Aktif', 'Informasi Alamat RS', 'Informasi Program REHAB'];
    } else if (jenis === 'Pengaduan') {
        options = ['Keluhan Layanan RS', 'Keluhan Aplikasi Mobile JKN', 'Keluhan Keterlambatan Kartu'];
    }

    options.forEach(o => {
        const opt = document.createElement('option');
        opt.value = o; opt.innerText = o;
        sel.appendChild(opt);
    });
};

// Utils for UI
window.showModal = (id) => {
    const el = document.getElementById(id);
    if(el) el.style.display = 'flex';
};
window.hideModal = (id) => {
    const el = document.getElementById(id);
    if(el) el.style.display = 'none';
};
