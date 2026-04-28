const o=localStorage.getItem("auth_token"),n=localStorage.getItem("user_role");(!o||n!=="pengurus"&&n!=="admin")&&(window.location.href="/login");document.addEventListener("DOMContentLoaded",()=>{document.getElementById("date-now").innerText=new Date().toLocaleDateString("id-ID",{weekday:"long",year:"numeric",month:"long",day:"numeric"}),d()});async function d(e=1){try{const t=await axios.get(`admin/informations?page=${e}`);s(t.data.data.data),i(t.data.data)}catch{showToast("Gagal memuat data","error")}}function s(e){const t=document.getElementById("infoTableBody");t.innerHTML="",e.forEach(a=>{t.innerHTML+=`
                <tr>
                    <td class="ps-4">${new Date(a.created_at).toLocaleDateString()}</td>
                    <td>
                        <div class="font-weight-bold">${a.title}</div>
                        <small class="text-muted">${a.attachment_path?"Ada Lampiran":"Teks Saja"}</small>
                    </td>
                    <td><span class="badge bg-primary">${a.type.toUpperCase()}</span></td>
                    <td><span class="badge ${a.is_active?"bg-success":"bg-secondary"}">${a.is_active?"AKTIF":"DRAFT"}</span></td>
                    <td class="text-end pe-4">
                        <button class="btn btn-icon btn-light-info" onclick="openEditModal(${a.id})"><i class="bi bi-pencil"></i></button>
                    </td>
                </tr>
            `})}function i(e){const t=document.getElementById("paginationContainer");t.innerHTML=`<div class="small text-muted">Halaman ${e.current_page} dari ${e.last_page}</div>`}
