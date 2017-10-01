<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    /**
     * Get the parent invoice.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }   
}
