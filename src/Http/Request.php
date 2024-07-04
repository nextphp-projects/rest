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

 namespace NextPHP\Rest\Http;
 
/**
 * Class Request
 * 
 * A simple implementation of a PSR-7 http message interface.
 *
 * This class represents an HTTP request.
 *
 * @package NextPHP\Rest
 */
class Request
{
    /**
     * @var string The HTTP method.
     */
    private string $method;

    /**
     * @var string The request URI.
     */
    private string $uri;

    /**
     * @var array The request headers.
     */
    private array $headers;

    /**
     * @var mixed The request body.
     */
    private $body;

    /**
     * @var array The query parameters.
     */
    private array $queryParams;

    /**
     * @var mixed The parsed body.
     */
    private $parsedBody;

    /**
     * Request constructor.
     *
     * @param string $method The HTTP method.
     * @param string $uri The request URI.
     * @param array $headers The request headers.
     * @param mixed $body The request body.
     * @param array $queryParams The query parameters.
     * @param mixed $parsedBody The parsed body.
     */
    public function __construct(string $method, string $uri, array $headers, $body, array $queryParams, $parsedBody)
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->headers = $headers;
        $this->body = $body;
        $this->queryParams = $queryParams;
        $this->parsedBody = $parsedBody;
    }

    /**
     * Gets the HTTP method.
     *
     * @return string The HTTP method.
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Gets the request URI.
     *
     * @return string The request URI.
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * Gets the request headers.
     *
     * @return array The request headers.
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Gets the request body.
     *
     * @return mixed The request body.
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Gets the query parameters.
     *
     * @return array The query parameters.
     */
    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    /**
     * Gets the parsed body.
     *
     * @return mixed The parsed body.
     */
    public function getParsedBody()
    {
        return $this->parsedBody;
    }
}