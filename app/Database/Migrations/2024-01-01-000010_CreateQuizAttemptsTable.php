<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateQuizAttemptsTable extends Migration
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
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'quiz_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'attempt_number' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
            ],
            'answers' => [
                'type' => 'JSON',
                'comment' => 'User submitted answers',
            ],
            'score' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => '0.00',
            ],
            'total_points' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'earned_points' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'is_passed' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'time_taken' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'comment'    => 'in seconds',
            ],
            'started_at' => [
                'type' => 'DATETIME',
            ],
            'completed_at' => [
                'type' => 'DATETIME',
                'null' => true,
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
        $this->forge->addKey(['user_id', 'quiz_id']);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('quiz_id', 'quizzes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('quiz_attempts');
    }

    public function down()
    {
        $this->forge->dropTable('quiz_attempts');
    }
}
