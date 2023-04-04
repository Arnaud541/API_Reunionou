<?php

namespace reunionou\models;

class Event extends \Illuminate\Database\Eloquent\Model
{
        protected $table = 'events';
        protected $fillable = ['title', 'description', 'street', 'zipcode', 'city', 'organizer_id'];
        public $timestamps = false;
}