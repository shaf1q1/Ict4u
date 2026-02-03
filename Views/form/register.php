<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertai Kami | ICT4U Enterprise</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --brand-primary: #10b981;
            --brand-dark: #064e3b;
            --surface: rgba(255, 255, 255, 0.9);
        }

        body { 
            font-family: 'Inter', sans-serif; 
            /* Gambar Background dengan Overlay */
            background: linear-gradient(rgba(15, 23, 42, 0.7), rgba(15, 23, 42, 0.7)), 
                        url('http://googleusercontent.com/image_collection/image_retrieval/14759502793213218391_0');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center;
            padding: 24px;
        }

        .auth-wrapper {
            width: 100%;
            max-width: 480px;
        }

        .auth-card {
            background: var(--surface);
            backdrop-filter: blur(12px); /* Kesan Glassmorphism */
            border-radius: 28px;
            padding: 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .brand-logo {
            font-weight: 800;
            font-size: 1.25rem;
            color: var(--brand-dark);
            letter-spacing: -0.5px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .brand-logo i {
            color: var(--brand-primary);
            font-size: 1.5rem;
        }

        h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .subtitle {
            color: #475569;
            font-size: 0.9rem;
            margin-bottom: 24px;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #1e293b;
            margin-bottom: 6px;
        }

        .form-control {
            border: 1px solid #cbd5e1;
            padding: 10px 16px;
            border-radius: 12px;
            font-size: 0.9rem;
            background-color: rgba(248, 250, 252, 0.8);
            transition: all 0.2s ease;
        }

        .form-control:focus {
            background-color: #fff;
            border-color: var(--brand-primary);
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        }

        .btn-submit {
            background-color: var(--brand-primary);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 12px;
            font-weight: 600;
            width: 100%;
            margin-top: 10px;
            transition: all 0.3s;
        }

        .btn-submit:hover {
            background-color: var(--brand-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.3);
        }

        .footer-text {
            text-align: center;
            margin-top: 24px;
            font-size: 0.85rem;
            color: #475569;
        }

        .footer-text a {
            color: var(--brand-primary);
            text-decoration: none;
            font-weight: 700;
        }

        /* Responsive Fix */
        @media (max-width: 480px) {
            .auth-card { padding: 30px 20px; }
        }
    </style>
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="brand-logo">
            <i class="bi bi-cpu-fill"></i> ICT4U
        </div>

        <h1>Cipta Akaun</h1>
        <p class="subtitle">Sertai komuniti teknologi kami hari ini.</p>

        <form action="<?= base_url('/register') ?>" method="post">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label class="form-label">Nama Penuh</label>
                <input type="text" name="fullname" class="form-control" placeholder="Ahmad Zulkarnain" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Emel Rasmi</label>
                <input type="email" name="email" class="form-control" placeholder="nama@syarikat.com" required>
            </div>

            <div class="row g-2 mb-4">
                <div class="col-6">
                    <label class="form-label">Kata Laluan</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <div class="col-6">
                    <label class="form-label">Sahkan</label>
                    <input type="password" name="confirm_password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn-submit">Daftar Sekarang</button>

            <p class="footer-text">
                Dah ada akaun? <a href="<?= base_url('/login') ?>">Log masuk</a>
            </p>
        </form>
    </div>
    
    <p class="text-center mt-4 text-white small opacity-75">
        &copy; 2026 ICT4U. Smart Solutions for You.
    </p>
</div>

</body>
</html>