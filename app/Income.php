<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id', 'bank_id', 'check_amount', 'date', 'invoice', 'invoice_amount'
    ];

    public function project() {
        return $this->hasOne('App\Project', 'id', 'project_id');
    }

    public function bank() {
        return $this->hasOne('App\Bank', 'id', 'bank_id');
    }
}
