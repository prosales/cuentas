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
        'business_id', 'user_id', 'number', 'amount', 'photo', 'date'
    ];

    public function business() {
        return $this->hasOne('App\Business', 'id', 'business_id');
    }
}
