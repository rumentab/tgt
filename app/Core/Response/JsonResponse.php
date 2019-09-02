<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

namespace App\Core\Response;


use App\Core\Exception\Response\JsonResponseBadDataException;
use App\Core\Response;

class JsonResponse extends Response
{
    /**
     * @param array|string $data
     * @param int $code
     * @throws JsonResponseBadDataException
     */
    public function render($data, $code = 200): void
    {
        $this->setResponseCode($code);
        $this->setHeaders([
            'Content-Type' => 'application/json; charset=utf-8'
        ]);
        die($this->generate($data));
    }

    /**
     * @param array|string $data
     * @return string
     * @throws JsonResponseBadDataException
     */
    public function generate($data): string
    {
        if (!is_array($data)) {
            throw new JsonResponseBadDataException();
        }
        $this->setBody(json_encode($data, JSON_PRETTY_PRINT));

        return $this->getBody();
    }

    /**
     * @param string $data
     */
    public function setBody(string $data): void
    {
        $this->body = $data;
    }

    public function getBody(): string
    {
        return $this->body;
    }
}
