<?php $uri = service('uri'); // Mengambil servis URI daripada CodeIgniter untuk mengesan URL semasa ?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Sistem Approval Dokumen</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

    <style>
        /* Tetapan asas rupa bentuk badan halaman */
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
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
        /*SIDEBAR ACTIVE STATE*/
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

        /* Kesan Glassmorphism untuk kad maklumat */
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
        }

        /* Gaya warna warni untuk label status (Pending, Approved, Rejected) */
        .status-pill { padding: 4px 12px; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; }
        .status-pending { background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .status-approved { background-color: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .status-rejected { background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

        /* Animasi masuk untuk modal perincian */
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="min-h-screen antialiased text-slate-800">
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
<!-- ===== HAMBURGER ===== -->
<button id="sidebarToggle">
    <i class="bi bi-list h-6 w-6"></i>
</button>

<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="glass-card rounded-3xl p-8 mb-8 flex flex-col md:flex-row items-center justify-between">
        <div class="flex items-center gap-6">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900">Pengesahan Dokumen</h1>
                <p class="text-slate-500 font-medium italic">Jalan Kerja Dokumen Servis</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-6">
        <div class="md:col-span-3">
            <select id="filterStatus" class="w-full appearance-none bg-white border border-slate-200 p-3 rounded-xl focus:outline-none transition font-semibold text-slate-600">
                <option value="all">Semua Status</option>
                <option value="pending">Menunggu (Pending)</option>
                <option value="approved">Diterima (Approved)</option>
                <option value="rejected">Ditolak (Rejected)</option>
            </select>
        </div>
        <div class="md:col-span-6 relative">
            <i class="bi bi-search absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
            <input type="text" id="searchDokumen" placeholder="Cari tajuk dokumen..." class="w-full bg-white border border-slate-200 p-3 pl-12 rounded-xl focus:outline-none">
        </div>
    </div>

    <div class="glass-card rounded-3xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto" id="dokumenTable">
                <thead>
                    <tr class="bg-slate-50 border-b text-slate-500 text-xs font-bold uppercase tracking-wider">
                        <th class="p-5 text-center w-20">No</th>
                        <th class="p-5 text-left">Maklumat Dokumen</th>
                        <th class="p-5 text-left">Format</th>
                        <th class="p-5 text-center">Status</th>
                        <th class="p-5 text-left">Tarikh Hantar</th>
                        <th class="p-5 text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    </tbody>
            </table>
        </div>
        
        <div class="p-6 border-t bg-slate-50/50 flex justify-between items-center">
            <p id="totalInfo" class="text-sm text-slate-500 font-medium"></p>
            <div class="flex space-x-2 pagination"></div>
        </div>
    </div>
</div>

<div id="viewModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4">
    <div class="modal-container bg-white rounded-3xl w-full max-w-2xl overflow-hidden shadow-2xl">
        <div class="bg-slate-50 p-6 flex justify-between items-center border-b">
            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">Perincian Dokumen</h2>
            <button id="closeViewModal" class="text-slate-400 hover:text-slate-600 transition text-2xl"><i class="bi bi-x-circle-fill"></i></button>
        </div>
        <div id="dokumenDetails" class="p-8 max-h-96 overflow-y-auto"></div>
        <div class="p-6 bg-slate-50 flex justify-end">
            <button onclick="document.getElementById('viewModal').classList.add('hidden')" class="bg-white border px-6 py-2 rounded-xl font-bold">Tutup</button>
        </div>
    </div>
</div>

<lottie-player id="successAnimation" src="https://assets10.lottiefiles.com/packages/lf20_jbrw3hcz.json" background="transparent" speed="1" style="width:250px;height:250px;position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);z-index:1000;display:none;" autoplay></lottie-player>

<script>
document.addEventListener('DOMContentLoaded',()=>{
    // Deklarasi pembolehubah elemen UI
    const tbody=document.querySelector('#dokumenTable tbody');
    const searchInput=document.getElementById('searchDokumen');
    const filterStatus=document.getElementById('filterStatus');
    const viewModal=document.getElementById('viewModal');
    const dokumenDetails=document.getElementById('dokumenDetails');
    const paginationContainer=document.querySelector('.pagination');


    // Toggle Sidebar: Buka/tutup menu tepi
    const sidebar = document.getElementById('mainSidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const pageContainer = document.querySelector('.main-container');

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('active');
        pageContainer.classList.toggle('shifted');
    });

    // Tetapan asal pagination dan sorting
    let currentPage=1, totalPages=1, sortColumn='', sortOrder='asc', limit=10;

    // Fungsi Utama: Ambil data dari server (API)
    async function loadData(page=1){
        const status=filterStatus.value;
        try{
            // Panggil URL backend untuk dapatkan data JSON
            const res=await fetch(`/approvaldokumen/getAll?status=${status}&page=${page}`);
            const result=await res.json();
            let data=result.data;

            // Masukkan data ke dalam table HTML
            populateTable(data);
            currentPage=result.pagination.page;
            totalPages=Math.ceil(result.pagination.total/result.pagination.limit);
            document.getElementById('totalInfo').innerText = `Menunjukkan ${data.length} daripada ${result.pagination.total} rekod`;
            renderPagination(); // Bina butang muka surat
        }catch(err){console.error(err);}
    }

    // Fungsi Bina Table: Tukar data JSON menjadi baris <tr>
    function populateTable(data){
        tbody.innerHTML=''; // Kosongkan table sebelum isi data baru
        data.forEach((d,index)=>{
            const statusLabel = d.status ?? 'pending';
            const tr=document.createElement('tr');
            tr.innerHTML=`
                <td class="p-5 text-center text-slate-400 font-semibold">${index+1+(currentPage-1)*limit}</td>
                <td class="p-5">
                    <div class="font-bold text-slate-800">${d.nama}</div>
                    <div class="text-xs text-slate-400 mt-0.5">ID: #${d.iddoc}</div>
                </td>
                <td class="p-5">
                    <span class="text-xs bg-slate-100 text-slate-500 px-2 py-1 rounded font-bold uppercase">${d.mime.split('/')[1] || d.mime}</span>
                </td>
                <td class="p-5 text-center">
                    <span class="status-pill status-${statusLabel}">${statusLabel}</span>
                </td>
                <td class="p-5 text-slate-500 text-sm">
                    <div class="flex items-center gap-2"><i class="bi bi-clock-history"></i> ${formatDate(d.created_at)}</div>
                </td>
                <td class="p-5">
                    <div class="flex justify-center gap-2">
                        <button class="viewBtn btn-action bg-indigo-50 text-indigo-600 p-2 rounded-xl hover:bg-indigo-600 hover:text-white" data-id="${d.iddoc}"><i class="bi bi-eye-fill pointer-events-none"></i></button>
                        <button class="approveBtn btn-action bg-emerald-50 text-emerald-600 p-2 rounded-xl hover:bg-emerald-600 hover:text-white" data-id="${d.iddoc}"><i class="bi bi-check-lg pointer-events-none"></i></button>
                        <button class="rejectBtn btn-action bg-rose-50 text-rose-600 p-2 rounded-xl hover:bg-rose-600 hover:text-white" data-id="${d.iddoc}"><i class="bi bi-x-lg pointer-events-none"></i></button>
                    </div>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    // Fungsi Format Tarikh: Tukar timestamp jadi format manusia (13 Jan 2026)
    function formatDate(str) {
        if (!str) return '-';
        const d = new Date(str);
        const datePart = d.toLocaleDateString('ms-MY', { day: '2-digit', month: 'short', year: 'numeric' });
        const timePart = d.toLocaleTimeString('ms-MY', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false });
        return `${datePart} • ${timePart}`;
    }

    // Fungsi Pagination: Jana butang nombor muka surat (1, 2, 3...)
    function renderPagination(){
        paginationContainer.innerHTML='';
        for(let i=1;i<=totalPages;i++){
            const btn=document.createElement('button');
            btn.textContent=i;
            btn.className=`w-10 h-10 rounded-xl font-bold transition ${i===currentPage?'bg-indigo-600 text-white':'bg-white text-slate-500'}`;
            btn.addEventListener('click',()=>{currentPage=i; loadData(i);});
            paginationContainer.appendChild(btn);
        }
    }

    // Fungsi Papar Modal: Ambil data satu dokumen spesifik dan tunjuk fail (PDF/Gambar)
    async function showDokumenModal(id) {
        try {
            const res = await fetch(`/approvaldokumen/getDokumen/${id}`);
            const data = await res.json();
            if(data.status) {
                const d = data.data;
                const fileUrl = `/dokumen/viewFile/${d.idservis}/${d.namafail}`;
                let fileHTML = '';
                
                // Cek jenis fail: Jika gambar tunjuk <img>, jika PDF tunjuk <iframe>
                if(d.mime.includes('image')) fileHTML = `<img src="${fileUrl}" class="w-full rounded-2xl shadow-lg" />`;
                else if(d.mime==='application/pdf') fileHTML = `<iframe src="${fileUrl}" width="100%" height="450px" class="rounded-2xl border"></iframe>`;
                else fileHTML = `<div class="p-8 border-2 border-dashed rounded-2xl text-center"><a href="${fileUrl}" target="_blank" class="text-indigo-600 font-bold underline">Muat Turun Fail</a></div>`;

                // Masukkan kandungan ke dalam modal
                dokumenDetails.innerHTML = `
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="bg-slate-50 p-4 rounded-2xl"><span class="text-xs text-slate-400 font-bold uppercase">Nama Dokumen</span><p class="font-bold text-slate-700">${d.nama}</p></div>
                        <div class="bg-slate-50 p-4 rounded-2xl"><span class="text-xs text-slate-400 font-bold uppercase">Status Semasa</span><div class="mt-1"><span class="status-pill status-${d.status}">${d.status}</span></div></div>
                    </div>
                    <div class="mb-6"><span class="text-xs text-slate-400 font-bold uppercase">Catatan</span><p class="text-slate-600">${d.descdoc || 'Tiada catatan.'}</p></div>
                    ${fileHTML}
                `;
                viewModal.classList.remove('hidden');
            }
        } catch(err) { console.error(err); }
    }

    // Event Listener Table: Kesan klik pada butang View, Approve, atau Reject
    tbody.addEventListener('click', e => {
        const id = e.target.getAttribute('data-id');
        if(!id) return;
        if(e.target.classList.contains('viewBtn')) showDokumenModal(id);
        else if(e.target.classList.contains('approveBtn')) changeStatus(id,'approved');
        else if(e.target.classList.contains('rejectBtn')) changeStatus(id,'rejected');
    });

    // Fungsi Tukar Status: Hantar arahan ke server untuk tukar status dokumen
    async function changeStatus(id, status){
        const confirmText = status.charAt(0).toUpperCase() + status.slice(1);
        // Popup pengesahan guna SweetAlert2
        const result = await Swal.fire({
            title: `Pengesahan ${confirmText}`,
            text: `Anda pasti mahu menukar status dokumen ini kepada ${status}?`,
            icon: status === 'approved' ? 'success' : 'warning',
            showCancelButton: true,
            confirmButtonText: `Ya, ${confirmText}!`
        });
        if(!result.isConfirmed) return;

        try{
            // Kirim POST request ke API
            const res = await fetch(`/approvaldokumen/changeStatus/${id}/${status}`, { method:'POST' });
            const data = await res.json();
            if(data.status){
                // Jika approved, tunjuk animasi lottie kejap
                if(status==='approved'){
                    successAnimation.style.display = 'block';
                    successAnimation.play();
                    setTimeout(()=> successAnimation.style.display='none', 1500);
                }
                Swal.fire('Berjaya!', data.message, 'success');
                loadData(currentPage); // Refresh data table
            }
        } catch(err){ console.error(err); }
    }

    // Fungsi Carian Laju: Tapis baris table berdasarkan input carian
    searchInput.addEventListener('input',()=>{
        const term=searchInput.value.toLowerCase();
        Array.from(tbody.rows).forEach(row=>{
            row.style.display=row.textContent.toLowerCase().includes(term)?'':'none';
        });
    });

    // Event Listener Filter: Refresh data bila status ditukar
    filterStatus.addEventListener('change',()=>{currentPage=1; loadData();});

    // Jalankan loadData buat kali pertama bila page siap load
    loadData(1);
});
</script>
</body>
</html>
