<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SectionsLessonsSeeder extends Seeder
{
    public function run()
    {
        // Sections for Course 1 (Complete Web Development Bootcamp)
        $sections = [
            [
                'course_id' => 1,
                'title' => 'Getting Started',
                'description' => 'Introduction to web development and setting up your environment',
                'order_number' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'course_id' => 1,
                'title' => 'HTML Fundamentals',
                'description' => 'Learn the building blocks of web pages',
                'order_number' => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'course_id' => 1,
                'title' => 'CSS Styling',
                'description' => 'Make your websites beautiful with CSS',
                'order_number' => 3,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'course_id' => 1,
                'title' => 'JavaScript Basics',
                'description' => 'Add interactivity to your websites',
                'order_number' => 4,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // Sections for Course 4 (Python for Data Science)
            [
                'course_id' => 4,
                'title' => 'Python Basics',
                'description' => 'Learn Python fundamentals',
                'order_number' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'course_id' => 4,
                'title' => 'NumPy for Data Analysis',
                'description' => 'Master numerical computing with NumPy',
                'order_number' => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'course_id' => 4,
                'title' => 'Pandas DataFrames',
                'description' => 'Work with structured data using Pandas',
                'order_number' => 3,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('sections')->insertBatch($sections);

        // Lessons for Section 1 (Getting Started)
        $lessons = [
            [
                'section_id' => 1,
                'title' => 'Welcome to the Course',
                'content_type' => 'video',
                'content' => 'Introduction video explaining what you will learn',
                'video_url' => 'https://example.com/videos/welcome.mp4',
                'video_duration' => 300,
                'order_number' => 1,
                'is_preview' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'section_id' => 1,
                'title' => 'Setting Up Your Development Environment',
                'content_type' => 'video',
                'content' => 'Learn how to install VS Code and set up your workspace',
                'video_url' => 'https://example.com/videos/setup.mp4',
                'video_duration' => 600,
                'order_number' => 2,
                'is_preview' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // Lessons for Section 2 (HTML Fundamentals)
            [
                'section_id' => 2,
                'title' => 'HTML Document Structure',
                'content_type' => 'video',
                'content' => 'Understanding the basic structure of an HTML document',
                'video_url' => 'https://example.com/videos/html-structure.mp4',
                'video_duration' => 480,
                'order_number' => 1,
                'is_preview' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'section_id' => 2,
                'title' => 'Common HTML Tags',
                'content_type' => 'article',
                'content' => '<h1>Common HTML Tags</h1><p>In this lesson, we will explore the most commonly used HTML tags including headings, paragraphs, links, and images.</p>',
                'video_url' => null,
                'video_duration' => 0,
                'order_number' => 2,
                'is_preview' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'section_id' => 2,
                'title' => 'Forms and Inputs',
                'content_type' => 'video',
                'content' => 'Creating interactive forms with HTML',
                'video_url' => 'https://example.com/videos/html-forms.mp4',
                'video_duration' => 720,
                'order_number' => 3,
                'is_preview' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // Lessons for Section 3 (CSS Styling)
            [
                'section_id' => 3,
                'title' => 'CSS Selectors',
                'content_type' => 'video',
                'content' => 'Learn how to target HTML elements with CSS selectors',
                'video_url' => 'https://example.com/videos/css-selectors.mp4',
                'video_duration' => 540,
                'order_number' => 1,
                'is_preview' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'section_id' => 3,
                'title' => 'Box Model',
                'content_type' => 'video',
                'content' => 'Understanding margins, padding, and borders',
                'video_url' => 'https://example.com/videos/box-model.mp4',
                'video_duration' => 600,
                'order_number' => 2,
                'is_preview' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'section_id' => 3,
                'title' => 'Flexbox Layout',
                'content_type' => 'video',
                'content' => 'Master modern layouts with Flexbox',
                'video_url' => 'https://example.com/videos/flexbox.mp4',
                'video_duration' => 900,
                'order_number' => 3,
                'is_preview' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // Lessons for Section 4 (JavaScript Basics)
            [
                'section_id' => 4,
                'title' => 'Variables and Data Types',
                'content_type' => 'video',
                'content' => 'Learn about JavaScript variables and data types',
                'video_url' => 'https://example.com/videos/js-variables.mp4',
                'video_duration' => 660,
                'order_number' => 1,
                'is_preview' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'section_id' => 4,
                'title' => 'Functions',
                'content_type' => 'video',
                'content' => 'Creating and using functions in JavaScript',
                'video_url' => 'https://example.com/videos/js-functions.mp4',
                'video_duration' => 780,
                'order_number' => 2,
                'is_preview' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'section_id' => 4,
                'title' => 'DOM Manipulation',
                'content_type' => 'video',
                'content' => 'Interacting with HTML elements using JavaScript',
                'video_url' => 'https://example.com/videos/dom-manipulation.mp4',
                'video_duration' => 840,
                'order_number' => 3,
                'is_preview' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // Lessons for Section 5 (Python Basics)
            [
                'section_id' => 5,
                'title' => 'Introduction to Python',
                'content_type' => 'video',
                'content' => 'Getting started with Python programming',
                'video_url' => 'https://example.com/videos/python-intro.mp4',
                'video_duration' => 420,
                'order_number' => 1,
                'is_preview' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'section_id' => 5,
                'title' => 'Python Variables and Types',
                'content_type' => 'video',
                'content' => 'Working with different data types in Python',
                'video_url' => 'https://example.com/videos/python-variables.mp4',
                'video_duration' => 540,
                'order_number' => 2,
                'is_preview' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'section_id' => 5,
                'title' => 'Lists and Dictionaries',
                'content_type' => 'article',
                'content' => '<h1>Python Lists and Dictionaries</h1><p>Learn how to work with Python\'s most common data structures.</p>',
                'video_url' => null,
                'video_duration' => 0,
                'order_number' => 3,
                'is_preview' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // Lessons for Section 6 (NumPy for Data Analysis)
            [
                'section_id' => 6,
                'title' => 'Installing NumPy',
                'content_type' => 'video',
                'content' => 'How to install and import NumPy',
                'video_url' => 'https://example.com/videos/numpy-install.mp4',
                'video_duration' => 240,
                'order_number' => 1,
                'is_preview' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'section_id' => 6,
                'title' => 'NumPy Arrays',
                'content_type' => 'video',
                'content' => 'Creating and manipulating NumPy arrays',
                'video_url' => 'https://example.com/videos/numpy-arrays.mp4',
                'video_duration' => 720,
                'order_number' => 2,
                'is_preview' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // Lessons for Section 7 (Pandas DataFrames)
            [
                'section_id' => 7,
                'title' => 'Introduction to Pandas',
                'content_type' => 'video',
                'content' => 'Getting started with Pandas library',
                'video_url' => 'https://example.com/videos/pandas-intro.mp4',
                'video_duration' => 480,
                'order_number' => 1,
                'is_preview' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'section_id' => 7,
                'title' => 'Working with DataFrames',
                'content_type' => 'video',
                'content' => 'Creating and manipulating Pandas DataFrames',
                'video_url' => 'https://example.com/videos/pandas-dataframes.mp4',
                'video_duration' => 900,
                'order_number' => 2,
                'is_preview' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('lessons')->insertBatch($lessons);

        echo "Sections and Lessons seeded successfully!\n";
    }
}
