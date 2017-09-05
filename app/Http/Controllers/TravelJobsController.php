<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\TravelJob;
use App\Timesheet;

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
        // Use parent helper method to store
        $talliedTimesheet = $this->storeTimesheetAsset($request, new TravelJob, $this->validationFields);

        // Return response for ajax call
        return response()->json([
            'result' => 'success',
            'payload' => $talliedTimesheet
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
        // Use parent helper method to update
        $talliedTimesheet = $this->updateTimesheetAsset($request, TravelJob::findOrFail($request->id), $this->validationFields);

        // Return response for ajax call
        return response()->json([
            'result' => 'success',
            'payload' => $talliedTimesheet
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
        $talliedTimesheet = $this->deleteTimesheetAsset(TravelJob::findOrFail($id));

        // Return successful response for ajax call
        return response()->json([
            'result' => 'success',
            'payload' => $talliedTimesheet
        ], 200);
    }       

}
