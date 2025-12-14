<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\EnrollmentModel;
use CodeIgniter\HTTP\ResponseInterface;

class EnrollmentController extends BaseController
{
    protected $enrollmentModel;

    public function __construct()
    {
        $this->enrollmentModel = new EnrollmentModel();
    }

    public function index()
    {
        $page    = $this->request->getGet('page') ?? 1;
        $perPage = $this->request->getGet('per_page') ?? 10;

        $enrollments = $this->enrollmentModel
            ->select('enrollments.*, users.first_name, users.last_name, users.email, courses.title as course_title')
            ->join('users', 'users.id = enrollments.user_id')
            ->join('courses', 'courses.id = enrollments.course_id')
            ->paginate($perPage, 'default', $page);

        $pager = $this->enrollmentModel->pager;

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => [
                'enrollments' => $enrollments,
                'pagination'  => [
                    'current_page' => $pager->getCurrentPage(),
                    'total_pages'  => $pager->getPageCount(),
                    'per_page'     => $pager->getPerPage(),
                    'total'        => $pager->getTotal()
                ]
            ]
        ]);
    }

    public function create()
    {
        $data = $this->request->getJSON(true);

        $rules = [
            'user_id'   => 'required|numeric',
            'course_id' => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Validation failed',
                'errors'  => $this->validator->getErrors()
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $data['enrollment_date'] = date('Y-m-d H:i:s');

        $enrollmentId = $this->enrollmentModel->insert($data);
        $enrollment = $this->enrollmentModel->find($enrollmentId);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Enrollment created successfully',
            'data'    => $enrollment
        ])->setStatusCode(ResponseInterface::HTTP_CREATED);
    }

    public function delete($id)
    {
        $enrollment = $this->enrollmentModel->find($id);

        if (!$enrollment) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Enrollment not found'
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        $this->enrollmentModel->delete($id);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Enrollment deleted successfully'
        ]);
    }
}
