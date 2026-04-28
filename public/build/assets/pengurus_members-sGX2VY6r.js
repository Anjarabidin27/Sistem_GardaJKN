const r=localStorage.getItem("auth_token"),i=localStorage.getItem("user_role"),s=["admin","admin_wilayah","petugas_pil","petugas_keliling","pengurus"];(!r||!s.includes(i))&&(window.location.href="/login");document.addEventListener("DOMContentLoaded",()=>{const t=document.getElementById("memberSearch");t&&t.addEventListener("input",n=>{const e=n.target.value.toLowerCase();document.querySelectorAll("#memberTableBody tr").forEach(a=>{const o=a.innerText.toLowerCase();a.style.display=o.includes(e)?"":"none"})}),d()});async function d(t=1){try{const e=(await axios.get(`admin/members?page=${t}`)).data.data;l(e.data),c(e)}catch{showToast("Gagal memuat data anggota","error")}}function l(t){const n=document.getElementById("memberTableBody");n&&(n.innerHTML="",t.forEach(e=>{n.innerHTML+=`
                <tr>
                    <td>
                        <div class="v-flex v-items-center v-gap-3">
                            <div style="width: 32px; height: 32px; background: var(--v-gray-50); border: 1px solid var(--v-gray-100); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 0.7rem; color: var(--v-gray-500);">
                                ${e.name.substring(0,1)}
                            </div>
                            <div>
                                <span style="font-weight: 800; color: var(--v-black); display: block;">${e.name}</span>
                                <span style="font-size: 10px; color: var(--v-gray-400); font-weight: 700;">${e.nik}</span>
                            </div>
                        </div>
                    </td>
                    <td style="font-weight: 600; font-size: 0.8rem; color: var(--v-gray-500);">${e.phone}</td>
                    <td>
                        <span style="font-weight: 700; color: var(--v-black); display: block;">${e.city?.name||"-"}</span>
                        <span style="font-size: 9px; color: var(--v-gray-400); font-weight: 700;">${e.province?.name||"-"}</span>
                    </td>
                    <td><span style="padding: 2px 8px; border-radius: 9999px; font-size: 9px; font-weight: 900; background: rgba(37, 99, 235, 0.05); color: var(--v-blue-600); border: 1px solid rgba(37, 99, 235, 0.1);">${e.occupation||"UMUM"}</span></td>
                    <td style="text-align: right;">
                        <span style="color: var(--v-emerald-500); font-weight: 900; font-size: 10px;">AKTIF</span>
                    </td>
                </tr>
            `}))}function c(t){const n=document.getElementById("pagination");n.innerHTML="";for(let e=1;e<=t.last_page;e++)n.innerHTML+=`<button onclick="fetchMembers(${e})" style="margin: 0 4px; padding: 4px 10px; border: 1px solid #e2e8f0; background: ${t.current_page===e?"#004aad":"white"}; color: ${t.current_page===e?"white":"#334155"}; border-radius: 4px; cursor: pointer;">${e}</button>`}
