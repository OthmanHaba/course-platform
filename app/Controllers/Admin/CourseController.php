<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\SectionModel;
use App\Models\LessonModel;
use App\Models\QuizModel;
use CodeIgniter\HTTP\ResponseInterface;

class CourseController extends BaseController
{
    protected $courseModel;
    protected $sectionModel;
    protected $lessonModel;
    protected $quizModel;

    public function __construct()
    {
        $this->courseModel = new CourseModel();
        $this->sectionModel = new SectionModel();
        $this->lessonModel = new LessonModel();
        $this->quizModel = new QuizModel();
    }

    public function index()
    {
        $page     = $this->request->getGet('page') ?? 1;
        $perPage  = $this->request->getGet('per_page') ?? 10;
        $status   = $this->request->getGet('status');
        $search   = $this->request->getGet('search');

        $builder = $this->courseModel;

        if ($status) {
            $builder = $builder->where('status', $status);
        }

        if ($search) {
            $builder = $builder->like('title', $search);
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
        $course = $this->courseModel->find($id);

        if (!$course) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Course not found'
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        // Fetch sections with their lessons
        $sections = $this->sectionModel
            ->where('course_id', $id)
            ->orderBy('order_number', 'ASC')
            ->findAll();

        // Fetch lessons for each section with quiz info
        foreach ($sections as &$section) {
            $lessons = $this->lessonModel
                ->where('section_id', $section['id'])
                ->orderBy('order_number', 'ASC')
                ->findAll();

            // Add quiz info to each lesson
            foreach ($lessons as &$lesson) {
                $quiz = $this->quizModel
                    ->where('lesson_id', $lesson['id'])
                    ->first();
                $lesson['quiz_id'] = $quiz ? $quiz['id'] : null;
            }

            $section['lessons'] = $lessons;
        }

        $course['sections'] = $sections;

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $course
        ]);
    }

    public function create()
    {
        $data = $this->request->getJSON(true);

        $rules = [
            'title'         => 'required|min_length[3]',
            'slug'          => 'required|is_unique[courses.slug]',
            'instructor_id' => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Validation failed',
                'errors'  => $this->validator->getErrors()
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $courseId = $this->courseModel->insert($data);

        $course = $this->courseModel->find($courseId);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Course created successfully',
            'data'    => $course
        ])->setStatusCode(ResponseInterface::HTTP_CREATED);
    }

    public function update($id)
    {
        $course = $this->courseModel->find($id);

        if (!$course) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Course not found'
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        $data = $this->request->getJSON(true);

        $this->courseModel->update($id, $data);

        $updatedCourse = $this->courseModel->find($id);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Course updated successfully',
            'data'    => $updatedCourse
        ]);
    }

    public function delete($id)
    {
        $course = $this->courseModel->find($id);

        if (!$course) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Course not found'
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        $this->courseModel->delete($id);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Course deleted successfully'
        ]);
    }

    public function updateStatus($id)
    {
        $course = $this->courseModel->find($id);

        if (!$course) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Course not found'
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        $data = $this->request->getJSON(true);

        if (!isset($data['status']) || !in_array($data['status'], ['draft', 'published', 'archived'])) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Invalid status'
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $this->courseModel->update($id, ['status' => $data['status']]);

        $updatedCourse = $this->courseModel->find($id);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Course status updated successfully',
            'data'    => $updatedCourse
        ]);
    }
}
