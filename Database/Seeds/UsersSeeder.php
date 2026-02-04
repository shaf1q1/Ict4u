<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'id'               => 1,
                'fullname'         => 'Muhammad Shafiq bin Tajol ',
                'email'            => 'smtmrk36@gmail.com',
                'password'         => '$2y$12$eRjNEkWTBVh1H.C17HMywecT9/oUd3BNe3YAwPkJT70Db71WvWyrC',
                'created_at'       => date('Y-m-d H:i:s'), // Mengisi default jika null
                'updated_at'       => date('Y-m-d H:i:s'),
                'profile_pic'      => '1768356004_73bd58342dced12c4510.jpeg',
                'reset_token'      => '979027a5a9724a2b1aec34fc435b45e474d4ac8afaf100e4354090bbe7a8ee6e',
                'reset_expires_at' => '2026-01-23 10:17:51',
            ],
        ];

        // Memasukkan data ke dalam jadual 'users'
        $this->db->table('users')->insertBatch($users);
    }
}