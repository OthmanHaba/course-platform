<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class QuizzesQuestionsSeeder extends Seeder
{
    public function run()
    {
        // Quizzes
        $quizzes = [
            [
                'lesson_id' => 1,
                'title' => 'HTML Fundamentals Quiz',
                'description' => 'Test your knowledge of HTML basics',
                'pass_percentage' => 70,
                // 'order_number' => 4,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'lesson_id' => 4,
                'title' => 'CSS Basics Quiz',
                'description' => 'Check your understanding of CSS fundamentals',
                'pass_percentage' => 70,
                // 'order_number' => 4,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'lesson_id' => 7,
                'title' => 'Python Basics Quiz',
                'description' => 'Test your Python fundamentals',
                'pass_percentage' => 75,
                // 'order_number' => 4,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('quizzes')->insertBatch($quizzes);

        // Questions for Quiz 1 (HTML Fundamentals)
        $questions = [
            [
                'quiz_id' => 1,
                'question_text' => 'What does HTML stand for?',
                'question_type' => 'multiple_choice',
                'options' => json_encode([
                    'Hyper Text Markup Language',
                    'High Tech Modern Language',
                    'Home Tool Markup Language',
                    'Hyperlinks and Text Markup Language'
                ]),
                'correct_answer' => json_encode(['Hyper Text Markup Language']),
                'points' => 10,
                'order_number' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'quiz_id' => 1,
                'question_text' => 'Which tag is used to create a hyperlink?',
                'question_type' => 'multiple_choice',
                'options' => json_encode(['<link>', '<a>', '<href>', '<hyperlink>']),
                'correct_answer' => json_encode(['<a>']),
                'points' => 10,
                'order_number' => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'quiz_id' => 1,
                'question_text' => 'Which tag is used for the largest heading?',
                'question_type' => 'multiple_choice',
                'options' => json_encode(['<heading>', '<h6>', '<h1>', '<head>']),
                'correct_answer' => json_encode(['<h1>']),
                'points' => 10,
                'order_number' => 3,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'quiz_id' => 1,
                'question_text' => 'Select all self-closing tags',
                'question_type' => 'multiple_choice',
                'options' => json_encode(['<img>', '<br>', '<input>', '<div>']),
                'correct_answer' => json_encode(['<img>', '<br>', '<input>']),
                'points' => 15,
                'order_number' => 4,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'quiz_id' => 1,
                'question_text' => 'HTML is a programming language',
                'question_type' => 'true_false',
                'options' => json_encode(['True', 'False']),
                'correct_answer' => json_encode(['False']),
                'points' => 10,
                'order_number' => 5,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // Questions for Quiz 2 (CSS Basics)
            [
                'quiz_id' => 2,
                'question_text' => 'What does CSS stand for?',
                'question_type' => 'multiple_choice',
                'options' => json_encode([
                    'Cascading Style Sheets',
                    'Creative Style Sheets',
                    'Computer Style Sheets',
                    'Colorful Style Sheets'
                ]),
                'correct_answer' => json_encode(['Cascading Style Sheets']),
                'points' => 10,
                'order_number' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'quiz_id' => 2,
                'question_text' => 'Which CSS property controls text size?',
                'question_type' => 'multiple_choice',
                'options' => json_encode(['text-size', 'font-size', 'text-style', 'font-weight']),
                'correct_answer' => json_encode(['font-size']),
                'points' => 10,
                'order_number' => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'quiz_id' => 2,
                'question_text' => 'How do you select an element with id "header"?',
                'question_type' => 'multiple_choice',
                'options' => json_encode(['#header', '.header', 'header', '*header']),
                'correct_answer' => json_encode(['#header']),
                'points' => 10,
                'order_number' => 3,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'quiz_id' => 2,
                'question_text' => 'CSS can only be written in external files',
                'question_type' => 'true_false',
                'options' => json_encode(['True', 'False']),
                'correct_answer' => json_encode(['False']),
                'points' => 10,
                'order_number' => 4,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // Questions for Quiz 3 (Python Basics)
            [
                'quiz_id' => 3,
                'question_text' => 'Which of the following is the correct way to create a variable in Python?',
                'question_type' => 'multiple_choice',
                'options' => json_encode([
                    'int x = 5',
                    'x = 5',
                    'var x = 5',
                    'x := 5'
                ]),
                'correct_answer' => json_encode(['x = 5']),
                'points' => 10,
                'order_number' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'quiz_id' => 3,
                'question_text' => 'What is the output of: print(type([]))?',
                'question_type' => 'multiple_choice',
                'options' => json_encode([
                    '<class \'list\'>',
                    '<class \'array\'>',
                    '<class \'dict\'>',
                    '<class \'tuple\'>'
                ]),
                'correct_answer' => json_encode(['<class \'list\'>']),
                'points' => 10,
                'order_number' => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'quiz_id' => 3,
                'question_text' => 'Python is case-sensitive',
                'question_type' => 'true_false',
                'options' => json_encode(['True', 'False']),
                'correct_answer' => json_encode(['True']),
                'points' => 10,
                'order_number' => 3,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'quiz_id' => 3,
                'question_text' => 'Which are valid Python data types?',
                'question_type' => 'multiple_choice',
                'options' => json_encode(['int', 'string', 'float', 'bool', 'char']),
                'correct_answer' => json_encode(['int', 'float', 'bool']),
                'points' => 15,
                'order_number' => 4,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('questions')->insertBatch($questions);

        echo "Quizzes and Questions seeded successfully!\n";
    }
}
