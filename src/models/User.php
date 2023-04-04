<?php

namespace reunionou\models;

class User extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'users';
    protected $fillable = ['firstname', 'lastname', 'email', 'password'];
    public $timestamps = false;
}