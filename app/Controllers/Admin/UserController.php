<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class UserController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $page     = $this->request->getGet('page') ?? 1;
        $perPage  = $this->request->getGet('per_page') ?? 10;
        $userType = $this->request->getGet('user_type');
        $search   = $this->request->getGet('search');

        $builder = $this->userModel;

        if ($userType) {
            $builder = $builder->where('user_type', $userType);
        }

        if ($search) {
            $builder = $builder->groupStart()
                ->like('first_name', $search)
                ->orLike('last_name', $search)
                ->orLike('email', $search)
                ->groupEnd();
        }

        $users = $builder->paginate($perPage, 'default', $page);
        $pager = $this->userModel->pager;

        foreach ($users as &$user) {
            unset($user['password']);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => [
                'users'      => $users,
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
        $user = $this->userModel->find($id);

        if (!$user) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'User not found'
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        unset($user['password']);

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $user
        ]);
    }

    public function update($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'User not found'
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        $data = $this->request->getJSON(true);

        $allowedFields = ['first_name', 'last_name', 'bio', 'is_active', 'user_type'];
        $updateData = [];

        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updateData[$field] = $data[$field];
            }
        }

        if (empty($updateData)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'No valid fields to update'
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $this->userModel->update($id, $updateData);

        $updatedUser = $this->userModel->find($id);
        unset($updatedUser['password']);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'User updated successfully',
            'data'    => $updatedUser
        ]);
    }

    public function delete($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'User not found'
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        $this->userModel->delete($id);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'User deleted successfully'
        ]);
    }
}
