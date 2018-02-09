<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReserveUser extends Model
{
    protected $fillable = ['reserve_id', 'user_id'];
}
