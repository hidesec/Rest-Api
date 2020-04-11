<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class verified_token extends Model
{
    protected $table = 'verified_token';
    protected $fillable = [
        'id_user', 'token'
    ];
}
