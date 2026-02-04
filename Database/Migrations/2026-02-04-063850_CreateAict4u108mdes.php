<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateAict4u108mdes extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'iddesc' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            '108idservis' => [
                'type'           => 'INT',
                'constraint'     => 11,
            ],
            'description' => [
                'type'           => 'TEXT',
                'null'           => true,
                'default'        => null,
            ],
            'created_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
                'default'        => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
                'default'        => new RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            ],
            'deleted_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
                'default'        => null,
            ],
            'created_by' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'null'           => true,
                'default'        => null,
            ],
        ]);

        // Primary Key
        $this->forge->addKey('iddesc', true);

        // Index for 108idservis
        $this->forge->addKey('108idservis', false);

        // Foreign Key Constraint
        // REFERENCES aict4u103dservis(idservis) ON DELETE CASCADE
        $this->forge->addForeignKey('108idservis', 'aict4u103dservis', 'idservis', 'NO ACTION', 'CASCADE');

        $attributes = [
            'ENGINE'  => 'InnoDB',
            'COLLATE' => 'utf8mb4_0900_ai_ci'
        ];

        $this->forge->createTable('aict4u108mdes', true, $attributes);

        // Set starting auto_increment to 14
        $this->db->query("ALTER TABLE aict4u108mdes AUTO_INCREMENT = 14;");
    }

    public function down()
    {
        // Drop foreign key first to avoid issues
        // Note: CI4 naming convention is usually table_column_foreign
        $this->db->query('ALTER TABLE aict4u108mdes DROP FOREIGN KEY IF EXISTS aict4u108mdes_108idservis_foreign');
        $this->forge->dropTable('aict4u108mdes');
    }
}