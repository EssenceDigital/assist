<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Project;
use App\ProjectUser;
use App\ProjectComment;
use App\User;

use Session;

class ProjectsController extends Controller
{

    /*public function __construct()
    {
        $this->middleware('auth');
    }*/

    // Fields and their respective validation rules
    private $validationFields = [
        'client_company_name' => 'required|max:30',
        'province' => 'max:20',
        'location' => 'max:100',
        'details' => 'max:750',
        'client_contact_name' => 'max:30',
        'client_contact_phone' => 'max:14',
        'client_contact_email' => 'email|nullable',
        'first_contact_by' => 'max:30',
        'first_contact_date' => 'date|nullable',
        'land_ownership' => 'max:20',
        'land_access_granted' => 'boolean',
        'land_access_granted_by' => 'max:30',
        'land_access_contact' => 'max:30',
        'land_access_phone' => 'max:14',
        'plans' => 'max:750',
        'work_type' => 'max:30',
        'work_overview' => 'max:750',
        'response_by' => 'date|nullable',
        'estimate' => 'numeric|between:0,1000000000000.99',
        'approval_date' => 'date|nullable',
        'invoiced_date' => 'date|nullable',
        'invoice_paid_date' => 'date|nullable',        
        'invoice_amount' => 'numeric|between:0,1000000000000.99'

    ];

    /**
     * Find all projects and returns a paginated result
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        $projects = Project::all();

        // Return response for ajax call
        return response()->json([
            'result' => 'success',
            'payload' => $projects
        ], 200);     
    }

    public function filter($client = false, $province = false, $location = false, $invoice = null){
        // Construct where array for query
        $queryArray = [];
        // Add company field or not
        if($client){
            // Push array clause
            array_push($queryArray, ['client_company_name', '=', $client]);
        }
        // Add province field or not
        if($province){
            // Push array clause
            array_push($queryArray, ['province', '=', $province]);
        }
        // Add location field or not
        if($location){
            // Push array clause
            array_push($queryArray, ['location', 'LIKE', '%' . $location . '%']);
        }

        if($invoice != 'any'){
            if($invoice == 0){
                array_push($queryArray, ['invoice_paid_date', '=', null]);
            }
            if($invoice ==  1){
                array_push($queryArray, ['invoice_paid_date', '<>', null]);
            } 
        }
   



       $projects = Project::where($queryArray)->get();  

        // Return response for ajax call
        return response()->json([
            'result' => 'success',
            'payload' => $projects
        ], 200);          
    }

    /**
     * Find a project
     *
     * @param Int - The primary key
     * @return \Illuminate\Http\JsonResponse
     */
    public function single($id)
    {
        // With all foreign keys / children
        $project = Project::with(['comments', 'comments.user', 'timeline', 'users', 'timesheets'])->find($id);

        // Return response for ajax call
        return response()->json([
            'result' => 'success',
            'payload' => $project
        ], 200);        
    }

    /**
     * Find unique clients from all projects
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function clients()
    {
        // Run query to find unique clients from projects
        $clients = Project::distinct()->get(['client_company_name']);

        // Format unique companies in array
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validationFields = [
            'client_company_name' => 'required|max:30'
        ];

        // Validate and populate the request
        $project = $this->validateAndPopulate($request, new Project, $validationFields);

        // Attempt to store model
        $result = $project->save();

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
            'payload' => $project
        ], 200);

    }

    /**
     * Associates a user with a project based on the id in the request
     *
     * @param Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function addCrewMember(Request $request){
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
        
        // Return response for ajax call
        return response()->json([
            'result' => 'success',
            'model' => $user
        ], 200);               
    }

    /**
     * Unassociates a user with a project based on the id in the request
     *
     * @param Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function removeCrewMember(Request $request){
        // Find the association
        $project = Project::find($request->project_id);
        // Attempt to detach
        $result = $project->users()->detach($request->user_id);
        // Verify success
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

    /**
     * Adds a ProjectComment to storage
     *
     * @param Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function addComment(Request $request){
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

        // Add the user to the comment model before returning response
        // Get the user model
        $user = User::find($request->user_id);
        // Return failed response if no user
        if(! $user){
            // Return response for ajax call
            return response()->json([
                'result' => false
            ], 404);
        }
        // Add user to comment model
        $comment->user = $user;

        // Return response for ajax call
        return response()->json([
            'result' => 'success',
            'model' => $comment
        ], 200);

    }

    /**
     * Removes a ProjectComment from storage.
     *
     * @param Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function removeComment(Request $request){
        // Find or throw 404
        $comment = ProjectComment::findOrFail($request->comment_id);

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
            'result' => 'success'
        ], 200);        
    }


    /**
     * Updates a single field on a project
     *
     * @param Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function updateField(Request $request){
        // Use the parent method to update
        return $this->updateModelField(
            $request,
            Project::with(['comments', 'comments.user', 'timeline', 'users'])->find($request->id),
            $this->validationFields
        );        
    }

    /**
     * Update a resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {   
        $project = Project::with('proposal')->find($request->id);

        // Return failed response if collection empty
        if(! $project){
            // Return response for ajax call
            return response()->json([
                'result' => false
            ], 404);
        }

        // Validate and populate the request
        $project = $this->validateAndPopulate($request, $project, $this->validationFields);

        // Attempt to store model
        $result = $project->save();

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
            'model' => $project->toArray()
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
        // With all foreign keys / children
        $project = Project::with(['comments', 'comments.user', 'timeline', 'users', 'timesheets'])->find($request->id);

        if(count($project->users) > 0 || count($project->timesheets) > 0){
            // Return response for ajax call
            return response()->json([
                'result' => false,
                'message' => 'Project has crew assigned or timesheets present. Cannot remove!',
                'data' => $project->users
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
