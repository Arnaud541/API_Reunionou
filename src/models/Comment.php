<?php

namespace reunionou\models;

class Comment extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'comments';
    protected $fillable = ['event_id', 'participant_id', 'content'];
    public $timestamps = false;
}