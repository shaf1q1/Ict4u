<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<style>
    :root {
        --primary-indigo: #4f46e5;
        --soft-bg: #f1f5f9;
    }

    body {
        background-color: var(--soft-bg);
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .edit-container {
        max-width: 850px;
        margin: 40px auto;
    }

    .enterprise-card {
        background: #ffffff;
        border-radius: 24px;
        border: none;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
        overflow: hidden;
    }

    .card-header-premium {
        background: #ffffff;
        padding: 30px 40px;
        border-bottom: 1px solid #f1f5f9;
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
        border-color: var(--primary-indigo);
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
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
        background: var(--primary-indigo);
        color: white;
        border-radius: 12px;
        padding: 12px 30px;
        font-weight: 700;
        border: none;
        box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
        transition: all 0.3s;
    }

    .btn-save:hover {
        background: #4338ca;
        transform: translateY(-2px);
        box-shadow: 0 20px 25px -5px rgba(79, 70, 229, 0.4);
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
        background: #eef2ff;
        color: #4f46e5;
        padding: 6px 14px;
        border-radius: 100px;
        font-size: 0.85rem;
        font-weight: 700;
    }
</style>

<div class="container edit-container animate__animated animate__fadeIn">
    
    <div class="mb-5">
        <div class="d-flex align-items-center gap-3 mb-2">
             <i class="bi bi-pencil-square text-indigo-600 fs-4"></i>
             <span class="text-uppercase fw-extrabold text-muted small tracking-widest">FAQ Configuration</span>
        </div>
        <h2 class="fw-black text-dark tracking-tight">Kemaskini FAQ</h2>
        <div class="mt-2">
            <span class="service-badge">
                <i class="bi bi-layers-fill me-1"></i> <?= esc($servis['namaservis']) ?>
            </span>
        </div>
    </div>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger border-0 shadow-sm rounded-4 p-3 mb-4 d-flex align-items-center gap-3">
            <i class="bi bi-exclamation-octagon-fill fs-4"></i>
            <div><?= session()->getFlashdata('error') ?></div>
        </div>
    <?php endif; ?>

    <div class="enterprise-card">
        <form id="editFaqForm" method="post" action="<?= base_url('/faq/update/'.$faq['id']) ?>">
            <?= csrf_field() ?>

            <div class="card-body-premium">
                <input type="hidden" name="idservis" value="<?= esc($servis['idservis']) ?>">

                <div class="mb-5">
                    <label class="form-label">Soalan Lazim</label>
                    <input type="text" name="question" class="form-control modern-input" 
                           placeholder="cth: Bagaimanakah cara untuk mendaftar?" 
                           value="<?= old('question', esc($faq['question'])) ?>" required>
                    <div class="form-text mt-2">Pastikan soalan ringkas dan mudah difahami.</div>
                </div>

                <div class="mb-5">
                    <label class="form-label">Jawapan Terperinci</label>
                    <textarea id="faqEditor" name="answer" class="form-control" required><?= old('answer', $faq['answer']) ?></textarea>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-4 border-t">
                    <a href="<?= base_url('/faq') ?>?servis=<?= esc($servis['idservis']) ?>" class="btn-back d-flex align-items-center gap-2">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn-save d-flex align-items-center gap-2">
                        <i class="bi bi-check2-circle"></i> Sahkan & Kemaskini
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="<?= base_url('ckeditor5-build-classic/build/ckeditor.js') ?>"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let editorInstance;

        ClassicEditor
            .create(document.querySelector('#faqEditor'), {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo'],
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                        { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                    ]
                }
            })
            .then(editor => {
                editorInstance = editor;
                
                // Live sync data dari editor ke textarea asal
                editor.model.document.on('change:data', () => {
                    editor.updateSourceElement();
                });
            })
            .catch(error => {
                console.error(error);
            });

        // Pastikan data editor disubmit bersama form
        const form = document.getElementById('editFaqForm');
        form.addEventListener('submit', function() {
            if (editorInstance) {
                document.querySelector('#faqEditor').value = editorInstance.getData();
            }
        });
    });
</script>

<?= $this->endSection() ?>  