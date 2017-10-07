<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the comments by the user.
     */
    public function comments()
    {
        return $this->hasMany('App\ProjectComment');
    }

    /**
     * Get the invoices by the user.
     */
    public function invoices()
    {
        return $this->hasMany('App\Invoice');
    }

    /**
     * Get the work items by the user.
     */
    public function workItems()
    {
        return $this->hasMany('App\WorkItem');
    }

    /**
     * The projects that this user is assigned to
     */
    public function projects()
    {
        return $this->belongsToMany('App\Project');
    }

    /**
     * Get the work items by the user.
     */
    public function notifications()
    {
        return $this->hasMany('App\Notification');
    }

}
