<?php

namespace reunionou\models;

class Event extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'events';
    protected $fillable = ['title', 'description', 'longitude', 'latitude', 'street', 'zipcode', 'city', 'organizer_id', 'date'];
    public $timestamps = false;
}