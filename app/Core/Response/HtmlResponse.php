<?php
/**
 * Created by PhpStorm.
 * User: rumen
 * Date: 21.08.19
 * Time: 16:50
 */

namespace App\Core\Response;


use App\Core\Response;

class HtmlResponse extends Response implements ResponseInterface
{

    /**
     * Outputs the response
     * @param string $data
     * @param int $code
     */
    public function render($data, $code = 200): void
    {
        $this->setResponseCode($code);
        $this->setHeaders([
            'Content-Type' => 'text/html'
        ]);
        die($this->generate($data));
    }

    /**
     * Get response as string
     * @param string|array $data
     * @return string
     */
    public function generate($data): string
    {
        $this->setBody($data);
        return $this->getBody();
    }

    /**
     * Set the response body
     * @param string $data
     */
    public function setBody(string $data): void
    {
        $this->body = \htmlspecialchars($data);
    }

    /**
     * Get the response body
     * @return string
     */
    public function getBody(): string
    {
        return \htmlspecialchars_decode($this->body);
    }
}
