<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectComment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'project_comments';

    /**
     * Get the project that the comment belongs to.
     */
    public function project()
    {
        return $this->belongsTo('App\Project');
    }

    /**
     * Get the user that owns the comment.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }        
}
