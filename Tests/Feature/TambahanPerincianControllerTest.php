<?php

namespace App\Controllers;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

class TambahanPerincianControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait, DatabaseTestTrait;

    protected $migrate = true;
    protected $refresh = true;

    /**
     * Test halaman utama
     */
    public function testIndex()
    {
        // Buang perkataan 'pages' supaya sama dengan route group
        $result = $this->get('dashboard/TambahanPerincian');

        $result->assertStatus(200);
        $result->assertSee('Tambahan Perincian Modul');
    }

    /**
     * Test mendapatkan data servis tunggal
     */
    public function testGetServisFound()
    {
        $db = \Config\Database::connect();

        // Guna nama jadual yang betul: aict4u103dservis
        $db->table('aict4u103dservis')->insert([
            'idservis'   => 6,
            'namaservis' => 'Servis Testing',
            'status'     => 5,
            'infourl'    => 'https://info.test',
            'mohonurl'   => 'https://mohon.test',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        // Perincian modul (pastikan nama jadual perincian juga betul)
        $db->table('aict4u103dperincianmodul')->insert([
            'idservis'    => 6,
            'description' => 'Ini adalah perincian testing'
        ]);

        // Panggil route (pastikan Route Group 'dashboard' dipatuhi)
        $result = $this->get('dashboard/TambahanPerincian/getServis/6');

        $result->assertStatus(200);
        $result->assertJSONFragment(['status' => true]);
    }
    /**
     * Test simpan servis baru (Insert)
     */
    public function testSaveServisInsert()
    {
        $data = [
            'namaservis'  => 'Servis Baru',
            'infourl'     => 'https://google.com',
            'mohonurl'    => 'https://apply.com',
            'description' => 'Keterangan servis baru'
        ];

        // TAMBAH 'dashboard/' di depan URL
        $result = $this->post('dashboard/TambahanPerincian/saveServis', $data);

        $result->assertStatus(200);
        $result->assertJSONFragment(['status' => true, 'message' => 'Servis berjaya disimpan']);

        // Gunakan nama table yang betul (aict4u103dservis)
        $this->seeInDatabase('aict4u103dservis', ['namaservis' => 'Servis Baru']);
        $this->seeInDatabase('aict4u103dperincianmodul', ['description' => 'Keterangan servis baru']);
    }

        /**
         * Test kemaskini servis sedia ada (Update)
         */
        public function testSaveServisUpdate()
        {
        // 1. Setup data asal
        $db = \Config\Database::connect();
        $db->table('aict4u103dservis')->insert(['idservis' => 19, 'namaservis' => 'Asal']);
        $db->table('aict4u103dperincianmodul')->insert(['id' => 19, 'idservis' => 19, 'description' => 'Lama']);

        // 2. Data baru untuk update
        $data = [
            'idservis'    => 19,
            'namaservis'  => 'Sudah Tukar',
            'description' => 'Baru'
        ];

        $result = $this->post('dashboard/TambahanPerincian/saveServis', $data);

        $result->assertJSONFragment(['status' => true]);
        
        // 3. Semak perubahan dalam DB
        $this->seeInDatabase('aict4u103dservis', ['idservis' => 19, 'namaservis' => 'Sudah Tukar']);
        $this->seeInDatabase('aict4u103dperincianmodul', ['idservis' => 19, 'description' => 'Baru']);
    }

    /**
     * Test simpan tanpa nama servis (Validation Fail)
     */
    public function testSaveServisEmptyName()
    {
        $result = $this->post('dashboard/TambahanPerincian/saveServis', [
            'namaservis' => ''
        ]);

        $result->assertJSONFragment([
            'status'  => false,
            'message' => 'Nama servis diperlukan'
        ]);
    }

    /**
     * Test padam servis
     */
    public function testDeleteServis()
    {
        // Setup data
        $db = \Config\Database::connect();
        $db->table('aict4u103dservis')->insert(['idservis' => 2, 'namaservis' => 'Mahu Padam']);
        $db->table('aict4u103dperincianmodul')->insert(['idservis' => 2, 'description' => 'Padam jugak']);

        $result = $this->post('dashboard/TambahanPerincian/deleteServis', [
            'idservis' => 2 
        ]);

        $result->assertJSONFragment(['status' => true, 'message' => 'Servis berjaya dipadam']);
        
        // Pastikan hilang dari DB
        $this->dontSeeInDatabase('aict4u103dservis', ['idservis' => 2]);
        $this->dontSeeInDatabase('aict4u103dperincianmodul', ['idservis' => 2 ]);
    }

    /**
     * Test fungsi getAll
     */
    public function testGetAll()
    {
        $result = $this->get('dashboard/TambahanPerincian/getAll');
        
        $result->assertStatus(200);
        $result->assertJSONFragment(['status' => true]);
    }
}