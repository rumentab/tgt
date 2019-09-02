<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

namespace App\Core\Response;


interface ResponseInterface
{
    /**
     * Outputs the response
     * @param string|array $data
     * @param int $code
     */
    public function render($data, $code = 200): void;

    /**
     * Get response as string
     * @param string|array $data
     * @return string
     */
    public function generate($data): string;

    /**
     * Set response headers
     * @param array $headers
     */
    public function setHeaders(array $headers): void;

    /**
     * Set the response body
     * @param string $data
     */
    public function setBody(string $data): void;

    /**
     * Get the response body
     * @return string
     */
    public function getBody(): string;

    /**
     * Set the response code
     * @param int $code
     */
    public function setResponseCode(int $code = 200): void;
}
