<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ReviewsWishlistSeeder extends Seeder
{
    public function run()
    {
        // Reviews for various courses
        $reviews = [
            // Reviews for Course 1 (Complete Web Development Bootcamp)
            [
                'user_id' => 9,
                'course_id' => 1,
                'rating' => 5,
                'review' => 'Excellent course! Very comprehensive and well-structured. I learned so much and feel confident building websites now.',
                'is_approved' => 1,
                'created_at' => date('Y-m-d H:i:s', strtotime('-14 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-14 days')),
            ],
            [
                'user_id' => 5,
                'course_id' => 1,
                'rating' => 4,
                'review' => 'Great course overall. The instructor explains concepts clearly. Would have liked more advanced topics though.',
                'is_approved' => 1,
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
            ],
            [
                'user_id' => 6,
                'course_id' => 1,
                'rating' => 5,
                'review' => 'Best web development course I\'ve taken! Highly recommend for beginners.',
                'is_approved' => 1,
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-1 days')),
            ],

            // Reviews for Course 2 (Advanced Vue.js)
            [
                'user_id' => 6,
                'course_id' => 2,
                'rating' => 5,
                'review' => 'Amazing deep dive into Vue 3! The Composition API section was particularly helpful.',
                'is_approved' => 1,
                'created_at' => date('Y-m-d H:i:s', strtotime('-8 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-8 days')),
            ],

            // Reviews for Course 3 (React Native)
            [
                'user_id' => 8,
                'course_id' => 3,
                'rating' => 4,
                'review' => 'Good course on React Native. Covers all the essentials. Some sections could be more in-depth.',
                'is_approved' => 1,
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
            ],

            // Reviews for Course 4 (Python for Data Science)
            [
                'user_id' => 5,
                'course_id' => 4,
                'rating' => 5,
                'review' => 'Perfect introduction to data science! The hands-on projects were really valuable.',
                'is_approved' => 1,
                'created_at' => date('Y-m-d H:i:s', strtotime('-9 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-9 days')),
            ],
            [
                'user_id' => 7,
                'course_id' => 4,
                'rating' => 5,
                'review' => 'Excellent course for beginners. Clear explanations and practical examples.',
                'is_approved' => 1,
                'created_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
            ],

            // Reviews for Course 5 (Machine Learning)
            [
                'user_id' => 7,
                'course_id' => 5,
                'rating' => 4,
                'review' => 'Comprehensive coverage of ML algorithms. Requires some math background but very rewarding.',
                'is_approved' => 1,
                'created_at' => date('Y-m-d H:i:s', strtotime('-6 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-6 days')),
            ],

            // Reviews for Course 6 (UI/UX Design)
            [
                'user_id' => 9,
                'course_id' => 6,
                'rating' => 5,
                'review' => 'Love this course! I\'m now confident in creating user-friendly designs.',
                'is_approved' => 1,
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-1 days')),
            ],
            [
                'user_id' => 5,
                'course_id' => 6,
                'rating' => 4,
                'review' => 'Great introduction to UX principles. Would like more advanced prototyping techniques.',
                'is_approved' => 1,
                'created_at' => date('Y-m-d H:i:s', strtotime('-12 hours')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-12 hours')),
            ],

            // Reviews for Course 7 (Start Your Own Business)
            [
                'user_id' => 7,
                'course_id' => 7,
                'rating' => 4,
                'review' => 'Solid business fundamentals. The business plan template was very useful.',
                'is_approved' => 1,
                'created_at' => date('Y-m-d H:i:s', strtotime('-4 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-4 days')),
            ],

            // Reviews for Course 8 (Digital Marketing)
            [
                'user_id' => 8,
                'course_id' => 8,
                'rating' => 5,
                'review' => 'Exactly what I needed to start my marketing career. Practical and up-to-date!',
                'is_approved' => 1,
                'created_at' => date('Y-m-d H:i:s', strtotime('-6 hours')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-6 hours')),
            ],

            // Pending review (not yet approved)
            [
                'user_id' => 6,
                'course_id' => 2,
                'rating' => 3,
                'review' => 'The course is okay but moves too fast for intermediate level.',
                'is_approved' => 0,
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-2 hours')),
            ],
        ];

        $this->db->table('reviews')->insertBatch($reviews);

        // Wishlist items
        $wishlist = [
            // Student 5 wishlist
            [
                'user_id' => 5,
                'course_id' => 2,
                'created_at' => date('Y-m-d H:i:s', strtotime('-20 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-20 days')),
            ],
            [
                'user_id' => 5,
                'course_id' => 3,
                'created_at' => date('Y-m-d H:i:s', strtotime('-15 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-15 days')),
            ],
            [
                'user_id' => 5,
                'course_id' => 5,
                'created_at' => date('Y-m-d H:i:s', strtotime('-10 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-10 days')),
            ],

            // Student 6 wishlist
            [
                'user_id' => 6,
                'course_id' => 4,
                'created_at' => date('Y-m-d H:i:s', strtotime('-18 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-18 days')),
            ],
            [
                'user_id' => 6,
                'course_id' => 6,
                'created_at' => date('Y-m-d H:i:s', strtotime('-12 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-12 days')),
            ],

            // Student 7 wishlist
            [
                'user_id' => 7,
                'course_id' => 1,
                'created_at' => date('Y-m-d H:i:s', strtotime('-25 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-25 days')),
            ],
            [
                'user_id' => 7,
                'course_id' => 3,
                'created_at' => date('Y-m-d H:i:s', strtotime('-8 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-8 days')),
            ],
            [
                'user_id' => 7,
                'course_id' => 8,
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
            ],

            // Student 8 wishlist
            [
                'user_id' => 8,
                'course_id' => 1,
                'created_at' => date('Y-m-d H:i:s', strtotime('-14 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-14 days')),
            ],
            [
                'user_id' => 8,
                'course_id' => 4,
                'created_at' => date('Y-m-d H:i:s', strtotime('-7 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-7 days')),
            ],

            // Student 9 wishlist
            [
                'user_id' => 9,
                'course_id' => 2,
                'created_at' => date('Y-m-d H:i:s', strtotime('-22 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-22 days')),
            ],
            [
                'user_id' => 9,
                'course_id' => 3,
                'created_at' => date('Y-m-d H:i:s', strtotime('-16 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-16 days')),
            ],
            [
                'user_id' => 9,
                'course_id' => 5,
                'created_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
            ],
        ];

        $this->db->table('wishlist')->insertBatch($wishlist);

        echo "Reviews and Wishlist seeded successfully!\n";
    }
}
