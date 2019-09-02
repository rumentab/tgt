<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

return [
    'defaultApp' => [
        'method' => 'GET',
        'pattern' => '/',
        'handler' => 'App\Application\Controller\DefaultController::index'
    ],
    'defaultApi' => [
        'method' => 'GET',
        'pattern' => 'api',
        'handler' => 'App\Api\Controller\DefaultController::index'
    ],
    'test' => [
        'method' => 'GET',
        'pattern' => 'api/test/{id:number}/{name:text}/{email:text}/{password:text}',
        'handler' => 'App\Api\Controller\DefaultController::test'
    ],
    'getAllUsers' => [
        'method' => 'GET',
        'pattern' => 'api/user/all',
        'handler' => 'App\Api\Controller\UserController::listAll'
    ],
    'getAllUsersSort' => [
        'method' => 'GET',
        'pattern' => 'api/user/all/{field:text}/{order:text}',
        'handler' => 'App\Api\Controller\UserController::listAllSorted'
    ],
    'getSomeUsers' => [
        'method' => 'GET',
        'pattern' => 'api/user/some/{limit:number}/{offset:number}',
        'handler' => 'App\Api\Controller\UserController::getSome'
    ],
    'getUserById' => [
        'method' => 'GET',
        'pattern' => 'api/user/{id:number}',
        'handler' => 'App\Api\Controller\UserController::getById'
    ],
    'getUserByString' => [
        'method' => 'GET',
        'pattern' => 'api/user/{parameter:text}',
        'handler' => 'App\Api\Controller\UserController::getByString'
    ],
    'updateUser' => [
        'method' => 'PUT',
        'pattern' => 'api/user/update/{id:number}',
        'handler' => 'App\Api\Controller\UserController::updateUser'
    ],
    'createUser' => [
        'method' => 'POST',
        'pattern' => 'api/user/create',
        'handler' => 'App\Api\Controller\UserController::createUser'
    ],
    'deleteUser' => [
        'method' => 'DELETE',
        'pattern' => 'api/user/delete/{id:number}',
        'handler' => 'App\Api\Controller\UserController::deleteUser'
    ],
];
