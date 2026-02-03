<div class="form-section">
    <div class="brand-logo"><i class="bi bi-key-fill"></i> ICT4U</div>
    <div class="mb-4">
        <h1 class="fw-bold h3 mb-2">Kata Laluan Baru</h1>
        <p class="text-muted">Sila cipta kata laluan yang kuat untuk keselamatan akaun anda.</p>
    </div>

    <form action="<?= base_url('/update-password') ?>" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="token" value="<?= $token ?>">
        
        <div class="mb-3">
            <label class="form-label small fw-semibold">Kata Laluan Baru</label>
            <input type="password" name="password" class="form-control" placeholder="Minima 6 aksara" required>
        </div>

        <div class="mb-4">
            <label class="form-label small fw-semibold">Sahkan Kata Laluan</label>
            <input type="password" name="confirm_password" class="form-control" placeholder="Taip semula kata laluan" required>
        </div>

        <button type="submit" class="btn btn-login w-100 mb-3">Kemaskini Kata Laluan</button>
    </form>
</div>