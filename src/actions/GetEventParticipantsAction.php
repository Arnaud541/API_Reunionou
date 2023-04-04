<?php

namespace reunionou\actions;

use reunionou\services\ParticipantService;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

final class GetEventParticipantsAction
{
    public function __invoke(Request $request, Response $response, mixed $args): Response
    {
        $eventId = $args['id'];
        $participants = ParticipantService::getParticipantsByEventId($eventId);

        if ($participants === null) {
            $response = new \Slim\Psr7\Response(404);
            return $response;
        }

        $data = [
            'id' => 'resource',
            'participants' => $participants
        ];

        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($data));

        return $response;
    }
}
