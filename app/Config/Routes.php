<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->options('(:any)', static function () {}); // Handle OPTIONS requests

// Admin Routes
// $routes->group('', ['filter' => 'cors'], static function(RouteCollection $routes) {


$routes->group('admin', function ($routes) {
    // Auth (no authentication required)
    $routes->post('auth/login', 'Admin\AuthController::login');
    $routes->post('auth/logout', 'Admin\AuthController::logout', ['filter' => 'auth:admin']);

    // Protected admin routes (require admin authentication)
    $routes->group('', ['filter' => 'auth:admin'], function ($routes) {
        // Users
        $routes->get('users', 'Admin\UserController::index');
        $routes->get('users/(:num)', 'Admin\UserController::show/$1');
        $routes->put('users/(:num)', 'Admin\UserController::update/$1');
        $routes->delete('users/(:num)', 'Admin\UserController::delete/$1');

        // Courses
        $routes->get('courses', 'Admin\CourseController::index');
        $routes->post('courses', 'Admin\CourseController::create');
        $routes->get('courses/(:num)', 'Admin\CourseController::show/$1');
        $routes->put('courses/(:num)', 'Admin\CourseController::update/$1');
        $routes->delete('courses/(:num)', 'Admin\CourseController::delete/$1');
        $routes->put('courses/(:num)/status', 'Admin\CourseController::updateStatus/$1');

        // Sections
        $routes->post('courses/(:num)/sections', 'Admin\SectionController::create/$1');
        $routes->put('sections/(:num)', 'Admin\SectionController::update/$1');
        $routes->delete('sections/(:num)', 'Admin\SectionController::delete/$1');

        // Lessons
        $routes->post('sections/(:num)/lessons', 'Admin\LessonController::create/$1');
        $routes->put('lessons/(:num)', 'Admin\LessonController::update/$1');
        $routes->delete('lessons/(:num)', 'Admin\LessonController::delete/$1');

        // Quizzes
        $routes->post('lessons/(:num)/quizzes', 'Admin\QuizController::create/$1');
        $routes->put('quizzes/(:num)', 'Admin\QuizController::update/$1');
        $routes->delete('quizzes/(:num)', 'Admin\QuizController::delete/$1');

        // Questions
        $routes->post('quizzes/(:num)/questions', 'Admin\QuizController::addQuestion/$1');
        $routes->put('questions/(:num)', 'Admin\QuizController::updateQuestion/$1');
        $routes->delete('questions/(:num)', 'Admin\QuizController::deleteQuestion/$1');

        // Categories
        $routes->get('categories', 'Admin\CategoryController::index');
        $routes->post('categories', 'Admin\CategoryController::create');
        $routes->put('categories/(:num)', 'Admin\CategoryController::update/$1');
        $routes->delete('categories/(:num)', 'Admin\CategoryController::delete/$1');

        // Enrollments
        $routes->get('enrollments', 'Admin\EnrollmentController::index');
        $routes->post('enrollments', 'Admin\EnrollmentController::create');
        $routes->delete('enrollments/(:num)', 'Admin\EnrollmentController::delete/$1');

        // Reviews
        $routes->get('reviews', 'Admin\ReviewController::index');
        $routes->put('reviews/(:num)/approve', 'Admin\ReviewController::approve/$1');
        $routes->delete('reviews/(:num)', 'Admin\ReviewController::delete/$1');

        // Analytics
        $routes->get('analytics/overview', 'Admin\AnalyticsController::overview');
        $routes->get('analytics/users', 'Admin\AnalyticsController::users');
        $routes->get('analytics/courses', 'Admin\AnalyticsController::courses');

        // Media
        $routes->post('media/upload', 'Admin\MediaController::upload');
        $routes->delete('media/(:num)', 'Admin\MediaController::delete/$1');
    });
});

