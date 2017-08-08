<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TravelJob extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'travel_jobs';

    /**
     * Get the parent timesheet
     */
    public function timesheet()
    {
        return $this->belongsTo('App\Timesheet');
    }    
}
