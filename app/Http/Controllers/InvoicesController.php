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
		'lodging_cost' => 'numeric|between:0,1000000000000.99|nullable',
        'equipment_desc' => 'max:45|nullable',
        'equipment_cost' => 'numeric|between:0,1000000000000.99|nullable'        
    ];

    /**
     * Finds all invoices belonging to the authenticated user
     *
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
     * Finds all invoices
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        // With all foreign keys / children
        $invoices = Invoice::with(['workItems', 'workItems.project', 'user'])->where('is_published', '=', 1)->get();

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

    public function filter($user = false, $invoice = false, $from_date = false, $to_date = false) {

        // Construct where array for query
        $queryArray = [];  
        $query = Invoice::with(['user', 'workItems', 'workItems.project']);

        // Make sure invoice is published
        array_push($queryArray, ['is_published', '=', 1]);

        // Add user id field or not
        if($user){
            // Push array clause
            array_push($queryArray, ['user_id', '=', $user]);
        } 

        // Add invoice field or not
        if($invoice){
            // Push array clause
            array_push($queryArray, ['is_paid', '=', $invoice]);
        } 

        // Add single day (from date) field or not
        if($from_date && !$to_date){
            // Push array clause
            array_push($queryArray, ['from_date', '=', $from_date]);
        }        

        // Add where queries
        $query->where($queryArray);

        // If the to date is set then it's a dange range query
        if($from_date && $to_date){
            // Add possible where between
            $query->whereBetween('to_date', [$from_date, $to_date]);
        }

        // Get results
        $invoices = $query->get();

        // Return response for ajax call
        return response()->json([
            'result' => 'success',
            'payload' => $invoices
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
        // Add user id to the invoice
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
     * Tag an invoice as published
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function publish(Request $request)
    {
        // Validate request id
        $this->validate($request, [
            'id' => 'numeric'
        ]);

        // Find invoice
        $invoice = Invoice::find($request->id);
        // Update
        $invoice->is_published = 1;
        // Save
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
            'payload' => $invoice->id
        ], 200);
    }

    /**
     * Tag and invoice as paid in storate.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function markPaid(Request $request)
    {
        // Grab the array of invoice ids
        $invoiceIds = $request->id;

        // Itterate each id and update that invoice
        forEach($invoiceIds as $id) {
            if(is_numeric($id)) {
                $invoice = Invoice::find($id);
                $invoice->is_paid = 1;
                $result = $invoice->save();
                // Verify success on store
                if(! $result){
                    // Return response for ajax call
                    return response()->json([
                        'result' => false
                    ], 404);
                }                 
            }   
        }

        // Return response for ajax call
        return response()->json([
            'result' => 'success',
            'payload' => $request->id
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
        // Add user id to the timesheet
        $item->user_id = Auth::id();
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
