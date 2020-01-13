<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
	protected $table = 'discipline';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'discipline_date', 'type', 'details', 'overturned', 'overturned_by', 'overturned_date', 'overturned_reason', 'disputed', 'disputed_date', 'disputed_reason'
    ];
}
