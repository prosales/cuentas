<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'driver_id', 'user_id', 'number', 'amount', 'photo', 'date'
    ];

    public function driver() {
        return $this->hasOne('App\Driver', 'id', 'driver_id');
    }
}
