<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkJob extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'work_jobs';

    /**
     * Get the parent timesheet
     */
    public function timesheet()
    {
        return $this->belongsTo('App\Timesheet');
    }    
}
