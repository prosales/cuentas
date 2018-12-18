<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GasStation extends Model
{
    protected $table = 'gas_stations';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];
}
