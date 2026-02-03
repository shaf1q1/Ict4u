<?php

namespace App\Controllers;

use App\Models\UserModel;

class MigrateController extends BaseController
{
    public function hashAllPasswords()
    {
        $userModel = new UserModel();
        $users = $userModel->findAll();

        $count = 0;
        foreach($users as $user){
            // Skip if password already hashed (basic check: length >= 60)
            if(strlen($user['password']) < 60){
                $userModel->update($user['id'], [
                    'password' => password_hash($user['password'], PASSWORD_DEFAULT)
                ]);
                $count++;
            }
        }

        echo "Migrated $count users to hashed passwords ✅";
    }
}
