<?php

namespace App\Controllers\Portal;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class ProfileController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function show()
    {
        $userId = $this->request->user_id ?? null;

        if (!$userId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Unauthorized'
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $user = $this->userModel->find($userId);

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

    public function update()
    {
        $userId = $this->request->user_id ?? null;

        if (!$userId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Unauthorized'
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $data = $this->request->getJSON(true);

        $allowedFields = ['first_name', 'last_name', 'bio'];
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

        $this->userModel->update($userId, $updateData);

        $updatedUser = $this->userModel->find($userId);
        unset($updatedUser['password']);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Profile updated successfully',
            'data'    => $updatedUser
        ]);
    }

    public function changePassword()
    {
        $userId = $this->request->user_id ?? null;

        if (!$userId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Unauthorized'
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $rules = [
            'current_password' => 'required',
            'new_password'     => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Validation failed',
                'errors'  => $this->validator->getErrors()
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $user = $this->userModel->find($userId);

        if (!password_verify($this->request->getPost('current_password'), $user['password'])) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Current password is incorrect'
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $this->userModel->update($userId, [
            'password' => $this->request->getPost('new_password')
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Password changed successfully'
        ]);
    }

    public function uploadAvatar()
    {
        $userId = $this->request->user_id ?? null;

        if (!$userId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Unauthorized'
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $validation = \Config\Services::validation();

        $validation->setRules([
            'avatar' => 'uploaded[avatar]|is_image[avatar]|max_size[avatar,2048]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Validation failed',
                'errors'  => $validation->getErrors()
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $file = $this->request->getFile('avatar');

        if (!$file->isValid()) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => $file->getErrorString()
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $fileName = $file->getRandomName();
        $filePath = $file->store('avatars', $fileName);

        $this->userModel->update($userId, [
            'avatar' => $filePath
        ]);

        $user = $this->userModel->find($userId);
        unset($user['password']);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Avatar uploaded successfully',
            'data'    => $user
        ]);
    }
}
