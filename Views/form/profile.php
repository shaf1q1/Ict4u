<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<style>
    .profile-header-bg { background: var(--primary-gradient, linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%)); height: 150px; border-radius: 20px 20px 0 0; }
    .avatar-wrapper { position: relative; display: inline-block; margin-top: -55px; margin-left: 30px; }
    .avatar-box { width: 110px; height: 110px; background: white; border: 4px solid white; border-radius: 28px; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; font-weight: 800; color: #4f46e5; box-shadow: 0 10px 25px rgba(0,0,0,0.1); overflow: hidden; transition: transform 0.3s ease; }
    .avatar-box img { width: 100%; height: 100%; object-fit: cover; }
    .upload-badge { position: absolute; bottom: -5px; right: -5px; background: #4f46e5; color: white; width: 34px; height: 34px; border-radius: 12px; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 3px solid white; transition: 0.3s; box-shadow: 0 4px 10px rgba(0,0,0,0.2); z-index: 10; }
    .enterprise-card { background: white; border-radius: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.05); overflow: hidden; }
    .modern-input { background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 14px; padding: 0.8rem 1.1rem; font-weight: 600; transition: all 0.2s; }
    .modern-input:focus { border-color: #4f46e5; background: white; outline: none; box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1); }
    .section-title { font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1.2px; }
</style>

<div class="container py-4 animate__animated animate__fadeIn">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="enterprise-card mb-4">
                <div class="profile-header-bg"></div>
                
                <form action="<?= base_url('/profile/update') ?>" method="post" enctype="multipart/form-data" id="formProfile" class="profile-form">
                    <?= csrf_field() ?>
                    
                    <div class="avatar-wrapper">
                     <div class="avatar-box" id="imagePreview">
                    <?php 
                        // 1. Ambil nama fail dari session atau database
                        $picName = session()->get('profile_pic') ?: $user['profile_pic'];
                        
                        // 2. TUKAR FCPATH KEPADA WRITEPATH
                        // Ini kerana folder 'uploads' anda sekarang berada di dalam folder 'writable'
                        $fullPath = WRITEPATH . 'uploads/profile/' . $picName;
                        
                        // 3. Semak jika fail wujud secara fizikal di folder writable
                        if (!empty($picName) && file_exists($fullPath)): 
                    ?>
                        <img src="<?= base_url('get-profile-pic/' . $picName) ?>?t=<?= time() ?>" alt="Profile">
                    <?php else: ?>
                        <span class="text-uppercase"><?= substr(session()->get('fullname') ?? $user['fullname'], 0, 1) ?></span>
                    <?php endif; ?>
                  </div>
                        <label for="profile_pic" class="upload-badge">
                            <i class="bi bi-camera-fill"></i>
                            <input type="file" name="profile_pic" id="profile_pic" class="d-none" accept="image/png, image/jpeg, image/jpg">
                        </label>
                    </div>

                    <div class="p-4 p-md-5 pt-4">
                        <div class="mb-5">
                            <h3 class="fw-800 text-dark mb-1"><?= esc($user['fullname']) ?></h3>
                            <p class="text-muted small fw-bold"><i class="bi bi-patch-check-fill text-primary"></i> Administrator Sistem</p>
                        </div>

                        <div class="d-flex align-items-center gap-2 mb-4">
                            <span class="section-title">Maklumat Peribadi</span>
                            <div class="flex-grow-1 border-bottom"></div>
                        </div>
                        <div class="row g-3 mb-5">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Nama Penuh</label>
                                <input type="text" name="fullname" class="form-control modern-input" value="<?= old('fullname', $user['fullname']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Alamat Emel</label>
                                <input type="email" name="email" class="form-control modern-input" value="<?= old('email', $user['email']) ?>" required>
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary px-4 fw-bold rounded-3 btn-submit">
                                    Simpan Perubahan Profil
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <form action="<?= base_url('/profile/update-password') ?>" method="post" id="formPassword" class="profile-form px-4 px-md-5 pb-5">
                    <?= csrf_field() ?>
                    <div class="d-flex align-items-center gap-2 mb-4">
                        <span class="section-title text-danger">Keselamatan Kata Laluan</span>
                        <div class="flex-grow-1 border-bottom"></div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Kata Laluan Lama</label>
                            <div class="input-group">
                                <input type="password" name="old_password" class="form-control modern-input" id="old_pw" required>
                                <button class="btn toggle-password border-2 border-start-0 bg-light" type="button" data-target="old_pw"><i class="bi bi-eye"></i></button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Kata Laluan Baru</label>
                            <div class="input-group">
                                <input type="password" name="new_password" class="form-control modern-input" id="new_pw" required>
                                <button class="btn toggle-password border-2 border-start-0 bg-light" type="button" data-target="new_pw"><i class="bi bi-eye"></i></button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Sahkan Kata Laluan</label>
                            <div class="input-group">
                                <input type="password" name="conf_password" class="form-control modern-input" id="conf_pw" required>
                                <button class="btn toggle-password border-2 border-start-0 bg-light" type="button" data-target="conf_pw"><i class="bi bi-eye"></i></button>
                            </div>
                        </div>
                        <div class="col-12 text-end mt-4">
                            <div id="pw-error" class="text-danger small mb-3 d-none">Kata laluan tidak sepadan!</div>
                            <button type="submit" class="btn btn-dark px-4 fw-bold rounded-3 btn-submit" id="btn-submit-pw">Kemaskini Password</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // 1. SWEETALERT FLASH MESSAGES
        <?php if(session()->getFlashdata('success')): ?>
            Swal.fire({ icon: 'success', title: 'Berjaya!', text: '<?= session()->getFlashdata('success') ?>', timer: 2500, showConfirmButton: false });
        <?php endif; ?>

        <?php if(session()->getFlashdata('error_pw')): ?>
            Swal.fire({ icon: 'error', title: 'Ralat!', text: '<?= session()->getFlashdata('error_pw') ?>' });
        <?php endif; ?>

        // 2. IMAGE PREVIEW & VALIDATION
        const profileInput = document.getElementById('profile_pic');
        const previewBox = document.getElementById('imagePreview');

        profileInput.onchange = function() {
            const file = this.files[0];
            if (file) {
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!allowedTypes.includes(file.type)) {
                    Swal.fire('Format Salah', 'Gunakan JPG/PNG sahaja.', 'error');
                    this.value = ''; return;
                }
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire('Terlalu Besar', 'Maksimum saiz 2MB.', 'error');
                    this.value = ''; return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    previewBox.innerHTML = `<img src="${e.target.result}" style="width:100%; height:100%; object-fit:cover;">`;
                }
                reader.readAsDataURL(file);
            }
        };

        // 3. CONFIRMATION POPUP
        document.querySelectorAll('.profile-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Kemaskini maklumat?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Simpan',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#4f46e5'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const btn = this.querySelector('.btn-submit');
                        btn.disabled = true;
                        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
                        this.submit();
                    }
                });
            });
        });

        // 4. PASSWORD LOGIC
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const input = document.getElementById(this.getAttribute('data-target'));
                const icon = this.querySelector('i');
                input.type = input.type === 'password' ? 'text' : 'password';
                icon.classList.toggle('bi-eye');
                icon.classList.toggle('bi-eye-slash');
            });
        });

        const newPw = document.getElementById('new_pw'), confPw = document.getElementById('conf_pw'), 
              err = document.getElementById('pw-error'), btn = document.getElementById('btn-submit-pw');
        function checkMatch() {
            if (confPw.value.length > 0) {
                const match = newPw.value === confPw.value;
                err.classList.toggle('d-none', match);
                confPw.style.borderColor = match ? '#198754' : '#dc3545';
                btn.disabled = !match;
            }
        }
        newPw.addEventListener('input', checkMatch);
        confPw.addEventListener('input', checkMatch);
    });
</script>
<?= $this->endSection() ?>