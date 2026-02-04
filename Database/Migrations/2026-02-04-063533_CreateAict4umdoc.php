<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAict4u106mdoc extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idservis' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'iddoc' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 145,
                'null'       => true,
                'default'    => null,
            ],
            'namafail' => [
                'type'       => 'VARCHAR',
                'constraint' => 145,
                'null'       => true,
                'default'    => null,
            ],
            'mime' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'default'    => null,
            ],
            'descdoc' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'tkhkemas' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
            'created_by' => [
                'type' => 'INT',
                'null' => true,
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            ],
            'uploaded_by' => [
                'type' => 'INT',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected'],
                'default'    => 'pending',
                'null'       => true,
            ],
        ]);

        // iddoc is the Primary Key
        $this->forge->addKey('iddoc', true);

        $attributes = [
            'ENGINE'  => 'InnoDB',
            'COLLATE' => 'utf8mb4_0900_ai_ci'
        ];

        $this->forge->createTable('aict4u106mdoc', true, $attributes);

        // Set starting auto_increment to 19
        $this->db->query("ALTER TABLE aict4u106mdoc AUTO_INCREMENT = 19;");
    }

    public function down()
    {
        $this->forge->dropTable('aict4u106mdoc');
    }
}