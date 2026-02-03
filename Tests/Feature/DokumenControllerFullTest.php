<?php

namespace App\Controllers;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

class DokumenControllerFullTest extends CIUnitTestCase
{
    use FeatureTestTrait;
    use DatabaseTestTrait;

    protected function setUp(): void
    {
        parent::setUp();
        // Sini kau boleh masukkan Seeder kalau nak database ada data siap-siap
        // $this->seed('DokumenSeeder'); 
    }

    public function testIndexPaparHalaman()
    {
        // Ikut route: $routes->get('/', 'DokumenController::index'); dalam group 'dokumen'
        $result = $this->get('dokumen');

        $result->assertStatus(200);
        // assertSee selalunya check teks yang ada dalam HTML view kau
        $result->assertSee('Pengurusan Dokumen Modul'); 
    }

    public function testGetDokumenReturnsJson()
    {
        // Ikut route: dokumen/getDokumen/(:num)
        $result = $this->get('dokumen/getDokumen/1');

        $result->assertStatus(200);
        $result->assertJSONFragment(['status' => true]);
    }


    public function testTambahDokumenBerjaya()
    {
        // Buat fail dummy
        $path = WRITEPATH . 'tests/test_image.jpg';
        if (!is_dir(dirname($path))) mkdir(dirname($path), 0777, true);
        file_put_contents($path, 'fake image content');

        // Kita 'paksa' masukkan dalam $_FILES sebab Controller kau guna $this->request->getFile()
        $_FILES['file'] = [
            'name'     => 'test_image.jpg',
            'type'     => 'image/jpeg',
            'tmp_name' => $path,
            'error'    => 0,
            'size'     => filesize($path),
        ];

        $result = $this->post('dokumen/tambah', [
            'idservis' => 1,
            'nama'     => 'Dokumen Test',
            'descdoc'  => 'Ini keterangan test'
        ]);

        $result->assertStatus(200);
        $result->assertJSONFragment(['status' => false]);
    }

    public function testEditDokumenWujud()
    {
        $db = \Config\Database::connect();

        // Guna nama table yang betul: aict4u106mdoc
        $db->table('aict4u106mdoc')->ignore()->insert([
            'iddoc'    => 1,
            'idservis' => 1,
            'nama'     => 'Dokumen Asal'
        ]);

        // Pastikan route ini juga menghala ke fungsi yang betul
        $result = $this->get('dokumen/edit/1');
        
        $result->assertStatus(200);
        
        // Jika ia memulangkan HTML, semak teks yang kita insert tadi
        $result->assertSee('Dokumen Asal');
    }

    public function testKemaskiniBerjaya()
    {
        // Ikut route: dokumen/kemaskini/(:num) (Method: POST)
        $result = $this->post('dokumen/kemaskini/1', [
            'nama'    => 'Nama Baru Update',
            'descdoc' => 'Keterangan baru'
        ]);

        $result->assertJSONFragment(['status' => true]);
    }

    public function testSoftDeleteBerjaya()
    {
        // Ikut route kau: $routes->post('softDelete/(:num)', ...)
        // Jadi kena guna post()
        $result = $this->post('dokumen/hapus/1', []);
        $result->assertJSONFragment(['status' => true]);
    }

    public function testKemaskiniDokumenTakWujud()
    {
        $result = $this->post('dokumen/kemaskini/999', [
            'nama' => 'Hantu'
        ]);

        $result->assertJSONFragment([
            'status' => false , 
            'msg'    => 'Dokumen tidak dijumpai.'
        ]);
    }
}