<x-admin-layout title="Persetujuan Pengurus - Admin Panel">
    <div class="table-card">
        <div class="table-header">
            <div>
                <h2 class="modal-title">Data Permohonan Masuk</h2>
                <p class="text-muted" style="font-size: 0.85rem; margin-top: 4px;">Daftar anggota yang mengajukan upgrade menjadi pengurus operasional.</p>
            </div>
            <div class="status-badge badge-info" style="font-size: 0.8rem; padding: 6px 16px;">
                {{ count($applicants) }} Permohonan Pending
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Biodata Calon</th>
                        <th>Kualifikasi Org.</th>
                        <th>Portfolio & Sertifikat</th>
                        <th class="text-right">Opsi Verifikasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applicants as $app)
                        <tr>
                            <td>
                                <div class="flex flex-col gap-2">
                                    <div class="font-bold text-dark" style="font-size:0.95rem;">{{ $app->name }}</div>
                                    <div class="text-muted" style="font-size:0.75rem; font-weight:600;">NIK: {{ $app->nik }}</div>
                                    <div class="flex gap-2" style="margin-top: 4px; display: flex; gap: 4px; flex-wrap: wrap;">
                                        @if($app->interest_pil)
                                            <span style="background: rgba(59, 130, 246, 0.1); color: #3B82F6; font-size: 10px; font-weight: 800; padding: 2px 8px; border-radius: 4px; border: 1px solid rgba(59, 130, 246, 0.2); text-transform: uppercase;">PENYULUHAN (PIL)</span>
                                        @endif
                                        @if($app->interest_keliling)
                                            <span style="background: rgba(16, 185, 129, 0.1); color: #10B981; font-size: 10px; font-weight: 800; padding: 2px 8px; border-radius: 4px; border: 1px solid rgba(16, 185, 129, 0.2); text-transform: uppercase;">BPJS KELILING</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($app->has_org_experience)
                                    <span class="status-badge badge-success">Berpengalaman</span>
                                    <div class="mt-4" style="margin-top: 8px;">
                                        <div class="font-bold text-dark" style="font-size: 0.85rem;">{{ $app->org_name }}</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">{{ $app->org_position }} • {{ $app->org_duration_months }} Bulan</div>
                                    </div>
                                @else
                                    <span class="status-badge badge-muted">Entry Level</span>
                                    <div class="text-muted" style="font-size: 0.75rem; margin-top: 6px;">Tanpa pengalaman sebelumnya</div>
                                @endif
                            </td>
                            <td style="max-width: 320px;">
                                @if($app->has_org_experience)
                                    <p class="text-muted" style="font-size: 0.8rem; line-height: 1.5; font-style: italic; margin-bottom:8px;">
                                        "{{ Str::limit($app->org_description, 100) }}"
                                    </p>
                                    @if($app->org_certificate_path)
                                        <a href="{{ asset('storage/' . $app->org_certificate_path) }}" target="_blank" class="status-badge badge-info" style="text-decoration: none; font-size: 0.7rem;">
                                            <i data-lucide="external-link" style="width: 12px; height: 12px; margin-right: 4px;"></i> VALIDASI DOKUMEN
                                        </a>
                                    @endif
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="flex gap-2 justify-between" style="justify-content: flex-end;">
                                    <form id="approve-form-{{ $app->id }}" action="{{ route('admin.approvals.approve', $app->id) }}" method="POST" style="display:none;">@csrf</form>
                                    <form id="reject-form-{{ $app->id }}" action="{{ route('admin.approvals.reject', $app->id) }}" method="POST" style="display:none;">@csrf</form>

                                    <button type="button" class="btn btn-primary" style="padding: 8px 16px; font-size: 0.75rem;" onclick="confirmAction('approve-form-{{ $app->id }}', 'Setujui {{ $app->name }} sebagai pengurus?', 'success')">
                                        <i data-lucide="check" style="width: 14px; height: 14px;"></i> SETUJUI
                                    </button>
                                    <button type="button" class="btn btn-secondary" style="padding: 8px 16px; font-size: 0.75rem; color: var(--danger) !important; border-color: #fee2e2 !important;" onclick="confirmAction('reject-form-{{ $app->id }}', 'Tolak permohonan {{ $app->name }}?', 'danger')">
                                        <i data-lucide="x" style="width: 14px; height: 14px;"></i> TOLAK
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    <div class="empty-icon"><i data-lucide="inbox"></i></div>
                                    <h3 class="empty-title">Antrian Kosong</h3>
                                    <p class="empty-text">Belum ada permohonan baru untuk ditinjau saat ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/pages/admin_approvals_pengurus.js'])
    @endpush
</x-admin-layout>
