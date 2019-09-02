<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

require_once '../vendor/autoload.php';

try {
    $request = new App\Core\Request();
    $container = new App\Core\Container($request);
    $container
        ->build()
        ->start();
} catch (\Exception $ex) {
    $response = new \App\Core\Response\HtmlResponse();
    $response->render("<pre>Internal Server Error (Error 500):\n
{$ex->getMessage()}\n
{$ex->getTraceAsString()}\n
Try again later</pre>", 500);
}
