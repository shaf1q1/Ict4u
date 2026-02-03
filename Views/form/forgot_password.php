<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Semula Kata Laluan | ICT4U</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --brand-color: #4f46e5; --brand-hover: #4338ca; }
        body { font-family: 'Inter', sans-serif; background-color: #ffffff; height: 100vh; margin: 0; overflow: hidden; }
        .main-container { display: flex; height: 100vh; }
        .image-section { flex: 1; background: url('https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&q=80&w=1200') center/cover no-repeat; position: relative; display: flex; align-items: center; justify-content: center; padding: 60px; }
        .image-section::before { content: ''; position: absolute; inset: 0; background: linear-gradient(135deg, rgba(79, 70, 229, 0.8), rgba(67, 56, 202, 0.4)); }
        .overlay-content { position: relative; z-index: 2; color: white; max-width: 500px; }
        .glass-card { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); padding: 30px; border-radius: 24px; }
        .form-section { width: 500px; display: flex; flex-direction: column; justify-content: center; padding: 60px; background: #fff; overflow-y: auto; }
        .brand-logo { font-weight: 800; font-size: 1.5rem; color: var(--brand-color); margin-bottom: 30px; display: flex; align-items: center; gap: 10px; }
        .form-control { border: 1.5px solid #e2e8f0; padding: 12px 16px; border-radius: 12px; }
        .btn-login { background: var(--brand-color); color: white; border: none; padding: 12px; border-radius: 12px; font-weight: 600; transition: 0.3s; }
        .btn-login:hover { background: var(--brand-hover); transform: translateY(-1px); }
        @media (max-width: 992px) { .image-section { display: none; } .form-section { width: 100%; } }
    </style>
</head>
<body>
<div class="main-container">
    <div class="image-section">
        <div class="overlay-content">
            <div class="glass-card">
                <h2 class="fw-bold mb-3">Kemaskini Segera</h2>
                <p class="mb-0 opacity-90">Masukkan emel dan kata laluan baharu anda untuk mengemaskini akses akaun secara terus.</p>
            </div>
        </div>
    </div>
    <div class="form-section">
        <div class="brand-logo"><i class="bi bi-shield-lock-fill"></i> ICT4U</div>
        <div class="mb-4">
            <h1 class="fw-bold h3 mb-2">Tukar Kata Laluan</h1>
            <p class="text-muted">Sila isi maklumat di bawah.</p>
        </div>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger border-0 rounded-4 small p-3 mb-4">
                <i class="bi bi-exclamation-circle me-2"></i> <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success border-0 rounded-4 small p-3 mb-4">
                <i class="bi bi-check-circle me-2"></i> <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('/forgot-password') ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="mb-3">
                <label class="form-label small fw-semibold">Alamat Emel</label>
                <input type="email" name="email" class="form-control" placeholder="nama@syarikat.com" required>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-semibold">Kata Laluan Baharu</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-semibold">Sahkan Kata Laluan</label>
                <input type="password" name="confirmpassword" class="form-control" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-login w-100 mb-3">Kemaskini Sekarang</button>
            
            <p class="text-center small text-muted">
                Batal urusan? <a href="<?= base_url('/login') ?>" class="text-primary fw-bold text-decoration-none">Log Masuk</a>
            </p>
        </form>
    </div>
</div>
</body>
</html>