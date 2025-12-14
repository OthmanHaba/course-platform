<?php

namespace App\Controllers\Portal;

use App\Controllers\BaseController;
use App\Models\ReviewModel;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use CodeIgniter\HTTP\ResponseInterface;

class ReviewController extends BaseController
{
    protected $reviewModel;
    protected $courseModel;
    protected $enrollmentModel;

    public function __construct()
    {
        $this->reviewModel = new ReviewModel();
        $this->courseModel = new CourseModel();
        $this->enrollmentModel = new EnrollmentModel();
    }

    public function create($courseId)
    {
        $userId = $this->request->user_id ?? null;

        if (!$userId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Unauthorized'
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $enrollment = $this->enrollmentModel
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();

        if (!$enrollment) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'You must be enrolled to review this course'
            ])->setStatusCode(ResponseInterface::HTTP_FORBIDDEN);
        }

        $existingReview = $this->reviewModel
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();

        if ($existingReview) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'You have already reviewed this course'
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $rules = [
            'rating' => 'required|numeric|greater_than[0]|less_than[6]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Validation failed',
                'errors'  => $this->validator->getErrors()
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $data = [
            'user_id'     => $userId,
            'course_id'   => $courseId,
            'rating'      => $this->request->getJSON(true)['rating'],
            'review_text' => $this->request->getJSON(true)['review_text'] ?? null
        ];

        $reviewId = $this->reviewModel->insert($data);
        $this->updateCourseRating($courseId);

        $review = $this->reviewModel->find($reviewId);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Review submitted successfully',
            'data'    => $review
        ])->setStatusCode(ResponseInterface::HTTP_CREATED);
    }

    public function update($reviewId)
    {
        $userId = $this->request->user_id ?? null;

        if (!$userId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Unauthorized'
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $review = $this->reviewModel
            ->where('id', $reviewId)
            ->where('user_id', $userId)
            ->first();

        if (!$review) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Review not found'
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        $data = $this->request->getJSON(true);

        $updateData = [];
        if (isset($data['rating'])) {
            $updateData['rating'] = $data['rating'];
        }
        if (isset($data['review_text'])) {
            $updateData['review_text'] = $data['review_text'];
        }

        $this->reviewModel->update($reviewId, $updateData);
        $this->updateCourseRating($review['course_id']);

        $updatedReview = $this->reviewModel->find($reviewId);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Review updated successfully',
            'data'    => $updatedReview
        ]);
    }

    public function delete($reviewId)
    {
        $userId = $this->request->user_id ?? null;

        if (!$userId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Unauthorized'
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $review = $this->reviewModel
            ->where('id', $reviewId)
            ->where('user_id', $userId)
            ->first();

        if (!$review) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Review not found'
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        $courseId = $review['course_id'];
        $this->reviewModel->delete($reviewId);
        $this->updateCourseRating($courseId);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Review deleted successfully'
        ]);
    }

    public function courseReviews($courseId)
    {
        $page    = $this->request->getGet('page') ?? 1;
        $perPage = $this->request->getGet('per_page') ?? 10;

        $reviews = $this->reviewModel
            ->select('reviews.*, users.first_name, users.last_name, users.avatar')
            ->join('users', 'users.id = reviews.user_id')
            ->where('reviews.course_id', $courseId)
            ->where('reviews.is_approved', 1)
            ->orderBy('reviews.created_at', 'DESC')
            ->paginate($perPage, 'default', $page);

        $pager = $this->reviewModel->pager;

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

    public function markHelpful($reviewId)
    {
        $review = $this->reviewModel->find($reviewId);

        if (!$review) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Review not found'
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        $this->reviewModel->update($reviewId, [
            'helpful_count' => $review['helpful_count'] + 1
        ]);

        $updatedReview = $this->reviewModel->find($reviewId);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Review marked as helpful',
            'data'    => $updatedReview
        ]);
    }

    private function updateCourseRating($courseId)
    {
        $reviews = $this->reviewModel
            ->where('course_id', $courseId)
            ->where('is_approved', 1)
            ->findAll();

        $totalRating = 0;
        $count = count($reviews);

        foreach ($reviews as $review) {
            $totalRating += $review['rating'];
        }

        $average = $count > 0 ? $totalRating / $count : 0;

        $this->courseModel->update($courseId, [
            'rating_average' => $average,
            'rating_count'   => $count
        ]);
    }
}
