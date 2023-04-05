<?php

namespace reunionou\actions;

use reunionou\services\EventService;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

final class GetEventCurrentEventsAction
{
    public function __invoke(Request $request, Response $response, mixed $args): Response
    {
        $events = EventService::getCurrentEvents($args['id']);

        $data = [
            'id' => 'resource',
            'events' => $events
        ];

        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($data));

        return $response;
    }
}
