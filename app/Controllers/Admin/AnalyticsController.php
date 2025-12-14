<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;

class AnalyticsController extends BaseController
{
    protected $userModel;
    protected $courseModel;
    protected $enrollmentModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->courseModel = new CourseModel();
        $this->enrollmentModel = new EnrollmentModel();
    }

    public function overview()
    {
        $totalUsers = $this->userModel->where('user_type', 'student')->countAllResults();
        $totalInstructors = $this->userModel->where('user_type', 'instructor')->countAllResults();
        $totalCourses = $this->courseModel->countAllResults();
        $publishedCourses = $this->courseModel->where('status', 'published')->countAllResults();
        $totalEnrollments = $this->enrollmentModel->countAllResults();
        $completedCourses = $this->enrollmentModel->where('is_completed', 1)->countAllResults();

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => [
                'total_students'      => $totalUsers,
                'total_instructors'   => $totalInstructors,
                'total_courses'       => $totalCourses,
                'published_courses'   => $publishedCourses,
                'total_enrollments'   => $totalEnrollments,
                'completed_courses'   => $completedCourses
            ]
        ]);
    }

    public function users()
    {
        $students = $this->userModel->where('user_type', 'student')->countAllResults();
        $instructors = $this->userModel->where('user_type', 'instructor')->countAllResults();
        $admins = $this->userModel->where('user_type', 'admin')->countAllResults();

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => [
                'students'    => $students,
                'instructors' => $instructors,
                'admins'      => $admins,
                'total'       => $students + $instructors + $admins
            ]
        ]);
    }

    public function courses()
    {
        $published = $this->courseModel->where('status', 'published')->countAllResults();
        $draft = $this->courseModel->where('status', 'draft')->countAllResults();
        $archived = $this->courseModel->where('status', 'archived')->countAllResults();

        $popularCourses = $this->courseModel
            ->orderBy('enrollment_count', 'DESC')
            ->limit(10)
            ->findAll();

        $topRatedCourses = $this->courseModel
            ->where('rating_count >', 0)
            ->orderBy('rating_average', 'DESC')
            ->limit(10)
            ->findAll();

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => [
                'status_breakdown' => [
                    'published' => $published,
                    'draft'     => $draft,
                    'archived'  => $archived
                ],
                'popular_courses'   => $popularCourses,
                'top_rated_courses' => $topRatedCourses
            ]
        ]);
    }
}
