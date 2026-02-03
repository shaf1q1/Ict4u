<?php $uri = service('uri'); ?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Perincian Modul</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <script src="<?= base_url('ckeditor5-build-classic/build/ckeditor.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* ===== GLOBAL STYLES ===== */
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f8fafc;
            color: #1e293b;
            background-image: radial-gradient(#e2e8f0 1px, transparent 1px);
            background-size: 20px 20px;
        }

        /* ===== SIDEBAR ===== */
        .main-sidebar {
            background-color: #ffffff;
            border-right: 1px solid #e2e8f0;
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: -260px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 50;
            padding-top: 1rem;
        }
        .main-sidebar.active { left: 0; }
        .brand-link { display: block; text-align: center; padding: 1.5rem 1rem; border-bottom: 1px solid #f1f5f9; }
        .brand-text { color: #1e293b; letter-spacing: -1px; font-size: 1.5rem; font-weight: 900; }
        
        .sidebar .nav-link {
            color: #64748b; border-radius: 12px; margin: 4px 12px; padding: 10px 15px;
            font-weight: 600; font-size: 0.9rem; transition: all 0.2s; display: flex; align-items: center;
        }
        .sidebar .nav-link i { margin-right: 10px; color: #94a3b8; font-size: 1.1rem; }
        .sidebar .nav-link.active { background-color: #f5f3ff; color: #4f46e5; }
        .sidebar .nav-link.active i { color: #4f46e5; }
        .sidebar .nav-link:hover:not(.active) { background-color: #f8fafc; color: #1e293b; }
        
        .logout-btn { margin-top: 20px; border: 1px solid #fee2e2 !important; color: #dc2626 !important; }
        .logout-btn:hover { background-color: #fef2f2 !important; }

        /* ===== UI LAYOUT ===== */
        #sidebarToggle {
            position: fixed; top: 1rem; left: 1rem; z-index: 60;
            background: rgba(255,255,255,0.9); backdrop-filter: blur(5px);
            border: 1px solid #e2e8f0; padding: 0.75rem; border-radius: 12px;
            cursor: pointer; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }
        .page-wrapper { transition: margin-left 0.3s ease; padding: 5rem 1.5rem 2rem 1.5rem; }
        .page-wrapper.shifted { margin-left: 250px; }

        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
            border-radius: 24px;
            max-width: 900px; margin: auto; padding: 2.5rem;
        }

        /* ===== FORM ELEMENTS ===== */
        label { font-size: 0.75rem; font-weight: 800; color: #64748b; margin-bottom: 0.5rem; display: block; text-transform: uppercase; letter-spacing: 0.05em; }
        .modern-input { width: 100%; padding: 0.75rem 1rem; border-radius: 12px; border: 2px solid transparent; background: #f1f5f9; transition: all 0.2s; font-weight: 500; }
        .modern-input:focus { border-color: #6366f1; background: #fff; outline: none; box-shadow: 0 0 0 4px rgba(99,102,241,0.1); }

        /* Custom Dropdown */
        #dropdownButton { background:#fff; border:2px solid #e2e8f0; border-radius:14px; padding:0.85rem 1.25rem; display:flex; justify-content:space-between; align-items:center; cursor:pointer; font-weight:600; transition: 0.2s; }
        #dropdownButton:hover { border-color: #6366f1; }
        #dropdownList { position:absolute; background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden; max-height:250px; overflow-y:auto; display:none; z-index:100; margin-top:8px; width: 100%; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }
        #dropdownList li { padding:0.75rem 1.25rem; cursor:pointer; transition:0.2s; border-bottom: 1px solid #f8fafc; }
        #dropdownList li:hover { background:#f5f3ff; color:#4f46e5; padding-left: 1.5rem; }

        /* CKEditor Customization */
        .ck-editor__main>.ck-editor__editable { min-height:250px; padding:1rem 1.5rem !important; }
        .ck.ck-editor { border:2px solid #f1f5f9 !important; border-radius:16px !important; overflow:hidden; }

        /* Buttons */
        .btn-primary { background:#6366f1; color:white; font-weight:700; padding:0.75rem 2.5rem; border-radius:12px; transition:0.2s; }
        .btn-primary:hover { background:#4f46e5; transform:translateY(-2px); box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3); }
        .btn-reset { color:#64748b; font-weight:700; padding:0.75rem 1.5rem; border-radius:12px; transition:0.2s; }
        .btn-reset:hover { background:#f1f5f9; color:#1e293b; }

        #servisForm:not(.hidden) { animation: slideUp 0.4s ease-out; }
        @keyframes slideUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
    </style>
</head>
<body>

<aside class="main-sidebar" id="mainSidebar">
    <a href="<?= site_url('/') ?>" class="brand-link">
        <span class="brand-text">ICT4U<span class="text-indigo-600">.</span></span>
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
                    <a href="<?= base_url('dashboard/loadPage/approvaldokumen') ?>" class="nav-link <?= ($uri->getSegment(2) === 'approvaldokumen') ? 'active' : '' ?>">
                        <i class="bi bi-check-all"></i> Pengesahan Dokumen
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('dashboard/loadPage/dokumen') ?>" class="nav-link <?= ($uri->getSegment(2) === 'dokumen') ? 'active' : '' ?>">
                        <i class="bi bi-files"></i> Pengurusan Dokumen
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('perincianmodul') ?>" class="nav-link <?= $uri->getSegment(1) === 'perincianmodul' ? 'active' : '' ?>">
                        <i class="bi bi-collection-fill"></i> Perincian Modul
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('dashboard/TambahanPerincian') ?>" class="nav-link <?= ($uri->getSegment(2) === 'TambahanPerincian') ? 'active' : '' ?>">
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

<button id="sidebarToggle"><i class="bi bi-list"></i></button>

<div class="page-wrapper" id="pageWrapper">
    <div class="glass-card">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-black text-slate-900 tracking-tight mb-2">Sistem Perincian Modul</h1>
            <p class="text-slate-500 font-medium">Kemaskini maklumat perincian servis dengan mudah.</p>
        </div>

        <div class="mb-10 relative">
            <label>Pilih Servis Utama</label>
            <button id="dropdownButton" class="w-full">
                <span>-- Sila Pilih Servis --</span>
                <i class="bi bi-chevron-down"></i>
            </button>
            <ul id="dropdownList">
                <?php foreach($servisList as $servis): ?>
                <li data-id="<?= $servis['idservis'] ?>" 
                    data-name="<?= htmlspecialchars($servis['namaservis']) ?>" 
                    data-infourl="<?= htmlspecialchars($servis['infourl']) ?>" 
                    data-mohonurl="<?= htmlspecialchars($servis['mohonurl']) ?>">
                    <?= htmlspecialchars($servis['namaservis']) ?>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <form id="servisForm" action="<?= site_url('perincianmodul/save') ?>" method="POST" class="hidden space-y-8">
            <?= csrf_field() ?>
            <input type="hidden" name="idservis" id="idservis">

            <div>
                <label>Nama Servis Rasmi (Max 145 Aksara)</label>
                <input id="namaservis" name="namaservis" class="modern-input" maxlength="145" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label>Info URL (http/https/ftp)</label>
                    <input type="url" id="infourl" name="infourl" class="modern-input" placeholder="https://...">
                </div>
                <div>
                    <label>Mohon URL (http/https/ftp)</label>
                    <input type="url" id="mohonurl" name="mohonurl" class="modern-input" placeholder="https://...">
                </div>
            </div>

            <div>
                <label>Description / Perincian</label>
                <textarea id="description" name="description"></textarea>
            </div>

            <div class="flex justify-end gap-4 pt-4">
                <button type="button" id="btnResetForm" class="btn-reset">Reset</button>
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Sidebar Control
    const sidebar = document.getElementById('mainSidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const pageWrapper = document.getElementById('pageWrapper');

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('active');
        pageWrapper.classList.toggle('shifted');
    });

    // 2. Elements
    const dropdownBtn = document.getElementById('dropdownButton');
    const dropdownList = document.getElementById('dropdownList');
    const form = document.getElementById('servisForm');
    const btnReset = document.getElementById('btnResetForm');
    
    const idField = document.getElementById('idservis');
    const nameField = document.getElementById('namaservis');
    const infoField = document.getElementById('infourl');
    const mohonField = document.getElementById('mohonurl');
    const descField = document.getElementById('description');

    let editor;
    let currentSelectedLi = null;

    // 3. CKEditor Initialization
    ClassicEditor.create(descField, {
        toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo']
    }).then(e => { editor = e; }).catch(console.error);

    // 4. Dropdown Toggle
    dropdownBtn.onclick = (e) => {
        e.stopPropagation();
        dropdownList.style.display = dropdownList.style.display === 'block' ? 'none' : 'block';
    };
    window.onclick = () => { dropdownList.style.display = 'none'; };

    // 5. Populate Data Function
    function loadData(li) {
        if(!li) return;
        currentSelectedLi = li;
        idField.value = li.dataset.id;
        nameField.value = li.dataset.name;
        infoField.value = li.dataset.infourl || '';
        mohonField.value = li.dataset.mohonurl || '';

        editor.setData('<p><i>Memuatkan data...</i></p>');
        fetch(`<?= base_url('perincianmodul/getServis') ?>/${li.dataset.id}`)
            .then(r => r.json())
            .then(d => {
                editor.setData(d.desc?.description || '');
            })
            .catch(() => editor.setData('Ralat memuatkan data.'));
    }

    // 6. Handle Item Selection
    dropdownList.querySelectorAll('li').forEach(item => {
        item.onclick = function() {
            dropdownBtn.querySelector('span').textContent = this.dataset.name;
            form.classList.remove('hidden');
            loadData(this);
        };
    });

    // 7. Logic Reset
    btnReset.onclick = () => {
        if(currentSelectedLi) {
            Swal.fire({
                title: 'Reset Semula?',
                text: "Data akan dikembalikan kepada maklumat asal.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#6366f1'
            }).then((result) => {
                if (result.isConfirmed) loadData(currentSelectedLi);
            });
        }
    };

    // 8. FORM VALIDATION LOGIC
    const urlPattern = /^(https?|ftp):\/\/[^\s/$.?#].[^\s]*$/i;
    const keyboardPattern = /^[a-zA-Z0-9\s!"#$%&'()*+,-./:;<=>?@[\\\]^_`{|}~]*$/;

    // Perkara 1.1: Sekat Paste aksara pelik
    nameField.addEventListener('paste', function(e) {
        const pasteData = (e.clipboardData || window.clipboardData).getData('text');
        if (!keyboardPattern.test(pasteData)) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Aksara Tidak Sah',
                text: 'Hanya aksara dari papan kekunci sahaja dibenarkan.',
                confirmButtonColor: '#6366f1'
            });
        }
    });

    form.onsubmit = function(e) {
        const skrg = new Date();
        const timestamp = skrg.toLocaleDateString('ms-MY') + ', ' + 
                          skrg.toLocaleTimeString('ms-MY', { hour: '2-digit', minute: '2-digit', hour12: true });
        
        let errorMsg = "";

        // Validasi 1.1: Nama Servis
        if (!keyboardPattern.test(nameField.value)) {
            errorMsg = "Nama Servis mengandungi aksara dilarang (Gunakan papan kekunci sahaja).";
        } else if (nameField.value.length > 145) {
            errorMsg = "Nama Servis tidak boleh melebihi 145 aksara.";
        }
        // Validasi 1.2: Info URL
        else if (infoField.value.trim() !== "" && !urlPattern.test(infoField.value)) {
            errorMsg = "Format Info URL tidak sah (Mesti bermula dengan http://, https:// atau ftp://).";
        }
        // Validasi 1.3: Mohon URL
        else if (mohonField.value.trim() !== "" && !urlPattern.test(mohonField.value)) {
            errorMsg = "Format Mohon URL tidak sah (Mesti bermula dengan http://, https:// atau ftp://).";
        }
        // Validasi Description
        else if (!editor.getData().trim()){
            errorMsg = "Sila isi perincian description sebelum simpan.";
        }

        if (errorMsg !== "") {
            e.preventDefault();
            Swal.fire({ 
                icon: 'error', 
                title: 'Ralat Validasi', 
                html: `<div class="text-center"><p class="mb-2 text-sm">${errorMsg}</p><p class="text-xs text-slate-400">${timestamp}</p></div>`,
                confirmButtonColor: '#6366f1' 
            });
            return false;
        }
    };

    // 9. SweetAlert Notifications (Server-Side)
    const skrgGlobal = new Date();
    const tsGlobal = skrgGlobal.toLocaleDateString('ms-MY') + ', ' + 
                     skrgGlobal.toLocaleTimeString('ms-MY', { hour: '2-digit', minute: '2-digit', hour12: true });

    <?php if(session()->getFlashdata('success')): ?>
        Swal.fire({ 
            icon: 'success', 
            title: 'Berjaya Disimpan', 
            html: `<div class="text-center"><p class="mb-2"><?= session()->getFlashdata("success") ?></p><hr class="my-2 border-slate-100"><p class="text-xs text-slate-400 font-semibold"><i class="bi bi-clock-history mr-1"></i> ${tsGlobal}</p></div>`,
            confirmButtonColor: '#6366f1'
        });
    <?php endif; ?>

    <?php if(session()->getFlashdata('error')): ?>
        Swal.fire({ 
            icon: 'error', 
            title: 'Ralat Sistem', 
            html: `<div class="text-center"><p class="mb-2"><?= session()->getFlashdata("error") ?></p><p class="text-xs text-red-400 font-medium">${tsGlobal}</p></div>`,
            confirmButtonColor: '#6366f1' 
        });
    <?php endif; ?>
});
</script>

</body>
</html>