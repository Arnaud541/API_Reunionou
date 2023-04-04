<?php

namespace reunionou\actions;

use reunionou\services\UserService;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Firebase\JWT\JWT;

final class LoginUserAction
{
    public function __invoke(Request $request, Response $response, mixed $args): Response
    {
        $data = $request->getParsedBody();
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if ($email === null || $password === null) {
            $response = $response->withStatus(400)->withHeader('Content-Type', 'application/json');
            $response->getBody()->write(json_encode([
                'error' => 'Missing required fields'
            ]));
            return $response;
        }

        $user = UserService::loginUser($email, $password);

        if ($user === null) {
            $response = $response->withStatus(401)->withHeader('Content-Type', 'application/json');
            $response->getBody()->write(json_encode([
                'error' => 'Unauthorized'
            ]));
            return $response;
        }

        $payload =    [
            'iat' => time(),
            'exp' => time() + (2000 * 2000),
            'user_id' => $user['id'],
            'firstname' => $user['firstname'],
            'lastname' => $user['lastname'],
        ];
    
        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT',
            'kid' => 'atelier-2023',
        ];
        
        $jwt = JWT::encode($payload, '63DDF4E66BEC66FAA5B66D87989B6', 'HS256', null, $header);
        
        $responseData = [
            'token' => $jwt,
            'user' => $user,
        ];
    
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($responseData));
    
        return $response;
    }
}
