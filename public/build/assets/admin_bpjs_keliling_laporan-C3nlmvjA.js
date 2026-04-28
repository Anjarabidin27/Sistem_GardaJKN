document.addEventListener("DOMContentLoaded",()=>{if(!localStorage.getItem("auth_token")){window.location.href="/login/admin";return}const u=document.getElementById("filterForm"),l=document.getElementById("kegiatan_id"),g=document.getElementById("search_peserta"),m=document.getElementById("status_filter"),d=document.getElementById("laporanTableBody"),y=document.getElementById("table-container"),h=document.getElementById("stats-container"),w=document.getElementById("initial-state");let r=[];function f(){window.axios.get("admin/bpjs-keliling").then(e=>{const a=e.data.data;l.innerHTML='<option value="">Pilih Kegiatan...</option>',a.forEach(n=>{const t=new Date(n.tanggal).toLocaleDateString("id-ID",{day:"numeric",month:"short"});l.innerHTML+=`<option value="${n.id}">${t} - ${n.judul}</option>`})}).catch(e=>{l.innerHTML='<option value="">Gagal memuat daftar.</option>',console.error(e)})}u.addEventListener("submit",e=>{e.preventDefault();const a=l.value;if(!a)return;const n=u.querySelector('button[type="submit"]');n.disabled=!0,n.innerText="Memuat...",window.axios.get(`admin/bpjs-keliling/${a}/participants`).then(t=>{r=t.data.data,c(),w.style.display="none",y.style.display="block",h.style.display="grid"}).catch(t=>{window.showToast("Gagal mengambil data laporan.","error")}).finally(()=>{n.disabled=!1,n.innerHTML='<i data-lucide="search"></i> Tampilkan',window.lucide&&window.lucide.createIcons()})});function c(){const e=g.value.toLowerCase(),a=m.value,n=r.filter(t=>{const o=t.nik.toLowerCase().includes(e)||t.jenis_layanan.toLowerCase().includes(e)||t.transaksi_layanan&&t.transaksi_layanan.toLowerCase().includes(e),s=a?t.status===a:!0;return o&&s});if(d.innerHTML="",n.length===0){d.innerHTML='<tr><td colspan="5" class="text-center p-8 text-muted">Data tidak ditemukan.</td></tr>',p(n);return}n.forEach(t=>{const o=document.createElement("tr"),s=t.status==="Berhasil"?"#10B981":"#EF4444",i=t.suara_pelanggan==="Puas"?"#3B82F6":"#EF4444";o.innerHTML=`
                <td style="padding: 12px 16px; border-bottom: 1px solid #F1F5F9;">
                    <div style="font-weight:700; color: #0F172A; font-size: 0.85rem;">${t.jam_mulai}</div>
                    <div style="font-size: 0.7rem; color: #64748B;">Selesai: ${t.jam_selesai}</div>
                </td>
                <td style="padding: 12px 16px; border-bottom: 1px solid #F1F5F9;">
                    <div style="font-weight:700; color: #0F172A; font-size: 0.85rem;">${t.nik}</div>
                    <div style="font-size: 0.7rem; color: #64748B;">${t.segmen_peserta}</div>
                </td>
                <td style="padding: 12px 16px; border-bottom: 1px solid #F1F5F9;">
                    <div style="font-weight:700; color: #3B82F6; font-size: 0.85rem;">${t.jenis_layanan}</div>
                    <div style="font-size: 0.7rem; color: #64748B;">${t.transaksi_layanan||"-"}</div>
                </td>
                <td style="padding: 12px 16px; border-bottom: 1px solid #F1F5F9;">
                    <div style="display: flex; align-items: center; gap: 6px; font-weight:700; color:${i}; font-size: 0.8rem;">
                        <i data-lucide="${t.suara_pelanggan==="Puas"?"smile":"frown"}" style="width:14px; height:14px;"></i>
                        ${t.suara_pelanggan||"-"}
                    </div>
                </td>
                <td style="padding: 12px 16px; border-bottom: 1px solid #F1F5F9; text-align: center;">
                    <span style="display: inline-block; padding: 4px 12px; border-radius: 20px; background: ${s}15; color: ${s}; font-size: 0.7rem; font-weight: 800; text-transform: uppercase;">
                        ${t.status}
                    </span>
                </td>
            `,d.appendChild(o)}),p(n),window.lucide&&window.lucide.createIcons()}document.querySelectorAll(".sb-folding-header").forEach(e=>{e.addEventListener("click",()=>{e.parentElement.classList.toggle("active")})});function p(e){const a=e.length,n=e.filter(i=>i.status==="Berhasil").length,t=e.filter(i=>i.status==="Tidak Berhasil").length,o=e.filter(i=>i.suara_pelanggan==="Puas").length,s=a>0?Math.round(o/a*100):0;document.getElementById("stat-total").innerText=a,document.getElementById("stat-berhasil").innerText=n,document.getElementById("stat-gagal").innerText=t,document.getElementById("stat-puas").innerText=s+"%"}window.exportExcel=()=>{if(r.length===0){window.showToast("Tidak ada data untuk di-export.","warning");return}const e=r.map(t=>({Waktu:`${t.jam_mulai} - ${t.jam_selesai}`,NIK:t.nik,Segmen:t.segmen_peserta,"Jenis Layanan":t.jenis_layanan,Transaksi:t.transaksi_layanan||"-",Status:t.status,"Suara Pelanggan":t.suara_pelanggan||"-"})),a=XLSX.utils.json_to_sheet(e),n=XLSX.utils.book_new();XLSX.utils.book_append_sheet(n,a,"Laporan Peserta"),XLSX.writeFile(n,`Laporan_BPJS_Keliling_${new Date().toISOString().slice(0,10)}.xlsx`)},window.printReport=()=>{window.print()},g.addEventListener("input",c),m.addEventListener("change",c),f()});
