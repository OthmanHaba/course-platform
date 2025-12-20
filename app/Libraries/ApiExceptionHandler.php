<?php

namespace App\Libraries;

use CodeIgniter\Debug\ExceptionHandlerInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Exceptions;
use Throwable;

class ApiExceptionHandler implements ExceptionHandlerInterface
{
    protected Exceptions $config;

    public function __construct(Exceptions $config)
    {
        $this->config = $config;
    }

    public function handle(
        Throwable $exception,
        RequestInterface $request,
        ResponseInterface $response,
        int $statusCode,
        int $exitCode
    ): void {
        // Ensure valid HTTP status code
        if ($statusCode < 100 || $statusCode > 599) {
            $statusCode = 500;
        }

        // Build error response
        $errorData = [
            'status' => 'error',
            'message' => $exception->getMessage() ?: 'An unexpected error occurred',
            'code' => $statusCode
        ];

        // Add detailed debugging info in development mode
        if (ENVIRONMENT === 'development' || ENVIRONMENT === 'testing') {
            $errorData['debug'] = [
                'exception' => get_class($exception),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $this->formatTrace($exception->getTrace()),
                'previous' => $exception->getPrevious() ? [
                    'message' => $exception->getPrevious()->getMessage(),
                    'file' => $exception->getPrevious()->getFile(),
                    'line' => $exception->getPrevious()->getLine(),
                ] : null
            ];
        }

        // Log the error
        if ($this->config->log && !in_array($statusCode, $this->config->ignoreCodes)) {
            log_message('error', sprintf(
                'EXCEPTION -> %s: %s in %s:%d',
                get_class($exception),
                $exception->getMessage(),
                $exception->getFile(),
                $exception->getLine()
            ));

            log_message('error', 'Stack trace: ' . $exception->getTraceAsString());
        }

        // Send JSON response
        $response->setStatusCode($statusCode)
            ->setJSON($errorData)
            ->send();

        exit($exitCode);
    }

    /**
     * Format exception trace for better readability
     */
    protected function formatTrace(array $trace): array
    {
        $formattedTrace = [];

        foreach ($trace as $index => $step) {
            $formattedTrace[] = [
                'step' => $index + 1,
                'file' => $step['file'] ?? 'unknown',
                'line' => $step['line'] ?? 0,
                'function' => ($step['class'] ?? '') . ($step['type'] ?? '') . ($step['function'] ?? ''),
                'args' => isset($step['args']) ? $this->formatArgs($step['args']) : []
            ];
        }

        return $formattedTrace;
    }

    /**
     * Format function arguments safely
     */
    protected function formatArgs(array $args): array
    {
        $formatted = [];

        foreach ($args as $arg) {
            if (is_object($arg)) {
                $formatted[] = get_class($arg);
            } elseif (is_array($arg)) {
                $formatted[] = 'Array(' . count($arg) . ')';
            } elseif (is_string($arg)) {
                // Truncate long strings
                $formatted[] = strlen($arg) > 100 ? substr($arg, 0, 100) . '...' : $arg;
            } elseif (is_bool($arg)) {
                $formatted[] = $arg ? 'true' : 'false';
            } elseif (is_null($arg)) {
                $formatted[] = 'null';
            } else {
                $formatted[] = (string) $arg;
            }
        }

        return $formatted;
    }
}
