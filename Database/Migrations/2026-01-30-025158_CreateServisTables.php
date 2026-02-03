<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateServisTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idservis' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => false,
                'auto_increment' => true,
            ],
            'namaservis' => [
                'type'       => 'VARCHAR',
                'constraint' => '145',
                'null'       => true,
            ],
            'status' => [
                'type'       => 'INT',
                'null'       => true,
            ],
            'infourl' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
                'null'       => true,
            ],
            'mohonurl' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
                'null'       => true,
            ],
            'kodkump' => [
                'type'       => 'VARCHAR',
                'constraint' => '45',
                'null'       => true,
            ],
            'kodservis' => [
                'type'       => 'VARCHAR',
                'constraint' => '5',
                'null'       => true,
            ],
            'infoperincian' => [
                'type' => 'MEDIUMTEXT',
                'null' => true,
            ],
            'imejkad' => [
                'type'       => 'VARCHAR',
                'constraint' => '45',
                'null'       => true,
            ],
            'imejheader' => [
                'type'       => 'VARCHAR',
                'constraint' => '45',
                'null'       => true,
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
            'created_by'  => ['type' => 'INT', 'null' => true],
            'uploaded_by' => ['type' => 'INT', 'null' => true],
        ]);

        $this->forge->addKey('idservis', true);
        $this->forge->createTable('aict4u103dservis');
    }

    public function down()
    {
        $this->forge->dropTable('aict4u103dservis');
    }
}
