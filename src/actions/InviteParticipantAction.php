<?php

namespace reunionou\actions;

use reunionou\services\ParticipantService;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


final class inviteParticipantAction
{
    public function __invoke(Request $request, Response $response, mixed $args): Response
    {


        $token = $request->getHeaderLine('Authorization');
        $token = str_replace('Bearer ', '', $token);

        try {
            $decoded = JWT::decode($token, new Key('63DDF4E66BEC66FAA5B66D87989B6', 'HS256'));
            $user_id = $decoded->user_id;
            $firstname = $decoded->firstname;
            $lastname = $decoded->lastname;
        } catch (\Exception $e) {
            return $response->withStatus(401);
        }


        $data = $request->getParsedBody();
        $event_id = $args['id'];
        $email = $data['email'] ?? null;

        if (!$user_id || !$event_id || !$email) {
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json')->write(json_encode([
                'error' => 'Missing required fields'
            ]));
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json')->write(json_encode([
                'error' => 'Invalid email address'
            ]));
        }

        $comment = ParticipantService::inviteParticipant($event_id, $user_id, $firstname, $lastname, $email, 'pending');
        
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($comment));

        return $response;
    }
}