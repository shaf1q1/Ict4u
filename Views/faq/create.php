<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<style>
    :root {
        --primary-emerald: #10b981;
        --indigo-base: #4f46e5;
        --slate-bg: #f1f5f9;
    }

    body {
        background-color: var(--slate-bg);
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .create-container {
        max-width: 850px;
        margin: 40px auto;
    }

    .enterprise-card {
        background: #ffffff;
        border-radius: 24px;
        border: none;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .card-body-premium {
        padding: 40px;
    }

    .form-label {
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        margin-bottom: 0.75rem;
    }

    .modern-input {
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.85rem 1.25rem;
        font-weight: 500;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .modern-input:focus {
        background: #ffffff;
        border-color: var(--primary-emerald);
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        outline: none;
    }

    /* CKEditor Customization */
    .ck-editor__main>.ck-editor__editable {
        min-height: 200px;
        border-radius: 0 0 12px 12px !important;
        border: 2px solid #e2e8f0 !important;
        border-top: none !important;
    }
    .ck.ck-toolbar {
        border-radius: 12px 12px 0 0 !important;
        border: 2px solid #e2e8f0 !important;
        background: #f8fafc !important;
    }

    .btn-save {
        background: var(--primary-emerald);
        color: white;
        border-radius: 12px;
        padding: 12px 30px;
        font-weight: 700;
        border: none;
        box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.3);
        transition: all 0.3s;
    }

    .btn-save:hover {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: 0 20px 25px -5px rgba(16, 185, 129, 0.4);
        color: white;
    }

    .btn-back {
        color: #64748b;
        font-weight: 700;
        padding: 12px 20px;
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-back:hover {
        background: #f1f5f9;
        color: #1e293b;
    }

    .service-badge {
        background: #ecfdf5;
        color: #059669;
        padding: 6px 14px;
        border-radius: 100px;
        font-size: 0.85rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
</style>

<div class="container create-container animate__animated animate__fadeIn">
    
    <div class="mb-5 text-start">
        <div class="d-flex align-items-center gap-3 mb-2">
             <div class="bg-emerald-500 p-2 rounded-lg text-white">
                <i class="bi bi-plus-circle-fill fs-5"></i>
             </div>
             <span class="text-uppercase fw-extrabold text-muted small tracking-widest">New Content Creation</span>
        </div>
        <h2 class="fw-black text-dark tracking-tight">Tambah FAQ Baru</h2>
        <div class="mt-2">
            <span class="service-badge">
                <i class="bi bi-layers"></i> <?= esc($servis['namaservis']) ?>
            </span>
        </div>
    </div>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger border-0 shadow-sm rounded-4 p-3 mb-4 d-flex align-items-center gap-3 animate__animated animate__shakeX">
            <i class="bi bi-shield-exclamation fs-4"></i>
            <div class="fw-medium"><?= session()->getFlashdata('error') ?></div>
        </div>
    <?php endif; ?>

    <div class="enterprise-card">
        <form id="faqForm" method="post" action="<?= base_url('faq/store') ?>">
            <?= csrf_field() ?>

            <div class="card-body-premium">
                <input type="hidden" name="idservis" value="<?= esc($servis['idservis']) ?>">

                <div class="mb-5">
                    <label class="form-label">Soalan FAQ</label>
                    <input type="text" name="question" class="form-control modern-input" 
                           placeholder="Masukkan soalan lazim yang sering ditanya..." 
                           value="<?= old('question') ?>" required autofocus>
                    <div class="form-text mt-2 text-slate-400">Pastikan soalan diakhiri dengan tanda soal (?).</div>
                </div>

                <div class="mb-5">
                    <label class="form-label">Jawapan FAQ</label>
                    <textarea id="faqEditor" name="answer" class="form-control"><?= old('answer') ?></textarea>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-4 border-top">
                    <a href="<?= base_url('faq') ?>?servis=<?= esc($servis['idservis']) ?>" class="btn-back d-flex align-items-center gap-2">
                        <i class="bi bi-chevron-left"></i> Batal
                    </a>
                    <button type="submit" class="btn-save d-flex align-items-center gap-2">
                        <i class="bi bi-cloud-arrow-up"></i> Simpan FAQ
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="<?= base_url('ckeditor5-build-classic/build/ckeditor.js') ?>"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let editorInstance;

        // 1. Initialize CKEditor
        ClassicEditor
            .create(document.querySelector('#faqEditor'), {
                placeholder: 'Tulis jawapan lengkap di sini...',
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo']
            })
            .then(editor => {
                editorInstance = editor;
                
                // Live sync: tolak data ke textarea asal setiap kali user menaip
                editor.model.document.on('change:data', () => {
                    editor.updateSourceElement();
                });
            })
            .catch(error => {
                console.error(error);
            });

        // 2. Handle Form Submission with SweetAlert
        const form = document.getElementById('faqForm');
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Stop dulu untuk kita check data

            if (editorInstance) {
                const data = editorInstance.getData();
                const question = document.querySelector('input[name="question"]').value;

                // Update value textarea secara manual
                document.querySelector('#faqEditor').value = data;

                // Validasi Soalan
                if (!question.trim()) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Soalan Diperlukan',
                        text: 'Sila masukkan soalan FAQ.',
                        confirmButtonColor: '#10b981'
                    });
                    return;
                }

                // Validasi Jawapan (CKEditor)
                if (!data.trim() || data === '&nbsp;') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Jawapan Diperlukan',
                        text: 'Sila isi bahagian jawapan FAQ!',
                        confirmButtonColor: '#10b981'
                    });
                    return;
                }

                // Kalau semua OK, tunjuk loading dan submit
                Swal.fire({
                    title: 'Sila Tunggu...',
                    text: 'Sedang menyimpan FAQ baru anda',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Submit form secara manual
                this.submit();
            }
        });

        // 3. Paparkan Flashdata dari Controller (Success / Error)
        <?php if (session()->getFlashdata('success')) : ?>
            Swal.fire({
                icon: 'success',
                title: 'Berjaya!',
                text: '<?= session()->getFlashdata('success') ?>',
                timer: 3000,
                showConfirmButton: false
            });
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')) : ?>
            Swal.fire({
                icon: 'error',
                title: 'Ralat!',
                html: '<?= session()->getFlashdata('error') ?>',
                confirmButtonColor: '#ef4444'
            });
        <?php endif; ?>

        // Tambah ini di dalam DOMContentLoaded
        const questionInput = document.querySelector('input[name="question"]');
        const questionHelp = document.querySelector('.form-text');

        questionInput.addEventListener('input', function() {
            const len = this.value.length;
            questionHelp.innerHTML = `Karakter: ${len}/255. Pastikan soalan diakhiri dengan tanda soal (?).`;
            
            if (len < 5) {
                questionHelp.classList.add('text-danger');
            } else {
                questionHelp.classList.remove('text-danger');
            }
        });
    });
</script>
<?= $this->endSection() ?>
