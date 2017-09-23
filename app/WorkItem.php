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
     * Get the parent invoice.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }    

}
