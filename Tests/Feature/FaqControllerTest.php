<?php

namespace App\Controllers;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

class FaqControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait, DatabaseTestTrait;

    // Automatikkan migration dan seeder jika perlu
    protected $migrate = true;
    protected $refresh = true;
    protected $seed    = ''; // Letakkan seeder jika ada

    /**
     * Test halaman utama FAQ
     */
    public function testIndex()
    {
        $result = $this->get('/faq');

        // 1. Pastikan status 200 OK
        $result->assertStatus(200);

        // 2. SEMAK TEKS: Pastikan teks 'Pengurusan FAQ' (dari data['title']) muncul
        $result->assertSee('Pengurusan FAQ');

        // 3. SEMAK ELEMEN: Pastikan ada dropdown/select untuk senarai servis
        // (Gunakan tag HTML yang wujud dalam file faq/index.php anda)
        $result->assertSeeElement('select'); 
    }
    /**
     * Test AJAX fetch FAQ - Berjaya
     */
    public function testAjaxSuccess()
    {
        // 1. Masukkan data dummy ke Servis
        $db = \Config\Database::connect();
        $db->table('aict4u103dservis')->insert([
            'idservis'   => 9,
            'namaservis' => 'ICT Support'
        ]);

        // 2. Simulasi AJAX request
        $result = $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
                       ->get('/faq/ajax/1');

        $result->assertStatus(200);
        $result->assertJSONFragment(['success' => true]);
    }

    /**
     * Test AJAX fetch FAQ - Akses Ditolak (Bukan AJAX)
     */
   public function testAjaxForbidden()
    {
        // Pastikan kita panggil tanpa header AJAX untuk trigger 403
        $result = $this->get('/faq/ajax/1');

        // 1. Semak status code
        $result->assertStatus(403);

        // 2. CI4 ResponseTrait biasanya membungkus mesej dalam 'messages' -> 'error'
        // atau 'messages' -> 'forbidden'
        $result->assertJSONFragment([
            'messages' => [
                'error' => 'Akses tidak dibenarkan.'
            ]
        ]);
    }

    /**
     * Test Simpan FAQ (Store) - Berjaya
     */
    public function testStoreSuccess()
    {
        $data = [
            'question' => 'Bagaimana cara login?',
            'answer'   => 'Gunakan emel dan kata laluan anda.',
            'idservis' => 1 // Pastikan ID ini wujud dalam table aict4u103dservis
        ];

        $result = $this->post('/faq/store', $data);

        // 1. CI4 menggunakan assertRedirect() bukannya assertRedirectTo()
        $result->assertRedirect();

        // 2. Semak jika redirect ke URL yang betul
        $this->assertEquals(site_url('/faq'), $result->getRedirectUrl());

        // 3. Semak Session
        $result->assertSessionHas('success', 'FAQ baru berjaya ditambah.');

        // 4. Sahkan dalam DB. 
        // PENTING: Guna nama table yang betul (rujuk model anda: 'faqs' atau 'faq')
        $this->seeInDatabase('faq', [
            'question' => 'Bagaimana cara login?'
        ]);
    }
    /**
     * Test Simpan FAQ (Store) - Gagal (Validation)
     */
    public function testStoreValidationFail()
    {
        $data = [
            'question' => 'Pendek', // Kurang min_length[5]
            'answer'   => '',      // Kosong
            'idservis' => 'bukan_integer'
        ];

        $result = $this->post('/faq/store', $data);

        // Patut patah balik ke form
        $result->assertSessionHas('error');
    }

    /**
     * Test Padam FAQ (Delete) via AJAX
     */
   public function testDeleteAjaxSuccess()
    {
        $db = \Config\Database::connect();

        // 1. Masukkan data INDUK
        $db->table('aict4u103dservis')->ignore()->insert([
            'idservis'   => 2,
            'namaservis' => 'Servis Testing'
        ]);

        // 2. Masukkan data ANAK (FAQ)
        $db->table('faq')->insert([
            'id'       => 12,
            'question' => 'Soalan untuk dipadam',
            'answer'   => 'Jawapan',
            'idservis' => 2
        ]);

        $sessionData = [
            'isLoggedIn'  => true,
            'profile_pic' => 'default.jpg',
            'fullname'    => 'Admin Test'
        ];

        // 3. JALANKAN DELETE 
        // Nota: Jika routes.php anda guna $routes->delete(), tukar .get() kepada .delete()
        $result = $this->withSession($sessionData)
                    ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
                    ->delete('/faq/delete/12'); // Tukar kepada delete() jika perlu

        // 4. PENGESAHAN
        // ResponseTrait untuk respondDeleted() biasanya hantar status 200 atau 204
        $result->assertStatus(200);
        
        // Semak fragment success
        $result->assertJSONFragment(['success' => true]);

        // 5. SAHKAN DB
        $this->dontSeeInDatabase('faq', ['id' => 12]);
    }
}