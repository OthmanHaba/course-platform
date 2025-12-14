<?php

namespace App\Controllers\Portal;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\CategoryModel;
use App\Models\SectionModel;
use App\Models\LessonModel;
use CodeIgniter\HTTP\ResponseInterface;

class CourseController extends BaseController
{
    protected $courseModel;
    protected $categoryModel;
    protected $sectionModel;
    protected $lessonModel;

    public function __construct()
    {
        $this->courseModel = new CourseModel();
        $this->categoryModel = new CategoryModel();
        $this->sectionModel = new SectionModel();
        $this->lessonModel = new LessonModel();
    }

    public function index()
    {
        $page     = $this->request->getGet('page') ?? 1;
        $perPage  = $this->request->getGet('per_page') ?? 12;
        $category = $this->request->getGet('category');
        $level    = $this->request->getGet('level');
        $search   = $this->request->getGet('search');
        $sort     = $this->request->getGet('sort') ?? 'newest';

        $builder = $this->courseModel->where('status', 'published');

        if ($category) {
            $builder = $builder->where('category_id', $category);
        }

        if ($level) {
            $builder = $builder->where('level', $level);
        }

        if ($search) {
            $builder = $builder->like('title', $search);
        }

        switch ($sort) {
            case 'popular':
                $builder = $builder->orderBy('enrollment_count', 'DESC');
                break;
            case 'rating':
                $builder = $builder->orderBy('rating_average', 'DESC');
                break;
            case 'newest':
            default:
                $builder = $builder->orderBy('created_at', 'DESC');
                break;
        }

        $courses = $builder->paginate($perPage, 'default', $page);
        $pager   = $this->courseModel->pager;

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => [
                'courses'    => $courses,
                'pagination' => [
                    'current_page' => $pager->getCurrentPage(),
                    'total_pages'  => $pager->getPageCount(),
                    'per_page'     => $pager->getPerPage(),
                    'total'        => $pager->getTotal()
                ]
            ]
        ]);
    }

    public function show($id)
    {
        $course = $this->courseModel
            ->select('courses.*, categories.name as category_name, users.first_name as instructor_first_name, users.last_name as instructor_last_name')
            ->join('categories', 'categories.id = courses.category_id', 'left')
            ->join('users', 'users.id = courses.instructor_id')
            ->where('courses.id', $id)
            ->where('courses.status', 'published')
            ->first();

        if (!$course) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Course not found'
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $course
        ]);
    }

    public function preview($id)
    {
        $course = $this->courseModel->find($id);

        if (!$course || $course['status'] !== 'published') {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Course not found'
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        $sections = $this->sectionModel
            ->where('course_id', $id)
            ->orderBy('order_number', 'ASC')
            ->findAll();

        $previewLessons = [];

        foreach ($sections as $section) {
            $lessons = $this->lessonModel
                ->where('section_id', $section['id'])
                ->where('is_preview', 1)
                ->where('is_published', 1)
                ->orderBy('order_number', 'ASC')
                ->findAll();

            if (!empty($lessons)) {
                $previewLessons = array_merge($previewLessons, $lessons);
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => [
                'course'          => $course,
                'preview_lessons' => $previewLessons
            ]
        ]);
    }

    public function featured()
    {
        $courses = $this->courseModel
            ->where('status', 'published')
            ->where('is_featured', 1)
            ->orderBy('created_at', 'DESC')
            ->limit(10)
            ->findAll();

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $courses
        ]);
    }

    public function popular()
    {
        $courses = $this->courseModel
            ->where('status', 'published')
            ->orderBy('enrollment_count', 'DESC')
            ->limit(10)
            ->findAll();

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $courses
        ]);
    }

    public function categories()
    {
        $categories = $this->categoryModel
            ->where('is_active', 1)
            ->findAll();

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $categories
        ]);
    }

    public function coursesByCategory($categoryId)
    {
        $page    = $this->request->getGet('page') ?? 1;
        $perPage = $this->request->getGet('per_page') ?? 12;

        $courses = $this->courseModel
            ->where('category_id', $categoryId)
            ->where('status', 'published')
            ->paginate($perPage, 'default', $page);

        $pager = $this->courseModel->pager;

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => [
                'courses'    => $courses,
                'pagination' => [
                    'current_page' => $pager->getCurrentPage(),
                    'total_pages'  => $pager->getPageCount(),
                    'per_page'     => $pager->getPerPage(),
                    'total'        => $pager->getTotal()
                ]
            ]
        ]);
    }

    public function instructor($instructorId)
    {
        $userModel = new \App\Models\UserModel();
        $instructor = $userModel->find($instructorId);

        if (!$instructor || $instructor['user_type'] !== 'instructor') {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Instructor not found'
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        unset($instructor['password']);

        $courses = $this->courseModel
            ->where('instructor_id', $instructorId)
            ->where('status', 'published')
            ->findAll();

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => [
                'instructor' => $instructor,
                'courses'    => $courses
            ]
        ]);
    }
}
