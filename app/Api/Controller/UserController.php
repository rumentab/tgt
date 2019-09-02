<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

namespace App\Api\Controller;


use App\Api\DataProcessor\UserDataProcessor;
use App\Api\Services\Validator\EmailValidator;
use App\Api\Services\Validator\FieldValidator;
use App\Core\Exception\Request\ParameterNotFoundException;
use App\Core\Request;
use App\Core\Response\ResponseInterface;
use App\Core\Services\Validator\OrderValidator;

class UserController
{
    /**
     * Get all stored users
     * @param ResponseInterface $response
     * @param UserDataProcessor $dataProcessor
     */
    public function listAll(ResponseInterface $response, UserDataProcessor $dataProcessor): void
    {
        $users = $dataProcessor->getAllUsers();

        $response->render([
            'error' => null,
            'result' => $users
        ]);
    }

    /**
     * Get all stored users sorted
     * @param Request $request
     * @param ResponseInterface $response
     * @param OrderValidator $orderValidator
     * @param FieldValidator $fieldValidator
     * @param UserDataProcessor $dataProcessor
     */
    public function listAllSorted(
        Request $request,
        ResponseInterface $response,
        OrderValidator $orderValidator,
        FieldValidator $fieldValidator,
        UserDataProcessor $dataProcessor
    ): void
    {
        try {
            $field = $request->getQuery()->get('field');
            $order = $request->getQuery()->get('order');
            if ($fieldValidator->validate($field) && $orderValidator->validate($order)) {
                $users = $dataProcessor->getAllUsers($field, $order);
                $response->render([
                    'error' => null,
                    'result' => $users
                ]);
            } else {
                throw new ParameterNotFoundException();
            }
        } catch (ParameterNotFoundException $ex) {
            $response->render([
                'error' => $ex->getMessage(),
                'result' => null
            ], 400);
        }
    }

    /**
     * Get all stored users sorted
     * @param Request $request
     * @param ResponseInterface $response
     * @param UserDataProcessor $dataProcessor
     */
    public function getSome(
        Request $request,
        ResponseInterface $response,
        UserDataProcessor $dataProcessor
    ): void
    {
        try {
            $limit = $request->getQuery()->get('limit');
            $offset = $request->getQuery()->get('offset');
            $users = $dataProcessor->getSomeUsers($limit, $offset);
            $response->render([
                'error' => null,
                'result' => $users
            ]);
        } catch (ParameterNotFoundException $ex) {
            $response->render([
                'error' => $ex->getMessage(),
                'result' => null
            ], 400);
        }
    }

    /**
     * Get user by id
     * @param Request $request
     * @param ResponseInterface $response
     * @param UserDataProcessor $dataProcessor
     */
    public function getById(Request $request, ResponseInterface $response, UserDataProcessor $dataProcessor): void
    {
        try {
            $id = $request->getQuery()->has('id') ? $request->getQuery()->get('id') : null;
            $user = $dataProcessor->getUserById((int)$id);
            if (empty($user)) {
                $response->render([
                    'error' => 'User not found',
                    'result' => null
                ], 404);
            } else {
                $response->render([
                    'error' => null,
                    'result' => $user
                ]);
            }
        } catch (ParameterNotFoundException $ex) {
            $response->render([
                'error' => $ex->getMessage(),
                'result' => null
            ], 400);
        }
    }

    /**
     * Get user by string
     * @param Request $request
     * @param ResponseInterface $response
     * @param UserDataProcessor $dataProcessor
     */
    public function getByString(Request $request, ResponseInterface $response, UserDataProcessor $dataProcessor): void
    {
        try {
            $string = $request->getQuery()->has('parameter') ? $request->getQuery()->get('parameter') : null;
            $user = $dataProcessor->getUserByString($string);
            if (empty($user)) {
                $response->render([
                    'error' => 'User not found',
                    'result' => null
                ], 404);
            } else {
                $response->render([
                    'error' => null,
                    'result' => $user
                ]);
            }
        } catch (ParameterNotFoundException $ex) {
            $response->render([
                'error' => 'Parameter id is invalid',
                'result' => null
            ], 400);
        }
    }

    /**
     * @param Request $request
     * @param ResponseInterface $response
     * @param UserDataProcessor $dataProcessor
     * @param FieldValidator $fieldValidator
     * @param EmailValidator $emailValidator
     */
    public function updateUser(
        Request $request,
        ResponseInterface $response,
        UserDataProcessor $dataProcessor,
        FieldValidator $fieldValidator,
        EmailValidator $emailValidator
    )
    {
        try {
            $id = $request->getQuery()->get('id');
            $updates = $request->getQuery()->get('update');
            foreach ($updates as $field => &$value) {
                if (!$fieldValidator->validate($field)) {
                    throw new ParameterNotFoundException($field);
                }
                // validate inputs
                switch ($field) {
                    // Sample
                    case 'email':
                        if (!$emailValidator->validate($value)) {
                            throw new ParameterNotFoundException($field);
                        }
                        break;
                }
            }
            if ($dataProcessor->updateUser($id, $updates)) {
                $response->render(['error' => null, 'response' => null]);
            } else {
                $response->render(['error' => 'Internal server error', 'response' => null], 500);
            }
        } catch (ParameterNotFoundException $ex) {
            $response->render([
                'error' => $ex->getMessage(),
                'result' => null
            ], 400);
        }
    }

    /**
     * @param Request $request
     * @param ResponseInterface $response
     * @param UserDataProcessor $dataProcessor
     * @param FieldValidator $fieldValidator
     * @param EmailValidator $emailValidator
     */
    public function createUser(
        Request $request,
        ResponseInterface $response,
        UserDataProcessor $dataProcessor,
        FieldValidator $fieldValidator,
        EmailValidator $emailValidator
    )
    {
        try {
            $values = $request->getRequest()->get('create');
            foreach ($values as $field => &$value) {
                if (!$fieldValidator->validate($field)) {
                    throw new ParameterNotFoundException($field);
                }
                switch ($field) {
                    // Sample
                    case 'email':
                        if (!$emailValidator->validate($value)) {
                            throw new ParameterNotFoundException($field);
                        }
                        break;
                }
            }
            if ($id = $dataProcessor->createUser($values)) {
                $response->render(['error' => null, 'response' => ['id' => $id]], 201);
            } else {
                $response->render(['error' => 'Internal server error', 'response' => null], 500);
            }
        } catch (ParameterNotFoundException $ex) {
            $response->render([
                'error' => $ex->getMessage(),
                'result' => null
            ], 400);
        }
    }

    public function deleteUser(Request $request, ResponseInterface $response, UserDataProcessor $dataProcessor)
    {
        try {
            $id = $request->getQuery()->get('id');

            if ($dataProcessor->deleteUser($id)) {
                $response->render(['error' => null, 'result' => null]);
            } else {
                $response->render(['error' => 'User not found!', 'result' => null], 404);
            }
        } catch (ParameterNotFoundException $ex) {
            $response->render(['error' => $ex->getMessage(), 'result' => null], 400);
        }
    }
}
