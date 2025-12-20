<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Load here all helpers you want to be available in your controllers that extend BaseController.
        // Caution: Do not put the this below the parent::initController() call below.
        // $this->helpers = ['form', 'url'];

        // Caution: Do not edit this line.
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        // $this->session = service('session');
    }

    /**
     * Log an error with detailed context
     */
    protected function logError(\Throwable $e, array $context = [])
    {
        $message = sprintf(
            'ERROR: %s in %s:%d',
            $e->getMessage(),
            $e->getFile(),
            $e->getLine()
        );

        log_message('error', $message);
        log_message('error', 'Stack trace: ' . $e->getTraceAsString());

        if (!empty($context)) {
            log_message('error', 'Context: ' . json_encode($context));
        }
    }

    /**
     * Return a standardized error response
     */
    protected function errorResponse(
        string $message,
        int $statusCode = ResponseInterface::HTTP_INTERNAL_SERVER_ERROR,
        array $errors = [],
        \Throwable $exception = null
    ) {
        $data = [
            'status' => 'error',
            'message' => $message
        ];

        if (!empty($errors)) {
            $data['errors'] = $errors;
        }

        // Add debug info in development mode
        if ((ENVIRONMENT === 'development' || ENVIRONMENT === 'testing') && $exception) {
            $data['debug'] = [
                'exception' => get_class($exception),
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => explode("\n", $exception->getTraceAsString())
            ];
        }

        return $this->response->setJSON($data)->setStatusCode($statusCode);
    }

    /**
     * Return a standardized success response
     */
    protected function successResponse($data = null, string $message = 'Success', int $statusCode = ResponseInterface::HTTP_OK)
    {
        $response = [
            'status' => 'success',
            'message' => $message
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return $this->response->setJSON($response)->setStatusCode($statusCode);
    }
}
