<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

namespace App\Api\Controller;


use App\Api\DataProcessor\UserDataProcessor;
use App\Core\Exception\Request\ParameterNotFoundException;
use App\Core\Request;
use App\Core\Response\ResponseInterface;

class DefaultController
{
    /**
     * @param ResponseInterface $response
     * @param UserDataProcessor $dp
     */
    public function index(ResponseInterface $response, UserDataProcessor $dp): void
    {
        $data = [
            'Title' => 'User list/insert/edit/delete API',
            'Author' => 'Rumen Tabakov <rumen.tabakov@gmail.com>'
        ];
        if (!$dp->checkIfInstalled()) {
            $data['hint'] = 'Data source is empty. Run <code>php bin/application install</code> from the console.';
        }
        $response->render($data);
    }

    /**
     * @param Request $request
     * @param ResponseInterface $response
     */
    public function test(Request $request, ResponseInterface $response): void
    {
        try {
            $params = [
                'error' => null,
                'result' => [
                    'id' => $request->getQuery()->get('id'),
                    'name' => $request->getQuery()->get('name'),
                    'email' => $request->getQuery()->get('email'),
                    'password' => $request->getQuery()->get('password')
                ]
            ];
            $response_code = 200;
        } catch (ParameterNotFoundException $ex) {
            $params = [
                'error' => $ex->getMessage(),
                'result' => null
            ];
            $response_code = 400;
        }
        $response->render($params, $response_code);
    }
}
