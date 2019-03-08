<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    protected $table = 'business';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'owner_name', 'business_name', 'nit', 'address', 'phone', 'email', 'balance', 'gas_station_id'
    ];
}
