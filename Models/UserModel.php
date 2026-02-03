<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    
    // TAMBAH 'profile_pic' di dalam array ini
    protected $allowedFields    = ['fullname','username', 'email', 'password', 'profile_pic', 'created_at','reset_token','reset_expires_at'];
    
        // app/Models/UserModel.php
        protected $beforeInsert = ['hashPassword'];
        protected $beforeUpdate = ['hashPassword'];

        protected function hashPassword(array $data)
        {
            if (isset($data['data']['password'])) {
                $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
            }
            return $data;
        }
}