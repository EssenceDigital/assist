<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\User;
use App\Project;
use App\Timesheet;

class UsersController extends Controller
{

    /*public function __construct()
    {
        $this->middleware('auth');
    }*/

    // Fields and their respective validation rules
    private $validationFields = [
        'first' => 'required|string|max:25',
        'last' => 'required|string|max:25',
        'email' => 'required|string|email|max:255|unique:users',
        'permissions' => 'required|string|max:10',
        'password' => 'required|string|min:6|confirmed',
        'company_name' => 'required|string|max:25',
        'hourly_rate_one' => 'required|numeric|between:0,1000000000000.99',
        'gst_number' => 'required|string|max:25'
    ];

    /**
     * Validates request data and then adds it to a model. Helper method used by store() and update()
     *
     * @return App\Model
     */
    private function validateUser(Request $request, User $user)
    {
        // Make sure hourly rate two is an integer
    	if($request->hourly_rate_two == null){
    		$request->hourly_rate_two = 0;
    	}

        // Ends up looking like this->validationFields
        $fields = [];
        // Populate rules[], but skip the password field
        foreach($this->validationFields as $key => $val){
            // If it's an edit then skip the password field
            if($user->id && $key == 'password') continue;
            // Populate array for validation
            $fields[$key] = $val;
            // Append the id to the email field to ensure 'unique' validator works properly for an update
            if($user->id && $key == 'email') $fields[$key] =  $fields[$key] . ',id,' . $user->id;
        }
       
        // Validate or stop proccessing :)
        $this->validate($request, $fields);

        // Add request data to model
        foreach($this->validationFields as $key => $val){
            // If it's an edit then skip the password field
            if($user->id && $key == 'password'){
                continue;
            }
            // Bcrypt if it's the password field and creating
            if($key == 'password'){
                $user->password = bcrypt($val);
            } else {
                // Add regular fields to model
                $user->$key = $request->$key;
            }
            
        }

        return $user;
    }

    /**
     * Find a user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        $users = User::all();

        // Return response for ajax call
        return response()->json([
            'result' => 'success',
            'payload' => $users
        ], 200);        
    }

    /**
     * Find a user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function single($id)
    {
        $user = User::find($id);

        // Return response for ajax call
        return response()->json([
            'result' => 'success',
            'payload' => $user
        ], 200);        
    }

    public function projects($user_id){

        // Construct query to find all projects user is a part of
        $projects = Project::whereHas('users', function ($q) use ($user_id) {
            $q->where('user_id', $user_id);
        // Now, only select the timesheets that belongs to this user
        })->with(['timesheets' => function($q) use ($user_id)  {
            $q->where('user_id', $user_id);
        }])->get();

        // Return failed response if collection empty
        if(! $projects){
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

    public function projectTimesheets($user_id, $project_id){

        // Get the users timesheets for the requested project
        $timesheets = Timesheet::where([
            ['user_id', '=', $user_id],
            ['project_id', '=', $project_id],
        ])->with(['workJobs', 'travelJobs', 'equipmentRentals', 'otherCosts'])->orderBy('date', 'desc')->get();

        // Return failed response if collection empty
        if(! $timesheets){
            // Return response for ajax call
            return response()->json([
                'result' => false,
            ], 404);
        }
        // Tally timesheet totals   
        $talliedTimesheets = $this->tallyTimesheets($timesheets);

        // Return response for ajax call
        return response()->json([
            'result' => 'success',
            'payload' => $talliedTimesheets
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
        $user = $this->validateUser($request, new User);

        // Attempt to store model
        $result = $user->save();
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
            'payload' => $user
        ], 200);

    }

    public function updateField(Request $request){

        return $this->updateModelField(
            $request,
            User::find($request->id),
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
        $user = User::find($request->id);

        // Return failed response if collection empty
        if(! $user){
            // Return response for ajax call
            return response()->json([
                'result' => false
            ], 404);
        }

        // Validate and populate the request
        $user = $this->validateUser($request, $user);

        // Attempt to store model
        $result = $user->save();

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
     * Changes a users password in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request)
    {
        // Validate or stop proccessing :)
        $this->validate($request, [
            'id' => 'required|numeric',
            'password' => 'required|min:6|confirmed',
        ]);
        // Find user in storage
        $user = User::find($request->id);
        // Return failed response if collection empty
        if(! $user){
            // Return response for ajax call
            return response()->json([
                'result' => false
            ], 404);
        }
        // Bcrypt password and add to model
        $user->password = bcrypt($request->password);
        // Attempt to save user
        $result = $user->save();
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        // Find user or throw 404 :)
        $user = User::findOrFail($request->id);

        // Attempt to remove 
        $result = $user->delete();
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
