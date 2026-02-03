<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// --------------------------------------------------------------------
// Default Route
// --------------------------------------------------------------------
$routes->get('/', 'Dashboard\DashboardController::index');

// --------------------------------------------------------------------
// Dashboard Routes (AJAX Pages)
// --------------------------------------------------------------------
$routes->group('dashboard', ['namespace' => 'App\Controllers\Dashboard'], function($routes) {
    $routes->get('/', 'DashboardController::index');
    $routes->get('loadPage/(:segment)', 'DashboardController::loadPage/$1');
    $routes->get('getData', 'DashboardController::getData');
});

// --------------------------------------------------------------------
// Perincian Modul
// --------------------------------------------------------------------
$routes->group('perincianmodul', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('/', 'PerincianModulController::index');
    $routes->get('getServis/(:num)', 'PerincianModulController::getServis/$1');
    $routes->post('save', 'PerincianModulController::save');
    $routes->get('delete/(:num)', 'PerincianModulController::delete/$1');
});

// --------------------------------------------------------------------
// Tambahan Perincian Modul
// --------------------------------------------------------------------
$routes->group('dashboard', ['namespace' => 'App\Controllers'], function ($routes) {

    // MAIN PAGE
    $routes->get('TambahanPerincian', 'TambahanPerincianController::index');

    // AJAX ROUTES
    $routes->get('TambahanPerincian/getServis/(:num)', 'TambahanPerincianController::getServis/$1');
    $routes->post('TambahanPerincian/saveServis', 'TambahanPerincianController::saveServis');
    $routes->post('TambahanPerincian/deleteServis', 'TambahanPerincianController::deleteServis');
    $routes->get('TambahanPerincian/getAll', 'TambahanPerincianController::getAll');
});

// --------------------------------------------------------------------
// Dokumen Management
        $routes->group('dokumen', ['namespace' => 'App\Controllers'], function($routes) {
            // Paparan utama dan senarai data
            $routes->get('/', 'DokumenController::index');
            $routes->get('getDokumen/(:num)', 'DokumenController::getDokumen/$1');
            
            // Operasi CRUD
            $routes->post('tambah', 'DokumenController::tambah');
            $routes->get('edit/(:num)', 'DokumenController::edit/$1');
            $routes->post('kemaskini/(:num)', 'DokumenController::kemaskini/$1');
            
            // Fungsi Padam (Penting untuk AJAX hapusDokumen)
            $routes->post('hapus/(:num)', 'DokumenController::hapus/$1');
            
            // Fungsi Paparan Fail (Penting untuk URL /viewFile/id/namafail)
            $routes->get('viewFile/(:num)/(:any)', 'DokumenController::viewFile/$1/$2');

            
            // Tukar kepada hapus kekal (Permanent Delete)
            $routes->post('hapus/(:num)', 'DokumenController::hapus/$1');

            // Route untuk paparan fail
            $routes->get('viewFile/(:num)/(:any)', 'DokumenController::viewFile/$1/$2');
        });

// --------------------------------------------------------------------
// Approval Dokumen Management
// --------------------------------------------------------------------
$routes->group('approvaldokumen', ['namespace' => 'App\Controllers'], function($routes) {

    // ============================================================
    // PAGE / VIEWS
    // ============================================================

    // Halaman utama Approval Dokumen
    $routes->get('/', 'ApprovalDokumenController::index');

    // AJAX: fetch all dokumen (with pagination & status filter)
    $routes->get('getAll', 'ApprovalDokumenController::getAll');

    // AJAX: fetch single dokumen details
    $routes->get('getDokumen/(:num)', 'ApprovalDokumenController::getDokumen/$1');

    // AJAX: approve or reject dokumen
    $routes->post('changeStatus/(:num)/(:any)', 'ApprovalDokumenController::changeStatus/$1/$2');
    
    // AJAX: view dokumen file
    $routes->get('viewFile/(:num)/(:any)', 'ApprovalDokumenController::viewFile/$1/$2');
   
});
// --------------------------------------------------------------------
// User Management
// --------------------------------------------------------------------
$routes->group('users', ['namespace' => 'App\Controllers'], function($routes){
    $routes->get('/', 'UserController::index');
    $routes->get('getAll', 'UserController::getAll');
    $routes->get('(:num)', 'UserController::show/$1');
    $routes->post('add', 'UserController::add');
    $routes->post('update/(:num)', 'UserController::update/$1');
    $routes->post('delete/(:num)', 'UserController::delete/$1');
});


