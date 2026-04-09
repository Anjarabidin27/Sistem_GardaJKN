<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Petugas Portal - Garda JKN</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --v-black: #000;
            --v-white: #fff;
            --v-gray-50: #fbfbfc;
            --v-gray-100: #f3f4f6;
            --v-gray-400: #9ca3af;
            --v-blue-600: #2563eb;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Plus Jakarta Sans', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            overflow: hidden;
        }

        .login-card {
            background: white;
            width: 100%;
            max-width: 440px;
            padding: 3rem;
            border-radius: 2.5rem;
            box-shadow: 0 40px 100px -20px rgba(0,0,0,0.1);
            position: relative;
            z-index: 10;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .logo-txt {
            font-family: 'Outfit', sans-serif;
            font-size: 1.75rem;
            font-weight: 900;
            letter-spacing: -0.04em;
            color: var(--v-black);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--v-gray-400);
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            padding: 1rem 1.25rem;
            border-radius: 1rem;
            border: 1.5px solid var(--v-gray-100);
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--v-black);
            transition: all 0.2s;
            margin-bottom: 1.5rem;
            box-sizing: border-box;
        }
        .form-input:focus {
            outline: none;
            border-color: var(--v-black);
            background: var(--v-gray-50);
        }

        .btn-login {
            width: 100%;
            padding: 1.125rem;
            border-radius: 1rem;
            background: var(--v-black);
            color: white;
            border: none;
            font-size: 1rem;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px -5px rgba(0,0,0,0.2);
        }
        .btn-login:active { transform: translateY(0); }

        .bg-blur {
            position: fixed;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.08) 0%, transparent 70%);
            z-index: 1;
            border-radius: 50%;
        }

        .error-msg {
            background: #fef2f2;
            color: #dc2626;
            padding: 1rem;
            border-radius: 1rem;
            font-size: 0.8rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            display: none;
            border: 1px solid #fee2e2;
        }
    </style>
</head>
<body>
    <div class="bg-blur" style="top: -200px; left: -200px;"></div>
    <div class="bg-blur" style="bottom: -200px; right: -200px;"></div>

    <div class="login-card">
        <div class="logo-section">
            <div class="logo-txt">
                <i data-lucide="shield-check" style="width: 32px; height: 32px; color: var(--v-black);"></i>
                Portal Petugas
            </div>
            <p style="font-size: 0.875rem; color: var(--v-gray-400); margin-top: 0.5rem; font-weight: 600;">Garda JKN Administrasi Lapangan</p>
        </div>

        <div id="error-box" class="error-msg"></div>

        <form id="petugasLoginForm">
            <div>
                <label class="form-label">NIK / Username</label>
                <input type="text" id="username" class="form-input" placeholder="Masukkan NIK Anda" required autocomplete="username">
            </div>

            <div>
                <label class="form-label">Password Sistem</label>
                <input type="password" id="password" class="form-input" placeholder="••••••••" required autocomplete="current-password">
            </div>

            <button type="submit" class="btn-login" id="submitBtn">
                Masuk Sekarang
                <i data-lucide="arrow-right" style="width: 20px; height: 20px;"></i>
            </button>
        </form>

        <div style="margin-top: 2rem; text-align: center;">
            <a href="/login" style="font-size: 0.75rem; font-weight: 800; color: var(--v-gray-400); text-decoration: none; text-transform: uppercase; letter-spacing: 0.05em;">← Kembali ke Portal Publik</a>
        </div>
    </div>

    @vite(['resources/js/pages/auth_petugas_login.js'])
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if(window.lucide) window.lucide.createIcons();
        });
    </script>
</body>
</html>
