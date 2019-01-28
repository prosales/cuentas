<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'number_nog', 'name', 'amount', 'municipality', 'place', 'balance', 'percentage'
    ];
}
