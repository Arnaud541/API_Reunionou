<?php

namespace reunionou\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use reunionou\services\UserService;
use Slim\Psr7\Response;

final class RegisterUserAction
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $requestData = $request->getParsedBody();

        if (!isset($requestData['firstname'], $requestData['lastname'], $requestData['email'], $requestData['password'])) {
            return $response->withStatus(400)->withHeader('Content-Type', 'text/plain')->getBody()->write('Required fields are missing');
        }

        $createdUser = UserService::registerUser($requestData['firstname'], $requestData['lastname'], $requestData['email'], $requestData['password']);

        if ($createdUser === null) {
            return $response->withStatus(500)->withHeader('Content-Type', 'text/plain')->getBody()->write('An error occurred during registration');
        }

        $data = [
            'id' => 'resource',
            'user' => $createdUser
        ];

        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($data));

        return $response;
    }
}
