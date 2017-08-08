<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectUser extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'project_user';

    /*public function project()
    {
        return $this->hasOne('App\Project');
    }    

    public function user()
    {
        return $this->hasOne('App\User');
    } */   
}
