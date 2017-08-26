<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\TravelJob;

class TravelJobsController extends Controller
{

    /*public function __construct()
    {
        $this->middleware('auth');
    }*/
    
    // Fields and their respective validation rules
    private $validationFields = [
        'timesheet_id' => 'required|numeric',
        'travel_distance' => 'required|numeric',
        'travel_time' => 'required|numeric|between:0,1000000000000.9',
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
        $travelJob = $this->validateAndPopulate($request, new TravelJob, $this->validationFields);

        // Attempt to store model
        $result = $travelJob->save();

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
            'payload' => $travelJob
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
        $travelJob = TravelJob::findOrFail($request->id);

        // Return failed response if collection empty
        if(! $travelJob){
            // Return response for ajax call
            return response()->json([
                'result' => false
            ], 404);
        }

        // Validate and populate the request
        $travelJob = $this->validateAndPopulate($request, $travelJob, $this->validationFields);

        // Attempt to store model
        $result = $travelJob->save();

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
            'payload' => $travelJob
        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        // Find or throw 404 :)
        $travelJob = TravelJob::findOrFail($id);
        // To return
        $return = $travelJob;

        // Attempt to remove 
        $result = $travelJob->delete();
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
            'payload' => $return
        ], 200);
    }        

}
