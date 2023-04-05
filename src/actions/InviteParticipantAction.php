<?php

namespace reunionou\actions;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use reunionou\services\ParticipantService;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use reunionou\services\EventService;
use reunionou\services\UserService;


final class inviteParticipantAction
{
    public function __invoke(Request $request, Response $response, mixed $args): Response
    {


        $token = $request->getHeaderLine('Authorization');
        $token = str_replace('Bearer ', '', $token);

        try {
            $decoded = JWT::decode($token, new Key('63DDF4E66BEC66FAA5B66D87989B6', 'HS256'));
            $user_id = $decoded->user_id;
        } catch (\Exception $e) {
            return $response->withStatus(401);
        }

        $data = $request->getParsedBody();
        $event_id = $args['id'];

        // $email = $data['email'] ?? null;
        $participants = $data["participants"];

        $event = EventService::getEventById($event_id);

        if ($user_id !== $event['organizer_id']) {
            $response = $response->withStatus(401)->withHeader('Content-Type', 'application/json');
            $response->getBody()->write(json_encode([
                'error' => 'You are not the creator of the event'
            ]));
            return $response;
        }



        $event_participants = [];
        foreach ($participants as $p) {
            if (!$p) {
                $response = $response->withStatus(401)->withHeader('Content-Type', 'application/json');
                $response->getBody()->write(json_encode([
                    'error' => 'missing required fields'
                ]));
                return $response;
            }

            if (!filter_var($p, FILTER_VALIDATE_EMAIL)) {
                $response = $response->withStatus(401)->withHeader('Content-Type', 'application/json');
                $response->getBody()->write(json_encode([
                    'error' => 'Invalid email address'
                ]));
                return $response;
            }
            $user = UserService::getUserByEmail($p);
            $participant = ParticipantService::inviteParticipant($event_id, $user['id'], $user['firstname'], $user['lastname'], $p, 'pending');
            array_push($event_participants, $participant);
        }



        $data = [
            "type" => "collection",
            "participants" => $event_participants,
            "message" => "Invitations envoyées !"
        ];



        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($data));

        return $response;
    }
}
