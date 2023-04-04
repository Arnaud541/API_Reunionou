<?php

namespace reunionou\actions;

use reunionou\services\CommentService;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

final class GetCommentsByEventIdAction
{
    public function __invoke(Request $request, Response $response, mixed $args): Response
    {
        $eventId = $args['id'];
        $comments = CommentService::getCommentsByEventId($eventId);

        $data = [
            'comments' => $comments
        ];

        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($data));

        return $response;
    }
}
