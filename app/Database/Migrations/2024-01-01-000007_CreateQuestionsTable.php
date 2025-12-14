<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateQuestionsTable extends Migration
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
            'quiz_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'question_text' => [
                'type' => 'TEXT',
            ],
            'question_type' => [
                'type'       => 'ENUM',
                'constraint' => ['multiple_choice', 'true_false', 'short_answer'],
                'default'    => 'multiple_choice',
            ],
            'options' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'For multiple choice questions',
            ],
            'correct_answer' => [
                'type' => 'TEXT',
                'comment' => 'Correct answer or answer key',
            ],
            'points' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
            ],
            'explanation' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'order_number' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
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
        $this->forge->addKey('quiz_id');
        $this->forge->addForeignKey('quiz_id', 'quizzes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('questions');
    }

    public function down()
    {
        $this->forge->dropTable('questions');
    }
}
