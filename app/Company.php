<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
      'agreement_id',
      'company_name',
      'zip_code',
      'prefecture_code',
      'street_address',
      'tel'
    ];
}
