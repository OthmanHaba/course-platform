<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
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

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $this->userModel->where('email', $email)->first();

        if (!$user) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Invalid credentials'
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        if ($user['user_type'] !== 'admin') {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Access denied'
            ])->setStatusCode(ResponseInterface::HTTP_FORBIDDEN);
        }

        if (!password_verify($password, $user['password'])) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Invalid credentials'
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        if (!$user['is_active']) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Account is inactive'
            ])->setStatusCode(ResponseInterface::HTTP_FORBIDDEN);
        }

        $token = $this->generateToken($user);

        unset($user['password']);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Login successful',
            'data'    => [
                'user'  => $user,
                'token' => $token
            ]
        ]);
    }

    private function generateToken($user)
    {
        $key = getenv('JWT_SECRET') ?: 'your-secret-key';
        $payload = [
            'iss' => base_url(),
            'aud' => base_url(),
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24 * 7), // 7 days
            'sub' => $user['id'],
            'email' => $user['email'],
            'user_type' => $user['user_type']
        ];

        return JWT::encode($payload, $key, 'HS256');
    }
}
