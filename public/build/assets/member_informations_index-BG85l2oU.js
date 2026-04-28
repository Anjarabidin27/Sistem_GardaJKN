document.addEventListener("DOMContentLoaded",()=>{lucide.createIcons(),l()});let n=[];async function l(){try{n=(await axios.get("member/informations")).data.data,r(n)}catch(t){console.error(t),document.getElementById("infoList").innerHTML=`
                <div class="col-12 text-center py-5">
                    <div class="text-danger mb-3">
                        <i data-lucide="alert-circle" style="width: 48px; height: 48px;"></i>
                    </div>
                    <h5>Gagal Memuat Informasi</h5>
                    <p class="text-muted">Terjadi kesalahan teknis. Silakan coba login ulang.</p>
                </div>
            `,lucide.createIcons()}}function r(t){const i=document.getElementById("infoList");if(t.length===0){i.innerHTML=`
                <div class="col-12 text-center py-5">
                    <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-state-2130362-1800926.png" style="width: 200px; opacity: 0.5;">
                    <h5 class="mt-3 text-muted">Belum ada informasi terbaru</h5>
                </div>
            `;return}i.innerHTML="",t.forEach(e=>{let a="";e.type==="image"&&e.attachment_url?a=`
                    <div class="position-relative overflow-hidden" style="height: 180px;">
                        <img src="${e.attachment_url}" class="w-100 h-100 card-img-preview" style="object-fit: cover; object-position: center;">
                        <div class="position-absolute top-0 end-0 p-3">
                            <span class="badge bg-dark bg-opacity-50 blur-sm text-white border-0 py-1 px-2" style="font-size: 0.65rem; backdrop-filter: blur(4px);">
                                <i class="bi bi-image me-1"></i> FOTO
                            </span>
                        </div>
                    </div>
                `:e.type==="pdf"&&e.attachment_url&&(a=`
                    <div class="d-flex align-items-center justify-content-center bg-light border-bottom-light" style="height: 180px;">
                        <div class="text-center p-4">
                            <div class="icon-box bg-danger bg-opacity-10 text-danger rounded-circle p-3 mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                                <i class="bi bi-file-earmark-pdf fs-1"></i>
                            </div>
                            <div class="small text-muted font-weight-bold letter-spacing-1">DOKUMEN PDF</div>
                        </div>
                    </div>
                `);const s=`
                <div class="col-md-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm hover-up transition-all overflow-hidden" onclick="showDetail(${e.id})" style="cursor: pointer; border-radius: 16px;">
                        ${a}
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <small class="text-muted font-weight-500">${c(e.created_at)}</small>
                            </div>
                            <h4 class="h5 font-weight-bold text-dark mb-3 line-clamp-2" style="line-height: 1.4;">${e.title}</h4>
                            <p class="text-muted small mb-0 line-clamp-3" style="line-height: 1.6;">
                                ${e.content||"Lihat detail untuk informasi selengkapnya."}
                            </p>
                        </div>
                        <div class="card-footer bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center border-top-light">
                            <span class="text-primary small font-weight-bold">Baca Selengkapnya</span>
                            <i class="bi bi-chevron-right text-primary small"></i>
                        </div>
                    </div>
                </div>
            `;i.insertAdjacentHTML("beforeend",s)}),lucide.createIcons()}function c(t){return new Date(t).toLocaleDateString("id-ID",{day:"numeric",month:"long",year:"numeric"})}
