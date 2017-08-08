<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timeline extends Model
{
    /**
     * Get the project that owns the timeline.
     */
    public function project()
    {
        return $this->belongsTo('App\Project');
    }

    /**
     * The users that belong to the timeline.
     */
    public function users()
    {
        return $this->belongsToMany('App\User');
    }

}
