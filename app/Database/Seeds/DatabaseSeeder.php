<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        echo "\n=================================\n";
        echo "Starting Database Seeding...\n";
        echo "=================================\n\n";

        // Run seeders in order (respecting foreign key constraints)
        echo "[1/7] Seeding Users...\n";
        $this->call('UsersSeeder');
        echo "\n";

        echo "[2/7] Seeding Categories...\n";
        $this->call('CategoriesSeeder');
        echo "\n";

        echo "[3/7] Seeding Courses...\n";
        $this->call('CoursesSeeder');
        echo "\n";

        echo "[4/7] Seeding Sections and Lessons...\n";
        $this->call('SectionsLessonsSeeder');
        echo "\n";

        echo "[5/7] Seeding Quizzes and Questions...\n";
        $this->call('QuizzesQuestionsSeeder');
        echo "\n";

        echo "[6/7] Seeding Enrollments and Progress...\n";
        $this->call('EnrollmentsProgressSeeder');
        echo "\n";

        echo "[7/7] Seeding Reviews and Wishlist...\n";
        $this->call('ReviewsWishlistSeeder');
        echo "\n";

        echo "=================================\n";
        echo "Database Seeding Complete!\n";
        echo "=================================\n\n";

        echo "Test Accounts:\n";
        echo "---------------------------------\n";
        echo "ADMIN:\n";
        echo "  Email: admin@example.com\n";
        echo "  Password: admin123\n\n";

        echo "INSTRUCTORS:\n";
        echo "  Email: john.instructor@example.com\n";
        echo "  Email: sarah.instructor@example.com\n";
        echo "  Email: mike.instructor@example.com\n";
        echo "  Password: password123\n\n";

        echo "STUDENTS:\n";
        echo "  Email: student1@example.com (Alice Smith)\n";
        echo "  Email: student2@example.com (Bob Williams)\n";
        echo "  Email: student3@example.com (Carol Davis)\n";
        echo "  Email: student4@example.com (David Martinez)\n";
        echo "  Email: student5@example.com (Emma Anderson)\n";
        echo "  Password: password123\n\n";

        echo "Database Summary:\n";
        echo "---------------------------------\n";
        echo "- 10 Users (1 Admin, 3 Instructors, 5 Students)\n";
        echo "- 8 Categories\n";
        echo "- 9 Courses (8 Published, 1 Draft)\n";
        echo "- 7 Sections with 19 Lessons\n";
        echo "- 3 Quizzes with 13 Questions\n";
        echo "- 12 Enrollments with Progress Tracking\n";
        echo "- 13 Reviews (12 Approved, 1 Pending)\n";
        echo "- 13 Wishlist Items\n\n";
    }
}
