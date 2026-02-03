<?php

namespace App\Controllers;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;
use App\Models\UserModel;

class AuthTest extends CIUnitTestCase
{
    use FeatureTestTrait, DatabaseTestTrait;

    protected $model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new UserModel();
    }

    // --- TEST REGISTRATION ---

    public function testRegisterView()
    {
        $result = $this->get('/register');
        $result->assertStatus(200);
        $result->assertSee('register'); 
    }

    public function testAttemptRegisterSuccess()
    {
        // Janakan string rawak untuk mengelakkan duplicate
        $suffix   = bin2hex(random_bytes(3));
        $username = 'user' . $suffix;
        $email    = 'test' . $suffix . '@example.com';

        $data = [
            'fullname'         => 'Test User',
            'username'         => $username, 
            'email'            => $email,
            'password'         => 'password123',
            'confirm_password' => 'password123'
        ];

        $result = $this->post('/register', $data);

        // Jika gagal, ini akan beritahu kenapa (contoh: ralat database)
        if ($result->getRedirectUrl() !== site_url('login')) {
            echo "\nDEBUG: Redirected to " . $result->getRedirectUrl();
            print_r(session()->getFlashdata());
        }

        $result->assertRedirectTo(site_url('login'));
        $this->seeInDatabase('users', ['username' => $username]);
    }
    // --- TEST LOGIN ---

  public function testAttemptLoginSuccess()
    {
        $db = \Config\Database::connect();
        
        // 1. Data unik
        $email = 'ali' . time() . '@example.com';
        $passwordMentah = '123456';
        $passwordHash = password_hash($passwordMentah, PASSWORD_DEFAULT);

        // 2. Masukkan ke DB
        $db->table('users')->insert([
            'fullname'    => 'Ali Abu',
            'username'    => 'ali' . time(),
            'email'       => $email,
            'password'    => $passwordHash,
            'profile_pic' => 'default.jpg' // Pastikan column ini ada nilai jika controller perlukannya
        ]);

        // 3. Cuba Login (Pastikan route /login ini merujuk ke Auth::attemptLogin)
        $result = $this->post('/login', [
            'email'    => $email,
            'password' => $passwordMentah
        ]);

        // --- PENGESAHAN ---

        // Guna assertRedirect() tanpa parameter
        $result->assertRedirect();

        // Semak sama ada destinasi redirect mengandungi kata kunci 'dashboard'
        $this->assertStringContainsString('dashboard', $result->getRedirectUrl());

        // Sahkan session isLoggedIn telah set
        $result->assertSessionHas('isLoggedIn', true);
        $result->assertSessionHas('email', $email);
    }
    // --- TEST PROFILE (Sesi Diperlukan) ---

    public function testProfileAccessWithoutLogin()
    {
        // Simulate no session
        session()->destroy();
        
        $result = $this->get('/profile');
        $result->assertRedirectTo('/login');
    }

        public function testUpdatePasswordIncorrectOldPassword()
        {
            $db = \Config\Database::connect();
            
            // Pastikan user 1 wujud dalam DB (Sangat Penting!)
            $db->table('users')->where('id', 1)->delete();
            $db->table('users')->insert([
                'id'       => 1,
                'username' => 'testadmin',
                'email'    => 'admin@test.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
            ]);

            $sessionData = ['user_id' => 1, 'isLoggedIn' => true];

            // GUNAKAN RUTE SEBENAR: 'profile/update-password'
            $result = $this->withSession($sessionData)
                        ->post('profile/update-password', [ 
                            'old_password'  => 'salah_password',
                            'new_password'  => 'newpass123',
                            'conf_password' => 'newpass123'
                        ]);

            // Sekarang rute ini patut dijumpai (200/302 OK)
            $result->assertRedirect();
            $result->assertSessionHas('error_pw', 'Kata laluan lama anda salah.');
        }

    // --- TEST FORGOT PASSWORD ---

        public function testLogout()
        {
            // 1. Set data sesi simulasi
            $sessionData = [
                'isLoggedIn' => true,
                'user_id'    => 1,
                'fullname'   => 'Admin Test'
            ];
            session()->set($sessionData);

            // 2. Jalankan request menggunakan rute yang betul: 'logout'
            $result = $this->get('logout');

            // 3. Pengesahan Redirect
            $result->assertRedirect();
            
            // Pastikan ia redirect ke halaman login
            $this->assertStringContainsString('login', $result->getRedirectUrl());

            // 4. Pengesahan Sesi (Pastikan sesi telah dibersihkan/destroy)
            // session()->get() akan memulangkan null jika data sudah tiada
            $this->assertNull(session()->get('isLoggedIn'));
            $this->assertNull(session()->get('user_id'));
        }
}
