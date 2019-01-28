<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id', 'detail', 'amount', 'photo'
    ];

    public function project() {
        return $this->hasOne('App\Project', 'id', 'project_id');
    }
}
