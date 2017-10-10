<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Project;
use App\ProjectUser;
use App\ProjectComment;
use App\Timeline;
use App\User;

use Session;

/** 
 * Handles project, project commment, and project crew related actions
*/
class ProjectsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    // Fields and their respective validation rules
    private $validationFields = [
        'client_company_name' => 'required|max:30',
        'province' => 'max:20',
        'location' => 'max:100',
        'details' => 'max:750',
        'client_contact_name' => 'max:30',
        'client_contact_phone' => 'max:14',
        'client_contact_email' => 'email',
        'first_contact_by' => 'max:30',
        'first_contact_date' => 'date|nullable',
        'response_by' => 'date|nullable',
        'plans' => 'max:750',
        'work_type' => 'max:30',
        'work_overview' => 'max:750',
        'land_ownership' => 'max:20',
        'land_access_granted' => 'boolean',
        'land_access_granted_by' => 'max:30',
        'land_access_contact' => 'max:30',
        'land_access_phone' => 'max:14',        
        'estimate' => 'numeric|between:0,1000000000000.99',
        'approval_date' => 'date|nullable',
        'invoiced_date' => 'date|nullable',
        'invoice_paid_date' => 'date|nullable',        
        'invoice_amount' => 'numeric|between:0,1000000000000.99'
    ];

    /**
     * Find all projects.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        // For ACL, only allows supplied roles to access this method
        $this->authorizeRoles(['admin', 'super']);        

        $projects = Project::with(['workItems', 'workItems.invoice'])->get();

        // Return response for ajax call
        return response()->json([
            'result' => 'success',
            'payload' => $projects
        ], 200);     
    }

    /** 
     * Filters projects based on the recieved paramaters.
     *
     * @param String $client - A client name
     * @param String $province - A full province name
     * @param String $location - Part of the location to match
     * @param String $invoice - The status of the client invoice (Method responds to: outstanding, not-invoiced, paid)
     * @param JSON response
    */
    public function filter($client = false, $province = false, $location = false, $invoice = null)
    {
        // For ACL, only allows supplied roles to access this method
        $this->authorizeRoles(['admin', 'super']);

        // Instatiate 'where array' for query
        $queryArray = [];

        // Add company field to 'where array' or not
        if($client){
            // Push array clause
            array_push($queryArray, ['client_company_name', '=', $client]);
        }
        // Add province field to 'where array' or not
        if($province){
            // Push array clause
            array_push($queryArray, ['province', '=', $province]);
        }
        // Add location field to 'where array' or not
        if($location){
            // Push array clause
            array_push($queryArray, ['location', 'LIKE', '%' . $location . '%']);
        }
        // Add invoice status to 'where array' or not
        if($invoice != 'any'){
            if($invoice == 'outstanding'){
                array_push($queryArray, ['invoice_paid_date', '=', null]);
                array_push($queryArray, ['invoiced_date', '<>', null]);
            }
            if($invoice == 'not-invoiced') {
                array_push($queryArray, ['invoiced_date', '=', null]);
            }            
            if($invoice ==  'paid'){
                array_push($queryArray, ['invoice_paid_date', '<>', null]);
            } 
        }
        
        // Now find the projects based on 'where array'
        $projects = Project::with(['workItems', 'workItems.invoice'])->where($queryArray)->get();  

        // Return response for ajax call
        return response()->json([
            'result' => 'success',
            'payload' => $projects
        ], 200);          
    }

    /** 
     * Finds all projects that the authenticated user has been added to.
     *
     * @return JSON response
    */
    public function authUsersProjects()
    {
        // Get ID of authenticated user
        $userId = Auth::id();

        // Construct query to find all projects user is a part of
        $projects = Project::whereHas('users', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->get();

        // Return failed response if collection empty
        if(!$projects){
            // Return response for ajax call
            return response()->json([
                'result' => false,
            ], 404);
        }   
        
        // Return response for ajax call
        return response()->json([
            'result' => 'success',
            'payload' => $projects
        ], 200);          
    }

    /**
     * Finds a single project.
     *
     * @param Integer $id - The primary key
     * @return JSON response
     */
    public function single($id)
    {
        // For ACL, only allows supplied roles to access this method
        $this->authorizeRoles(['admin', 'super']);

        // With all foreign keys / children
        $project = Project::with(['comments', 'comments.user', 'timeline', 'users', 'workItems', 'workItems.invoice', 'workItems.user'])
            ->find($id);

        // Return response for ajax call
        return response()->json([
            'result' => 'success',
            'payload' => $project
        ], 200);        
    }

    /**
     * Finds all unique clients from the projects table.
     *
     * @return JSON response
     */
    public function clients()
    {
        // Run query to find all unique clients from projects table
        $clients = Project::distinct()->get(['client_company_name']);

        // Format unique clients in array
        $list = [];
        foreach($clients as $client){
            array_push($list, $client->client_company_name);
        }

        // Return response for ajax call
        return response()->json([
            'result' => 'success',
            'payload' => $list
        ], 200);
    }

    /**
     * Saves a project to storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return JSON response
     */
    public function store(Request $request)
    {
        // For ACL, only allows supplied roles to access this method
        $this->authorizeRoles(['admin', 'super']);

        // Validation fields for starting a project
        $validationFields = [
            'client_company_name' => 'required|max:30'
        ];

        // Validate and populate the request
        $project = $this->validateAndPopulate($request, new Project, ['client_company_name' => 'required|max:30']);

        // Attempt to store model
        $result = $project->save();

        // Verify success on store
        if(! $result){
            // Return response for ajax call
            return response()->json([
                'result' => false
            ], 404);
        }
        // Add a timeline to the project (Needed for view to properly function)
        $timeline = new Timeline;
        // Attempt to store timeline
        $resultTwo = $project->timeline()->save($timeline);
        // Verify success on store
        if(! $resultTwo){
            // Return response for ajax call
            return response()->json([
                'result' => false,
            ], 404);
        }        

        // Load project assets, even though they're currently empty (Needed for view to properly function)
        $project->load('comments', 'comments.user', 'timeline', 'users');

        // Return response for ajax call
        return response()->json([
            'result' => 'success',
            'payload' => $project
        ], 200);
    }

    /**
     * Associates a user with a project based on the user id in the request.
     *
     * @param Illuminate\Http\Request
     * @return JSON response
    */
    public function addCrew(Request $request)
    {
        // For ACL, only allows supplied roles to access this method
        $this->authorizeRoles(['admin', 'super']);
        // Find project
        $project = Project::find($request->project_id);
        // Find the user now
        $user = User::find($request->user_id);        
        // Attempt to store association
        $result = $project->users()->save($user);
        // Verify success on store
        if(! $result){
            // Return response for ajax call
            return response()->json([
                'result' => false
            ], 404);
        } 
        // Create notification for the user
        $this->notify(
            $user->id,
            "You've been added to a project crew!",
            "This mean you can now use this project on future invoices.",
            null,
            array(
                'project_id' => $project->id,
                'project_company' => $project->client_company_name 
            )
        );

        // Return response for ajax call
        return response()->json([
            'result' => 'success',
            'payload' => $user
        ], 200);               
    }

    /**
     * Unassociates a user with a project based on the id in the request.
     *
     * @param Integer $project_id - The ID of the project
     * @param Integer $id - The ID of the user to remove from project
     * @return JSON response
    */
    public function deleteCrew($project_id, $id)
    {
        // For ACL, only allows supplied roles to access this method
        $this->authorizeRoles(['admin', 'super']);

        // Find the association
        $project = Project::with(['workItems'])->find($project_id);

        /* Check to see if the crew member (user) has any work items associated with this project and
         * return false if so. A user cannot be removed if they have work items on the project
        */
        forEach($project->workItems as $item){
            if($item->user_id == $id){
                // Return error response
                return response()->json([
                    'result' => 'false',
                    'error' => 'true',
                    'message' => "This crew member has work added to this project and may not be removed."
                ], 422);                   
            }
        }

        /* If the user does not have any work items we can detach the association with the project
        */
        $result = $project->users()->detach($id);
        // Verify success
        if(! $result){
            // Return response for ajax call
            return response()->json([
                'result' => false
            ], 422);
        }

        // Return successful response for ajax call
        return response()->json([
            'result' => 'success',
            'payload' => $id
        ], 200);
    }

    /**
     * Adds a ProjectComment to storage.
     *
     * @param Illuminate\Http\Request
     * @return JSON response
    */
    public function addComment(Request $request)
    {
        // For ACL, only allows supplied roles to access this method
        $this->authorizeRoles(['admin', 'super']);

        // Validate request
        $this->validate($request, [
            'project_id' => 'required|numeric',
            'user_id' => 'required|numeric',
            'comment' => 'required|max:255'
        ]);

        // Assemble the comment
        $comment = new ProjectComment;
        $comment->comment = $request->comment;
        $comment->user_id = $request->user_id;
        $comment->project_id = $request->project_id;

        // Attempt to store model
        $result = $comment->save();
        // Verify success on store
        if(! $result){
            // Return response for ajax call
            return response()->json([
                'result' => false
            ], 404);
        }

        // Add the user to the comment before returning response
        $comment->load('user');

        // Return response for ajax call
        return response()->json([
            'result' => 'success',
            'payload' => $comment
        ], 200);

    }

    /**
     * Removes a ProjectComment from storage.
     *
     * @param Integer $id - The id of the comment to remove.
     * @return JSON response
    */
    public function deleteComment($id)
    {
        // For ACL, only allows supplied roles to access this method
        $this->authorizeRoles(['admin', 'super']);
        // Find comment
        $comment = ProjectComment::find($id);
        // Attempt to remove 
        $result = $comment->delete();
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
            'payload' => $id
        ], 200);        
    }


    /**
     * Updates a single field on a project.
     *
     * @param Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function updateField(Request $request)
    {
        // For ACL, only allows supplied roles to access this method
        $this->authorizeRoles(['admin', 'super']);

        // Use the parent method to update
        return $this->updateModelField(
            $request,
            Project::with(['comments', 'comments.user', 'timeline', 'users', 'workItems', 'workItems.invoice'])->find($request->id),
            $this->validationFields
        );        
    }

    /**
     * Removes a project from storage.
     *
     * @param Illuminate\Http\Request
     * @return JSON response
     */
    public function delete(Request $request)
    {
        // For ACL, only allows supplied roles to access this method
        $this->authorizeRoles(['admin', 'super']);

        // With all foreign keys / children
        $project = Project::with(['comments', 'comments.user', 'timeline', 'users', 'invoices'])->find($request->id);

        if(count($project->users) <= 0 || count($project->workItems) <= 0){
            // Return response for ajax call
            return response()->json([
                'result' => false,
                'message' => 'Project has crew assigned or invoices present. Cannot remove!'
            ], 404);  

        } else {
            // Attempt to remove 
            $result = $project->delete();
            // Verify success on store
            if(! $result){
                // Return response for ajax call
                return response()->json([
                    'result' => false
                ], 404);
            }

            // Return successful response for ajax call
            return response()->json([
                'result' => 'success'
            ], 200);
        }
    }

}
