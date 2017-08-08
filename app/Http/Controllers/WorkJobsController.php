<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\WorkJob;

class WorkJobsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    // Fields and their respective validation rules
    private $validationFields = [
        'timesheet_id' => 'required|numeric',
        'job_type' => 'required|max:75',
        'hours_worked' => 'numeric|between:0,1000000000000.9',
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
        $workjob = $this->validateAndPopulate($request, new WorkJob, $this->validationFields);

        // Attempt to store model
        $result = $workjob->save();

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
            'model' => $workjob
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
        $workjob = WorkJob::findOrFail($request->id);

        // Return failed response if collection empty
        if(! $workjob){
            // Return response for ajax call
            return response()->json([
                'result' => false
            ], 404);
        }

        // Validate and populate the request
        $workjob = $this->validateAndPopulate($request, $workjob, $this->validationFields);

        // Attempt to store model
        $result = $workjob->save();

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
            'model' => $workjob
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
        $workjob = WorkJob::findOrFail($request->id);
        // To return
        $return = $workjob;

        // Attempt to remove 
        $result = $workjob->delete();
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
