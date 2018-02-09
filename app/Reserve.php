<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reserve extends Model
{
    protected $fillable = ['start_dt', 'end_dt', 'overview', 'detail', 'delete_flg'];
}
