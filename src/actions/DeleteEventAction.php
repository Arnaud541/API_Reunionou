<?php

namespace reunionou\actions;

use reunionou\services\EventService;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

final class DeleteEventAction
{
    public function __invoke(Request $request, Response $response, mixed $args): Response
    {
        $token = $request->getHeaderLine('Authorization');
        $token = str_replace('Bearer ', '', $token);

        try {
            $decoded = JWT::decode($token, new Key('63DDF4E66BEC66FAA5B66D87989B6', 'HS256'));
            $user_id = $decoded->user_id;
        } catch (\Exception $e) {
            return $response->withStatus(401)->withHeader('Content-Type', 'text/plain')->getBody()->write('Unauthorized');
        }

        $event_id = $args['id'];

        if ($event_id === null || $user_id === null) {
            return $response->withStatus(400)->withHeader('Content-Type', 'text/plain')->getBody()->write('Required fields are missing');
        }

        $result = EventService::deleteEvent($event_id, $user_id);


        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($result));

        return $response;
    }
}
