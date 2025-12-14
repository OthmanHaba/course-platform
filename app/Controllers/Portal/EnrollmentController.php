<?php

namespace App\Controllers\Portal;

use App\Controllers\BaseController;
use App\Models\EnrollmentModel;
use App\Models\CourseModel;
use CodeIgniter\HTTP\ResponseInterface;

class EnrollmentController extends BaseController
{
    protected $enrollmentModel;
    protected $courseModel;

    public function __construct()
    {
        $this->enrollmentModel = new EnrollmentModel();
        $this->courseModel = new CourseModel();
    }

    public function myCourses()
    {
        $userId = $this->request->user_id ?? null;

        if (!$userId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Unauthorized'
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $enrollments = $this->enrollmentModel
            ->select('enrollments.*, courses.title, courses.thumbnail, courses.slug, courses.description')
            ->join('courses', 'courses.id = enrollments.course_id')
            ->where('enrollments.user_id', $userId)
            ->orderBy('enrollments.last_accessed_at', 'DESC')
            ->findAll();

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $enrollments
        ]);
    }

    public function enroll($courseId)
    {
        $userId = $this->request->user_id ?? null;

        if (!$userId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Unauthorized'
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $course = $this->courseModel->find($courseId);

        if (!$course || $course['status'] !== 'published') {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Course not found or not available'
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        $existingEnrollment = $this->enrollmentModel
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();

        if ($existingEnrollment) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Already enrolled in this course'
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $data = [
            'user_id'         => $userId,
            'course_id'       => $courseId,
            'enrollment_date' => date('Y-m-d H:i:s')
        ];

        $enrollmentId = $this->enrollmentModel->insert($data);

        $this->courseModel->update($courseId, [
            'enrollment_count' => $course['enrollment_count'] + 1
        ]);

        $enrollment = $this->enrollmentModel->find($enrollmentId);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Enrolled successfully',
            'data'    => $enrollment
        ])->setStatusCode(ResponseInterface::HTTP_CREATED);
    }

    public function show($enrollmentId)
    {
        $userId = $this->request->user_id ?? null;

        if (!$userId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Unauthorized'
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $enrollment = $this->enrollmentModel
            ->select('enrollments.*, courses.title, courses.description, courses.thumbnail')
            ->join('courses', 'courses.id = enrollments.course_id')
            ->where('enrollments.id', $enrollmentId)
            ->where('enrollments.user_id', $userId)
            ->first();

        if (!$enrollment) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Enrollment not found'
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $enrollment
        ]);
    }
}
