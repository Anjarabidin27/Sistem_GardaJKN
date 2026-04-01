<x-member-layout title="BPJS Keliling - Garda JKN">
    <div style="max-width: 800px; margin: 0 auto; padding-bottom: 60px;">
        <div style="margin-bottom: 24px;">
            <h1 class="font-bold text-dark" style="font-size: 1.5rem;">Jadwal BPJS Keliling</h1>
            <p class="text-muted" style="margin-top: 4px; font-size: 0.9rem;">Temukan layanan jemput bola BPJS Kesehatan terdekat di wilayah Anda.</p>
        </div>
        
        <div id="loading" class="text-center p-4 text-muted">Memeriksa jadwal terbaru...</div>
        <div id="jadwal-container" style="display: flex; flex-direction: column; gap: 16px;"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            window.axios.get('/api/member/bpjs-keliling')
                .then(res => {
                    const data = res.data.data;
                    document.getElementById('loading').style.display = 'none';
                    const c = document.getElementById('jadwal-container');
                    
                    if(data.length === 0) {
                        c.innerHTML = `
                        <div class="empty-state card">
                            <i data-lucide="map" class="empty-icon" style="margin: 0 auto 16px;"></i>
                            <h3 class="empty-title">Belum Ada Jadwal</h3>
                            <p class="empty-text">Saat ini tidak ada kegiatan BPJS Keliling yang terdata di sistem.</p>
                        </div>`;
                        if(window.lucide) window.lucide.createIcons();
                        return;
                    }
                    
                    data.forEach(item => {
                        let locParts = [];
                        if(item.nama_desa) locParts.push(item.nama_desa);
                        if(item.kota) locParts.push(item.kota.name);
                        let locStr = locParts.length > 0 ? locParts.join(', ') : 'Lokasi menyusul';

                        let statusBadge = item.status === 'scheduled' ? '<span class="status-badge badge-info">TERJADWAL</span>' : 
                                       (item.status === 'ongoing' ? '<span class="status-badge badge-warning">SEDANG BERLANGSUNG</span>' : 
                                       '<span class="status-badge badge-success">SELESAI</span>');
                        
                        const mapJenis = {
                            'goes_to_village': 'Goes To Village (GTV)',
                            'around_city': 'Around City',
                            'goes_to_office': 'Goes To Office',
                            'institusi': 'Kunjungan Institusi',
                            'pameran': 'Pameran / Event',
                            'other': 'Lainnya'
                        };
                        const jns = mapJenis[item.jenis_kegiatan] || item.jenis_kegiatan;

                        c.innerHTML += `
                            <div class="card" style="padding: 24px; transition: 0.2s;">
                                <div class="flex justify-between items-start mb-3 border-b" style="padding-bottom:12px; border-color:var(--border);">
                                    <div>
                                        <div class="text-sm font-bold text-primary mb-1">${jns}</div>
                                        <h3 class="font-bold text-dark" style="font-size: 1.15rem;">${item.judul}</h3>
                                    </div>
                                    ${statusBadge}
                                </div>
                                
                                <div class="grid-2" style="gap: 16px;">
                                    <div>
                                        <div class="text-muted text-uppercase" style="font-size: 0.70rem; font-weight:700; margin-bottom: 4px;">WAKTU PELAKSANAAN</div>
                                        <div class="font-bold flex items-center gap-2" style="font-size:0.9rem;">
                                            <i data-lucide="calendar" style="width:16px;height:16px;"></i> 
                                            ${item.tanggal}
                                        </div>
                                        <div class="text-muted mt-1 flex items-center gap-2" style="font-size:0.85rem;">
                                            <i data-lucide="clock" style="width:16px;height:16px;"></i> 
                                            ${item.jam_mulai ? item.jam_mulai.slice(0,5) + ' s/d ' + (item.jam_selesai ? item.jam_selesai.slice(0,5) : 'Selesai') : 'Waktu menyusul'}
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <div class="text-muted text-uppercase" style="font-size: 0.70rem; font-weight:700; margin-bottom: 4px;">TITIK LOKASI</div>
                                        <div class="font-bold flex items-start gap-2" style="font-size:0.9rem; line-height: 1.3;">
                                            <i data-lucide="map-pin" style="width:16px;height:16px; flex-shrink:0; margin-top:2px;"></i> 
                                            ${locStr}
                                        </div>
                                        <div class="text-muted mt-1 ml-6" style="font-size:0.8rem; background:#f8fafc; padding:6px 10px; border-radius:6px; border:1px solid #e2e8f0;">
                                            ${item.lokasi_detail || '<i>Bawa dokumen lengkap seperti KTP/KK</i>'}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    if(window.lucide) window.lucide.createIcons();
                });
        });
    </script>
</x-member-layout>
