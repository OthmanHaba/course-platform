<?php

namespace App\Controllers\Portal;

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

    public function register()
    {
        $rules = [
            'email'      => 'required|valid_email|is_unique[users.email]',
            'password'   => 'required|min_length[6]',
            'first_name' => 'required|min_length[2]',
            'last_name'  => 'required|min_length[2]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Validation failed',
                'errors'  => $this->validator->getErrors()
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $data = [
            'email'              => $this->request->getPost('email'),
            'password'           => $this->request->getPost('password'),
            'first_name'         => $this->request->getPost('first_name'),
            'last_name'          => $this->request->getPost('last_name'),
            'user_type'          => 'student',
            'verification_token' => bin2hex(random_bytes(32))
        ];

        $userId = $this->userModel->insert($data);
        $user = $this->userModel->find($userId);

        // Remove sensitive data from user object
        $this->userModel->sanitizeUser($user);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Registration successful',
            'data'    => $user
        ])->setStatusCode(ResponseInterface::HTTP_CREATED);
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
        $email = $this->request->getPost('email') ?? $jsonData['email'] ?? null;
        $password = $this->request->getPost('password') ?? $jsonData['password'] ?? null;

        $user = $this->userModel->where('email', $email)->first();

        if (!$user) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Invalid credentials'
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
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
            ])->setStatusCode(ResponseInterface::HTTP_FORBIDDEN);
        }

        // Generate access token (expires in 30 days)
        $tokenData = $this->tokenModel->createToken(
            $user['id'],
            'portal-access',
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

    public function forgotPassword()
    {
        $rules = [
            'email' => 'required|valid_email'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Validation failed',
                'errors'  => $this->validator->getErrors()
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $email = $this->request->getPost('email');
        $user = $this->userModel->where('email', $email)->first();

        if (!$user) {
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'If the email exists, a reset link has been sent'
            ]);
        }

        $resetToken = bin2hex(random_bytes(32));
        $this->userModel->update($user['id'], [
            'reset_token'         => $resetToken,
            'reset_token_expires' => date('Y-m-d H:i:s', strtotime('+1 hour'))
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'If the email exists, a reset link has been sent',
            'data'    => ['reset_token' => $resetToken]
        ]);
    }

    public function resetPassword()
    {
        $rules = [
            'token'    => 'required',
            'password' => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Validation failed',
                'errors'  => $this->validator->getErrors()
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $token = $this->request->getPost('token');
        $user = $this->userModel->where('reset_token', $token)->first();

        if (!$user || strtotime($user['reset_token_expires']) < time()) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Invalid or expired reset token'
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $this->userModel->update($user['id'], [
            'password'            => $this->request->getPost('password'),
            'reset_token'         => null,
            'reset_token_expires' => null
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Password reset successful'
        ]);
    }

    public function verifyEmail($token)
    {
        $user = $this->userModel->where('verification_token', $token)->first();

        if (!$user) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Invalid verification token'
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $this->userModel->update($user['id'], [
            'email_verified_at'  => date('Y-m-d H:i:s'),
            'verification_token' => null
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Email verified successfully'
        ]);
    }

}
