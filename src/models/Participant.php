<?php

namespace reunionou\models;

class Participant extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'participants';
    protected $fillable = ['event_id', 'user_id', 'firstname', 'lastname', 'email','status'];
    public $timestamps = false;

    public function events()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}