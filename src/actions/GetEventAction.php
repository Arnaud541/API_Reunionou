<?php

namespace reunionou\actions;

use reunionou\services\EventService;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

final class GetEventAction
{
    public function __invoke(Request $request, Response $response, mixed $args): Response
    {
        $event = EventService::getEventById($args['id']);

        if ($event === null) {
            $response = new \Slim\Psr7\Response(404);
            return $response;
        }

        $data = [
            'id' => 'resource',
            'event' => $event
        ];

        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($data));

        return $response;
    }
}
