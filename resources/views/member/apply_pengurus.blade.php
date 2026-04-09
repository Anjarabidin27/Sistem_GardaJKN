@extends('layouts.app')

@push('styles')
    @vite(['resources/css/pages/apply_pengurus.css'])
@endpush

@section('title', 'Aplikasi Pengurus - Garda JKN')

@section('content')
<div class="apply-container">
    <div class="apply-card">
        <div class="card-header">
            <h2>Karir Pengurus</h2>
            <p>Jadilah bagian dari tim yang menggerakkan pelayanan publik JKN Nasional</p>
            
            <div class="stepper">
                <div class="step-item active" id="dot1"><div class="step-circle">1</div></div>
                <div class="step-item" id="dot2"><div class="step-circle">2</div></div>
                <div class="step-item" id="dot3"><div class="step-circle">3</div></div>
            </div>
        </div>

        <div class="card-body">
            <form action="{{ route('member.apply_pengurus') }}" method="POST" enctype="multipart/form-data" id="applyForm">
                @csrf
                
                <!-- Step 1: Interest -->
                <div class="step-content active" id="step1">
                    <div class="question-section">
                        <span class="question-text">Apakah Anda memiliki ketertarikan tinggi untuk berkontribusi sebagai Pengurus Garda JKN?</span>
                        <div class="choice-grid">
                            <button type="button" class="btn-choice" onclick="selectInterest(true)">
                                <i data-lucide="check-circle-2"></i>
                                YA, SAYA TERTARIK
                            </button>
                            <button type="button" class="btn-choice" onclick="selectInterest(false)">
                                <i data-lucide="x-circle"></i>
                                BELUM SAATNYA
                            </button>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="is_interested_pengurus" id="is_interested_pengurus" value="0">

                <!-- Step 1b: Role Interest Selection -->
                <div class="step-content" id="step1b">
                    <div class="question-section">
                        <span class="question-text">Pilih Bagian Pelayanan yang Anda Minati:</span>
                        <p class="text-muted" style="font-size: 0.8rem; margin-top: -16px; margin-bottom: 24px;">Anda dapat memilih satu atau keduanya</p>
                        
                        <input type="checkbox" name="interest_keliling" id="interest_keliling" value="1" style="display:none;">
                        <input type="checkbox" name="interest_pil" id="interest_pil" value="1" style="display:none;">

                        <div class="choice-grid" style="grid-template-columns: 1fr 1fr;">
                            <div class="role-card" onclick="toggleRole('interest_keliling')" id="card-keliling">
                                <i data-lucide="truck" class="role-icon"></i>
                                <div class="role-name">BPJS Keliling</div>
                                <div class="role-desc">Pelayanan lapangan & Admin</div>
                            </div>
                            <div class="role-card" onclick="toggleRole('interest_pil')" id="card-pil">
                                <i data-lucide="megaphone" class="role-icon"></i>
                                <div class="role-name">Penyuluhan (PIL)</div>
                                <div class="role-desc">Edukasi & Sosialisasi</div>
                            </div>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 12px; margin-top: 32px;">
                            <button type="button" class="primary-btn" onclick="validateRoleSelection()">
                                LANJUT KE PERTANYAAN BERIKUTNYA &rarr;
                            </button>
                            <button type="button" onclick="goToStep(1)" style="background: none; border: none; color: #94a3b8; font-weight: 700; cursor: pointer; font-size: 0.8rem;">&larr; KEMBALI</button>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Experience Check -->
                <div class="step-content" id="step2">
                    <div class="question-section">
                        <span class="question-text">Apakah Anda memiliki rekam jejak atau pengalaman dalam organisasi sebelumnya?</span>
                        <div class="choice-grid">
                            <button type="button" class="btn-choice" onclick="selectExperience(true)">
                                <i data-lucide="briefcase"></i>
                                SAYA MEMILIKI
                            </button>
                            <button type="button" class="btn-choice" onclick="selectExperience(false)">
                                <i data-lucide="user-plus"></i>
                                TIDAK MEMILIKI
                            </button>
                        </div>
                        <button type="button" onclick="goToStep(1)" style="margin-top: 32px; background: none; border: none; color: #94a3b8; font-weight: 700; cursor: pointer; font-size: 0.8rem;">&larr; KEMBALI KE SEBELUMNYA</button>
                    </div>
                </div>

                <input type="hidden" name="has_org_experience" id="has_org_experience" value="0">

                <!-- Step 3: Experience Form -->
                <div class="step-content" id="step3">
                    <h3 style="font-size: 1rem; font-weight: 800; color: #0f172a; margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
                        <i data-lucide="file-text" style="color: #004aad;"></i>
                        PORTFOLIO ORGANISASI
                    </h3>
                    
                    <div class="form-group">
                        <label class="form-label">Nama Lembaga / Organisasi</label>
                        <input type="text" name="org_name" class="form-control" placeholder="Contoh: BEM Universitas / Karang Taruna">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label class="form-label">Jabatan Terakhir</label>
                            <input type="text" name="org_position" class="form-control" placeholder="Contoh: Ketua / Sekretaris">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Durasi (Dalam Bulan)</label>
                            <input type="number" name="org_duration_months" class="form-control" placeholder="Contoh: 24">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Deskripsi Peran & Kontribusi</label>
                        <textarea name="org_description" class="form-control" rows="4" style="resize: none;" placeholder="Sebutkan tanggung jawab utama dan pencapaian Anda..."></textarea>
                    </div>

                    <div class="form-group" style="background: #f8fafc; padding: 20px; border-radius: 12px; border: 2px dashed #e2e8f0;">
                        <label class="form-label">Dokumen Sertifikat (Opsional)</label>
                        <input type="file" name="org_certificate" style="font-size: 0.8rem; color: #64748b;" accept=".pdf,.jpg,.png">
                        <div style="font-size: 0.7rem; color: #94a3b8; margin-top: 8px;">Maksimal file 10MB (PDF/JPG/PNG)</div>
                    </div>

                    <button type="submit" class="primary-btn">
                        KIRIM APLIKASI PENDAFTARAN
                    </button>
                    
                    <button type="button" onclick="goToStep(2)" style="width: 100%; margin-top: 16px; background: none; border: none; color: #94a3b8; font-weight: 700; cursor: pointer; font-size: 0.8rem;">KEMBALI KE SEBELUMNYA</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    @vite(['resources/js/pages/apply_pengurus.js'])
@endpush
@endsection

@push("scripts")
<script>
    window.sessionSuccess = "{{ session("success") }}";
    window.sessionError = "{{ session("error") }}";
</script>
@endpush
