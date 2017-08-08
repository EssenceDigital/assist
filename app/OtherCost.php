<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OtherCost extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'other_costs';

    /**
     * Get the parent timesheet
     */
    public function timesheet()
    {
        return $this->belongsTo('App\Timesheet');
    }    
}
