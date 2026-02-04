<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'fullname' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'profile_pic' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'reset_token' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'reset_expires_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        // Primary Key
        $this->forge->addKey('id', true);

        // Unique Indexes
        $this->forge->addUniqueKey('fullname', 'username'); // Menggunakan nama index 'username' seperti SQL anda
        $this->forge->addUniqueKey('email', 'email');

        $attributes = [
            'ENGINE'  => 'InnoDB',
            'COLLATE' => 'utf8mb4_general_ci'
        ];

        $this->forge->createTable('users', true, $attributes);

        // Set starting auto_increment (dalam SQL anda ia adalah 2)
        $this->db->query("ALTER TABLE users AUTO_INCREMENT = 2;");
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}