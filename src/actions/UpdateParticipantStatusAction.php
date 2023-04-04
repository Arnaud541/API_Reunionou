<?php

namespace reunionou\actions;

use reunionou\services\ParticipantService;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


final class UpdateParticipantStatusAction
{
    public function __invoke(Request $request, Response $response, mixed $args): Response
    {
        $token = $request->getHeaderLine('Authorization');
        $token = str_replace('Bearer ', '', $token);

        try {
            $decoded = JWT::decode($token, new Key('63DDF4E66BEC66FAA5B66D87989B6', 'HS256'));
        } catch (\Exception $e) {
            return $response->withStatus(401);
        }

        $data = $request->getParsedBody();
        $user_id = $args['id'];
        echo $user_id;
        $status = $data['status'] ?? null;

        if (!$user_id || !$status) {
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json')->write(json_encode([
                'error' => 'Missing required fields'
            ]));
        }

        $participant = ParticipantService::updateParticipantStatus($user_id, $status);

        if (!$participant) {
            return $response->withStatus(404);
        }

        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($participant));

        return $response;
    }
}
