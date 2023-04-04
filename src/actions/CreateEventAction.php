<?php

namespace reunionou\actions;

use reunionou\services\EventService;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

final class CreateEventAction
{
    public function __invoke(Request $request, Response $response): Response
    {
        $token = $request->getHeaderLine('Authorization');

        try {
            $decoded = JWT::decode($token, new Key('63DDF4E66BEC66FAA5B66D87989B6', 'HS256'));
            $user_id = $decoded->user_id;
        } catch (\Exception $e) {
            $response->withStatus(401)->withHeader('Content-Type', 'application/json');
            $response->getBody()->write(json_encode(['error' => 'Unauthorized']));
            return $response;
        }

        $requestData = $request->getParsedBody();

        if (!isset($requestData['title'], $requestData['street'], $requestData['zipcode'], $requestData['city'])) {
            $response->withStatus(400)->withHeader('Content-Type', 'application/json');
            $response->getBody()->write(json_encode([
                'error' => 'Missing required fields'
            ]));
            return $response;
        }

            $organizer_id = $user_id;
            $title = $requestData['title'];
            $description = $requestData['description'] ?? null;
            $street = $requestData['street'];
            $zipcode = $requestData['zipcode'];
            $city = $requestData['city'];


        $event = EventService::createEvent($title, $description, $street, $zipcode, $city, $organizer_id);

        $data = [
            'id' => 'resource',
            'event' => $event
        ];

        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($data));

        return $response;
    }
}
