<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Cors implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $response = service('response');

        // 1. Allow Origin
        $response->setHeader('Access-Control-Allow-Origin', '*');

        // 2. Allow Methods
        $response->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PATCH, PUT, DELETE');

        // 3. Dynamically Allow Headers
        // Instead of hardcoding, we read what the browser requested and allow it.
        $allowedHeaders = $request->getHeaderLine('Access-Control-Request-Headers');

        // Fallback to default common headers if the browser didn't ask for anything specific
        if (empty($allowedHeaders)) {
            $allowedHeaders = 'X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Requested-Method, Authorization';
        }

        $response->setHeader('Access-Control-Allow-Headers', $allowedHeaders);

        // 4. Handle OPTIONS method immediately
        if ($request->getMethod() === 'OPTIONS') {
            $response->setStatusCode(200);
            // Important: Return the response to stop further processing (like CSRF checks)
            return $response;
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
