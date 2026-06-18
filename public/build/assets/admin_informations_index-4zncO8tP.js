let f=1,p="",g=localStorage.getItem("info_view_mode")||"list",m="",u=[];document.addEventListener("DOMContentLoaded",()=>{const e=document.getElementById("grid-view"),t=document.getElementById("list-view"),n=document.getElementById("toggle-grid"),i=document.getElementById("toggle-list"),a=document.getElementById("status-chips");n&&n.addEventListener("click",()=>o("grid")),i&&i.addEventListener("click",()=>o("list")),a&&a.querySelectorAll(".chip").forEach(d=>{d.addEventListener("click",()=>{m=d.dataset.type,window.fetchData(1)})});function o(d){g=d,localStorage.setItem("info_view_mode",d),d==="grid"?(e&&(e.style.display="grid"),t&&(t.style.display="none"),n&&n.classList.add("active"),i&&i.classList.remove("active")):(e&&(e.style.display="none"),t&&(t.style.display="block"),n&&n.classList.remove("active"),i&&i.classList.add("active")),h()}const s=document.getElementById("btnOpenAddModal");s&&s.addEventListener("click",()=>{window.openAddModal()});const c=document.getElementById("infoSearchInput");c&&c.addEventListener("input",d=>{window.handleSearch(d.target.value)});const r=document.getElementById("infoForm");r&&r.addEventListener("submit",d=>{d.preventDefault(),window.submitForm(d)});const l=document.getElementById("type");l&&l.addEventListener("change",()=>window.toggleAttachmentField()),o(g),window.fetchData()});window.fetchData=async function(e=1,t=p){f=e,p=t;const n=document.getElementById("status-chips");n&&n.querySelectorAll(".chip").forEach(i=>{i.classList.toggle("active",i.dataset.type===m)});try{let i=`admin/informations?page=${e}&search=${t}`;m&&(i+=`&type=${m}`);const a=await window.axios.get(i);u=a.data.data.data,h(),v(a.data.data)}catch(i){console.error("Fetch Error:",i),typeof showToast<"u"&&showToast("Gagal memuat data","error")}};let y;window.handleSearch=function(e){clearTimeout(y),y=setTimeout(()=>{p=e,window.fetchData(1,e)},500)};function h(){g==="grid"?T():b()}function b(){const e=document.getElementById("infoTableBody");if(e){if(e.innerHTML="",u.length===0){e.innerHTML='<tr><td colspan="5" class="text-center py-5 text-muted">Tidak ada data informasi ditemukan.</td></tr>';return}u.forEach(t=>{const n=`
            <tr class="transition-all">
                <td class="ps-4">
                    <div class="text-dark font-weight-500">${w(t.created_at)}</div>
                    <div class="small text-muted">${E(t.created_at)} WIB</div>
                </td>
                <td>
                    <div class="font-weight-bold text-dark mb-0" style="font-size: 0.95rem;">${t.title}</div>
                    ${t.attachment_path?`<div class="mt-1"><span class="badge bg-light text-primary border border-primary-subtle py-1 ps-1 pe-2" style="font-size: 0.7rem; font-weight: 500;">
                            <i class="bi bi-paperclip me-1"></i>${t.type==="pdf"?"Dokumen PDF":"Foto Lampiran"}
                        </span></div>`:'<small class="text-muted italic">Tidak ada lampiran</small>'}
                </td>
                <td>
                    <span class="badge ${I(t.type)} d-inline-flex align-items-center gap-1 py-2 px-2" style="font-size: 0.75rem; border-radius: 6px;">
                        ${x(t.type)} ${t.type.toUpperCase()}
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
        `;e.insertAdjacentHTML("beforeend",n)}),typeof lucide<"u"&&lucide.createIcons()}}function v(e){const t=document.getElementById("paginationContainer");if(!t)return;if(e.last_page<=1){t.innerHTML="";return}let n=`
        <div class="d-flex justify-content-between align-items-center w-100">
            <div class="text-muted small">Menampilkan ${e.from||0} sampai ${e.to||0} dari ${e.total} entri</div>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item ${e.current_page===1?"disabled":""}">
                        <a class="page-link" href="javascript:void(0)" onclick="window.fetchData(${e.current_page-1})">Prev</a>
                    </li>
    `;for(let i=1;i<=e.last_page;i++)i===1||i===e.last_page||i>=e.current_page-1&&i<=e.current_page+1?n+=`
                <li class="page-item ${e.current_page===i?"active":""}">
                    <a class="page-link" href="javascript:void(0)" onclick="window.fetchData(${i})">${i}</a>
                </li>
            `:(i===e.current_page-2||i===e.current_page+2)&&(n+='<li class="page-item disabled"><span class="page-link">...</span></li>');n+=`
                    <li class="page-item ${e.current_page===e.last_page?"disabled":""}">
                        <a class="page-link" href="javascript:void(0)" onclick="window.fetchData(${e.current_page+1})">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    `,t.innerHTML=n}window.toggleAttachmentField=function(){const e=document.getElementById("type");if(!e)return;const t=e.value,n=document.getElementById("attachmentField"),i=document.getElementById("attachmentLabel"),a=document.getElementById("attachmentHint"),o=document.getElementById("attachment");!n||!i||!a||!o||(t==="text"?(n.style.display="none",o.required=!1):(n.style.display="block",i.innerText=t==="image"?"Lampiran Foto/Gambar":"Lampiran Dokumen PDF",a.innerText=t==="image"?"Format: JPG, PNG. Max 5MB":"Format: PDF. Max 5MB",o.accept=t==="image"?"image/*":".pdf"))};window.openAddModal=function(){console.log("Opening Add Modal...");const e=document.getElementById("infoForm"),t=document.getElementById("infoId"),n=document.getElementById("modalTitle"),i=document.getElementById("currentAttachment"),a=document.getElementById("infoModal");t&&(t.value=""),e&&e.reset(),n&&(n.innerText="Tambah Informasi"),i&&(i.innerHTML=""),a&&(a.style.display="flex",a.classList.remove("hide")),window.toggleAttachmentField()};window.openEditModal=async function(e){try{const n=(await window.axios.get(`admin/informations/${e}`)).data.data,i=document.getElementById("infoId"),a=document.getElementById("title"),o=document.getElementById("type"),s=document.getElementById("content"),c=document.getElementById("is_active"),r=document.getElementById("modalTitle"),l=document.getElementById("currentAttachment"),d=document.getElementById("infoModal");i&&(i.value=n.id),a&&(a.value=n.title),o&&(o.value=n.type),s&&(s.value=n.content||""),c&&(c.checked=!!n.is_active),window.toggleAttachmentField(),n.attachment_url&&l?l.innerHTML=`
                <div class="mt-2 small text-muted">
                    File saat ini: <a href="${n.attachment_url}" target="_blank">Lihat File</a>
                </div>
            `:l&&(l.innerHTML=""),r&&(r.innerText="Edit Informasi"),d&&(d.style.display="flex",d.classList.remove("hide"))}catch(t){console.error("Edit Load Error:",t),typeof showToast<"u"&&showToast("Gagal memuat detail","error")}};window.submitForm=async function(e){e&&e.preventDefault();const t=document.getElementById("infoId")?.value,n=new FormData;n.append("title",document.getElementById("title")?.value||""),n.append("type",document.getElementById("type")?.value||"text"),n.append("content",document.getElementById("content")?.value||""),n.append("is_active",document.getElementById("is_active")?.checked?1:0),t&&n.append("_method","PUT");const i=document.getElementById("attachment");i&&i.files[0]&&n.append("attachment",i.files[0]);const a=document.getElementById("btnSubmit");if(!a)return;const o=a.innerText;a.disabled=!0,a.innerText="Menyimpan...";try{const s=t?`admin/informations/${t}`:"admin/informations";await window.axios.post(s,n,{headers:{"Content-Type":"multipart/form-data"}}),typeof showToast<"u"&&showToast(t?"Informasi berhasil diupdate":"Informasi berhasil dibuat","success"),window.closeModal(),window.fetchData(f)}catch(s){console.error("Submit Error:",s);const c=s.response?.data?.message||s.message||"Terjadi kesalahan";typeof showToast<"u"&&showToast(c,"error")}finally{a.disabled=!1,a.innerText=o}};window.toggleStatus=async function(e){try{await window.axios.patch(`admin/informations/${e}/toggle-status`),typeof showToast<"u"&&showToast("Status berhasil diubah")}catch{typeof showToast<"u"&&showToast("Gagal mengubah status","error"),window.fetchData(f)}};window.deleteInfo=async function(e){if(typeof showConfirm>"u"){if(!confirm("Hapus Informasi?"))return}else if(!await showConfirm("Hapus Informasi?","Informasi ini akan dihapus secara permanen. Lanjutkan?",{type:"danger",confirmText:"Ya, Hapus",icon:"trash-2"}))return;try{await window.axios.delete(`admin/informations/${e}`),typeof showToast<"u"&&showToast("Informasi berhasil dihapus"),window.fetchData(f)}catch{typeof showToast<"u"&&showToast("Gagal menghapus informasi","error")}};window.closeModal=function(){const e=document.getElementById("infoModal");e&&(e.classList.add("hide"),setTimeout(()=>{e.style.display="none"},300))};function x(e){switch(e){case"text":return'<i class="bi bi-chat-left-text"></i>';case"image":return'<i class="bi bi-image"></i>';case"pdf":return'<i class="bi bi-file-earmark-pdf"></i>';default:return'<i class="bi bi-info-circle"></i>'}}function I(e){switch(e){case"text":return"bg-primary-subtle text-primary border border-primary";case"image":return"bg-success-subtle text-success border border-success";case"pdf":return"bg-danger-subtle text-danger border border-danger";default:return"bg-secondary-subtle text-secondary border border-secondary"}}function w(e){return new Date(e).toLocaleDateString("id-ID",{day:"numeric",month:"short",year:"numeric"})}function E(e){return new Date(e).toLocaleTimeString("id-ID",{hour:"2-digit",minute:"2-digit"})}function T(){const e=document.getElementById("grid-view");if(e){if(e.innerHTML="",u.length===0){e.innerHTML='<div class="text-center p-8 text-muted" style="grid-column: 1/-1;">Tidak ada data informasi ditemukan.</div>';return}u.forEach(t=>{let n="";t.type==="image"&&t.attachment_url?n=`<img src="${t.attachment_url}" alt="Preview">`:t.type==="pdf"?n='<i data-lucide="file-text" style="width:48px; height:48px;"></i>':n='<i data-lucide="align-left" style="width:48px; height:48px;"></i>';const i=document.createElement("div");i.className="drive-card",i.innerHTML=`
            <div class="card-dots" onclick="toggleContextMenu(event, ${t.id})">
                <i data-lucide="more-vertical" style="width:20px; height:20px;"></i>
            </div>

            <div class="context-menu" id="ctx-${t.id}">
                <div class="menu-item" onclick="window.openEditModal(${t.id})">
                    <i data-lucide="edit-2" style="width:14px;"></i> Edit Informasi
                </div>
                <div class="menu-item" onclick="window.toggleStatus(${t.id})">
                    <i data-lucide="${t.is_active?"eye-off":"eye"}" style="width:14px;"></i> ${t.is_active?"Sembunyikan":"Tampilkan"}
                </div>
                <div class="menu-item danger" onclick="window.deleteInfo(${t.id})">
                    <i data-lucide="trash-2" style="width:14px;"></i> Hapus Permanen
                </div>
            </div>

            <div class="info-preview">
                ${n}
            </div>

            <div style="padding-right: 24px;">
                <div style="font-size: 0.95rem; font-weight: 800; color: #0f172a; margin-bottom: 4px; line-height: 1.2; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">${t.title}</div>
                <div class="flex items-center gap-2 mb-3">
                    <span style="font-size: 0.7rem; color: #64748b; font-weight: 600;">${w(t.created_at)}</span>
                    <span style="width: 4px; height: 4px; background: #cbd5e1; border-radius: 50%;"></span>
                    <span style="font-size: 0.7rem; color: #64748b; font-weight: 600;">${t.type.toUpperCase()}</span>
                </div>
            </div>

            <div class="flex justify-between items-center mt-auto pt-2 border-t border-slate-100">
                <span style="background: ${t.is_active?"#10b981":"#64748b"}15; color: ${t.is_active?"#10b981":"#64748b"}; padding: 4px 10px; border-radius: 20px; font-size: 0.65rem; font-weight: 800; text-transform: uppercase;">
                    ${t.is_active?"PUBLIK":"DRAFT"}
                </span>
                ${t.attachment_path?'<i data-lucide="paperclip" style="width:14px; color: #94a3b8;"></i>':""}
            </div>
        `,e.appendChild(i)}),typeof lucide<"u"&&lucide.createIcons()}}window.toggleContextMenu=(e,t)=>{e.stopPropagation();const n=document.querySelectorAll(".context-menu"),i=document.getElementById(`ctx-${t}`),a=i.style.display==="block";n.forEach(o=>o.style.display="none"),a||(i.style.display="block")};document.addEventListener("click",()=>{document.querySelectorAll(".context-menu").forEach(e=>e.style.display="none")});
