<x-pengurus-layout title="Pengaturan Akun - Pengurus Garda JKN">
    <div style="max-width: 600px;">
        <div style="margin-bottom: 24px;">
            <h1 style="font-size: 1.5rem; font-weight: 900; letter-spacing: -0.02em; color: #0f172a; margin: 0;">Keamanan & Password</h1>
            <p style="font-size: 0.875rem; color: #64748b; margin-top: 4px;">Perbarui kata sandi Anda secara berkala untuk menjaga keamanan akun.</p>
        </div>

        <div style="background: white; border-radius: 1rem; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
            <div style="padding: 20px 24px; border-bottom: 1px solid #f1f5f9;">
                <h2 style="font-size: 0.95rem; font-weight: 800; color: #0f172a; margin: 0;">Ubah Kata Sandi</h2>
            </div>
            <div style="padding: 24px;">
                <form id="passwordForm">
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #374151; margin-bottom: 6px;">Kata Sandi Saat Ini</label>
                        <div style="position: relative;">
                            <input type="password" id="current_password" style="width: 100%; padding: 10px 44px 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.875rem; box-sizing: border-box;" placeholder="Masukkan password sekarang" required>
                            <button type="button" onclick="togglePassword('current_password')" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); border: none; background: none; color: #64748b; padding: 4px; cursor: pointer;" id="icon-current_password">
                                <i data-lucide="eye" style="width: 18px; height: 18px;"></i>
                            </button>
                        </div>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #374151; margin-bottom: 6px;">Kata Sandi Baru</label>
                        <div style="position: relative;">
                            <input type="password" id="new_password" style="width: 100%; padding: 10px 44px 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.875rem; box-sizing: border-box;" placeholder="Minimal 8 karakter" required>
                            <button type="button" onclick="togglePassword('new_password')" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); border: none; background: none; color: #64748b; padding: 4px; cursor: pointer;" id="icon-new_password">
                                <i data-lucide="eye" style="width: 18px; height: 18px;"></i>
                            </button>
                        </div>
                    </div>
                    <div style="margin-bottom: 32px;">
                        <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #374151; margin-bottom: 6px;">Konfirmasi Kata Sandi Baru</label>
                        <div style="position: relative;">
                            <input type="password" id="new_password_confirmation" style="width: 100%; padding: 10px 44px 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.875rem; box-sizing: border-box;" placeholder="Ulangi password baru" required>
                            <button type="button" onclick="togglePassword('new_password_confirmation')" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); border: none; background: none; color: #64748b; padding: 4px; cursor: pointer;" id="icon-new_password_confirmation">
                                <i data-lucide="eye" style="width: 18px; height: 18px;"></i>
                            </button>
                        </div>
                    </div>
                    <div style="display: flex; justify-content: flex-end;">
                        <button type="submit" id="btnSubmit" style="display: inline-flex; align-items: center; gap: 8px; background: #0f172a; color: white; padding: 12px 24px; border-radius: 10px; font-weight: 700; font-size: 0.875rem; border: none; cursor: pointer; transition: all 0.2s;">
                            <i data-lucide="save" style="width: 16px; height: 16px;"></i> Update Kata Sandi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15,23,42,0.6); z-index:9999; align-items:center; justify-content:center; backdrop-filter: blur(4px);">
        <div style="background: white; width:400px; padding:40px; border-radius: 24px; text-align:center;">
            <div style="width: 80px; height: 80px; background: #ecfdf5; color: #10b981; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
                <i data-lucide="check-circle" style="width: 48px; height: 48px;"></i>
            </div>
            <h3 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin-bottom: 8px;">Berhasil!</h3>
            <p style="color: #64748b; font-size: 0.95rem; margin-bottom: 24px;">Kata sandi Anda telah diperbarui.</p>
            <button onclick="closeSuccessModal()" style="width: 100%; padding: 14px; background: #0f172a; color: white; border: none; border-radius: 12px; font-weight: 700; cursor: pointer;">Selesai</button>
        </div>
    </div>

    @push('styles')
        @vite(['resources/css/pages/pengurus_dashboard.css'])
    @endpush
    @push('scripts')
        @vite(['resources/js/pages/common_settings.js'])
    @endpush
</x-pengurus-layout>
