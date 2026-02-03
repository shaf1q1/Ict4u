<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'ICT4U Premium') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-gradient: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            --sidebar-width: 270px;
            --transition-speed: 0.3s;
            --easing: cubic-bezier(0.4, 0, 0.2, 1);
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #f1f5f9;
            -webkit-font-smoothing: antialiased;
        }

        /* GPU Accelerated Smoothness */
        .content-wrapper, .main-sidebar, .nav-link, .card {
            will-change: transform, opacity;
            transform: translateZ(0);
        }

        /* Modern Sidebar Styling */
        .main-sidebar {
            background: #ffffff !important;
            border-right: 1px solid rgba(226, 232, 240, 0.8) !important;
            transition: width var(--transition-speed) var(--easing) !important;
        }

        /* Floating Header (Glassmorphism) */
        .main-header {
            background: rgba(255, 255, 255, 0.7) !important;
            backdrop-filter: blur(12px) saturate(180%);
            -webkit-backdrop-filter: blur(12px) saturate(180%);
            border-bottom: 1px solid rgba(226, 232, 240, 0.5) !important;
        }

        .content-wrapper { 
            background: #f1f5f9 !important; 
            padding: 2rem 1.5rem !important;
        }

        /* Premium Card Styling */
        .card {
            border: none !important;
            border-radius: 24px !important;
            background: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
            transition: all 0.4s var(--easing) !important;
        }

        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05) !important;
        }

        .fade-in-up {
            animation: fadeInUp 0.6s var(--easing) both;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        #page-loader {
            position: fixed;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: var(--primary-gradient);
            z-index: 9999;
            width: 0;
            transition: width 0.4s ease;
        }

        /* CSS TAMBAHAN UNTUK PREVIEW DOKUMEN */
        .preview-box {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 12px;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e2e8f0;
        }
        .status-active { background: #dcfce7; color: #166534; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; }
        .status-pending { background: #fef9c3; color: #854d0e; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed shadow-none">
<div id="page-loader"></div>

<div class="wrapper">

    <?= $this->include('layout/topbar') ?>

    <?= $this->include('layout/sidebar') ?>

    <div class="content-wrapper">
        <div class="container-fluid fade-in-up">
            
            <div class="d-md-flex align-items-center justify-content-between mb-5">
                <div>
                    <h1 class="fw-800 text-dark mb-1" style="letter-spacing: -0.5px; font-size: 2rem;">
                        <?= $title ?? 'Dashboard' ?>
                    </h1>

                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-white shadow-sm rounded-4 border-0 px-3 py-2 text-sm">
                        <i class="bi bi-calendar-event me-2"></i> 
                        <span id="realtime-clock"><?= date('M d, Y H:i:s') ?></span>
                    </button>
                </div>
            </div>

            <?= $this->renderSection('content') ?>

        </div>
    </div>

    <?= $this->include('layout/footer') ?>

</div>

<?= $this->renderSection('modals') ?>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Page Loader Logic
    document.onreadystatechange = () => {
        const loader = document.getElementById('page-loader');
        if (document.readyState === 'complete') {
            loader.style.width = '100%';
            setTimeout(() => { loader.style.opacity = '0'; }, 500);
        }
    };

    // Realtime Clock
    function updateClock() {
        const now = new Date();
        const options = { 
            month: 'short', day: '2-digit', year: 'numeric',
            hour: '2-digit', minute: '2-digit', second: '2-digit',
            hour12: false 
        };
        const el = document.getElementById('realtime-clock');
        if(el) el.textContent = now.toLocaleString('en-US', options).replace(',', '');
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>

<?= $this->renderSection('scripts') ?>
</body>
</html>