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

namespace NextPHP\Rest;

/**
 * Class Response
 *
 * A simple implementation of a PSR-7 http message interface.
 *
 * This class represents an HTTP response.
 *
 * @package NextPHP\Rest
 */
class Response
{
    /**
     * @var array The response headers.
     */
    private array $headers = [];

    /**
     * @var int The HTTP status code.
     */
    private int $statusCode = 200;

    /**
     * @var mixed The response body.
     */
    private $body;

    /**
     * Writes data to the response body.
     *
     * @param mixed $data The data to write.
     * @return $this
     */
    public function write($data): self
    {
        $this->body = $data;
        return $this;
    }

    /**
     * Sets the response body to JSON.
     *
     * @param mixed $data The data to encode as JSON.
     * @return $this
     */
    public function withJSON($data): self
    {
        $this->body = json_encode($data);
        $this->withHeader('Content-Type', 'application/json');
        return $this;
    }

    /**
     * Sets the response body to XML.
     *
     * @param mixed $data The data to encode as XML.
     * @return $this
     */
    public function withXML($data): self
    {
        $this->body = $this->toXML($data);
        $this->withHeader('Content-Type', 'application/xml');
        return $this;
    }

    /**
     * Sets the response body to plain text.
     *
     * @param mixed $data The data to convert to text.
     * @return $this
     */
    public function withTEXT($data): self
    {
        if (is_array($data) || is_object($data)) {
            $this->body = print_r($data, true); // Convert array or object to text
        } else {
            $this->body = (string)$data; // Convert other types to text
        }
        $this->withHeader('Content-Type', 'text/plain');
        return $this;
    }

    /**
     * Sets the response body to HTML.
     *
     * @param mixed $data The data to set as HTML.
     * @return $this
     */
    public function withHTML($data): self
    {
        $this->body = (string)$data;
        $this->withHeader('Content-Type', 'text/html');
        return $this;
    }

    /**
     * Sets the response body to YAML.
     *
     * @param mixed $data The data to encode as YAML.
     * @return $this
     */
    public function withYAML($data): self
    {
        if (function_exists('yaml_emit')) {
            $this->body = yaml_emit($data);
            $this->withHeader('Content-Type', 'application/x-yaml');
        } else {
            $this->body = "YAML extension is not available.";
            $this->withHeader('Content-Type', 'text/plain');
        }
        return $this;
    }

    /**
     * Sets the response body to CSV.
     *
     * @param mixed $data The data to convert to CSV.
     * @return $this
     */
    public function withCSV($data): self
    {
        $csv = $this->toCSV($data);
        if ($csv !== false) {
            $this->body = $csv;
            $this->withHeader('Content-Type', 'text/csv');
        } else {
            $this->body = "Error converting data to CSV.";
            $this->withHeader('Content-Type', 'text/plain');
        }
        return $this;
    }

    /**
     * Sets the response body to binary data.
     *
     * @param mixed $data The binary data to set.
     * @return $this
     */
    public function withBinary($data): self
    {
        $this->body = $data;
        $this->withHeader('Content-Type', 'application/octet-stream');
        return $this;
    }

    /**
     * Gets the response body.
     *
     * @return mixed The response body.
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Adds a header to the response.
     *
     * @param string $name The header name.
     * @param string $value The header value.
     * @return $this
     */
    public function withHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    /**
     * Gets all response headers.
     *
     * @return array The response headers.
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Sets the HTTP status code.
     *
     * @param int $code The HTTP status code.
     * @return $this
     */
    public function withStatus(int $code): self
    {
        $this->statusCode = $code;
        return $this;
    }

    /**
     * Gets the HTTP status code.
     *
     * @return int The HTTP status code.
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Converts data to XML format.
     *
     * @param mixed $data The data to convert.
     * @param string $rootElement The root element name.
     * @param \SimpleXMLElement|null $xml The SimpleXMLElement object.
     * @return string The XML string.
     */
    private function toXML($data, string $rootElement = 'root', \SimpleXMLElement $xml = null): string
    {
        if ($xml === null) {
            $xml = new \SimpleXMLElement("<$rootElement/>");
        }

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $this->toXML($value, $key, $xml->addChild($key));
            } else {
                $xml->addChild($key, htmlspecialchars($value));
            }
        }

        return $xml->asXML();
    }

    /**
     * Converts data to CSV format.
     *
     * @param mixed $data The data to convert.
     * @return false|string The CSV string or false on failure.
     */
    private function toCSV($data)
    {
        if (empty($data)) {
            return false;
        }

        ob_start();
        $df = fopen("php://output", 'w');
        if ($df === false) {
            return false;
        }

        fputcsv($df, array_keys(reset($data)));
        foreach ($data as $row) {
            fputcsv($df, $row);
        }
        fclose($df);

        return ob_get_clean();
    }
}