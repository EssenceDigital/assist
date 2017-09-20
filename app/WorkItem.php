<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkItem extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'work_items';

    /**
     * Get the parent project.
     */
    public function project()
    {
        return $this->belongsTo('App\Project');
    }

    /**
     * Get the parent invoice.
     */
    public function invoice()
    {
        return $this->belongsTo('App\Invoice');
    }

    /**
     * Get associated travel item.
     */
    public function travelItem()
    {
        return $this->hasOne('App\TravelItem', 'travel_item_id');
    } 

    /**
     * Get associated other cost.
     */
    public function otherCost()
    {
        return $this->hasOne('App\OtherCost', 'other_cost_id');
    }

}
