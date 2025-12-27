<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class EnrollmentsProgressSeeder extends Seeder
{
    public function run()
    {
        // Enrollments (Students 5-9 enrolled in various courses)
        $enrollments = [
            // Student 5 (Alice) enrollments
            [
                'user_id' => 5,
                'course_id' => 1,
                'enrollment_date' => date('Y-m-d H:i:s', strtotime('-30 days')),
                'progress_percentage' => 65.5,
                'last_accessed_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'completion_date' => null,
                'created_at' => date('Y-m-d H:i:s', strtotime('-30 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
            ],
            [
                'user_id' => 5,
                'course_id' => 4,
                'enrollment_date' => date('Y-m-d H:i:s', strtotime('-45 days')),
                'progress_percentage' => 100,
                'last_accessed_at' => date('Y-m-d H:i:s', strtotime('-10 days')),
                'completion_date' => date('Y-m-d H:i:s', strtotime('-10 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-45 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-10 days')),
            ],
            [
                'user_id' => 5,
                'course_id' => 6,
                'enrollment_date' => date('Y-m-d H:i:s', strtotime('-20 days')),
                'progress_percentage' => 35.0,
                'last_accessed_at' => date('Y-m-d H:i:s', strtotime('-1 days')),
                'completion_date' => null,
                'created_at' => date('Y-m-d H:i:s', strtotime('-20 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-1 days')),
            ],

            // Student 6 (Bob) enrollments
            [
                'user_id' => 6,
                'course_id' => 1,
                'enrollment_date' => date('Y-m-d H:i:s', strtotime('-25 days')),
                'progress_percentage' => 45.0,
                'last_accessed_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
                'completion_date' => null,
                'created_at' => date('Y-m-d H:i:s', strtotime('-25 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
            ],
            [
                'user_id' => 6,
                'course_id' => 2,
                'enrollment_date' => date('Y-m-d H:i:s', strtotime('-15 days')),
                'progress_percentage' => 20.0,
                'last_accessed_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
                'completion_date' => null,
                'created_at' => date('Y-m-d H:i:s', strtotime('-15 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
            ],

            // Student 7 (Carol) enrollments
            [
                'user_id' => 7,
                'course_id' => 4,
                'enrollment_date' => date('Y-m-d H:i:s', strtotime('-35 days')),
                'progress_percentage' => 80.0,
                'last_accessed_at' => date('Y-m-d H:i:s', strtotime('-1 days')),
                'completion_date' => null,
                'created_at' => date('Y-m-d H:i:s', strtotime('-35 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-1 days')),
            ],
            [
                'user_id' => 7,
                'course_id' => 5,
                'enrollment_date' => date('Y-m-d H:i:s', strtotime('-40 days')),
                'progress_percentage' => 55.0,
                'last_accessed_at' => date('Y-m-d H:i:s', strtotime('-4 days')),
                'completion_date' => null,
                'created_at' => date('Y-m-d H:i:s', strtotime('-40 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-4 days')),
            ],
            [
                'user_id' => 7,
                'course_id' => 7,
                'enrollment_date' => date('Y-m-d H:i:s', strtotime('-10 days')),
                'progress_percentage' => 15.0,
                'last_accessed_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'completion_date' => null,
                'created_at' => date('Y-m-d H:i:s', strtotime('-10 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
            ],

            // Student 8 (David) enrollments
            [
                'user_id' => 8,
                'course_id' => 3,
                'enrollment_date' => date('Y-m-d H:i:s', strtotime('-28 days')),
                'progress_percentage' => 90.0,
                'last_accessed_at' => date('Y-m-d H:i:s'),
                'completion_date' => null,
                'created_at' => date('Y-m-d H:i:s', strtotime('-28 days')),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id' => 8,
                'course_id' => 8,
                'enrollment_date' => date('Y-m-d H:i:s', strtotime('-12 days')),
                'progress_percentage' => 25.0,
                'last_accessed_at' => date('Y-m-d H:i:s', strtotime('-1 days')),
                'completion_date' => null,
                'created_at' => date('Y-m-d H:i:s', strtotime('-12 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-1 days')),
            ],

            // Student 9 (Emma) enrollments
            [
                'user_id' => 9,
                'course_id' => 1,
                'enrollment_date' => date('Y-m-d H:i:s', strtotime('-50 days')),
                'progress_percentage' => 100,
                'last_accessed_at' => date('Y-m-d H:i:s', strtotime('-15 days')),
                'completion_date' => date('Y-m-d H:i:s', strtotime('-15 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-50 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-15 days')),
            ],
            [
                'user_id' => 9,
                'course_id' => 6,
                'enrollment_date' => date('Y-m-d H:i:s', strtotime('-18 days')),
                'progress_percentage' => 60.0,
                'last_accessed_at' => date('Y-m-d H:i:s'),
                'completion_date' => null,
                'created_at' => date('Y-m-d H:i:s', strtotime('-18 days')),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('enrollments')->insertBatch($enrollments);

        // Progress tracking for some completed lessons
        $progress = [
            // Alice's progress in Course 1
            ['user_id' => 5, 'lesson_id' => 1, 'is_completed' => 1, 'completed_at' => date('Y-m-d H:i:s', strtotime('-29 days')), 'created_at' => date('Y-m-d H:i:s', strtotime('-29 days')), 'updated_at' => date('Y-m-d H:i:s', strtotime('-29 days'))],
            ['user_id' => 5, 'lesson_id' => 2, 'is_completed' => 1, 'completed_at' => date('Y-m-d H:i:s', strtotime('-28 days')), 'created_at' => date('Y-m-d H:i:s', strtotime('-28 days')), 'updated_at' => date('Y-m-d H:i:s', strtotime('-28 days'))],
            ['user_id' => 5, 'lesson_id' => 3, 'is_completed' => 1, 'completed_at' => date('Y-m-d H:i:s', strtotime('-25 days')), 'created_at' => date('Y-m-d H:i:s', strtotime('-25 days')), 'updated_at' => date('Y-m-d H:i:s', strtotime('-25 days'))],
            ['user_id' => 5, 'lesson_id' => 4, 'is_completed' => 1, 'completed_at' => date('Y-m-d H:i:s', strtotime('-24 days')), 'created_at' => date('Y-m-d H:i:s', strtotime('-24 days')), 'updated_at' => date('Y-m-d H:i:s', strtotime('-24 days'))],
            ['user_id' => 5, 'lesson_id' => 5, 'is_completed' => 1, 'completed_at' => date('Y-m-d H:i:s', strtotime('-23 days')), 'created_at' => date('Y-m-d H:i:s', strtotime('-23 days')), 'updated_at' => date('Y-m-d H:i:s', strtotime('-23 days'))],
            ['user_id' => 5, 'lesson_id' => 6, 'is_completed' => 1, 'completed_at' => date('Y-m-d H:i:s', strtotime('-20 days')), 'created_at' => date('Y-m-d H:i:s', strtotime('-20 days')), 'updated_at' => date('Y-m-d H:i:s', strtotime('-20 days'))],
            ['user_id' => 5, 'lesson_id' => 7, 'is_completed' => 1, 'completed_at' => date('Y-m-d H:i:s', strtotime('-18 days')), 'created_at' => date('Y-m-d H:i:s', strtotime('-18 days')), 'updated_at' => date('Y-m-d H:i:s', strtotime('-18 days'))],
            ['user_id' => 5, 'lesson_id' => 8, 'is_completed' => 0, 'completed_at' => null, 'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')), 'updated_at' => date('Y-m-d H:i:s', strtotime('-2 days'))],

            // Alice's completed progress in Course 4 (lessons 12-18 are in sections 5-7 for Course 4)
            ['user_id' => 5, 'lesson_id' => 12, 'is_completed' => 1, 'completed_at' => date('Y-m-d H:i:s', strtotime('-44 days')), 'created_at' => date('Y-m-d H:i:s', strtotime('-44 days')), 'updated_at' => date('Y-m-d H:i:s', strtotime('-44 days'))],
            ['user_id' => 5, 'lesson_id' => 13, 'is_completed' => 1, 'completed_at' => date('Y-m-d H:i:s', strtotime('-40 days')), 'created_at' => date('Y-m-d H:i:s', strtotime('-40 days')), 'updated_at' => date('Y-m-d H:i:s', strtotime('-40 days'))],
            ['user_id' => 5, 'lesson_id' => 14, 'is_completed' => 1, 'completed_at' => date('Y-m-d H:i:s', strtotime('-35 days')), 'created_at' => date('Y-m-d H:i:s', strtotime('-35 days')), 'updated_at' => date('Y-m-d H:i:s', strtotime('-35 days'))],
            ['user_id' => 5, 'lesson_id' => 15, 'is_completed' => 1, 'completed_at' => date('Y-m-d H:i:s', strtotime('-30 days')), 'created_at' => date('Y-m-d H:i:s', strtotime('-30 days')), 'updated_at' => date('Y-m-d H:i:s', strtotime('-30 days'))],
            ['user_id' => 5, 'lesson_id' => 16, 'is_completed' => 1, 'completed_at' => date('Y-m-d H:i:s', strtotime('-25 days')), 'created_at' => date('Y-m-d H:i:s', strtotime('-25 days')), 'updated_at' => date('Y-m-d H:i:s', strtotime('-25 days'))],
            ['user_id' => 5, 'lesson_id' => 17, 'is_completed' => 1, 'completed_at' => date('Y-m-d H:i:s', strtotime('-20 days')), 'created_at' => date('Y-m-d H:i:s', strtotime('-20 days')), 'updated_at' => date('Y-m-d H:i:s', strtotime('-20 days'))],

            // Bob's progress in Course 1
            ['user_id' => 6, 'lesson_id' => 1, 'is_completed' => 1, 'completed_at' => date('Y-m-d H:i:s', strtotime('-24 days')), 'created_at' => date('Y-m-d H:i:s', strtotime('-24 days')), 'updated_at' => date('Y-m-d H:i:s', strtotime('-24 days'))],
            ['user_id' => 6, 'lesson_id' => 2, 'is_completed' => 1, 'completed_at' => date('Y-m-d H:i:s', strtotime('-22 days')), 'created_at' => date('Y-m-d H:i:s', strtotime('-22 days')), 'updated_at' => date('Y-m-d H:i:s', strtotime('-22 days'))],
            ['user_id' => 6, 'lesson_id' => 3, 'is_completed' => 1, 'completed_at' => date('Y-m-d H:i:s', strtotime('-20 days')), 'created_at' => date('Y-m-d H:i:s', strtotime('-20 days')), 'updated_at' => date('Y-m-d H:i:s', strtotime('-20 days'))],
            ['user_id' => 6, 'lesson_id' => 4, 'is_completed' => 0, 'completed_at' => null, 'created_at' => date('Y-m-d H:i:s', strtotime('-5 days')), 'updated_at' => date('Y-m-d H:i:s', strtotime('-5 days'))],

            // Emma's completed progress in Course 1 (lessons 1-11 are in sections 1-4 for Course 1)
            ['user_id' => 9, 'lesson_id' => 1, 'is_completed' => 1, 'completed_at' => date('Y-m-d H:i:s', strtotime('-49 days')), 'created_at' => date('Y-m-d H:i:s', strtotime('-49 days')), 'updated_at' => date('Y-m-d H:i:s', strtotime('-49 days'))],
            ['user_id' => 9, 'lesson_id' => 2, 'is_completed' => 1, 'completed_at' => date('Y-m-d H:i:s', strtotime('-47 days')), 'created_at' => date('Y-m-d H:i:s', strtotime('-47 days')), 'updated_at' => date('Y-m-d H:i:s', strtotime('-47 days'))],
            ['user_id' => 9, 'lesson_id' => 3, 'is_completed' => 1, 'completed_at' => date('Y-m-d H:i:s', strtotime('-45 days')), 'created_at' => date('Y-m-d H:i:s', strtotime('-45 days')), 'updated_at' => date('Y-m-d H:i:s', strtotime('-45 days'))],
            ['user_id' => 9, 'lesson_id' => 4, 'is_completed' => 1, 'completed_at' => date('Y-m-d H:i:s', strtotime('-43 days')), 'created_at' => date('Y-m-d H:i:s', strtotime('-43 days')), 'updated_at' => date('Y-m-d H:i:s', strtotime('-43 days'))],
            ['user_id' => 9, 'lesson_id' => 5, 'is_completed' => 1, 'completed_at' => date('Y-m-d H:i:s', strtotime('-41 days')), 'created_at' => date('Y-m-d H:i:s', strtotime('-41 days')), 'updated_at' => date('Y-m-d H:i:s', strtotime('-41 days'))],
            ['user_id' => 9, 'lesson_id' => 6, 'is_completed' => 1, 'completed_at' => date('Y-m-d H:i:s', strtotime('-38 days')), 'created_at' => date('Y-m-d H:i:s', strtotime('-38 days')), 'updated_at' => date('Y-m-d H:i:s', strtotime('-38 days'))],
            ['user_id' => 9, 'lesson_id' => 7, 'is_completed' => 1, 'completed_at' => date('Y-m-d H:i:s', strtotime('-35 days')), 'created_at' => date('Y-m-d H:i:s', strtotime('-35 days')), 'updated_at' => date('Y-m-d H:i:s', strtotime('-35 days'))],
            ['user_id' => 9, 'lesson_id' => 8, 'is_completed' => 1, 'completed_at' => date('Y-m-d H:i:s', strtotime('-30 days')), 'created_at' => date('Y-m-d H:i:s', strtotime('-30 days')), 'updated_at' => date('Y-m-d H:i:s', strtotime('-30 days'))],
            ['user_id' => 9, 'lesson_id' => 9, 'is_completed' => 1, 'completed_at' => date('Y-m-d H:i:s', strtotime('-28 days')), 'created_at' => date('Y-m-d H:i:s', strtotime('-28 days')), 'updated_at' => date('Y-m-d H:i:s', strtotime('-28 days'))],
            ['user_id' => 9, 'lesson_id' => 10, 'is_completed' => 1, 'completed_at' => date('Y-m-d H:i:s', strtotime('-25 days')), 'created_at' => date('Y-m-d H:i:s', strtotime('-25 days')), 'updated_at' => date('Y-m-d H:i:s', strtotime('-25 days'))],
            ['user_id' => 9, 'lesson_id' => 11, 'is_completed' => 1, 'completed_at' => date('Y-m-d H:i:s', strtotime('-20 days')), 'created_at' => date('Y-m-d H:i:s', strtotime('-20 days')), 'updated_at' => date('Y-m-d H:i:s', strtotime('-20 days'))],
        ];

        $this->db->table('progress')->insertBatch($progress);

        echo "Enrollments and Progress seeded successfully!\n";
    }
}
