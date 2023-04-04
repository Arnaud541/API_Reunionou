<?php

namespace reunionou\actions;

use reunionou\services\CommentService;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


final class addCommentAction
{
    public function __invoke(Request $request, Response $response, mixed $args): Response
    {


        $token = $request->getHeaderLine('Authorization');
        $token = str_replace('Bearer ', '', $token);

        try {
            $decoded = JWT::decode($token, new Key('63DDF4E66BEC66FAA5B66D87989B6', 'HS256'));
            $user_id = $decoded->user_id;
            echo $user_id;
        } catch (\Exception $e) {
            return $response->withStatus(401);
        }


        $data = $request->getParsedBody();
        $event_id = $args['id'];
        $content = $data['content'] ?? null;

        if (!$user_id || !$event_id || !$content) {
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json')->write(json_encode([
                'error' => 'Missing required fields'
            ]));
        }

        $comment = CommentService::addComment($content, $user_id, $event_id);
        
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($comment));

        return $response;
    }
}