<?php $uri = service('uri'); ?>

<style>
    .main-sidebar {
        background-color: #ffffff !important;
        border-right: 1px solid #e2e8f0 !important;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .brand-link {
        border-bottom: 1px solid #f1f5f9 !important;
        padding: 1.5rem 1rem !important;
    }

    .brand-text {
        color: #1e293b !important;
        letter-spacing: -1px;
        font-size: 1.5rem;
    }

    .sidebar {
        padding-top: 10px;
    }

    /* Styling untuk Menu Items */
    .nav-pills .nav-link {
        color: #64748b !important;
        border-radius: 12px !important;
        margin: 4px 12px;
        padding: 10px 15px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.2s;
    }

    .nav-pills .nav-link i {
        color: #94a3b8;
        transition: all 0.2s;
    }

    /* Active State */
    .nav-pills .nav-link.active {
        background-color: #f5f3ff !important; /* Soft Indigo */
        color: #4f46e5 !important;
    }

    .nav-pills .nav-link.active i {
        color: #4f46e5 !important;
    }

    /* Hover State */
    .nav-pills .nav-link:hover:not(.active) {
        background-color: #f8fafc;
        color: #1e293b !important;
    }

    /* Logout Specific */
    .nav-link.logout-btn {
        margin-top: 20px;
        border: 1px solid #fee2e2;
    }

    .nav-link.logout-btn:hover {
        background-color: #fef2f2 !important;
        color: #dc2626 !important;
    }

    .nav-header {
        font-size: 0.7rem;
        font-weight: 800;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 1.5rem 1.5rem 0.5rem 1.5rem !important;
    }
</style>

<aside class="main-sidebar elevation-0">
    <a href="<?= site_url('/') ?>" class="brand-link text-center text-decoration-none">
        <span class="brand-text fw-black">ICT4U Management<span class="text-primary">.</span></span>
    </a>

    <div class="sidebar">
        <nav class="mt-3">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="<?= site_url('/') ?>" class="nav-link <?= $uri->getSegment(1, '') === '' ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-grid-fill"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('dashboard/loadPage/approvaldokumen') ?>" 
                       class="nav-link <?= (count($uri->getSegments()) >= 2 && $uri->getSegment(2) === 'approvaldokumen') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-check-all"></i>
                        <p>Pengesahan Dokumen</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= base_url('dashboard/loadPage/dokumen') ?>" 
                       class="nav-link <?= (count($uri->getSegments()) >= 2 && $uri->getSegment(2) === 'dokumen') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-files"></i>
                        <p>Pengurusan Dokumen</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('perincianmodul') ?>" 
                       class="nav-link <?= $uri->getSegment(1, '') === 'perincianmodul' ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-collection-fill"></i>
                        <p>Perincian Modul</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= site_url('dashboard/TambahanPerincian') ?>" 
                       class="nav-link <?= (count($uri->getSegments()) >= 2 && $uri->getSegment(2) === 'TambahanPerincian') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-folder-plus"></i>
                        <p>Tambahan Perincian</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('faq/1') ?>" class="nav-link <?= $uri->getSegment(1) === 'faq' ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-question-diamond-fill"></i>
                        <p>FAQ</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('profile') ?>" class="nav-link <?= $uri->getSegment(1) === 'profile' ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-person-bounding-box"></i>
                        <p>My Profile</p>
                    </a>
                </li>

                <li class="nav-item mt-4">
                    <a href="<?= site_url('logout') ?>" class="nav-link logout-btn text-danger">
                        <i class="nav-icon bi bi-box-arrow-left"></i>
                        <p>Sign Out</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>