// Portal Routes
$routes->group('portal', function ($routes) {
    // Public Auth Routes (no authentication required)
    $routes->post('auth/register', 'Portal\AuthController::register');
    $routes->post('auth/login', 'Portal\AuthController::login');
    $routes->post('auth/forgot-password', 'Portal\AuthController::forgotPassword');
    $routes->post('auth/reset-password', 'Portal\AuthController::resetPassword');
    $routes->get('auth/verify-email/(:any)', 'Portal\AuthController::verifyEmail/$1');

    // Public Course Routes (no authentication required)
    $routes->get('courses', 'Portal\CourseController::index');
    $routes->get('courses/featured', 'Portal\CourseController::featured');
    $routes->get('courses/popular', 'Portal\CourseController::popular');
    $routes->get('courses/(:num)', 'Portal\CourseController::show/$1');
    $routes->get('courses/(:num)/preview', 'Portal\CourseController::preview/$1');
    $routes->get('courses/(:num)/reviews', 'Portal\ReviewController::courseReviews/$1');

    // Public Category Routes
    $routes->get('categories', 'Portal\CourseController::categories');
    $routes->get('categories/(:num)/courses', 'Portal\CourseController::coursesByCategory/$1');

    // Public Instructor Routes
    $routes->get('instructors/(:num)', 'Portal\CourseController::instructor/$1');
    $routes->get('instructors/(:num)/courses', 'Portal\CourseController::instructor/$1');

    // Protected Routes (require authentication)
    $routes->group('', ['filter' => 'auth'], function ($routes) {
        // Auth - Logout
        $routes->post('auth/logout', 'Portal\AuthController::logout');

        // Profile
        $routes->get('profile', 'Portal\ProfileController::show');
        $routes->put('profile', 'Portal\ProfileController::update');
        $routes->put('profile/password', 'Portal\ProfileController::changePassword');
        $routes->post('profile/avatar', 'Portal\ProfileController::uploadAvatar');

        // Enrollments
        $routes->get('my-courses', 'Portal\EnrollmentController::myCourses');
        $routes->post('courses/(:num)/enroll', 'Portal\EnrollmentController::enroll/$1');
        $routes->delete('courses/(:num)/unenroll', 'Portal\EnrollmentController::unenroll/$1');
        $routes->get('enrollments/(:num)', 'Portal\EnrollmentController::show/$1');

        // Learning
        $routes->get('courses/(:num)/curriculum', 'Portal\LearningController::curriculum/$1');
        $routes->get('lessons/(:num)', 'Portal\LearningController::lesson/$1');
        $routes->post('lessons/(:num)/complete', 'Portal\LearningController::completeLesson/$1');
        $routes->get('lessons/(:num)/next', 'Portal\LearningController::nextLesson/$1');
        $routes->get('courses/(:num)/progress', 'Portal\LearningController::courseProgress/$1');
        $routes->get('progress', 'Portal\LearningController::overallProgress');

        // Quizzes
        $routes->get('quizzes/(:num)', 'Portal\QuizController::show/$1');
        $routes->post('quizzes/(:num)/submit', 'Portal\QuizController::submit/$1');
        $routes->get('quizzes/(:num)/result', 'Portal\QuizController::result/$1');
        $routes->get('quizzes/(:num)/attempts', 'Portal\QuizController::attempts/$1');

        // Reviews
        $routes->post('courses/(:num)/reviews', 'Portal\ReviewController::create/$1');
        $routes->put('reviews/(:num)', 'Portal\ReviewController::update/$1');
        $routes->delete('reviews/(:num)', 'Portal\ReviewController::delete/$1');
        $routes->post('reviews/(:num)/helpful', 'Portal\ReviewController::markHelpful/$1');

        // Wishlist
        $routes->get('wishlist', 'Portal\WishlistController::index');
        $routes->post('wishlist/(:num)', 'Portal\WishlistController::add/$1');
        $routes->delete('wishlist/(:num)', 'Portal\WishlistController::remove/$1');

        // Notes
        $routes->get('lessons/(:num)/notes', 'Portal\NoteController::index/$1');
        $routes->post('lessons/(:num)/notes', 'Portal\NoteController::create/$1');
        $routes->put('notes/(:num)', 'Portal\NoteController::update/$1');
        $routes->delete('notes/(:num)', 'Portal\NoteController::delete/$1');
    });
});
$routes->options('admin/(:any)', static function () {});
$routes->options('portal/(:any)', static function () {});

// });
