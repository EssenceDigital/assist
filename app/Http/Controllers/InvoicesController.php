<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

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

    protected function validateAndPopulate(Request $request, Model $model, Array $validationFields) {
        /* First, make sure to date is after from date
        */
        // Grab dates
        $to = strtotime($request->to_date);
        $from = strtotime($request->from_date);
        // Compare dates and return error is to is before from
        if($to < $from){
            // Return error response
            return response()->json([
                'result' => 'false',
                'error' => 'true',
                'message' => "'From date' must be before 'To Date'."
            ], 422);     
        }

        // Now delegate to parent method to finish validation
        return parent::validateAndPopulate($request, $model, $validationFields); 
    }

    protected function validateAndPopulateWorkItem(Request $request, Model $model, Array $validationFields){
        /* Get invoice first so we can make sure the invoice is not paid.
         * Work items may not be changed on an invoice that has been paid--the paid total is final.
        */
        $invoice = Invoice::find($request->invoice_id);
        // Check if invoice is paid and return false if so
        if($invoice->is_paid) {
            // Return failure response for ajax call
            return response()->json([
                'result' => false,
                'error' => 'true',                
                'message' => "Cannot edit work on an invoice that's already been paid."
            ], 422);
        }

        /* Next, make sure to date is after from date.
         * Get unix timestamps for work item date range
        */
        $itemFrom = strtotime($request->from_date);        
        $itemTo = strtotime($request->to_date);
        // Compare unix timestamps and return error is to is before from
        if($itemTo < $itemFrom){
            // Return error response
            return response()->json([
                'result' => 'false',
                'error' => 'true',
                'message' => "'From date' must be before 'To Date'."
            ], 422);     
        } 

        /* Finally, make sure the work item date range is within the invoice date range.
         * Get unix timestamps for invoice date range
        */       
        $invoiceFrom = strtotime($invoice->from_date);        
        $invoiceTo = strtotime($invoice->to_date);
        // Compate unix timestamps and return error if the work items date range is outside the invoice's
        if($itemFrom < $invoiceFrom || $itemTo > $invoiceTo){
            // Return error response
            return response()->json([
                'result' => 'false',
                'error' => 'true',
                'message' => "The work item must date range be within the invoice date range."
            ], 422); 
        } 
        
        // Now delegate to parent method to finish validation
        return parent::validateAndPopulate($request, $model, $validationFields);       
    }

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
        // For ACL, only allows supplied roles to access this method
        $this->authorizeRoles(['admin', 'super']);

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

    public function filter($user = false, $from_date = false, $to_date = false, $invoice = false) {
        // For ACL, only allows supplied roles to access this method
        $this->authorizeRoles(['super']);

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
            if($invoice == 'not-paid') $status = 0;
            if($invoice == 'paid') $status = 1;
            // Push array clause
            array_push($queryArray, ['is_paid', '=', $status]);
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
        // For ACL, only allows supplied roles to access this method
        $this->authorizeRoles(['super']);

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
        // Validate work item
        $item = $this->validateAndPopulateWorkItem($request, new WorkItem, $this->workItemValidationFields);
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
        // Validate and populate the work item
        $item = $this->validateAndPopulateWorkItem($request, WorkItem::findOrFail($request->id), $this->workItemValidationFields);
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

        /* Get the related invoice so we can make sure that it's not yet paid.
         * Work items may not be removed from an invoice that has been paid--the paid total is final.
        */
        $invoice = Invoice::find($item->invoice_id);
        // Check if invoice is paid and return false if so
        if($invoice->is_paid) {
            // Return failure response for ajax call
            return response()->json([
                'result' => false,
                'error' => 'true',                
                'message' => "Cannot remove work from an invoice that's already been paid."
            ], 422);
        }

        /* If invoice is not paid then proceed to removing the work item
        */         
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

    /**
     * Remove an invoice from storage (Only if it has no work items)
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        // Get requested invoice
        $invoice = Invoice::with(['workItems'])->find($id);

        /* Invoice can only be delete under certain conditions. 
         * Now we check for these. 
        */
        // Check if invoice is paid and return false if so (Fail safe)
        if($invoice->is_paid) {
            // Return failure response for ajax call
            return response()->json([
                'result' => false,
                'error' => 'true',                
                'message' => "Cannot remove a paid invoice."
            ], 422);
        }

        // Check if invoice has work items and return false if so
        if(count($invoice->workItems) > 0){
            // Return failure response for ajax call
            return response()->json([
                'result' => false,
                'error' => 'true',                
                'message' => "Cannot remove an invoice with work items."
            ], 422);            
        }

        /* If invoice is blank and has not been paid then it can be deleted
        */         
        // Attempt to remove 
        $result = $invoice->delete();
        // Verify success on removal
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
