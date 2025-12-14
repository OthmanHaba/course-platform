# Database Seeding Guide

This document explains how to seed your database with test data for the course platform.

## Prerequisites

1. Make sure you have run all migrations:
```bash
php spark migrate
```

## Running the Seeders

### Run All Seeders (Recommended)

To populate the entire database with test data, run:

```bash
php spark db:seed DatabaseSeeder
```

This will seed all tables in the correct order with comprehensive test data.

### Run Individual Seeders

You can also run individual seeders if needed:

```bash
# Seed users only
php spark db:seed UsersSeeder

# Seed categories only
php spark db:seed CategoriesSeeder

# Seed courses only
php spark db:seed CoursesSeeder

# Seed sections and lessons
php spark db:seed SectionsLessonsSeeder

# Seed quizzes and questions
php spark db:seed QuizzesQuestionsSeeder

# Seed enrollments and progress
php spark db:seed EnrollmentsProgressSeeder

# Seed reviews and wishlist
php spark db:seed ReviewsWishlistSeeder
```

**Note:** When running individual seeders, make sure to run them in order to respect foreign key constraints.

## Test Accounts

After seeding, you can use these accounts to test the system:

### Admin Account
- **Email:** admin@example.com
- **Password:** admin123
- **Access:** Full admin panel access

### Instructor Accounts
- **Email:** john.instructor@example.com
- **Email:** sarah.instructor@example.com
- **Email:** mike.instructor@example.com
- **Password:** password123 (for all)
- **Access:** Can create and manage courses

### Student Accounts
- **Email:** student1@example.com (Alice Smith)
- **Email:** student2@example.com (Bob Williams)
- **Email:** student3@example.com (Carol Davis)
- **Email:** student4@example.com (David Martinez)
- **Email:** student5@example.com (Emma Anderson)
- **Password:** password123 (for all)
- **Access:** Portal access with enrollments

## What Gets Seeded

### Users (10 total)
- 1 Admin user
- 3 Instructor users with bios
- 5 Student users with various enrollment statuses

### Categories (8 total)
- Web Development
- Mobile Development
- Data Science
- Design
- Business
- Marketing
- Photography
- Music

### Courses (9 total)
- 8 Published courses across different categories
- 1 Draft course
- Mix of free and paid courses
- Courses with realistic ratings and enrollment counts

### Sections and Lessons
- 7 Sections across 2 courses
- 19 Lessons (mix of video and article content)
- Lessons with realistic durations
- Some lessons marked as preview

### Quizzes and Questions
- 3 Quizzes attached to different sections
- 13 Questions total
- Multiple choice and true/false question types
- Properly configured correct answers and points

### Enrollments and Progress
- 12 Student enrollments across various courses
- Realistic progress percentages (0-100%)
- Some completed courses
- Progress tracking for individual lessons

### Reviews
- 13 Reviews across different courses
- Mix of ratings (3-5 stars)
- 12 Approved reviews
- 1 Pending review (not yet approved)
- Realistic review content

### Wishlist
- 13 Wishlist items
- Students have saved courses they're interested in
- Distributed across all student accounts

## Resetting the Database

If you want to start fresh:

```bash
# Drop all tables and re-run migrations
php spark migrate:refresh

# Then run the seeder again
php spark db:seed DatabaseSeeder
```

Or do both in one command:

```bash
php spark migrate:refresh --all
php spark db:seed DatabaseSeeder
```

## Data Overview

The seeded data includes:

1. **Complete Web Development Bootcamp**
   - 4 Sections with 13 lessons
   - Multiple students enrolled with varying progress
   - Excellent ratings (4.7/5)
   - 156 enrollments

2. **Python for Data Science** (Free Course)
   - 3 Sections with 6 lessons
   - Popular course with 234 enrollments
   - High rating (4.8/5)
   - Complete curriculum from basics to advanced

3. Other courses covering:
   - Advanced Vue.js
   - React Native
   - Machine Learning
   - UI/UX Design
   - Business and Marketing

## Testing Scenarios

With this seeded data, you can test:

1. **Admin Panel:**
   - View dashboard analytics
   - Manage courses, users, categories
   - Approve/reject reviews
   - Monitor enrollments

2. **Student Portal:**
   - Browse and search courses
   - View course details
   - Enroll in courses
   - Track learning progress
   - Take quizzes
   - Write reviews
   - Manage wishlist

3. **Course Learning:**
   - View course curriculum
   - Complete lessons
   - Track progress
   - Take quizzes
   - View completed courses

## Notes

- All passwords are hashed using PHP's `password_hash()` function
- Dates are set relative to the current date for realistic timeline
- The data includes various states (in-progress, completed, pending)
- Foreign key relationships are properly maintained
