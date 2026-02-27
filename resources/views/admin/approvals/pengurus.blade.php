@extends('layouts.app')

@section('title', 'Persetujuan Pengurus - Admin Panel')

@section('content')
<style>
    /* Force Layout Bases */
    .admin-layout { display: flex !important; min-height: 100vh !important; background: #f8fafc !important; }
    .sidebar { width: 280px !important; background: #004aad !important; color: white !important; display: flex !important; flex-direction: column !important; position: fixed !important; height: 100vh !important; z-index: 100 !important; overflow: hidden !important; border: none !important; }
    .sb-brand { padding: 28px 28px 10px; flex-shrink: 0; }
    .sb-brand-name { font-size: 1.1rem !important; font-weight: 800 !important; color: white !important; letter-spacing: 0.02em; }
    .sb-brand-sub { font-size: 0.75rem !important; color: rgba(255,255,255,0.6) !important; font-weight: 500; margin-top: 4px; }
    .sb-user-card { padding: 10px 28px 20px; flex-shrink: 0; }
    .sb-avatar { width: 52px !important; height: 52px !important; border-radius: 14px; background: rgba(255,255,255,0.15); border: 2px solid rgba(255,255,255,0.25); display: flex !important; align-items: center !important; justify-content: center !important; margin-bottom: 12px; overflow: hidden; }
    .sb-user-name { font-size: 0.95rem !important; font-weight: 800 !important; color: white !important; margin-bottom: 4px; }
    .sb-user-role { font-size: 0.7rem !important; color: rgba(255,255,255,0.5) !important; text-transform: uppercase; letter-spacing: 0.05em; }
    .sb-menu { padding: 16px 12px !important; flex: 1; overflow-y: auto !important; }
    .sb-link { display: flex !important; align-items: center !important; gap: 12px; padding: 12px 16px; border-radius: 10px; color: rgba(255,255,255,0.7) !important; text-decoration: none !important; font-weight: 600; font-size: 0.875rem; transition: 0.2s; }
    .sb-link:hover { background: rgba(255,255,255,0.1); color: white !important; }
    .sb-link.active { background: #ffffff15; color: white !important; }
    .sb-footer { padding: 20px 12px; border-top: 1px solid rgba(255,255,255,0.08); }

    .main-body { margin-left: 280px !important; flex: 1 !important; min-width: 0 !important; }
    .top-header { height: 64px !important; background: white !important; border-bottom: 1px solid #e2e8f0 !important; padding: 0 32px !important; display: flex !important; align-items: center !important; justify-content: space-between !important; position: sticky; top: 0; z-index: 50; }
    .view-container { padding: 32px !important; }

    /* Component Styles */
    .table-card { background: white !important; border: 1px solid #e2e8f0 !important; border-radius: 16px !important; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); margin-bottom: 24px; }
    .table-header { padding: 24px 32px; border-bottom: 1px solid #f1f5f9; display: flex !important; align-items: center !important; justify-content: space-between !important; }
    .data-table { width: 100% !important; border-collapse: collapse !important; border-spacing: 0 !important; }
    .data-table th { background: #f8fafc !important; padding: 16px 32px !important; text-align: left !important; font-size: 0.75rem !important; font-weight: 700 !important; color: #64748b !important; text-transform: uppercase !important; border-bottom: 1px solid #e2e8f0 !important; white-space: nowrap; }
    .data-table td { padding: 16px 32px !important; border-bottom: 1px solid #f1f5f9 !important; font-size: 0.875rem !important; color: #334155 !important; vertical-align: middle !important; background: white !important; }
    
    .status-badge { padding: 6px 12px; border-radius: 6px; font-size: 0.7rem; font-weight: 800; display: inline-block; text-transform: uppercase; }
    .badge-exp { background: #ecfdf5; color: #10b981; }
    .badge-no-exp { background: #f1f5f9; color: #64748b; }
    
    .btn-action-premium { 
        padding: 8px 16px; border-radius: 10px; font-size: 0.75rem; font-weight: 800; border: none; cursor: pointer; 
        transition: 0.2s; display: inline-flex; align-items: center; gap: 8px; letter-spacing: 0.02em;
    }
    .btn-approve-premium { background: #10b981; color: white; box-shadow: 0 4px 6px -1px rgba(16,185,129,0.2); }
    .btn-approve-premium:hover { background: #059669; transform: translateY(-1px); box-shadow: 0 6px 12px -2px rgba(16,185,129,0.3); }
    .btn-reject-premium { background: #fee2e2; color: #ef4444; border: 1px solid #fecaca; }
    .btn-reject-premium:hover { background: white; color: #dc2626; border-color: #ef4444; transform: translateY(-1px); }

    .empty-state { padding: 64px 32px; text-align: center; }
    .empty-icon { width: 64px; height: 64px; background: #f8fafc; border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; color: #cbd5e1; }
</style>

<div class="admin-layout">
    <aside class="sidebar">
        <div class="sb-brand">
            <div class="sb-brand-name">Garda JKN</div>
        </div>
        <div class="sb-user-card">
            <div class="sb-avatar" id="sb-avatar-wrap"><span id="sb-initials">A</span></div>
            <div class="sb-user-name" id="sb-user-name">Administrator</div>
        </div>
        <nav class="sb-menu">
            <div class="sb-section-label" style="font-size:0.6rem; font-weight:800; color:rgba(255,255,255,0.3); text-transform:uppercase; padding:0 16px; margin:16px 0 8px;">Menu</div>
            <a href="/admin/dashboard" class="sb-link"><i data-lucide="layout-dashboard" style="width:16px;height:16px;"></i> Dashboard</a>
            <a href="/admin/members" class="sb-link"><i data-lucide="users" style="width:16px;height:16px;"></i> Manajemen Anggota</a>
            <a href="/admin/approvals" class="sb-link active"><i data-lucide="user-check" style="width:16px;height:16px;"></i> Persetujuan Pengurus</a>
            <a href="/admin/informations" class="sb-link"><i data-lucide="megaphone" style="width:16px;height:16px;"></i> Informasi</a>
            <a href="/admin/audit-logs" class="sb-link"><i data-lucide="file-clock" style="width:16px;height:16px;"></i> Log Audit</a>
        </nav>
        <div class="sb-footer">
            <div class="sb-section-label" style="font-size:0.6rem; font-weight:800; color:rgba(255,255,255,0.3); text-transform:uppercase; padding:0 16px; margin-bottom:8px;">Pengaturan</div>
            <a href="/settings" class="sb-link"><i data-lucide="settings" style="width:16px;height:16px;"></i> Pengaturan Akun</a>
            <a href="#" class="sb-link" onclick="logout()" style="color:#fca5a5;margin-top:4px;"><i data-lucide="log-out" style="width:16px;height:16px;color:#fca5a5;"></i> Keluar Sesi</a>
        </div>
    </aside>

    <main class="main-body">
        <header class="top-header">
            <div style="font-weight: 600; color: #1e293b; font-size: 1rem;">Antrian Persetujuan Pengurus</div>
            <div id="user-info-header" style="display: flex; align-items: center; gap: 12px;">
                <span id="date-now" style="font-size: 0.75rem; color: #94a3b8; font-weight: 500;"></span>
                <div id="user-initials" style="width: 32px; height: 32px; background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem;">...</div>
            </div>
        </header>

        <div class="view-container">
            <div class="table-card">
                <div class="table-header">
                    <div>
                        <h2 style="font-size:1.5rem; font-weight:800; color:#0f172a;">Data Permohonan Masuk</h2>
                        <p style="font-size: 0.85rem; color: #64748b; margin-top: 4px;">Daftar anggota yang mengajukan upgrade menjadi pengurus operasional.</p>
                    </div>
                    <div style="background: #3b82f6; color: white; padding: 6px 14px; border-radius: 8px; font-weight: 700; font-size: 0.8rem;">
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
                                <th style="text-align: right;">Opsi Verifikasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($applicants as $app)
                                <tr>
                                    <td>
                                        <div style="display:flex; flex-direction:column; gap:4px;">
                                            <span style="font-weight:700; color:#1e293b; font-size:0.95rem;">{{ $app->name }}</span>
                                            <span style="font-size:0.75rem; color:#64748b; font-weight:600;">NIK: {{ $app->nik }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($app->has_org_experience)
                                            <span class="status-badge badge-exp">Berpengalaman</span>
                                            <div style="margin-top: 8px;">
                                                <div style="font-size: 0.85rem; font-weight: 700; color: #334155;">{{ $app->org_name }}</div>
                                                <div style="font-size: 0.75rem; color: #64748b;">{{ $app->org_position }}  {{ $app->org_duration_months }} Bulan</div>
                                            </div>
                                        @else
                                            <span class="status-badge badge-no-exp">Entry Level</span>
                                            <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 6px;">Tanpa pengalaman sebelumnya</div>
                                        @endif
                                    </td>
                                    <td style="max-width: 320px;">
                                        @if($app->has_org_experience)
                                            <p style="font-size: 0.8rem; color: #64748b; line-height: 1.5; font-style: italic; margin-bottom:8px;">
                                                "{{ Str::limit($app->org_description, 100) }}"
                                            </p>
                                            @if($app->org_certificate_path)
                                                <a href="{{ asset('storage/' . $app->org_certificate_path) }}" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; color: #3b82f6; font-size: 0.7rem; font-weight: 800; text-decoration: none; background: #eff6ff; padding: 4px 10px; border-radius: 6px;">
                                                    <i data-lucide="external-link" style="width: 12px; height: 12px;"></i> VALIDASI DOKUMEN
                                                </a>
                                            @endif
                                        @else
                                            <span style="color: #cbd5e1;"></span>
                                        @endif
                                    </td>
                                    <td style="text-align: right;">
                                        <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                            <form id="approve-form-{{ $app->id }}" action="{{ route('admin.approvals.approve', $app->id) }}" method="POST" style="display:none;">@csrf</form>
                                            <form id="reject-form-{{ $app->id }}" action="{{ route('admin.approvals.reject', $app->id) }}" method="POST" style="display:none;">@csrf</form>

                                            <button type="button" class="btn-action-premium btn-approve-premium" onclick="confirmAction('approve-form-{{ $app->id }}', 'Setujui {{ $app->name }} sebagai pengurus?', 'success')">
                                                <i data-lucide="check" style="width: 14px; height: 14px;"></i> SETUJUI
                                            </button>
                                            <button type="button" class="btn-action-premium btn-reject-premium" onclick="confirmAction('reject-form-{{ $app->id }}', 'Tolak permohonan {{ $app->name }}?', 'danger')">
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
                                            <h3 style="font-weight: 800; color: #0f172a; margin-bottom: 8px;">Antrian Kosong</h3>
                                            <p style="color: #64748b; font-size: 0.9rem;">Belum ada permohonan baru untuk ditinjau saat ini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('date-now').innerText = new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });

        @if(session('success'))
            showToast("{{ session('success') }}", 'success');
        @endif
        @if(session('error'))
            showToast("{{ session('error') }}", 'error');
        @endif
    });

    async function confirmAction(formId, message, type) {
        const ok = await showConfirm('Konfirmasi Tindakan', message, { 
            type: type === 'success' ? 'primary' : 'danger', 
            confirmText: 'Ya, Lanjutkan',
            icon: type === 'success' ? 'check-circle' : 'alert-circle'
        });
        if (ok) {
            document.getElementById(formId).submit();
        }
    }
</script>
@endpush
@endsection
