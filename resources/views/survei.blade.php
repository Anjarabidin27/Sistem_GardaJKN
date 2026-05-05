<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survei Kepuasan Pelanggan (Supel)</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            color: #0f172a;
        }
        .container {
            background: #ffffff;
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            border-radius: 1.5rem;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
            text-align: center;
        }
        .logo-wrap {
            width: 60px;
            height: 60px;
            background: #0ea5e9;
            color: white;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 800;
            margin: 0 auto 1.5rem;
        }
        h1 {
            font-size: 1.25rem;
            margin: 0 0 0.5rem;
            font-weight: 800;
        }
        p {
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 2rem;
        }
        .btn-puas {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            background: #ecfdf5;
            color: #10b981;
            border: 2px solid #a7f3d0;
            padding: 1.5rem;
            border-radius: 1rem;
            width: 100%;
            font-size: 1.125rem;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.2s;
            margin-bottom: 1rem;
        }
        .btn-puas:hover {
            background: #10b981;
            color: white;
            border-color: #10b981;
        }
        .btn-tidak-puas {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            background: #fef2f2;
            color: #ef4444;
            border: 2px solid #fecaca;
            padding: 1.5rem;
            border-radius: 1rem;
            width: 100%;
            font-size: 1.125rem;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-tidak-puas:hover {
            background: #ef4444;
            color: white;
            border-color: #ef4444;
        }
        .alert-success {
            background: #ecfdf5;
            color: #10b981;
            padding: 1rem;
            border-radius: 0.75rem;
            margin-bottom: 1.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            border: 1px solid #a7f3d0;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="logo-wrap">
        <i data-lucide="shield-check" style="width: 32px; height: 32px;"></i>
    </div>
    
    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
        <p>Anda dapat menutup halaman ini sekarang.</p>
    @else
        <h1>Survei Kepuasan Layanan</h1>
        <p>Bagaimana tingkat kepuasan Anda terhadap layanan <strong>{{ $kegiatan->judul }}</strong> hari ini?</p>

        <form method="POST" action="{{ route('survei.store', $kegiatan->id) }}">
            @csrf
            <button type="submit" name="suara_pelanggan" value="Puas" class="btn-puas">
                <i data-lucide="smile" style="width: 48px; height: 48px;"></i>
                <span>PUAS</span>
            </button>
            <button type="submit" name="suara_pelanggan" value="Tidak puas" class="btn-tidak-puas">
                <i data-lucide="frown" style="width: 48px; height: 48px;"></i>
                <span>TIDAK PUAS</span>
            </button>
        </form>
    @endif
</div>

<script>
    lucide.createIcons();
</script>
</body>
</html>
