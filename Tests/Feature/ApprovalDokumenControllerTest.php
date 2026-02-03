<?php

namespace App\Controllers;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

class ApprovalDokumenControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait, DatabaseTestTrait;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Sediakan data mock session jika perlu
        $sessionData = [
            'user_id' => 1,
            'isLoggedIn' => true,
        ];
        session()->set($sessionData);
    }
    public function testIndex()
    {
        $sessionData = [
            'isLoggedIn' => true,
            'user_id'    => 1
        ];

        $result = $this->withSession($sessionData)->get('approvaldokumen');

        // PADAM INI SELEPAS DEBUG: Tunjukkan apa yang PHPUnit nampak
        fwrite(STDERR, $result->getBody()); 

        $result->assertStatus(200);
        if( method_exists($result, 'assertSee')) {
            $result->assertSee('Pengesahan Dokumen');
        } else {
            $this->assertStringContainsString('Senarai Dokumen', $result->getBody());
        }
    }
    public function testGetAllReturnsJsonResponse()
    {
        $result = $this->get('/approvaldokumen/getAll?status=all');

        $result->assertStatus(200);
        $result->assertJSONFragment(['status' => true]);
    }

    public function testChangeStatusToApproved()
    {
        // 1. Dapatkan akses ke database dalam test
        $db = \Config\Database::connect();

        // 2. Masukkan data dummy ke jadual INDUK (aict4u106mdoc)
        // Ini supaya Foreign Key tidak 'error'
        $db->table('aict4u106mdoc')->insert([
            'iddoc'  => 9,
            'status' => 'pending',
            // Tambah kolom wajib lain jika ada
        ]);

        $iddoc = 9; 
        $status = 'rejected';

        // 3. Jalankan request
        $result = $this->withSession(['user_id' =>2])
                    ->post("approvaldokumen/changeStatus/{$iddoc}/{$status}");

        // 4. Semak respons
        $result->assertStatus(200);
        $result->assertJSONFragment(['status' => true]);

        // 5. Semak jadual approval
        $this->seeInDatabase('aict4u106m_approval_dokumen', [
            'iddoc'  => $iddoc, 
            'status' => 'rejected'
        ]);

        // 6. Semak jadual induk juga (kerana Controller ada buat update)
        $this->seeInDatabase('aict4u106mdoc', [
            'iddoc'  => $iddoc,
            'status' => 'rejected'
        ]);
    }
    public function testChangeStatusWithInvalidStatus()
    {
        $iddoc = 1;
        $status = 'salah_status';

        $result = $this->post("/approvaldokumen/changeStatus/{$iddoc}/{$status}");

        $result->assertJSONFragment([
            'status' => false,
            'message' => 'Status tidak sah'
        ]);
    }

        public function testViewFileNotFound()
        {
            // Akses fail yang memang tak wujud
            $result = $this->get("approvaldokumen/viewFile/99/file_hantu.pdf");

            // Sahkan server jawab 404
            $result->assertStatus(404);
        }

    public function testViewFileSuccess()
        {
            // 1. Cipta folder dan fail dummy untuk testing
            $idservis = 99;
            $filename = 'test.pdf';
            $dir = WRITEPATH . "uploads/dokumen/{$idservis}/";
            if (!is_dir($dir)) mkdir($dir, 0777, true);
            file_put_contents($dir . $filename, 'dummy content');

            // 2. Jalankan request
            $result = $this->get("approvaldokumen/viewFile/{$idservis}/{$filename}");

            // 3. Pengesahan
            $result->assertStatus(200);
            $result->assertHeader('Content-Type', 'text/plain'); // mime dummy file biasanya text/plain
            
            // 4. Padam fail selepas test (Cleanup)
            unlink($dir . $filename);
        }
}