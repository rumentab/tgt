<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

namespace App\Application\Controller;


use App\Core\Response\HtmlResponse;

class DefaultController
{
    /**
     * @param HtmlResponse $response
     */
    public function index(HtmlResponse $response): void
    {
        $response->render('<h1>It Works!</h1>');
    }
}
