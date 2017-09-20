<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Invoice;
use App\WorkItem;

class InvoicesController extends Controller
{
    // Fields and their respective validation rules
    private $validationFields = [
        'from_date' => 'date',
        'to_date' => 'date',
    ];

    private $workItemValidationFields = [
		'project_id' => 'numeric',
		'invoice_id' => 'numeric',
		'from_date' => 'required|date',
		'to_date' => 'required|date',
		'desc' => 'required|max:255',
		'hours' => 'required|numeric|between:0,1000000000000.99',
		'hourly_rate' => 'required|numeric|between:0,1000000000000.99',
		'per_diem' => 'required|numeric|between:0,1000000000000.99',
		'per_diem_desc' => 'required|max:45',
		'travel_mileage' => 'numeric',
		'mileage_rate' => 'numeric|between:0,1000000000000.99',
		'lodging_desc' => 'max:45|nullable',
		'lodging_cost' => 'numeric|between:0,1000000000000.99|nullable'
    ];

    /**
     * Finds all invoices belonging to the authenticated user
     *
     * @param Int - The primary key
     * @return \Illuminate\Http\JsonResponse
     */
    public function authUsersInvoices()
    {
        // With all foreign keys / children
        $invoices = Invoice::with(['workItems', 'workItems.project'])
        	->where('user_id', '=', Auth::id())
        	->get();

        // Return response for ajax call
        return response()->json([
            'result' => 'success',
            'payload' => $invoices
        ], 200);        
    }

    /**
     * Find an invoice
     *
     * @param Int - The primary key
     * @return \Illuminate\Http\JsonResponse
     */
    public function single($id)
    {
        // With all foreign keys / children
        $invoice = Invoice::with(['user', 'workItems', 'workItems.project'])->find($id);

        // Return response for ajax call
        return response()->json([
            'result' => 'success',
            'payload' => $invoice
        ], 200);        
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
        $invoice = $this->validateAndPopulate($request, new Invoice, $this->validationFields);
        // Add user id to the timesheet
        $invoice->user_id = Auth::id();
        // Attempt to store model
        $result = $invoice->save();
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
            'payload' => $invoice
        ], 200);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeWorkItem(Request $request)
    {
        // Validate and populate the request
        $item = $this->validateAndPopulate($request, new WorkItem, $this->workItemValidationFields);

        // Attempt to store model
        $result = $item->save();
        // Verify success on store
        if(! $result){
            // Return response for ajax call
            return response()->json([
                'result' => false
            ], 404);
        }
        // Load related project
        $item->load('project');
        // Return response for ajax call
        return response()->json([
            'result' => 'success',
            'payload' => $item
        ], 200);
    }

    /**
     * Updata a resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateWorkItem(Request $request)
    {
        // Validate and populate the request
        $item = $this->validateAndPopulate($request, WorkItem::findOrFail($request->id), $this->workItemValidationFields);
        // Attempt to store model
        $result = $item->save();
        // Verify success on store
        if(! $result){
            // Return response for ajax call
            return response()->json([
                'result' => false
            ], 404);
        }        
        // Load related project
        $item->load('project');
        // Return response for ajax call
        return response()->json([
            'result' => 'success',
            'payload' => $item
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteWorkItem($id)
    {
    	// Find work item
    	$item = WorkItem::findOrFail($id);
        // Attempt to remove 
        $result = $item->delete();
        // Verify success on store
        if(! $result){
            // Return response for ajax call
            return response()->json([
                'result' => false
            ], 404);
        }   
        // Return response
        return response()->json([
            'result' => 'success',
            'payload' => $id
        ], 200);        	

    }     

}
