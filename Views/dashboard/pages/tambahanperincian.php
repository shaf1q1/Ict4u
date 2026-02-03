<?php $uri = service('uri'); ?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pengurusan Perincian | ICT4U</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="<?= base_url('ckeditor5-build-classic/build/ckeditor.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; color: #1e293b; }
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

        .main-container { max-width: 1200px; margin: 0 auto; padding: 2rem; }
        
        .card-modern {
            background: #ffffff; border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); border-radius: 24px;
        }

        .modern-input {
            width: 100%; background: #f1f5f9; border: 2px solid transparent; border-radius: 14px;
            padding: 0.8rem 1rem; transition: all 0.2s; font-size: 0.9rem;
        }
        .modern-input:focus { background: #fff; border-color: #6366f1; outline: none; }

        /* Sidebar Styling */
        .main-sidebar {
            background: white; border-right: 1px solid #e2e8f0; width: 260px; height: 100vh;
            position: fixed; top: 0; left: -270px; transition: all 0.3s; z-index: 50;
        }
        .main-sidebar.active { left: 0; }
        .sidebar .nav-link {
            color: #64748b; border-radius: 12px; margin: 4px 16px; padding: 12px 16px;
            font-weight: 600; display: flex; align-items: center;
        }
        .sidebar .nav-link.active { background: #6366f1; color: white; }

        #sidebarToggle {
            position: fixed; top: 1.5rem; left: 1.5rem; z-index: 60;
            background: white; border: 1px solid #e2e8f0; padding: 0.8rem; border-radius: 14px;
        }

        #editorOverlay {
            display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px); z-index: 100; align-items: center; justify-content: center; padding: 1.5rem;
        }

        .btn-link-info {
            padding: 6px 12px; border-radius: 10px; font-size: 11px; font-weight: 800;
            transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px;
        }
        .btn-link-exists { background: #eef2ff; color: #4f46e5; border: 1px solid #e0e7ff; cursor: pointer; }
        .btn-link-exists:hover { background: #4f46e5; color: white; }
        .btn-link-none { background: #f1f5f9; color: #94a3b8; cursor: not-allowed; border: 1px solid #e2e8f0; }
    </style>
</head>
<body>

<!-- ===== SIDEBAR ===== -->
<aside class="main-sidebar" id="mainSidebar">
    <a href="<?= site_url('/') ?>" class="brand-link">
        <span class="brand-text fw-black">ICT4U<span class="text-primary">.</span></span>
    </a>
    <div class="sidebar">
        <nav class="mt-3">
            <ul class="nav flex-column">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="<?= site_url('/') ?>" class="nav-link <?= $uri->getSegment(1, '') === '' ? 'active' : '' ?>">
                        <i class="bi bi-grid-fill"></i> Dashboard
                    </a>
                </li>

                <!-- Pengesahan Dokumen -->
                <li class="nav-item">
                    <a href="<?= base_url('dashboard/loadPage/approvaldokumen') ?>" 
                       class="nav-link <?= (count($uri->getSegments()) >= 2 && $uri->getSegment(2) === 'approvaldokumen') ? 'active' : '' ?>">
                        <i class="bi bi-check-all"></i> Pengesahan Dokumen
                    </a>
                </li>

                <!-- Pengurusan Dokumen -->
                <li class="nav-item">
                    <a href="<?= base_url('dashboard/loadPage/dokumen') ?>" 
                       class="nav-link <?= (count($uri->getSegments()) >= 2 && $uri->getSegment(2) === 'dokumen') ? 'active' : '' ?>">
                        <i class="bi bi-files"></i> Pengurusan Dokumen
                    </a>
                </li>

                <!-- Perincian Modul -->
                <li class="nav-item">
                    <a href="<?= site_url('perincianmodul') ?>" class="nav-link <?= $uri->getSegment(1, '') === 'perincianmodul' ? 'active' : '' ?>">
                        <i class="bi bi-collection-fill"></i> Perincian Modul
                    </a>
                </li>

                <!-- Tambahan Perincian -->
                <li class="nav-item">
                    <a href="<?= site_url('dashboard/TambahanPerincian') ?>" 
                       class="nav-link <?= (count($uri->getSegments()) >= 2 && $uri->getSegment(2) === 'TambahanPerincian') ? 'active' : '' ?>">
                        <i class="bi bi-folder-plus"></i> Tambahan Perincian
                    </a>
                </li>

                <!-- FAQ -->
                <li class="nav-item">
                    <a href="<?= site_url('faq/1') ?>" class="nav-link <?= $uri->getSegment(1) === 'faq' ? 'active' : '' ?>">
                        <i class="bi bi-question-diamond-fill"></i> FAQ
                    </a>
                </li>

                <!-- Profile -->
                <li class="nav-item">
                    <a href="<?= site_url('profile') ?>" class="nav-link <?= $uri->getSegment(1) === 'profile' ? 'active' : '' ?>">
                        <i class="bi bi-person-bounding-box"></i> My Profile
                    </a>
                </li>

                <!-- Logout -->
                <li class="nav-item mt-4">
                    <a href="<?= site_url('logout') ?>" class="nav-link logout-btn">
                        <i class="bi bi-box-arrow-left"></i> Sign Out
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>
<button id="sidebarToggle"><i data-lucide="menu" class="w-5 h-5"></i></button>

<div class="main-container">
    <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
        <div>
            <h1 class="text-4xl font-black text-slate-900 tracking-tight">Tambahan Perincian Modul </h1>
            <p class="text-slate-500 font-medium">Urus pautan maklumat dan perincian servis.</p>
        </div>
        <button onclick="openEditor()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3.5 rounded-2xl font-bold flex items-center gap-2 shadow-lg transition-all">
            <i data-lucide="plus" class="w-5 h-5"></i> Tambah Perincian
        </button>
    </div>

    <div class="card-modern overflow-hidden">
        <div class="p-6 bg-slate-50/50 border-b border-slate-100 flex flex-wrap gap-4 items-center justify-between">
            <div class="relative flex-1 min-w-[300px]">
                <i data-lucide="search" class="w-5 h-5 absolute left-4 top-3.5 text-slate-400"></i>
                <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Cari nama servis..." class="modern-input pl-12">
            </div>
            <div class="flex items-center gap-3">
                <span class="text-xs font-bold text-slate-400 uppercase">Susunan:</span>
                <select id="sortOrder" onchange="sortData()" class="modern-input py-2 text-sm w-40">
                    <option value="desc">Descending (ID)</option>
                    <option value="asc">Ascending (ID)</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest bg-slate-50/30">
                        <th class="px-8 py-5">Nama Servis</th>
                        <th class="px-8 py-5 text-center">Pautan Luar</th>
                        <th class="px-8 py-5 text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody id="serviceTableBody" class="divide-y divide-slate-100"></tbody>
            </table>
        </div>
    </div>
</div>

<div id="editorOverlay">
    <div id="editorContainer" class="card-modern p-8 shadow-2xl bg-white w-full max-w-4xl max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-6 pb-4 border-b">
            <h2 id="editorTitle" class="text-2xl font-black text-slate-900">Tambah Perincian</h2>
            <button onclick="closeEditor()"><i data-lucide="x" class="w-6 h-6 text-slate-400"></i></button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <input type="hidden" id="idservis">
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase">Nama Servis</label>
                    <input id="namaservis" class="modern-input mt-1 font-bold">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase">URL Informasi</label>
                    <input id="infourl" placeholder="https://..." class="modern-input mt-1">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase">URL Permohonan</label>
                    <input id="mohonurl" placeholder="https://..." class="modern-input mt-1">
                </div>
                <div class="pt-4 flex gap-2">
                    <button onclick="saveServis()" class="flex-1 bg-indigo-600 text-white py-3 rounded-xl font-bold hover:bg-indigo-700 transition-all">Simpan</button>
                    <button id="deleteBtn" onclick="deleteServis()" class="hidden bg-rose-50 text-rose-600 px-4 rounded-xl border border-rose-100"><i data-lucide="trash-2" class="w-5 h-5"></i></button>
                </div>
            </div>
            <div>
                <label class="text-[10px] font-bold text-slate-400 uppercase">Penerangan</label>
                <div class="mt-1"><textarea id="description"></textarea></div>
            </div>
        </div>
    </div>
</div>

<script>
let editor;
let allServis = [];
let selectedId = null;

lucide.createIcons();

ClassicEditor.create(document.getElementById('description'), {
    toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList']
}).then(ed => editor = ed);

document.getElementById('sidebarToggle').addEventListener('click', () => {
    document.getElementById('mainSidebar').classList.toggle('active');
});

// Modal pautan URL guna SweetAlert
function showLinks(id) {
    const s = allServis.find(item => item.idservis == id);
    let htmlContent = `<div class="text-left space-y-4 p-2">`;
    
    htmlContent += `<div><p class="text-[10px] font-bold text-gray-400 uppercase">URL Info</p>
                    <a href="${s.infourl}" target="_blank" class="text-indigo-600 break-all text-sm underline font-medium">${s.infourl || 'Tiada pautan'}</a></div>`;
    
    htmlContent += `<div><p class="text-[10px] font-bold text-gray-400 uppercase">URL Mohon</p>
                    <a href="${s.mohonurl}" target="_blank" class="text-indigo-600 break-all text-sm underline font-medium">${s.mohonurl || 'Tiada pautan'}</a></div>`;
    
    htmlContent += `</div>`;

    Swal.fire({
        title: 'Pautan Servis',
        html: htmlContent,
        showCloseButton: true,
        confirmButtonText: 'Tutup',
        confirmButtonColor: '#6366f1'
    });
}

// Logic Susunan (Sorting)
function sortData() {
    const order = document.getElementById('sortOrder').value;
    allServis.sort((a, b) => {
        const idA = parseInt(a.idservis);
        const idB = parseInt(b.idservis);
        return order === 'asc' ? idA - idB : idB - idA;
    });
    renderTable();
}

function renderTable(){
    const body = document.getElementById('serviceTableBody');
    body.innerHTML = '';
    
    allServis.forEach(s => {
        const hasLinks = s.infourl || s.mohonurl;
        const tr = document.createElement('tr');
        tr.className = "hover:bg-slate-50/80 transition-all";
        tr.innerHTML = `
            <td class="px-8 py-6">
                <div class="font-bold text-slate-800">${s.namaservis}</div>
                <div class="text-[9px] font-mono text-slate-400 uppercase mt-1">ID: ${s.idservis}</div>
            </td>
            <td class="px-8 py-6 text-center">
                <button onclick="${hasLinks ? `showLinks('${s.idservis}')` : ''}" 
                        class="btn-link-info ${hasLinks ? 'btn-link-exists' : 'btn-link-none'}">
                    <i data-lucide="external-link" class="w-3 h-3"></i>
                    ${hasLinks ? 'LIHAT PAUTAN' : 'TIADA PAUTAN'}
                </button>
            </td>
            <td class="px-8 py-6 text-right">
                <button onclick="openEditor('${s.idservis}')" class="text-xs font-bold text-indigo-600 bg-indigo-50 px-4 py-2 rounded-lg hover:bg-indigo-600 hover:text-white transition-all">
                    KEMASKINI
                </button>
            </td>
        `;
        body.appendChild(tr);
    });
    lucide.createIcons();
}

function openEditor(id = null) {
    if (id) {
        const s = allServis.find(item => item.idservis == id);
        selectedId = s.idservis;
        document.getElementById('idservis').value = s.idservis;
        document.getElementById('namaservis').value = s.namaservis;
        document.getElementById('infourl').value = s.infourl || '';
        document.getElementById('mohonurl').value = s.mohonurl || '';
        editor.setData(s.perincian?.description || '');
        document.getElementById('deleteBtn').classList.remove('hidden');
    } else {
        selectedId = null;
        document.getElementById('idservis').value = '';
        document.getElementById('namaservis').value = '';
        document.getElementById('infourl').value = '';
        document.getElementById('mohonurl').value = '';
        editor.setData('');
        document.getElementById('deleteBtn').classList.add('hidden');
    }
    document.getElementById('editorOverlay').style.display = 'flex';
}

function closeEditor() { document.getElementById('editorOverlay').style.display = 'none'; }

async function fetchServis(){
    const res = await fetch('<?= base_url("dashboard/TambahanPerincian/getAll") ?>');
    const json = await res.json();
    if(json.status) { 
        allServis = json.data; 
        sortData(); // Automatik sort masa mula-mula load
    }
}

async function saveServis(){
    const fd = new FormData();
    fd.append('idservis', selectedId || '');
    fd.append('namaservis', document.getElementById('namaservis').value);
    fd.append('infourl', document.getElementById('infourl').value);
    fd.append('mohonurl', document.getElementById('mohonurl').value);
    fd.append('description', editor.getData());

    const res = await fetch('<?= base_url("dashboard/TambahanPerincian/saveServis") ?>', { method:'POST', body:fd });
    const json = await res.json();
    if(json.status){ fetchServis(); closeEditor(); Swal.fire('Berjaya', 'Data disimpan', 'success'); }
}

async function deleteServis(){
    const res = await Swal.fire({ title: 'Padam?', icon: 'warning', showCancelButton: true });
    if(res.isConfirmed){
        const fd = new FormData();
        fd.append('idservis', selectedId);
        await fetch('<?= base_url("dashboard/TambahanPerincian/deleteServis") ?>', { method:'POST', body:fd });
        fetchServis(); closeEditor();
    }
}

function filterTable() {
    const q = document.getElementById("searchInput").value.toLowerCase();
    document.querySelectorAll("#serviceTableBody tr").forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(q) ? "" : "none";
    });
}

fetchServis();
</script>
</body>
</html>