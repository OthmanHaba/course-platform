<?php

namespace App\Controllers\Portal;

use App\Controllers\BaseController;
use App\Models\WishlistModel;
use App\Models\CourseModel;
use CodeIgniter\HTTP\ResponseInterface;

class WishlistController extends BaseController
{
    protected $wishlistModel;
    protected $courseModel;

    public function __construct()
    {
        $this->wishlistModel = new WishlistModel();
        $this->courseModel = new CourseModel();
    }

    public function index()
    {
        $userId = $this->request->user_id ?? null;

        if (!$userId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Unauthorized'
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $wishlist = $this->wishlistModel
            ->select('wishlist.*, courses.title, courses.slug, courses.description, courses.thumbnail, courses.price, courses.is_free, courses.rating_average')
            ->join('courses', 'courses.id = wishlist.course_id')
            ->where('wishlist.user_id', $userId)
            ->orderBy('wishlist.created_at', 'DESC')
            ->findAll();

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $wishlist
        ]);
    }

    public function add($courseId)
    {
        $userId = $this->request->user_id ?? null;

        if (!$userId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Unauthorized'
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $course = $this->courseModel->find($courseId);

        if (!$course) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Course not found'
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        $existing = $this->wishlistModel
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();

        if ($existing) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Course already in wishlist'
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $data = [
            'user_id'   => $userId,
            'course_id' => $courseId
        ];

        $wishlistId = $this->wishlistModel->insert($data);
        $wishlistItem = $this->wishlistModel->find($wishlistId);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Course added to wishlist',
            'data'    => $wishlistItem
        ])->setStatusCode(ResponseInterface::HTTP_CREATED);
    }

    public function remove($courseId)
    {
        $userId = $this->request->user_id ?? null;

        if (!$userId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Unauthorized'
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $wishlistItem = $this->wishlistModel
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();

        if (!$wishlistItem) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Course not in wishlist'
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        $this->wishlistModel->delete($wishlistItem['id']);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Course removed from wishlist'
        ]);
    }
}
