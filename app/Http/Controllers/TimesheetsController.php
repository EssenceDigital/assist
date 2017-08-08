<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Timesheet;

class TimesheetsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    // Fields and their respective validation rules
    private $validationFields = [
        'project_id' => 'required|numeric',
        'date' => 'required|date',
        'per_diem' => 'numeric|between:0,1000000000000.99',
        'comment' => 'max:255'
    ];

    /**
     * Find a timesheet
     *
     * @param Int - The primary key
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        return Timesheet::with(['workJobs', 'travelJobs', 'equipmentRentals', 'otherCosts', 'user'])->paginate(15);
    }

    /**
     * Find a timesheet
     *
     * @param Int - The primary key
     * @return \Illuminate\Http\JsonResponse
     */
    public function single($id)
    {
        // Find the timesheet
        $timesheet = Timesheet::with(['workJobs', 'travelJobs', 'equipmentRentals', 'otherCosts'])->find($id);

        // Return response for ajax call
        return response()->json([
            'result' => 'success',
            'model' => $timesheet
        ], 200);        
    } 

    public function filter(Request $request){
        // Validate or stop proccessing :)
        $this->validate($request, [
            'from_date' => 'date|nullable',
            'to_date' => 'date|nullable',
            'project_id' => 'numeric|nullable',
            'user_id' => 'numeric|nullable'
        ]);

        // Construct where array for query
        $queryArray = [];  
        $timesheets = Timesheet::with(['workJobs', 'travelJobs', 'equipmentRentals', 'otherCosts', 'user']);

        // Add project id field or not
        if($request->project_id != ''){
            // Push array clause
            array_push($queryArray, ['project_id', '=', $request->project_id]);
        }      

        // Add single day (from date) field or not
        if($request->from_date != ''){
            // Push array clause
            array_push($queryArray, ['date', '=', $request->from_date]);
        }

        // Add single day (from date) field or not
        if($request->user_id != ''){
            // Push array clause
            array_push($queryArray, ['user_id', '=', $request->user_id]);
        }

        // Add where queries
        $timesheets->where($queryArray);

        // If the to date is set then it's a dange range query
        if($request->from_date != '' && $request->to_date != ''){
            // Add possible where between
            $timesheets->whereBetween('date', [$request->from_date, $request->to_date]);
        }

        return $timesheets->paginate($request->perPage);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate and populate the request
        $timesheet = $this->validateAndPopulate($request, new Timesheet, $this->validationFields);
        
        // Add user id to the timesheet
        $timesheet->user_id = Auth::id();

        // Attempt to store model
        $result = $timesheet->save();

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
            'model' => $timesheet
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
        $timesheet = Timesheet::with(['workJobs', 'travelJobs', 'equipmentRentals', 'otherCosts'])->find($request->id);

        // Return failed response if collection empty
        if(! $timesheet){
            // Return response for ajax call
            return response()->json([
                'result' => false
            ], 404);
        }

        // Validate and populate the request
        $timesheet = $this->validateAndPopulate($request, $timesheet, $this->validationFields);

        // Attempt to store model
        $result = $timesheet->save();

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
            'model' => $timesheet
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
        // Find user or throw 404 :)
        $timesheet = Timesheet::find($request->id);
        $return = $timesheet;

        // Attempt to remove 
        $result = $timesheet->delete();
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
