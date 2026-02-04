<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreatePasswordResetsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'token' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);

        // Menetapkan Primary Key pada 'token'
        $this->forge->addKey('token', true);

        // Atribut jadual
        $attributes = [
            'ENGINE'  => 'InnoDB',
            'COLLATE' => 'utf8mb4_0900_ai_ci'
        ];

        $this->forge->createTable('password_resets', true, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('password_resets');
    }
}