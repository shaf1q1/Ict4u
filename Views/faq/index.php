<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<style>
    :root {
        --primary-indigo: #4f46e5;
        --soft-slate: #f8fafc;
    }

    body {
        background-color: #f1f5f9;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .faq-wrapper {
        background: #ffffff;
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
    }

    .dashboard-header {
        font-weight: 800;
        letter-spacing: -0.02em;
        color: #1e293b;
        font-size: 1.875rem;
    }

    /* Modern Select Styling */
    .form-select-lg {
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        padding: 0.75rem 1rem;
        font-weight: 500;
        transition: all 0.2s;
    }

    .form-select-lg:focus {
        border-color: var(--primary-indigo);
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    }

    /* Button Styling */
    .btn-create {
        background: var(--primary-indigo);
        color: white;
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 700;
        border: none;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
    }

    .btn-create:hover {
        background: #4338ca;
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(79, 70, 229, 0.3);
        color: white;
    }

    /* Accordion Styling */
    .accordion-item {
        border: 1px solid #e2e8f0 !important;
        border-radius: 16px !important;
        margin-bottom: 1rem;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .accordion-item:hover {
        border-color: var(--primary-indigo) !important;
        box-shadow: 0 12px 20px -8px rgba(0, 0, 0, 0.08);
    }

    .accordion-button {
        padding: 1.25rem 1.5rem;
        font-weight: 600;
        color: #334155;
        background: white;
    }

    .accordion-button:not(.collapsed) {
        background: #f5f3ff;
        color: var(--primary-indigo);
        box-shadow: none;
    }

    .accordion-button:focus {
        box-shadow: none;
    }

    .action-btns {
        padding-right: 1rem;
        display: flex;
        gap: 8px;
    }

    .btn-action {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.2s;
        border: 1px solid #e2e8f0;
        background: white;
    }

    .btn-edit:hover { background: #eff6ff; color: #2563eb; border-color: #bfdbfe; }
    .btn-delete:hover { background: #fef2f2; color: #dc2626; border-color: #fecaca; }

    /* Search Box Styling */
    .search-box .input-group-text {
        background: #fff;
        border-right: none;
        border-radius: 12px 0 0 12px;
    }
    .search-box input {
        border-left: none;
        border-radius: 0 12px 12px 0;
        box-shadow: none !important;
    }
    .search-box .input-group:focus-within {
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        border-radius: 12px;
    }
    .search-box .input-group:focus-within .form-control,
    .search-box .input-group:focus-within .input-group-text {
        border-color: var(--primary-indigo);
    }

    /* Skeleton Loading */
    .skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #f8fafc 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
        border-radius: 4px;
        display: inline-block;
    }

    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }
</style>

<div class="container-fluid py-4">
    <div class="faq-wrapper animate__animated animate__fadeIn">
        
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
            <div>
                <h2 class="dashboard-header mb-1">Frequently Asked Questions</h2>
                <p class="text-muted mb-0">Uruskan soalan lazim mengikut kategori servis sistem.</p>
            </div>
            <button id="btnCreateFaq" class="btn btn-create d-flex align-items-center gap-2">
                <i class="bi bi-plus-lg"></i> Tambah FAQ Baru
            </button>
        </div>

        <div class="row mb-4">
            <div class="col-md-5 mb-3 mb-md-0">
                <div class="p-4 rounded-4 border border-light shadow-sm bg-light h-100">
                    <label class="form-label fw-bold text-dark small text-uppercase tracking-wider">Kategori Servis</label>
                    <select id="servisSelect" class="form-select form-select-lg">
                        <option value="">-- Sila Pilih Servis --</option>
                        <?php foreach($servisList as $s): ?>
                            <option value="<?= $s['idservis'] ?>"><?= esc($s['namaservis']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="col-md-7">
                <div id="searchContainer" class="p-4 rounded-4 border border-light shadow-sm bg-white h-100 d-flex align-items-end" style="display: none !important;">
                    <div class="w-100 search-box">
                        <label class="form-label fw-bold text-dark small text-uppercase tracking-wider">Carian Pantas</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" id="faqSearch" class="form-control form-control-lg" 
                                placeholder="Taip soalan atau jawapan untuk menapis...">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="faqContainer">
            <div class="text-center py-5">
                <img src="https://illustrations.popsy.co/slate/searching.svg" alt="Search" style="width: 150px;" class="mb-4 opacity-50">
                <h5 class="text-slate-400">Pilih servis untuk memaparkan FAQ</h5>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(function(){

    // --- LOGIK AUTO-SELECT DARI URL ---
    const urlParams = new URLSearchParams(window.location.search);
    const serviceIdFromUrl = urlParams.get('servis');

    let selectedServis = serviceIdFromUrl || null;
    let refreshInterval = null;

    // Jika ada ID servis dalam URL, terus pilih dropdown dan load data
    if (serviceIdFromUrl) {
        $('#servisSelect').val(serviceIdFromUrl);
        skeleton();
        setTimeout(() => loadFaq(serviceIdFromUrl), 400);
        refreshInterval = setInterval(() => { loadFaq(serviceIdFromUrl); }, 30000);
    }
    // ---------------------------------

    // Tambah CSRF Setup secara Global untuk semua AJAX
    $.ajaxSetup({
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    });

    // 1. Notifikasi Global
    <?php if (session()->getFlashdata('success')) : ?>
        Swal.fire({
            icon: 'success',
            title: 'Berjaya!',
            text: '<?= session()->getFlashdata('success') ?>',
            timer: 2500,
            showConfirmButton: false
        });
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        Swal.fire({
            icon: 'error',
            title: 'Ralat!',
            text: '<?= session()->getFlashdata('error') ?>',
            confirmButtonColor: '#4f46e5'
        });
    <?php endif; ?>

    function skeleton(){
        let html = '<div class="accordion">';
        for(let i=0;i<3;i++){
            html += `
            <div class="accordion-item mb-3">
                <div class="p-4">
                    <div class="skeleton w-50" style="height: 24px;"></div>
                </div>
            </div>`;
        }
        html += '</div>';
        $('#faqContainer').html(html);
    }

    function loadFaq(id){
        if(!id) return;
        
        $.ajax({
            url: '<?= base_url("faq/ajax") ?>/' + id,
            method: 'GET',
            dataType: 'json',
            success: function(res){
                selectedServis = id;
                $('#searchContainer').slideDown().css('display', 'flex');

                if(res.success && res.faqs.length){
                    let html = '<div class="accordion animate__animated animate__fadeIn" id="faqAccordion">';
                    res.faqs.forEach((faq, i)=>{
                        html += `
                        <div class="accordion-item">
                            <div class="d-flex align-items-center bg-white">
                                <button class="accordion-button ${i>0?'collapsed':''}" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#faq${faq.id}">
                                    ${faq.question}
                                </button>
                                <div class="action-btns">
                                    <a href="<?= base_url("faq/edit") ?>/${faq.id}" class="btn-action btn-edit" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button class="btn-action btn-delete btnDeleteFaq" data-id="${faq.id}" title="Padam">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="faq${faq.id}" class="accordion-collapse collapse ${i===0?'show':''}" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-secondary lh-lg">
                                    ${faq.answer}
                                </div>
                            </div>
                        </div>`;
                    });
                    html += '</div>';
                    $('#faqContainer').html(html);
                } else {
                    $('#faqContainer').html(`
                        <div class="text-center py-5 animate__animated animate__fadeIn">
                            <div class="p-4 rounded-circle bg-light d-inline-block mb-3">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                            </div>
                            <h5 class="text-muted">Tiada rekod FAQ ditemui.</h5>
                            <p class="text-secondary small">Klik "Tambah FAQ Baru" untuk mulakan.</p>
                        </div>
                    `);
                }
                $('#faqSearch').trigger('keyup');
            },
            error: function(){
                $('#faqContainer').html('<div class="text-center py-5 text-danger">Gagal memuatkan data. Sila login semula.</div>');
            }
        });
    }

    $('#servisSelect').on('change', function(){
        let id = $(this).val();
        $('#faqSearch').val('');
        
        if(!id){
            $('#faqContainer').html(`
                <div class="text-center py-5">
                    <img src="https://illustrations.popsy.co/slate/searching.svg" alt="Search" style="width: 150px;" class="mb-4 opacity-50">
                    <h5 class="text-slate-400">Pilih servis untuk memaparkan FAQ</h5>
                </div>
            `);
            $('#searchContainer').slideUp();
            clearInterval(refreshInterval);
            // Update URL untuk buang parameter servis jika tiada pilihan
            const url = new URL(window.location);
            url.searchParams.delete('servis');
            window.history.pushState({}, '', url);
            return;
        }

        // Update URL tanpa refresh page bila user tukar dropdown
        const url = new URL(window.location);
        url.searchParams.set('servis', id);
        window.history.pushState({}, '', url);

        skeleton();
        setTimeout(() => loadFaq(id), 400); 

        clearInterval(refreshInterval);
        refreshInterval = setInterval(()=>{ loadFaq(id); }, 30000); 
    });

    $('#btnCreateFaq').on('click', function(){
        let id = $('#servisSelect').val();
        if(!id){
            Swal.fire({
                icon: 'warning',
                title: 'Pilih Servis',
                text: 'Sila pilih servis terlebih dahulu sebelum menambah FAQ.',
                confirmButtonColor: '#4f46e5'
            });
            return;
        }
        window.location.href = '<?= base_url("faq/create") ?>/' + id;
    });

    $(document).on('click', '.btnDeleteFaq', function(e){
        e.stopPropagation(); 
        let id = $(this).data('id');
        Swal.fire({
            title: 'Padam FAQ?',
            text: "Data ini akan dibuang secara kekal.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Padam',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Memadam...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });

                $.ajax({
                    url: '<?= base_url("faq/delete") ?>/' + id,
                    method: 'DELETE',
                    data: {
                        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                    },
                    success: function(res){ 
                        loadFaq(selectedServis);
                        Swal.fire({
                            icon: 'success',
                            title: 'Dipadam!',
                            text: res.message || 'FAQ telah berjaya dibuang.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    },
                    error: function(xhr){
                        let errorMsg = xhr.responseJSON ? xhr.responseJSON.message : 'Gagal memadam.';
                        Swal.fire('Ralat!', errorMsg, 'error');
                    }
                });
            }
        });
    });

    $('#faqSearch').on('keyup', function() {
        let value = $(this).val().toLowerCase();
        $("#faqAccordion .accordion-item").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });

        if ($('#faqAccordion .accordion-item:visible').length === 0 && value !== "") {
            if (!$('#noResults').length) {
                $('#faqAccordion').append('<div id="noResults" class="text-center py-4 text-muted border rounded-3 mt-3 bg-light">Tiada padanan ditemui...</div>');
            }
        } else {
            $('#noResults').remove();
        }
    });
});
</script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<?= $this->endSection() ?>