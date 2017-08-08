<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EquipmentRental extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'equipment_rentals';

    /**
     * Get the parent timesheet
     */
    public function timesheet()
    {
        return $this->belongsTo('App\Timesheet');
    }    
}
