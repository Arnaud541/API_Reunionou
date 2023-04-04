<?php


namespace reunionou\services;

use reunionou\models\Comment;

class CommentService {

    public static function getCommentsByEventId(int $event_id): ?array
    {
        $comments = Comment::select('id', 'event_id', 'user_id','content', 'created_at')
                    ->where('event_id', '=', $event_id)
                    ->get();
    
        return $comments ? $comments->toArray() : null;
    }

    public static function addComment(string $content, int $user_id, int $event_id) :?array
    {
        $comment = new Comment;
        $comment->user_id = $user_id;
        $comment->event_id = $event_id;
        $comment->content = $content;
        $comment->save();

        return $comment->toArray();
    }
}