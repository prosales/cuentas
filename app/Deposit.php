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

    public function payments() {
        return $this->hasMany('App\Payment', 'deposit_id', 'id');
    }

    public static function boot() {
        parent::boot();

        static::deleting(function($registro) { // before delete() method call this
             $registro->payments()->delete();
             // do the rest of the cleanup...
        });
    }
}
