<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\AccessTokenModel;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{
    protected $userModel;
    protected $tokenModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->tokenModel = new AccessTokenModel();
    }

    public function login()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Validation failed',
                'errors'  => $this->validator->getErrors()
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        // Support both JSON and form data
        $jsonData = $this->request->getJSON(true) ?? [];
        $email    = $this->request->getPost('email') ?? $jsonData['email'] ?? null;
        $password = $this->request->getPost('password') ?? $jsonData['password'] ?? null;

        $user = $this->userModel->where('email', $email)->first();

        if (!$user) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Invalid credentials'
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        if ($user['user_type'] !== 'admin') {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Access denied. Admin access required.'
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        if (!password_verify($password, $user['password'])) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Invalid credentials'
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        if (!$user['is_active']) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Account is inactive'
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        // Generate access token (expires in 30 days)
        $tokenData = $this->tokenModel->createToken(
            $user['id'],
            'admin-access',
            ['*'],
            30
        );

        if (!$tokenData) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Failed to generate access token'
            ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }

        // Remove sensitive data from user object
        $this->userModel->sanitizeUser($user);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Login successful',
            'data'    => [
                'user'  => $user,
                'token' => $tokenData['plain_text_token']
            ]
        ]);
    }

    public function logout()
    {
        // Get token from Authorization header
        $authHeader = $this->request->getHeaderLine('Authorization');

        if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            $token = $matches[1];
            $this->tokenModel->revokeToken($token);
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Logged out successfully'
        ]);
    }
}
