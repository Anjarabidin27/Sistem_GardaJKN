document.addEventListener("DOMContentLoaded",()=>{l(),c()});const i=()=>window.axios;async function l(){try{const a=(await i().get("member/pil")).data.data;u(a),m(a)}catch(t){console.error("Failed load PIL",t)}}function u(t){const a=document.getElementById("list-pil");a&&(a.innerHTML="",t.forEach(e=>{const s=new Date(e.tanggal).toLocaleDateString("id-ID",{day:"2-digit",month:"short",year:"numeric"}),n=e.jumlah_peserta>0?((parseFloat(e.rata_nps_ketertarikan)+parseFloat(e.rata_nps_rekomendasi_program)+parseFloat(e.rata_nps_rekomendasi_bpjs))/3).toFixed(1):"-";a.innerHTML+=`
            <tr>
                <td class="ps-4" style="font-size:0.875rem;">${s}</td>
                <td>
                    <div style="font-weight:600; color:#1e293b;">${e.judul}</div>
                    <div style="font-size:0.75rem; color:#64748b;">${e.kota?.name||"-"}</div>
                </td>
                <td style="font-size:0.875rem; color:#475569;">${e.nama_frontliner}</td>
                <td>
                    <span class="badge rounded-pill bg-light text-dark" style="font-weight:600;">${e.participants?.length||0} Orang</span>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <i data-lucide="star" style="width:14px; color:#fbbf24; margin-right:5px;"></i>
                        <span style="font-weight:700;">${n}</span>
                    </div>
                </td>
                <td class="text-end pe-4">
                    <button class="btn btn-sm btn-outline-primary" onclick="entryParticipant('${e.id}', '${e.judul}')">
                        <i data-lucide="user-plus" style="width:14px;"></i>
                        <span>Entry Survei</span>
                    </button>
                    <button class="btn btn-sm btn-icon" style="background:#f1f5f9; color:#64748b;">
                        <i data-lucide="eye" style="width:14px;"></i>
                    </button>
                </td>
            </tr>
        `}),typeof lucide<"u"&&lucide.createIcons())}function m(t){document.getElementById("count-kegiatan").innerText=t.length;let a=0,e=0,s=0;t.forEach(n=>{a+=n.participants?.length||0,n.jumlah_peserta>0&&(e+=(parseFloat(n.rata_nps_ketertarikan)+parseFloat(n.rata_nps_rekomendasi_program)+parseFloat(n.rata_nps_rekomendasi_bpjs))/3,s++)}),document.getElementById("count-peserta").innerText=a,document.getElementById("avg-nps").innerText=s>0?(e/s).toFixed(1):"-"}function c(){const t=document.getElementById("formKegiatan");t&&t.addEventListener("submit",async e=>{e.preventDefault();const s=e.target.querySelector('button[type="submit"]');s.disabled=!0,s.innerText="Menyimpan...";const n={judul:document.getElementById("judul").value,tanggal:document.getElementById("tanggal").value,nama_frontliner:document.getElementById("nama_frontliner").value,provinsi_id:document.getElementById("provinsi_id").value,kota_id:document.getElementById("kota_id").value,kecamatan_id:document.getElementById("kecamatan_id").value,nama_desa:document.getElementById("nama_desa").value};try{const d=await i().post("member/pil",n);d.data.status==="success"&&(window.hideModal("modalKegiatan"),l(),entryParticipant(d.data.data.id,d.data.data.judul))}catch(d){console.error(d),alert("Gagal memulai sesi PIL. Pastikan data terisi.")}finally{s.disabled=!1,s.innerText="Mulai Sesi & Input Survei Peserta"}});const a=document.getElementById("formParticipant");a&&a.addEventListener("submit",async e=>{e.preventDefault();const s=document.getElementById("p_activity_id").value,n=e.target.querySelector('button[type="submit"]');n.disabled=!0,n.innerText="Menyimpan...";const d={nik:document.getElementById("p_nik").value,name:document.getElementById("p_name").value,phone_number:document.getElementById("p_phone").value,segmen_peserta:document.getElementById("p_segmen").value,jam_sosialisasi_mulai:document.getElementById("p_jam_mulai").value,jam_sosialisasi_selesai:document.getElementById("p_jam_selesai").value,nilai_pemahaman:document.getElementById("p_pemahaman").value,efektifitas_sosialisasi:document.getElementById("p_efektifitas").value,nps_ketertarikan:document.getElementById("p_nps1").value,nps_rekomendasi_program:document.getElementById("p_nps2").value,nps_rekomendasi_bpjs:document.getElementById("p_nps3").value};try{(await i().post(`member/pil/${s}/participants`,d)).data.status==="success"&&(a.reset(),document.getElementById("p_activity_id").value=s,a.querySelectorAll("output").forEach(r=>r.value=5),window.showToast("Data survei tersimpan!","success"),l())}catch(o){console.error(o),alert("Gagal simpan survei. Cek NIK dan kelengkapan skor.")}finally{n.disabled=!1,n.innerText="Simpan & Peserta Lainnya"}})}window.entryParticipant=(t,a)=>{document.getElementById("p_activity_id").value=t,window.showModal("modalParticipant")};window.showModal=t=>{document.getElementById(t).style.display="flex"};window.hideModal=t=>{document.getElementById(t).style.display="none"};
