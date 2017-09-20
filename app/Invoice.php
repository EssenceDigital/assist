<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    /**
     * Get the parent project.
     */
    public function project()
    {
        return $this->belongsTo('App\Project');
    }

    /**
     * Get the user.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the work items for the invoice.
     */
    public function workItems()
    {
        return $this->hasMany('App\WorkItem');
    }
}
