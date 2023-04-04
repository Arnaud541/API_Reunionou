<?php

namespace reunionou\actions;

use reunionou\services\EventService;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

final class GetCreatedEventsByUserIdAction
{
    public function __invoke(Request $request, Response $response, mixed $args): Response
    {
        $user_id = $args['id'];

        $events = EventService::getCreatedEventsByUserId($user_id);

        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($events));

        return $response;
    }
}
