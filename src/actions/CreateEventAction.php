<?php

namespace reunionou\actions;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use reunionou\services\EventService;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

final class CreateEventAction
{
    public function __invoke(Request $request, Response $response): Response
    {
        $token = $request->getHeaderLine('Authorization');
        $token = str_replace('Bearer ', '', $token);

        try {
            $decoded = JWT::decode($token, new Key('63DDF4E66BEC66FAA5B66D87989B6', 'HS256'));
            $user_id = $decoded->user_id;
        } catch (\Exception) {
            $response->withStatus(401)->withHeader('Content-Type', 'application/json');
            $response->getBody()->write(json_encode(['error' => 'Unauthorized']));
            return $response;
        }

        $requestData = $request->getParsedBody();

        if (!isset($requestData['title'], $requestData['description'], $requestData['date'])) {
            $response->withStatus(400)->withHeader('Content-Type', 'application/json');
            $response->getBody()->write(json_encode([
                'error' => 'Missing required fields'
            ]));
            return $response;
        }

        $organizer_id = $user_id;
        $title = $requestData['title'];
        $description = $requestData['description'];
        $longitude = $requestData['longitude'] ?? null;
        $latitude = $requestData['latitude'] ?? null;
        $street = $requestData['street'] ?? null;
        $zipcode = $requestData['zipcode'] ?? null;
        $city = $requestData['city'] ?? null;
        $date = $requestData['date'];


        $event = EventService::createEvent($title, $description, $organizer_id, $date, $longitude, $latitude, $street, $zipcode, $city);

        $data = [
            'id' => 'resource',
            'event' => $event
        ];

        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($data));

        return $response;
    }
}
