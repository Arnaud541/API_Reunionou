<?php

namespace reunionou\actions;

use reunionou\services\EventService;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

final class GetEventsAction
{
    public function __invoke(Request $request, Response $response): Response
    {
        $events = EventService::getAllEvents();

        $data = [
            'events' => $events
        ];

        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($data));

        return $response;
    }
}
