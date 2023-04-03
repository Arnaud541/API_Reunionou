<?php

namespace reunionou\actions;

use reunionou\services\EventService;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

final class CreateEventAction
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $token = $request->getHeaderLine('Authorization');
        $token = str_replace('Bearer ', '', $token);

        try {
            $decoded = JWT::decode($token, new Key('63DDF4E66BEC66FAA5B66D87989B6', 'HS256'));
            $user_id = $decoded->user_id;
        } catch (\Exception $e) {
            return $response->withStatus(401)->withHeader('Content-Type', 'text/plain')->getBody()->write('Unauthorized');
        }

        $requestData = $request->getParsedBody();

        if (!isset($requestData['title'], $requestData['street'], $requestData['zipcode'], $requestData['city'])) {
            return $response->withStatus(400)->withHeader('Content-Type', 'text/plain')->getBody()->write('Required fields are missing');
        }

        $eventData = [
            'organizer_id' => $user_id,
            'title' => $requestData['title'],
            'description' => $requestData['description'] ?? null,
            'street' => $requestData['street'],
            'zipcode' => $requestData['zipcode'],
            'city' => $requestData['city']
        ];

        $event = EventService::createEvent($eventData);

        $data = [
            'id' => 'resource',
            'event' => $event
        ];

        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($data));

        return $response;
    }
}
