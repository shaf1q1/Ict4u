<?php

namespace App\Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

class PerincianModulControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait;
    use DatabaseTestTrait;

    // Pastikan database intern_test ada table ni
    protected $refresh = false; 

    /**
     * Test halaman utama boleh load
     */

    public function testIndexPaparHalaman()
    {
        $db = \Config\Database::connect();

        // Masukkan data dummy
        $db->table('aict4u103dservis')->insert([
            'namaservis' => 'Servis Testing',
            'infourl'    => 'https://test.com',
            'mohonurl'   => 'https://test.com'
        ]);

        $result = $this->get('/perincianmodul');

        $result->assertStatus(200);

        // 1. Semak adakah data dummy muncul dalam list dropdown (tag <li>)
        $result->assertSee('Servis Testing');

        // 2. Semak elemen yang benar-benar wujud dalam view anda
        // Kita semak ID borang atau ID dropdown button
        $result->assertSeeElement('#dropdownButton');
        $result->assertSeeElement('#servisForm');
    }

    /**
     * Test fetch data JSON untuk AJAX
     */
    public function testGetServisSuccess()
    {
        $db = \Config\Database::connect();

        // 1. Bersihkan dulu data lama (elak duplicate entry 999)
        $db->table('aict4u103dservis')->where('idservis', 999)->delete();

        // 2. Masukkan data dummy
        $db->table('aict4u103dservis')->insert([
            'idservis'   => 999,
            'namaservis' => 'Test Servis',
        ]);

        // 3. Jalankan request
        $result = $this->get('perincianmodul/getServis/999');

        // 4. Assertions
        $result->assertStatus(200);
        $result->assertJSONFragment(['status' => true]);
        $result->assertJSONFragment(['namaservis' => 'Test Servis']);

        // 5. (Opsional tapi bagus) Padam balik data dummy lepas habis test
        $db->table('aict4u103dservis')->where('idservis', 999)->delete();
    }
    /**
     * Test fetch data ID tak wujud
     */
    public function testGetServisNotFound()
    {
        $result = $this->get('perincianmodul/getServis/9999'); // ID mustahil

        $result->assertJSONFragment([
            'status'  => false,
            'message' => 'Servis tidak ditemui.'
        ]);
    }

    /**
     * Test Simpan Data (Validation Fail - Description Kosong)
     */
    public function testSaveValidationFail()
    {
        $result = $this->post('perincianmodul/save', [
            'idservis'    => 25, //ini ID tak wujud dalam DB.
            'namaservis'  => 'Servis Baru',
            'description' => '' // Kosongkan untuk trigger error
        ]);

        $result->assertRedirect();
        $result->assertSessionHas('error');
    }

    /**
     * Test Simpan Data Berjaya (Update)
     */
    public function testSaveSuccess()
{
    $db = \Config\Database::connect();
    
    // Pastikan table aict4u103dservis ada ID 21
    $db->table('aict4u103dservis')->ignore(true)->insert([
        'idservis'   => 21,
        'namaservis' => 'Servis Asal'
    ]);

    $result = $this->post('perincianmodul/save', [
        'idservis'    => 21,
        'namaservis'  => 'Servis Updated',
        'infourl'     => 'https://ict4u.my', // Mesti ada https://
        'mohonurl'    => 'https://ict4u.my', // Mesti ada https://
        'description' => 'Ini testing description yang sah'
    ]);

    // Kita guna assertRedirect() secara umum dulu sebab CI4 
    // kadang-kadang tambah index.php dalam URL testing
    $result->assertRedirect(); 
}

    /**
     * Test Delete Servis
     */
    public function testDeleteServis()
    {
        // 1. Guna ID yang tak wujud
        $id = 2;

        $result = $this->get("perincianmodul/delete/$id");

        $result->assertRedirect();
        $result->assertSessionHas('error', 'Servis tidak ditemui.');

        if (true) {
        // 2. Masukkan data dummy untuk delete
        $db = \Config\Database::connect();
        $db->table('aict4u103dservis')->insert([
            'idservis'   => 13,
            'namaservis' => 'Servis Untuk Delete'
        ]);
        $id = 13;
        // 3. Jalankan delete
        $result = $this->get("perincianmodul/delete/$id");
        $result->assertRedirect();
        $result->assertSessionHas('success', 'Servis berjaya dipadam.');
        }
        
    }
}