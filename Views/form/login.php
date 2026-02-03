<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Masuk | ICT4U</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --brand-color: #4f46e5;
            --brand-hover: #4338ca;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #ffffff;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }

        .main-container {
            display: flex;
            height: 100vh;
        }

        /* --- Left Side: Visuals --- */
        .image-section {
            flex: 1;
            background: url('https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80&w=1200') center/cover no-repeat;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px;
        }

        .image-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.8), rgba(67, 56, 202, 0.4));
        }

        .overlay-content {
            position: relative;
            z-index: 2;
            color: white;
            max-width: 500px;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 30px;
            border-radius: 24px;
        }

        /* --- Right Side: Form --- */
        .form-section {
            width: 500px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px;
            background: #fff;
            overflow-y: auto;
        }

        .brand-logo {
            font-weight: 800;
            font-size: 1.5rem;
            color: var(--brand-color);
            margin-bottom: 40px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-control {
            border: 1.5px solid #e2e8f0;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.2s;
        }

        .form-control:focus {
            border-color: var(--brand-color);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        .btn-login {
            background: var(--brand-color);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 12px;
            font-weight: 600;
            transition: 0.3s;
            margin-top: 10px;
        }

        .btn-login:hover {
            background: var(--brand-hover);
            transform: translateY(-1px);
        }

        .social-btn {
            border: 1.5px solid #e2e8f0;
            background: white;
            padding: 10px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-weight: 500;
            color: #475569;
            transition: 0.2s;
        }

        .social-btn:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        /* --- Responsive --- */
        @media (max-width: 992px) {
            .image-section { display: none; }
            .form-section { width: 100%; padding: 30px; }
        }
    </style>
</head>
<body>

<div class="main-container">
    <div class="image-section">
        <div class="overlay-content">
            <div class="glass-card">
                <h2 class="fw-bold mb-3">Revolusi Digital ICT4U</h2>
                <p class="mb-0 opacity-90">Satu platform untuk semua keperluan pengurusan sistem anda. Pantas, selamat, dan efisien.</p>
            </div>
        </div>
    </div>

    <div class="form-section">
        <div class="brand-logo">
            <i class="bi bi-cpu-fill"></i> ICT4U
        </div>

        <div class="mb-4">
            <h1 class="fw-bold h3 mb-2">Selamat Kembali</h1>
            <p class="text-muted">Masukkan emel dan kata laluan anda untuk akses.</p>
        </div>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger border-0 rounded-4 small p-3 mb-4">
                <i class="bi bi-exclamation-circle me-2"></i> <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('/login') ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="mb-3">
                <label class="form-label small fw-semibold">Alamat Emel</label>
                <input type="email" name="email" class="form-control" placeholder="nama@syarikat.com" required>
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between">
                    <label class="form-label small fw-semibold">Kata Laluan</label>
                    <a href="<?= base_url('/forgot-password') ?>" class="small text-decoration-none fw-bold text-primary">Lupa?</a>
                </div>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>

            <button type="submit" class="btn btn-login w-100 mb-3">Log Masuk</button>

            <p class="text-center small text-muted">
                Belum mempunyai akaun? <a href="<?= base_url('/register') ?>" class="text-primary fw-bold text-decoration-none">Daftar </a>
            </p>
        </form>

        <div class="mt-auto pt-5 text-center">
            <p class="small text-muted">&copy; 2026 ICT4U Management System</p>
        </div>
    </div>
</div>

</body>
</html>
