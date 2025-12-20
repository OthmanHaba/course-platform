<?php

namespace App\Filters;

use App\Models\AccessTokenModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class TokenAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $response = service('response');

        // Get the Authorization header
        $authHeader = $request->getHeaderLine('Authorization');

        // Check if Authorization header exists
        if (empty($authHeader)) {
            return $response->setJSON([
                'status' => 'error',
                'message' => 'Authorization header missing'
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        // Extract the token from "Bearer <token>"
        if (!preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            return $response->setJSON([
                'status' => 'error',
                'message' => 'Invalid Authorization header format. Use: Bearer <token>'
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $plainTextToken = $matches[1];

        // Verify the token
        $tokenModel = new AccessTokenModel();
        $user = $tokenModel->verifyToken($plainTextToken);

        if (!$user) {
            return $response->setJSON([
                'status' => 'error',
                'message' => 'Invalid or expired token'
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        // Check if user is active
        if (!$user['is_active']) {
            return $response->setJSON([
                'status' => 'error',
                'message' => 'User account is inactive'
            ])->setStatusCode(ResponseInterface::HTTP_FORBIDDEN);
        }

        // Attach user to request for use in controllers
        $request->user = $user;
        $request->user_id = $user['id'];

        // If arguments are provided, check user type (role-based access)
        if (!empty($arguments)) {
            $allowedTypes = is_array($arguments) ? $arguments : [$arguments];

            if (!in_array($user['user_type'], $allowedTypes)) {
                return $response->setJSON([
                    'status' => 'error',
                    'message' => 'Insufficient permissions'
                ])->setStatusCode(ResponseInterface::HTTP_FORBIDDEN);
            }
        }

        // Allow the request to proceed
        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing to do after the response
        return $response;
    }
}
