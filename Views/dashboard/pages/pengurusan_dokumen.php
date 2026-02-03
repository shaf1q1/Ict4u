<?php $uri = service('uri'); ?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Sistem Pengurusan Dokumen Modul</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary-color: #4361ee;
            --bg-body: #f4f7fe;
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        body { 
            background-color: var(--bg-body); 
            font-family: 'Inter', sans-serif;
            color: #2d3436;
        }
        /* ===== SIDEBAR ===== */
        .main-sidebar {
            background-color: #ffffff;
            border-right: 1px solid #e2e8f0;
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: -260px; /* hidden by default */
            transition: left 0.3s;
            z-index: 50;
            padding-top: 1rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .main-sidebar.active { left: 0; }
        .brand-link { display: block; text-align: center; padding: 1.5rem 1rem; border-bottom: 1px solid #f1f5f9; }
        .brand-text { color: #1e293b; letter-spacing: -1px; font-size: 1.5rem; font-weight: 900; }
        .sidebar .nav-link {
            color: #64748b; border-radius: 12px; margin: 4px 12px; padding: 10px 15px;
            font-weight: 600; font-size: 0.9rem; transition: all 0.2s; display: flex; align-items: center;
        }
        .sidebar .nav-link i { margin-right: 10px; color: #94a3b8; transition: all 0.2s; }
        .sidebar .nav-link.active { background-color: #f5f3ff; color: #4f46e5; }
        .sidebar .nav-link.active i { color: #4f46e5; }
        .sidebar .nav-link:hover:not(.active) { background-color: #f8fafc; color: #1e293b; }
        .nav-header { font-size: 0.7rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; padding: 1rem 1.5rem 0.5rem 1.5rem; }
        .nav-link.logout-btn { margin-top: 20px; border: 1px solid #fee2e2; color: #dc2626; }
        .nav-link.logout-btn:hover { background-color: #fef2f2; color: #dc2626; }

        /* ===== HAMBURGER BUTTON ===== */
        #sidebarToggle {
            position: fixed; top: 1rem; left: 1rem; z-index: 60;
            background: rgba(255,255,255,0.9); backdrop-filter: blur(5px);
            border: 1px solid #e2e8f0; padding: 0.75rem; border-radius: 12px;
            cursor: pointer; transition: all 0.2s;
        }
        #sidebarToggle:hover { background: #f8faff; }

        .page-title {
            font-weight: 700;
            letter-spacing: -0.5px;
            color: #1e293b;
        }

        /* Custom Select & Input */
        .form-select, .form-control {
            border-radius: 10px;
            padding: 10px 15px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .form-select:focus, .form-control:focus {
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
            border-color: var(--primary-color);
        }

        /* Modern Table Styling */
        .table {
            border-collapse: separate;
            border-spacing: 0 12px;
        }

        .table thead th {
            border: none;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 1px;
            padding: 0 1rem;
        }

        .table tbody tr {
            background-color: white;
            box-shadow: var(--card-shadow);
            transition: transform 0.2s ease;
        }

        .table tbody tr:hover {
            transform: translateY(-3px);
            background-color: #fafbff;
        }

        .table td {
            padding: 1.25rem 1rem;
            border: none;
            vertical-align: middle;
        }

        .table td:first-child { border-radius: 15px 0 0 15px; }
        .table td:last-child { border-radius: 0 15px 15px 0; }

        /* Badge Styling */
        .badge {
            padding: 8px 14px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 10px;
            letter-spacing: 0.5px;
        }
        .status-pending { background-color: #fff9db; color: #f08c00; }
        .status-approved { background-color: #ebfbee; color: #2b8a3e; }
        .status-rejected { background-color: #fff5f5; color: #c92a2a; }

        /* Preview Box */
        .preview-box {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            object-fit: cover;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e2e8f0;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
        }

        .modal-content {
            border-radius: 24px;
            border: none;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.2);
        }
        
        .action-btn {
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: all 0.2s;
        }
    </style>
</head>
<body>
<aside class="main-sidebar" id="mainSidebar">
    <a href="<?= site_url('/') ?>" class="brand-link">
        <span class="brand-text fw-black">ICT4U<span class="text-primary">.</span></span>
    </a>
    <div class="sidebar">
        <nav class="mt-3">
            <ul class="nav flex-column">

                <li class="nav-item">
                    <a href="<?= site_url('/') ?>" class="nav-link <?= $uri->getSegment(1, '') === '' ? 'active' : '' ?>">
                        <i class="bi bi-grid-fill"></i> Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= base_url('dashboard/loadPage/approvaldokumen') ?>" 
                       class="nav-link <?= (count($uri->getSegments()) >= 2 && $uri->getSegment(2) === 'approvaldokumen') ? 'active' : '' ?>">
                        <i class="bi bi-check-all"></i> Pengesahan Dokumen
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= base_url('dashboard/loadPage/dokumen') ?>" 
                       class="nav-link <?= (count($uri->getSegments()) >= 2 && $uri->getSegment(2) === 'dokumen') ? 'active' : '' ?>">
                        <i class="bi bi-files"></i> Pengurusan Dokumen
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= site_url('perincianmodul') ?>" class="nav-link <?= $uri->getSegment(1, '') === 'perincianmodul' ? 'active' : '' ?>">
                        <i class="bi bi-collection-fill"></i> Perincian Modul
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= site_url('dashboard/TambahanPerincian') ?>" 
                       class="nav-link <?= (count($uri->getSegments()) >= 2 && $uri->getSegment(2) === 'TambahanPerincian') ? 'active' : '' ?>">
                        <i class="bi bi-folder-plus"></i> Tambahan Perincian
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= site_url('faq/1') ?>" class="nav-link <?= $uri->getSegment(1) === 'faq' ? 'active' : '' ?>">
                        <i class="bi bi-question-diamond-fill"></i> FAQ
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= site_url('profile') ?>" class="nav-link <?= $uri->getSegment(1) === 'profile' ? 'active' : '' ?>">
                        <i class="bi bi-person-bounding-box"></i> My Profile
                    </a>
                </li>

                <li class="nav-item mt-4">
                    <a href="<?= site_url('logout') ?>" class="nav-link logout-btn">
                        <i class="bi bi-box-arrow-left"></i> Sign Out
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>
<button id="sidebarToggle">
    <i class="bi bi-list h-6 w-6"></i>
</button>

<div class="container py-5">
    <div class="row mb-5 align-items-center">
        <div class="col-md-6">
            <h2 class="page-title mb-1">Pengurusan Dokumen Modul</h2>

        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <button id="btnTambahModal" class="btn btn-primary shadow" data-bs-toggle="modal" data-bs-target="#modalTambah" disabled>
                <i class="bi bi-cloud-arrow-up-fill me-2"></i> Muat Naik Dokumen
            </button>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-5">
                    <label class="form-label fw-bold text-secondary mb-2 small">PILIH KATEGORI SERVIS</label>
                    <select id="dropdownServis" class="form-select shadow-sm">
                        <option value="">Sila Pilih Servis...</option>
                        <?php foreach($servis as $s): ?>
                            <option value="<?= esc($s['idservis']) ?>"><?= esc($s['namaservis']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div id="dokumenArea" class="table-responsive">
        <div class="text-center py-5">
            <img src="https://cdn-icons-png.flaticon.com/512/5082/5082574.png" width="120" class="opacity-25 mb-3">
            <h5 class="text-muted fw-light">Pilih servis untuk memaparkan senarai dokumen</h5>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-2">
            <form id="formTambah">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Muat Naik Dokumen Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="idservis" id="inputServisTambah">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tajuk Dokumen</label>
                        <input type="text" name="nama" class="form-control" required placeholder="Contoh: Sijil Kelayakan">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Penerangan / Nota</label>
                        <textarea name="descdoc" class="form-control" rows="3" placeholder="Nota tambahan tentang dokumen ini..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Fail (PDF Sahaja - Maks 10MB)</label>
                        <input type="file" name="file" class="form-control" accept="application/pdf" required>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="submit" class="btn btn-primary w-100 py-3 mt-2">Hantar Dokumen</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-2">
            <form id="formEdit">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Kemaskini Maklumat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="iddoc" id="edit_iddoc">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Dokumen</label>
                        <input type="text" name="nama" id="edit_nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Catatan</label>
                        <textarea name="descdoc" id="edit_desc" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tukar Fail (PDF Sahaja - Maks 10MB)</label>
                        <input type="file" name="file" id="edit_file" class="form-control" accept="application/pdf">
                        <small class="text-muted">Biarkan kosong jika tiada perubahan fail.</small>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="submit" class="btn btn-primary w-100 py-3">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    
    const sidebar = document.getElementById('mainSidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const pageContainer = document.querySelector('.main-container');

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('active');
        if(pageContainer) pageContainer.classList.toggle('shifted');
    });

    function refreshTable(idservis){
        if(!idservis){
            $('#dokumenArea').html('<div class="text-center py-5"><p class="text-muted">Sila pilih servis.</p></div>');
            $('#btnTambahModal').prop('disabled', true);
            return;
        }
        
        $('#btnTambahModal').prop('disabled', false);

        $.get('/dokumen/getDokumen/' + idservis, function(res){
            var items = res.items;
            if(!items || items.length === 0){
                $('#dokumenArea').html('<div class="alert bg-white shadow-sm rounded-4 text-center p-5 fw-medium text-muted">Tiada dokumen dijumpai untuk servis ini.</div>');
                return;
            }

            var html = '<table class="table align-middle">';
            html += '<thead><tr><th>Fail</th><th>Maklumat Dokumen</th><th class="text-center">Status</th><th class="text-end pe-4">Aksi</th></tr></thead><tbody>';

            for(var i=0; i<items.length; i++){
                var d = items[i];
                var fileUrl = '/dokumen/viewFile/'+d.idservis+'/'+d.namafail;
                
                var previewHtml = '';
                if(d.mime && d.mime.includes('image')){
                    previewHtml = '<img class="preview-box shadow-sm" src="'+fileUrl+'">';
                } else {
                    previewHtml = '<div class="preview-box shadow-sm text-danger"><i class="bi bi-file-earmark-pdf-fill fs-4"></i></div>';
                }

                html += '<tr>' +
                    '<td style="width: 80px">' + previewHtml + '</td>' +
                    '<td>' +
                        '<div class="fw-bold text-dark mb-1" style="font-size: 1rem;">' + d.nama + '</div>' +
                        '<div class="text-muted small mb-2 text-truncate" style="max-width: 300px;">' + (d.descdoc || '<i>Tiada catatan</i>') + '</div>' +
                        
                        '<div class="d-flex flex-wrap gap-3">' +
                            '<span class="text-muted small" style="font-size: 11px;">' +
                                '<i class="bi bi-calendar-plus me-1 text-secondary"></i>Dicipta: ' + formatDate(d.created_at) + 
                            '</span>' +
                            '<span class="text-primary small" style="font-size: 11px;">' +
                                '<i class="bi bi-calendar-check me-1"></i>Kemaskini: ' + formatDate(d.updated_at) + 
                            '</span>' +
                        '</div>' +
                    '</td>' +
                    '<td class="text-center"><span class="badge status-' + d.status + '">' + d.status.toUpperCase() + '</span></td>' +
                    '<td>' +
                        '<div class="d-flex justify-content-end gap-2 pe-3">' +
                            '<a href="'+fileUrl+'" target="_blank" class="action-btn btn btn-light text-secondary" title="Paparan Fail"><i class="bi bi-eye"></i></a>' +
                            '<button onclick="editDokumen('+d.iddoc+')" class="action-btn btn btn-light text-primary" title="Kemaskini"><i class="bi bi-pencil-square"></i></button>' +
                            '<button onclick="hapusDokumen('+d.iddoc+')" class="action-btn btn btn-light text-danger" title="Padam"><i class="bi bi-trash"></i></button>' +
                        '</div>' +
                    '</td>' +
                '</tr>';
            }
            html += '</tbody></table>';
            $('#dokumenArea').html(html);
        });
    }

    function formatDate(dateString){
        if(!dateString) return '-';
        var d = new Date(dateString);
        return d.toLocaleDateString('ms-MY', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
    }

    $('#dropdownServis').change(function(){
        var val = $(this).val();
        $('#inputServisTambah').val(val);
        refreshTable(val);
    });

    // VALIDASI TAMBAH
    $('#formTambah').submit(function(e){
        e.preventDefault();
        
        var fileInput = $(this).find('input[type="file"]')[0];
        if(fileInput.files.length > 0){
            var file = fileInput.files[0];
            if(file.type !== "application/pdf"){
                Swal.fire('Ralat!', 'Hanya format PDF sahaja yang dibenarkan.', 'error');
                return false;
            }
            if(file.size > 10 * 1024 * 1024){
                Swal.fire('Ralat!', 'Saiz fail tidak boleh melebihi 10MB.', 'error');
                return false;
            }
        }

        var formData = new FormData(this);
        $.ajax({
            url:'/dokumen/tambah',
            type:'POST',
            data:formData,
            processData:false,
            contentType:false,
            success:function(res){
                if(res.status){
                    Swal.fire({ icon: 'success', title: 'Berjaya', text: res.msg, showConfirmButton: false, timer: 1500 });
                    $('#formTambah')[0].reset();
                    bootstrap.Modal.getInstance(document.getElementById('modalTambah')).hide();
                    refreshTable($('#dropdownServis').val());
                } else {
                    Swal.fire('Gagal', res.msg || 'Ralat berlaku', 'error');
                }
            }
        });
    });

    function hapusDokumen(iddoc){
        Swal.fire({
            title: 'Hapus Fail?',
            text: "Fail akan dipadam secara kekal dari server!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4361ee',
            cancelButtonColor: '#ef4444',
            confirmButtonText: 'Ya, Padam',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('/dokumen/hapus/' + iddoc, function(res){
                    if(res.status) {
                        Swal.fire('Terpadam!', res.msg, 'success');
                        refreshTable($('#dropdownServis').val());
                    }
                });
            }
        });
    }

    function editDokumen(iddoc){
        $.get('/dokumen/edit/' + iddoc, function(res){
            if(res.status){
                $('#edit_iddoc').val(res.data.iddoc);
                $('#edit_nama').val(res.data.nama);
                $('#edit_desc').val(res.data.descdoc);
                $('#edit_file').val(''); // Reset file input
                new bootstrap.Modal(document.getElementById('modalEdit')).show();
            }
        });
    }

    // VALIDASI EDIT
    $('#formEdit').submit(function(e){
        e.preventDefault();
        
        var fileInput = $('#edit_file')[0];
        if(fileInput.files.length > 0){
            var file = fileInput.files[0];
            if(file.type !== "application/pdf"){
                Swal.fire('Ralat!', 'Hanya format PDF sahaja yang dibenarkan.', 'error');
                return false;
            }
            if(file.size > 10 * 1024 * 1024){
                Swal.fire('Ralat!', 'Saiz fail tidak boleh melebihi 10MB.', 'error');
                return false;
            }
        }

        var formData = new FormData(this);
        $.ajax({
            url:'/dokumen/kemaskini/' + $('#edit_iddoc').val(),
            type:'POST',
            data:formData,
            processData:false,
            contentType:false,
            success:function(res){
                if(res.status){
                    Swal.fire({ icon: 'success', title: 'Berjaya Dikemaskini', showConfirmButton: false, timer: 1500 });
                    bootstrap.Modal.getInstance(document.getElementById('modalEdit')).hide();
                    refreshTable($('#dropdownServis').val());
                } else {
                    Swal.fire('Gagal', res.msg || 'Ralat berlaku', 'error');
                }
            }
        });
    });
</script>
</body>
</html>