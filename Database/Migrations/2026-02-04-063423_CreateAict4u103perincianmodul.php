<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAict4u103dperincianmodul extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'idservis' => [
                'type'           => 'INT',
                'unsigned'       => true,
            ],
            'description' => [
                'type'           => 'TEXT',
                'null'           => true,
                'default'        => null,
            ],
            'created_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
                'default'        => null,
            ],
            'updated_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
                'default'        => null,
            ],
            'deleted_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
                'default'        => null,
            ],
            'created_by' => [
                'type'           => 'INT',
                'null'           => true,
                'default'        => null,
            ],
            'uploaded_by' => [
                'type'           => 'INT',
                'null'           => true,
                'default'        => null,
            ],
        ]);

        // Define Primary Key
        $this->forge->addKey('id', true);

        // Define Index (idx_idservis)
        $this->forge->addKey('idservis', false, false, 'idx_idservis');

        // Table Attributes (Engine and Collation)
        $attributes = [
            'ENGINE'  => 'InnoDB',
            'COLLATE' => 'utf8mb4_0900_ai_ci'
        ];

        $this->forge->createTable('aict4u103dperincianmodul', true, $attributes);

        // Manual SQL for starting AUTO_INCREMENT at 10
        $this->db->query("ALTER TABLE aict4u103dperincianmodul AUTO_INCREMENT = 10;");
    }

    public function down()
    {
        $this->forge->dropTable('aict4u103dperincianmodul');
    }
}