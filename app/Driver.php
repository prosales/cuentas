<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'business_id'
    ];

    public function business() {
        return $this->hasOne('App\Business', 'id', 'business_id');
    }
}
