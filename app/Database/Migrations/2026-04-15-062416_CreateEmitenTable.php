<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEmitenTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'code' => [
                'type' => 'VARCHAR',
                'constraint' => '5',
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
            'sector' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ],
            'ai_analysis' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'sector' // Menaruh kolom setelah kolom sector agar rapi
            ],
            'last_ai_update' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'ai_analysis'
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('code'); // Agar tidak ada kode saham ganda
        $this->forge->createTable('emiten');
    }

    public function down()
    {
        $this->forge->dropTable('emiten');
    }
}