// --- Authentication ---
$routes->get('/register', 'Auth::register');
$routes->post('/register', 'Auth::attemptRegister');
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::attemptLogin');
$routes->get('/logout', 'Auth::logout');

// --- Profile Management ---
$routes->get('profile', 'Auth::profile');
$routes->post('profile/update', 'Auth::updateProfile');
$routes->post('profile/update-password', 'Auth::updatePassword');
$routes->get('get-profile-pic/(:any)', 'Auth::getFile/$1');
$routes->get('profile/delete-pic', 'Auth::deleteProfilePic');

// --- Direct Password Reset (Tiada Emel) ---
$routes->get('forgot-password', 'Auth::forgotPassword');
// Rute di bawah akan memproses pertukaran password terus dari borang
$routes->post('forgot-password', 'Auth::attemptDirectReset');

// --------------------------------------------------------------------
// Servis Kelulusan
// --------------------------------------------------------------------
$routes->group('serviskelulusan', ['namespace' => 'App\Controllers\Servis'], function($routes){
    $routes->get('/', 'ServisKelulusanController::index');
    $routes->get('getAll', 'ServisKelulusanController::getAll');
    $routes->get('getServis/(:num)', 'ServisKelulusanController::getServis/$1');
    $routes->post('changeStatus/(:num)/(:segment)', 'ServisKelulusanController::changeStatus/$1/$2');
});

// --------------------------------------------------------------------
// Frontend
// --------------------------------------------------------------------
$routes->group('', ['namespace' => 'App\Controllers\Frontend'], function($routes) {
    $routes->get('dashboard', 'DashboardController::index');

    // Perincian
    $routes->group('perincian', function($routes) {
        $routes->get('/', 'PerincianController::index');
        $routes->get('getServis/(:num)', 'PerincianController::getServis/$1');
        $routes->post('save', 'PerincianController::save');
    });

    // Dokumen Pengurusan
    $routes->group('pengurusan', function($routes) {
        $routes->get('/', 'DokumenPengurusanController::index');
        $routes->get('getDokumen/(:num)', 'DokumenPengurusanController::getDokumen/$1');
        $routes->get('getDokumenById/(:num)', 'DokumenPengurusanController::getDokumenById/$1');
        $routes->post('tambah', 'DokumenPengurusanController::tambah');
        $routes->post('kemaskini/(:num)', 'DokumenPengurusanController::kemaskini/$1');
        $routes->get('remove/(:num)', 'DokumenPengurusanController::remove/$1');
    });
});

$routes->group('faq', function($routes){
    // Paparan utama FAQ
    $routes->get('', 'FaqController::index');             // index tanpa parameter
    $routes->get('(:num)', 'FaqController::index/$1');   // optional: index ikut servis ID (boleh remove kalau tak guna)
    
    // Create FAQ
    $routes->get('create/(:num)', 'FaqController::create/$1'); // form create untuk servis tertentu
    $routes->post('store', 'FaqController::store');             // simpan FAQ baru

    // Edit / Update FAQ
    $routes->get('edit/(:num)', 'FaqController::edit/$1');     // form edit
    $routes->post('update/(:num)', 'FaqController::update/$1'); // kemaskini FAQ

    // Delete FAQ via POST
    $routes->delete('delete/(:num)', 'FaqController::delete/$1');

    // AJAX untuk fetch FAQ
    $routes->get('ajax/(:num)', 'FaqController::ajax/$1');
});

