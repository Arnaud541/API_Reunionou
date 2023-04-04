<?php

namespace reunionou\actions;

use reunionou\services\UserService;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

final class GetUserAction
{
    public function __invoke(Request $request, Response $response, mixed $args): Response
    {
        $user = UserService::getUserById($args['id']);

        if ($user === null) {
            $response = new \Slim\Psr7\Response(404);
            return $response;
        }

        $data = [
            'id' => 'resource',
            'user' => $user
        ];

        // Create a JSON response
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($data));

        return $response;
    }
}