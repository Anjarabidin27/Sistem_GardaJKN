let r=1,m="";console.log("Admin Informations JS Loaded");document.addEventListener("DOMContentLoaded",()=>{console.log("Admin Informations DOM Ready");const e=document.getElementById("btnOpenAddModal");e&&e.addEventListener("click",()=>{console.log("Add Modal Button Clicked"),window.openAddModal()});const n=document.getElementById("infoSearchInput");n&&n.addEventListener("input",i=>{window.handleSearch(i.target.value)});const t=document.getElementById("infoForm");t&&t.addEventListener("submit",i=>{i.preventDefault(),window.submitForm(i)});const a=document.getElementById("type");a&&a.addEventListener("change",()=>window.toggleAttachmentField()),window.fetchData()});window.fetchData=async function(e=1,n=m){r=e,m=n;try{const t=await window.axios.get(`admin/informations?page=${e}&search=${n}`);p(t.data.data.data),g(t.data.data)}catch(t){console.error("Fetch Error:",t),typeof showToast<"u"&&showToast("Gagal memuat data","error")}};let f;window.handleSearch=function(e){clearTimeout(f),f=setTimeout(()=>{m=e,window.fetchData(1,e)},500)};function p(e){const n=document.getElementById("infoTableBody");if(n){if(n.innerHTML="",e.length===0){n.innerHTML='<tr><td colspan="5" class="text-center py-5 text-muted">Tidak ada data informasi ditemukan.</td></tr>';return}e.forEach(t=>{const a=`
            <tr class="transition-all">
                <td class="ps-4">
                    <div class="text-dark font-weight-500">${w(t.created_at)}</div>
                    <div class="small text-muted">${b(t.created_at)} WIB</div>
                </td>
                <td>
                    <div class="font-weight-bold text-dark mb-0" style="font-size: 0.95rem;">${t.title}</div>
                    ${t.attachment_path?`<div class="mt-1"><span class="badge bg-light text-primary border border-primary-subtle py-1 ps-1 pe-2" style="font-size: 0.7rem; font-weight: 500;">
                            <i class="bi bi-paperclip me-1"></i>${t.type==="pdf"?"Dokumen PDF":"Foto Lampiran"}
                        </span></div>`:'<small class="text-muted italic">Tidak ada lampiran</small>'}
                </td>
                <td>
                    <span class="badge ${y(t.type)} d-inline-flex align-items-center gap-1 py-2 px-2" style="font-size: 0.75rem; border-radius: 6px;">
                        ${h(t.type)} ${t.type.toUpperCase()}
                    </span>
                </td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <div class="form-check form-switch m-0">
                            <input class="form-check-input cursor-pointer" type="checkbox" ${t.is_active?"checked":""} onchange="window.toggleStatus(${t.id})">
                            <label class="small ${t.is_active?"text-success":"text-muted"} mb-0" style="font-weight: 600; font-size: 0.7rem;">
                                ${t.is_active?"PUBLIK":"DRAFT"}
                            </label>
                        </div>
                    </div>
                </td>
                <td class="text-end pe-4">
                    <div class="d-flex justify-content-end gap-1">
                        <div class="btn-actions-group">
                            <button class="btn-icon-square btn-edit" onclick="window.openEditModal(${t.id})" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button class="btn-icon-square btn-delete" onclick="window.deleteInfo(${t.id})" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </td>
            </tr>
        `;n.insertAdjacentHTML("beforeend",a)}),typeof lucide<"u"&&lucide.createIcons()}}function g(e){const n=document.getElementById("paginationContainer");if(!n)return;if(e.last_page<=1){n.innerHTML="";return}let t=`
        <div class="d-flex justify-content-between align-items-center w-100">
            <div class="text-muted small">Menampilkan ${e.from||0} sampai ${e.to||0} dari ${e.total} entri</div>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item ${e.current_page===1?"disabled":""}">
                        <a class="page-link" href="javascript:void(0)" onclick="window.fetchData(${e.current_page-1})">Prev</a>
                    </li>
    `;for(let a=1;a<=e.last_page;a++)a===1||a===e.last_page||a>=e.current_page-1&&a<=e.current_page+1?t+=`
                <li class="page-item ${e.current_page===a?"active":""}">
                    <a class="page-link" href="javascript:void(0)" onclick="window.fetchData(${a})">${a}</a>
                </li>
            `:(a===e.current_page-2||a===e.current_page+2)&&(t+='<li class="page-item disabled"><span class="page-link">...</span></li>');t+=`
                    <li class="page-item ${e.current_page===e.last_page?"disabled":""}">
                        <a class="page-link" href="javascript:void(0)" onclick="window.fetchData(${e.current_page+1})">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    `,n.innerHTML=t}window.toggleAttachmentField=function(){const e=document.getElementById("type");if(!e)return;const n=e.value,t=document.getElementById("attachmentField"),a=document.getElementById("attachmentLabel"),i=document.getElementById("attachmentHint"),o=document.getElementById("attachment");!t||!a||!i||!o||(n==="text"?(t.style.display="none",o.required=!1):(t.style.display="block",a.innerText=n==="image"?"Lampiran Foto/Gambar":"Lampiran Dokumen PDF",i.innerText=n==="image"?"Format: JPG, PNG. Max 5MB":"Format: PDF. Max 5MB",o.accept=n==="image"?"image/*":".pdf"))};window.openAddModal=function(){console.log("Opening Add Modal...");const e=document.getElementById("infoForm"),n=document.getElementById("infoId"),t=document.getElementById("modalTitle"),a=document.getElementById("currentAttachment"),i=document.getElementById("infoModal");n&&(n.value=""),e&&e.reset(),t&&(t.innerText="Tambah Informasi"),a&&(a.innerHTML=""),i&&(i.style.display="flex",i.classList.remove("hide")),window.toggleAttachmentField()};window.openEditModal=async function(e){try{const t=(await window.axios.get(`admin/informations/${e}`)).data.data,a=document.getElementById("infoId"),i=document.getElementById("title"),o=document.getElementById("type"),d=document.getElementById("content"),s=document.getElementById("is_active"),u=document.getElementById("modalTitle"),c=document.getElementById("currentAttachment"),l=document.getElementById("infoModal");a&&(a.value=t.id),i&&(i.value=t.title),o&&(o.value=t.type),d&&(d.value=t.content||""),s&&(s.checked=!!t.is_active),window.toggleAttachmentField(),t.attachment_url&&c?c.innerHTML=`
                <div class="mt-2 small text-muted">
                    File saat ini: <a href="${t.attachment_url}" target="_blank">Lihat File</a>
                </div>
            `:c&&(c.innerHTML=""),u&&(u.innerText="Edit Informasi"),l&&(l.style.display="flex",l.classList.remove("hide"))}catch(n){console.error("Edit Load Error:",n),typeof showToast<"u"&&showToast("Gagal memuat detail","error")}};window.submitForm=async function(e){e&&e.preventDefault();const n=document.getElementById("infoId")?.value,t=new FormData;t.append("title",document.getElementById("title")?.value||""),t.append("type",document.getElementById("type")?.value||"text"),t.append("content",document.getElementById("content")?.value||""),t.append("is_active",document.getElementById("is_active")?.checked?1:0),n&&t.append("_method","PUT");const a=document.getElementById("attachment");a&&a.files[0]&&t.append("attachment",a.files[0]);const i=document.getElementById("btnSubmit");if(!i)return;const o=i.innerText;i.disabled=!0,i.innerText="Menyimpan...";try{const d=n?`admin/informations/${n}`:"admin/informations";await window.axios.post(d,t,{headers:{"Content-Type":"multipart/form-data"}}),typeof showToast<"u"&&showToast(n?"Informasi berhasil diupdate":"Informasi berhasil dibuat","success"),window.closeModal(),window.fetchData(r)}catch(d){console.error("Submit Error:",d);const s=d.response?.data?.message||d.message||"Terjadi kesalahan";typeof showToast<"u"&&showToast(s,"error")}finally{i.disabled=!1,i.innerText=o}};window.toggleStatus=async function(e){try{await window.axios.patch(`admin/informations/${e}/toggle-status`),typeof showToast<"u"&&showToast("Status berhasil diubah")}catch{typeof showToast<"u"&&showToast("Gagal mengubah status","error"),window.fetchData(r)}};window.deleteInfo=async function(e){if(typeof showConfirm>"u"){if(!confirm("Hapus Informasi?"))return}else if(!await showConfirm("Hapus Informasi?","Informasi ini akan dihapus secara permanen. Lanjutkan?",{type:"danger",confirmText:"Ya, Hapus",icon:"trash-2"}))return;try{await window.axios.delete(`admin/informations/${e}`),typeof showToast<"u"&&showToast("Informasi berhasil dihapus"),window.fetchData(r)}catch{typeof showToast<"u"&&showToast("Gagal menghapus informasi","error")}};window.closeModal=function(){const e=document.getElementById("infoModal");e&&(e.classList.add("hide"),setTimeout(()=>{e.style.display="none"},300))};function h(e){switch(e){case"text":return'<i class="bi bi-chat-left-text"></i>';case"image":return'<i class="bi bi-image"></i>';case"pdf":return'<i class="bi bi-file-earmark-pdf"></i>';default:return'<i class="bi bi-info-circle"></i>'}}function y(e){switch(e){case"text":return"bg-primary-subtle text-primary border border-primary";case"image":return"bg-success-subtle text-success border border-success";case"pdf":return"bg-danger-subtle text-danger border border-danger";default:return"bg-secondary-subtle text-secondary border border-secondary"}}function w(e){return new Date(e).toLocaleDateString("id-ID",{day:"numeric",month:"short",year:"numeric"})}function b(e){return new Date(e).toLocaleTimeString("id-ID",{hour:"2-digit",minute:"2-digit"})}
