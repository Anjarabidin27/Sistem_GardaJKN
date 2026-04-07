<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Garda JKN - Portal Keanggotaan Nasional</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    @vite(['resources/css/app.css', 'resources/css/anggota.css', 'resources/js/app.js'])
</head>
<body>
    <nav>
        <a href="/" class="logo">
            <div class="logo-box">G</div>
            Garda JKN
        </a>
        <div class="nav-links">
            <a href="#">Beranda</a>
            <a href="#features">Keunggulan</a>
            <a href="#stats">Data Real-time</a>
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/member/profile') }}" class="btn-auth btn-register">Dashboard Profil</a>
                @else
                    <a href="{{ route('login') }}" class="btn-auth btn-login">Masuk</a>
                    <a href="{{ route('register') }}" class="btn-auth btn-register">Daftar Anggota</a>
                @endauth
            @endif
        </div>
    </nav>

    <section class="hero">
        <div class="hero-content">
            <span class="hero-tag">Sistem Informasi Keanggotaan Nasional</span>
            <h1 class="hero-title">Garda Terdepan <span>Pelayanan JKN</span> Bangsa.</h1>
            <p class="hero-desc">Platform digital terintegrasi untuk pengelolaan basis data keanggotaan dan pemberdayaan operasional JKN di seluruh pelosok Indonesia.</p>
            <div class="hero-btns">
                <a href="{{ route('register') }}" class="btn-auth btn-register" style="padding: 16px 36px; font-size: 1rem;">Gabung Sekarang</a>
                <a href="#features" class="btn-auth btn-login" style="padding: 16px 36px; font-size: 1rem;">Pelajari Fitur</a>
            </div>
        </div>
        <div class="hero-visual">
            <div class="visual-card">
                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px;">
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: var(--primary);">
                        <i data-lucide="shield-check"></i>
                    </div>
                    <div>
                        <div style="font-weight: 800; font-size: 1.1rem;">Sertifikasi Keanggotaan</div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Terverifikasi secara Nasional</div>
                    </div>
                </div>
                <div style="background: #f8fafc; border-radius: 12px; padding: 20px; margin-bottom: 16px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="font-size: 0.75rem; font-weight: 700; color: #94a3b8;">CAKUPAN PROVINSI</span>
                        <span style="font-size: 0.75rem; font-weight: 800; color: var(--primary);">100% TERHUBUNG</span>
                    </div>
                    <div style="height: 8px; background: #e2e8f0; border-radius: 4px; overflow: hidden;">
                        <div style="width: 100%; height: 100%; background: var(--primary);"></div>
                    </div>
                </div>
            </div>
            <!-- Floating Elements -->
            <div style="position: absolute; top: -20px; right: -20px; background: white; padding: 16px; border-radius: 16px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); display: flex; align-items: center; gap: 12px;">
                <div style="width: 10px; height: 10px; background: #10b981; border-radius: 50%;"></div>
                <span style="font-size: 0.75rem; font-weight: 800;">34 Provinsi Aktif</span>
            </div>
        </div>
    </section>

    <section id="stats" class="stats">
        <div class="stat-item">
            <div class="stat-val">34+</div>
            <div class="stat-lbl">Provinsi</div>
        </div>
        <div class="stat-item">
            <div class="stat-val">500+</div>
            <div class="stat-lbl">Kab/Kota</div>
        </div>
        <div class="stat-item">
            <div class="stat-val">100%</div>
            <div class="stat-lbl">Digitalisasi</div>
        </div>
        <div class="stat-item">
            <div class="stat-val">24/7</div>
            <div class="stat-lbl">Monitoring</div>
        </div>
    </section>

    <section id="features" class="features">
        <div class="section-header">
            <h2>Modern, Aman, Akurat.</h2>
            <p>Ecosystem digital yang dirancang khusus untuk mempercepat alur koordinasi dan verifikasi data nasional.</p>
        </div>
        <div class="feature-grid">
            <div class="f-card">
                <div class="f-icon"><i data-lucide="fingerprint"></i></div>
                <h3>Keamanan Data Biometrik</h3>
                <p>Setiap data anggota dilindungi dengan enkripsi tingkat tinggi dan verifikasi identitas yang ketat sesuai regulasi.</p>
            </div>
            <div class="f-card">
                <div class="f-icon"><i data-lucide="bar-chart-3"></i></div>
                <h3>Analitik Real-time</h3>
                <p>Dashboard pemantauan yang menyajikan data statistik secara instan untuk pengambilan keputusan yang cepat dan tepat.</p>
            </div>
            <div class="f-card">
                <div class="f-icon"><i data-lucide="globe"></i></div>
                <h3>Integrasi Wilayah</h3>
                <p>Terhubung langsung dengan seluruh kantor operasional di 34 provinsi untuk sinkronisasi data tanpa batas.</p>
            </div>
        </div>
    </section>

    <footer>
        <div class="footer-grid">
            <div class="footer-brand">
                <h4>Garda JKN</h4>
                <p>Inovasi digital untuk perlindungan kesehatan bangsa yang lebih merata dan transparan.</p>
            </div>
            <div class="footer-col">
                <h5>Layanan</h5>
                <ul>
                    <li><a href="{{ route('register') }}">Daftar Anggota</a></li>
                    <li><a href="{{ route('login') }}">Masuk Portal</a></li>
                    <li><a href="#">Bantuan Verifikasi</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h5>Institusi</h5>
                <ul>
                    <li><a href="#">Tentang Kami</a></li>
                    <li><a href="#">Kebijakan Privasi</a></li>
                    <li><a href="#">Kontak Respon Cepat</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h5>Kontak</h5>
                <ul>
                    <li><a href="#">(021) 1234-5678</a></li>
                    <li><a href="#">support@garda-jkn.go.id</a></li>
                    <li><a href="#">Jakarta, Indonesia</a></li>
                </ul>
            </div>
        </div>
        <div class="copyright">
            SISTEM INFORMASI GARDA JKN &copy; 2026. SELURUH HAK CIPTA DILINDUNGI.
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
