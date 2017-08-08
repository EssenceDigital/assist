<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\EquipmentRental;

class EquipmentRentalsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    // Fields and their respective validation rules
    private $validationFields = [
        'timesheet_id' => 'required|numeric',
        'equipment_type' => 'required|max:75',
        'rental_fee' => 'numeric|between:0,1000000000000.99',
        'comment' => 'max:255'
    ];

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate and populate the request
        $equipment = $this->validateAndPopulate($request, new EquipmentRental, $this->validationFields);

        // Attempt to store model
        $result = $equipment->save();

        // Verify success on store
        if(! $result){
            // Return response for ajax call
            return response()->json([
                'result' => false
            ], 404);
        }

        // Return response for ajax call
        return response()->json([
            'result' => 'success',
            'model' => $equipment
        ], 200);
    }

    /**
     * Update a resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {   
        $equipment = EquipmentRental::findOrFail($request->id);

        // Return failed response if collection empty
        if(! $equipment){
            // Return response for ajax call
            return response()->json([
                'result' => false
            ], 404);
        }

        // Validate and populate the request
        $equipment = $this->validateAndPopulate($request, $equipment, $this->validationFields);

        // Attempt to store model
        $result = $equipment->save();

        // Verify success on store
        if(! $result){
            // Return response for ajax call
            return response()->json([
                'result' => false
            ], 404);
        }

        // Return response for ajax call
        return response()->json([
            'result' => 'success',
            'model' => $equipment
        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        // Find or throw 404 :)
        $equipment = EquipmentRental::findOrFail($request->id);
        // To return
        $return = $equipment;

        // Attempt to remove 
        $result = $equipment->delete();
        // Verify success on store
        if(! $result){
            // Return response for ajax call
            return response()->json([
                'result' => false
            ], 404);
        }

        // Return successful response for ajax call
        return response()->json([
            'result' => 'success',
            'model' => $return
        ], 200);
    }        

}
