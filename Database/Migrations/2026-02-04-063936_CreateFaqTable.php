<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateFaqTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'idservis' => [
                'type'           => 'INT',
                'constraint'     => 11,
            ],
            'question' => [
                'type'           => 'VARCHAR',
                'constraint'     => 255,
            ],
            'answer' => [
                'type'           => 'TEXT',
            ],
            'created_at' => [
                'type'           => 'TIMESTAMP',
                'null'           => true,
                'default'        => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type'           => 'TIMESTAMP',
                'null'           => true,
                'default'        => new RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            ],
            'is_pinned' => [
                'type'           => 'TINYINT',
                'constraint'     => 1,
                'null'           => true,
                'default'        => 0,
            ],
            'sort_order' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'null'           => true,
                'default'        => 0,
            ],
        ]);

        // Primary Key
        $this->forge->addKey('id', true);

        // Index for idservis
        $this->forge->addKey('idservis', false);

        // Foreign Key Constraint
        // REFERENCES aict4u103dservis(idservis) ON DELETE CASCADE
        $this->forge->addForeignKey('idservis', 'aict4u103dservis', 'idservis', 'NO ACTION', 'CASCADE', 'faq_ibfk_1');

        $attributes = [
            'ENGINE'  => 'InnoDB',
            'COLLATE' => 'utf8mb4_0900_ai_ci'
        ];

        $this->forge->createTable('faq', true, $attributes);

        // Set starting auto_increment to 77
        $this->db->query("ALTER TABLE faq AUTO_INCREMENT = 77;");
    }

    public function down()
    {
        // Drop foreign key first
        $this->db->query('ALTER TABLE faq DROP FOREIGN KEY IF EXISTS faq_ibfk_1');
        $this->forge->dropTable('faq');
    }
}