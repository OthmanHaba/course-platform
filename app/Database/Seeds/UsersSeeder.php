<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Admin User
            [
                'email' => 'admin@example.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'first_name' => 'Admin',
                'last_name' => 'User',
                'user_type' => 'admin',
                'is_active' => 1,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // Instructors
            [
                'email' => 'john.instructor@example.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'first_name' => 'John',
                'last_name' => 'Instructor',
                'user_type' => 'instructor',
                // 'bio' => 'Experienced web developer with 10+ years in the industry. Passionate about teaching modern web technologies.',
                'is_active' => 1,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'email' => 'sarah.instructor@example.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'first_name' => 'Sarah',
                'last_name' => 'Johnson',
                'user_type' => 'instructor',
                // 'bio' => 'Full-stack developer and designer. Love creating beautiful and functional web applications.',
                'is_active' => 1,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'email' => 'mike.instructor@example.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'first_name' => 'Mike',
                'last_name' => 'Chen',
                'user_type' => 'instructor',
                // 'bio' => 'Data scientist and machine learning engineer with a passion for education.',
                'is_active' => 1,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // Students
            [
                'email' => 'student1@example.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'first_name' => 'Alice',
                'last_name' => 'Smith',
                'user_type' => 'student',
                'is_active' => 1,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'email' => 'student2@example.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'first_name' => 'Bob',
                'last_name' => 'Williams',
                'user_type' => 'student',
                'is_active' => 1,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'email' => 'student3@example.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'first_name' => 'Carol',
                'last_name' => 'Davis',
                'user_type' => 'student',
                'is_active' => 1,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'email' => 'student4@example.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'first_name' => 'David',
                'last_name' => 'Martinez',
                'user_type' => 'student',
                'is_active' => 1,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'email' => 'student5@example.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'first_name' => 'Emma',
                'last_name' => 'Anderson',
                'user_type' => 'student',
                'is_active' => 1,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('users')->ignore(true)->insertBatch($data);

        echo "Users seeded successfully!\n";
        echo "Admin: admin@example.com / admin123\n";
        echo "Instructors: john.instructor@example.com, sarah.instructor@example.com, mike.instructor@example.com / password123\n";
        echo "Students: student1-5@example.com / password123\n";
    }
}
