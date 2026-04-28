const r=["superadmin","administrator","admin","admin_wilayah"];(!localStorage.getItem("auth_token")||!r.includes(localStorage.getItem("user_role")))&&(window.location.href="/login/admin");let c=1;document.addEventListener("DOMContentLoaded",()=>{document.getElementById("date-now").innerText=new Date().toLocaleDateString("id-ID",{weekday:"long",year:"numeric",month:"long",day:"numeric"}),o()});async function o(t=1){try{const e=(await window.axios.get(`admin/audit-logs?page=${t}`)).data.data;c=t,p(e.data),l(e),lucide.createIcons()}catch(n){console.error(n),showToast("Gagal memuat log audit: "+(n.response?.data?.message||n.message),"error")}}function l(t){const n=document.getElementById("pagination");n&&(n.innerHTML=`
            <div class="text-muted" style="font-size: 0.85rem;">Menampilkan ${t.from||0}-${t.to||0} dari ${t.total} Entri</div>
            <div class="flex gap-2">
                <button class="btn btn-secondary btn-sm" id="btn-prev" onclick="fetchLogs(${t.current_page-1})" ${t.prev_page_url?"":"disabled"}>Sebelumnya</button>
                <button class="btn btn-secondary btn-sm" id="btn-next" onclick="fetchLogs(${t.current_page+1})" ${t.next_page_url?"":"disabled"}>Selanjutnya</button>
            </div>
        `)}function p(t){const n=document.getElementById("logTableBody");n.innerHTML="",t.forEach(e=>{const d=new Date(e.created_at),s=d.toLocaleDateString("id-ID",{day:"2-digit",month:"short",year:"numeric"}),i=d.toLocaleTimeString("id-ID",{hour:"2-digit",minute:"2-digit"});let a="bg-update";e.action.includes("reset")&&(a="bg-reset"),e.action.includes("delete")&&(a="bg-delete"),e.action.includes("login")&&(a="bg-login"),e.action.includes("logout")&&(a="bg-logout"),n.innerHTML+=`
                <tr>
                    <td style="white-space:nowrap;">
                        <div style="font-weight:700; color:#0f172a;">${s}</div>
                        <div style="font-size:0.75rem; color:#64748b; font-weight:500;">${i} WIB</div>
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div style="width: 24px; height: 24px; background: #f1f5f9; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 0.7rem;"><i data-lucide="user" style="width: 12px; height: 12px; color: #64748b;"></i></div>
                            <div>
                                <div style="font-weight:700; color:#334155;">${e.actor?.name||(e.actor_type==="system"?"Sistem":"Unknown")}</div>
                                <div style="font-size:0.7rem; color:#64748b; font-weight:600; text-transform: uppercase;">ID: ${e.actor_id} | ${e.actor_type.split("\\").pop()}</div>
                            </div>
                        </div>
                    </td>
                    <td><span class="action-badge ${a}">${u(e.action)}</span></td>
                    <td>
                        <div style="font-weight:700; color:#334155;">${e.entity_type}</div>
                        <div style="font-size:0.75rem; color:#64748b;">Target ID: ${e.entity_id}</div>
                    </td>
                    <td>
                        <div id="metadata-${e.id}">
                            ${m(e.changes_json)}
                        </div>
                    </td>
                </tr>
            `})}function u(t){return{login_admin:"LOGIN ADMIN",logout_admin:"LOGOUT ADMIN",login_member:"LOGIN MEMBER",logout_member:"LOGOUT MEMBER",create_member:"TAMBAH ANGGOTA",update_member_by_admin:"UPDATE ANGGOTA",delete_member:"HAPUS ANGGOTA",reset_password_by_admin:"RESET PASSWORD",update_profile:"UPDATE PROFIL",restore_member:"PULIHKAN ANGGOTA",verify_pengurus:"VERIFIKASI PENGURUS"}[t]||t.replace("_"," ").toUpperCase()}function m(t){if(!t||Object.keys(t).length===0)return'<span class="metadata-empty">Tidak ada detail perubahan</span>';const n={name:"Nama",phone:"WhatsApp",gender:"Gender",education:"Pendidikan",occupation:"Pekerjaan",province_id:"Provinsi",city_id:"Kab/Kota",district_id:"Kecamatan",address_detail:"Alamat",nik:"NIK",deleted_at:"Dihapus pada",restored_at:"Dipulihkan pada",ip:"Alamat IP",user_agent:"Perangkat"},e=s=>{if(!s)return"-";if(typeof s!="string")return s;if(/^\d{4}-\d{2}-\d{2}T/.test(s)){const a=new Date(s);if(!isNaN(a))return a.toLocaleDateString("id-ID",{day:"2-digit",month:"short",year:"numeric"})+" "+a.toLocaleTimeString("id-ID",{hour:"2-digit",minute:"2-digit"})+" WIB"}let i=s.replace(/KAB\.?\s+KABUPATEN/gi,"Kabupaten");return i=i.replace(/KOTA\s+KOTA/gi,"Kota"),i.toLowerCase().replace(/\b\w/g,a=>a.toUpperCase())};let d="";for(const[s,i]of Object.entries(t)){const a=n[s]||s;i&&typeof i=="object"&&"new"in i&&"old"in i?d+=`
                    <div class="change-item">
                        <span class="change-label">${a}</span>
                        <span class="change-separator">:</span>
                        <div class="change-values">
                            <span class="value-old">${e(i.old)}</span>
                            <span class="change-arrow">â†’</span>
                            <span class="value-new">${e(i.new)}</span>
                        </div>
                    </div>
                `:d+=`
                    <div class="change-item">
                        <span class="change-label">${a}</span>
                        <span class="change-separator">:</span>
                        <span class="value-new">${e(i)}</span>
                    </div>
                `}return d}window.fetchLogs=o;
