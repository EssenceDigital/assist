<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\OtherCost;

class OtherCostsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    // Fields and their respective validation rules
    private $validationFields = [
        'timesheet_id' => 'required|numeric',
        'cost_name' => 'required|max:75',
        'cost' => 'numeric|between:0,1000000000000.99',
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
        $otherCost = $this->validateAndPopulate($request, new OtherCost, $this->validationFields);

        // Attempt to store model
        $result = $otherCost->save();

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
            'model' => $otherCost
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
        $otherCost = OtherCost::findOrFail($request->id);

        // Return failed response if collection empty
        if(! $otherCost){
            // Return response for ajax call
            return response()->json([
                'result' => false
            ], 404);
        }

        // Validate and populate the request
        $otherCost = $this->validateAndPopulate($request, $otherCost, $this->validationFields);

        // Attempt to store model
        $result = $otherCost->save();

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
            'model' => $otherCost
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
        $otherCost = OtherCost::findOrFail($request->id);
        // To return
        $return = $otherCost;

        // Attempt to remove 
        $result = $otherCost->delete();
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
