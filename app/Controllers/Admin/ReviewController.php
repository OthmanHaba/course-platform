<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ReviewModel;
use CodeIgniter\HTTP\ResponseInterface;

class ReviewController extends BaseController
{
    protected $reviewModel;

    public function __construct()
    {
        $this->reviewModel = new ReviewModel();
    }

    public function index()
    {
        $page    = $this->request->getGet('page') ?? 1;
        $perPage = $this->request->getGet('per_page') ?? 10;
        $status  = $this->request->getGet('status');

        $builder = $this->reviewModel
            ->select('reviews.*, users.first_name, users.last_name, courses.title as course_title')
            ->join('users', 'users.id = reviews.user_id')
            ->join('courses', 'courses.id = reviews.course_id');

        if ($status === 'approved') {
            $builder = $builder->where('reviews.is_approved', 1);
        } elseif ($status === 'pending') {
            $builder = $builder->where('reviews.is_approved', 0);
        }

        $reviews = $builder->paginate($perPage, 'default', $page);
        $pager   = $this->reviewModel->pager;

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => [
                'reviews'    => $reviews,
                'pagination' => [
                    'current_page' => $pager->getCurrentPage(),
                    'total_pages'  => $pager->getPageCount(),
                    'per_page'     => $pager->getPerPage(),
                    'total'        => $pager->getTotal()
                ]
            ]
        ]);
    }

    public function approve($id)
    {
        $review = $this->reviewModel->find($id);

        if (!$review) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Review not found'
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        $this->reviewModel->update($id, ['is_approved' => 1]);

        $updatedReview = $this->reviewModel->find($id);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Review approved successfully',
            'data'    => $updatedReview
        ]);
    }

    public function delete($id)
    {
        $review = $this->reviewModel->find($id);

        if (!$review) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Review not found'
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        $this->reviewModel->delete($id);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Review deleted successfully'
        ]);
    }
}
