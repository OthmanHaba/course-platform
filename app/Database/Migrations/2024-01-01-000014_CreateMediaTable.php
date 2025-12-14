<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMediaTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'file_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'file_path' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
            ],
            'file_type' => [
                'type'       => 'ENUM',
                'constraint' => ['image', 'video', 'document', 'other'],
                'default'    => 'other',
            ],
            'mime_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'file_size' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'comment'    => 'in bytes',
            ],
            'uploaded_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'entity_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
                'comment'    => 'course, lesson, user, etc.',
            ],
            'entity_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
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
        $this->forge->addKey('uploaded_by');
        $this->forge->addKey(['entity_type', 'entity_id']);
        $this->forge->addForeignKey('uploaded_by', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('media');
    }

    public function down()
    {
        $this->forge->dropTable('media');
    }
}
