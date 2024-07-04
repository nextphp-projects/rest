<?php

/*
 * This file is part of the NextPHP package.
 *
 * (c) Vedat Yıldırım <vedat@nextphp.io>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 *
 * @license https://opensource.org/licenses/MIT MIT License
 */

namespace NextPHP\Rest\Http;Middleware;

use NextPHP\Rest\Request;
use NextPHP\Rest\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * Class AuthMiddleware
 * 
 * A simple implementation of a PSR-7 http message interface and PSR-15 http handlers.
 *
 * Middleware for handling JWT authentication.
 *
 * @package NextPHP\Rest\Middleware
 */
class AuthMiddleware
{
    /**
     * Handles the incoming request and checks for JWT authentication.
     *
     * @param Request $request The HTTP request.
     * @param Response $response The HTTP response.
     * @param callable $next The next middleware or controller.
     * @return Response The modified response.
     */
    public function handle(Request $request, Response $response, callable $next): Response
    {
        $authHeader = $request->getHeaders()['Authorization'] ?? '';
        if (!$authHeader) {
            return $response->withStatus(401)->withJSON(['error' => 'Unauthorized']);
        }

        list($jwt) = sscanf($authHeader, 'Bearer %s');
        if (!$jwt) {
            return $response->withStatus(401)->withJSON(['error' => 'Unauthorized']);
        }

        try {
            $decoded = JWT::decode($jwt, new Key('your-secret-key', 'HS256'));
            // Token is valid, proceed with the request
            return $next($request, $response);
        } catch (\Exception $e) {
            return $response->withStatus(401)->withJSON(['error' => 'Unauthorized']);
        }
    }
}