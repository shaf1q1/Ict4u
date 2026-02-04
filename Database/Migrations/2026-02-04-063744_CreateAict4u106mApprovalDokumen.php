<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAict4u106mApprovalDokumen extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'iddoc' => [
                'type'           => 'INT',
                'constraint'     => 11,
            ],
            'status' => [
                'type'           => 'ENUM',
                'constraint'     => ['pending', 'approved', 'rejected'],
                'default'        => 'pending',
            ],
            'approved_by' => [
                'type'           => 'VARCHAR',
                'constraint'     => 100,
                'null'           => true,
                'default'        => null,
            ],
            'approved_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
                'default'        => null,
            ],
            'created_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
                'default'        => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
                'default'        => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            ],
        ]);

        // Primary Key
        $this->forge->addKey('id', true);

        // Standard Index
        $this->forge->addKey('iddoc', false);

        // Foreign Key Constraint
        // REFERENCES aict4u106mdoc(iddoc) ON DELETE CASCADE
        $this->forge->addForeignKey('iddoc', 'aict4u106mdoc', 'iddoc', 'NO ACTION', 'CASCADE');

        $attributes = [
            'ENGINE'  => 'InnoDB',
            'COLLATE' => 'utf8mb4_0900_ai_ci'
        ];

        $this->forge->createTable('aict4u106m_approval_dokumen', true, $attributes);

        // Set starting auto_increment to 39
        $this->db->query("ALTER TABLE aict4u106m_approval_dokumen AUTO_INCREMENT = 39;");
    }

    public function down()
    {
        // Drop foreign key first to avoid errors
        $this->db->query('ALTER TABLE aict4u106m_approval_dokumen DROP FOREIGN KEY aict4u106m_approval_dokumen_iddoc_foreign');
        $this->forge->dropTable('aict4u106m_approval_dokumen');
    }
}