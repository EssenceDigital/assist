<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\User;
use App\Project;
use App\Timesheet;

/** 
 * Handles all generic user related actions.
*/
class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    // Fields and their respective validation rules
    private $validationFields = [
        'first' => 'required|string|max:25',
        'last' => 'required|string|max:25',
        'email' => 'required|string|email|max:255|unique:users',
        'permissions' => 'required|string|max:10',
        'password' => 'required|string|min:6|confirmed',
        'company_name' => 'required|string|max:25',
        'gst_number' => 'required|string|max:25'
    ];

    /**
     * Validates request data and then adds it to a model. Helper method used by store() and update()
     *
     * @param  \Illuminate\Http\Request $request     
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
     * Find all users.
     *
     * @return JSON response
     */
    public function all()
    {
        // For ACL, only allows supplied roles to access this method
        $this->authorizeRoles(['admin', 'super']);
        // Fetch users
        $users = User::all();

        // Return response for ajax call
        return response()->json([
            'result' => 'success',
            'payload' => $users
        ], 200);        
    }

    /**
     * Find a single user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function single($id)
    {
        // For ACL, only allows supplied roles to access this method
        $this->authorizeRoles(['admin', 'super']);
        // Fetch user
        $user = User::find($id);

        // Return response for ajax call
        return response()->json([
            'result' => 'success',
            'payload' => $user
        ], 200);        
    }

    /** 
     * Finds all projects a user has been added to.
     *
     * @param Integer $user_id - ID of the user
     * @return JSON response
    */
    public function projects($user_id)
    {
        // For ACL, only allows supplied roles to access this method
        $this->authorizeRoles(['admin', 'super']);

        // Construct query to find all projects user is a part of
        $projects = Project::whereHas('users', function ($q) use ($user_id) {
            $q->where('user_id', $user_id);
        // Now, only select the timesheets that belongs to this user
        })->get();

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

    /**
     * Save a user to storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JSON response
     */
    public function store(Request $request)
    {
        // For ACL, only allows supplied roles to access this method
        $this->authorizeRoles(['super']);

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

    /** 
     * Updates a single field on a user.
     *
     * @param  \Illuminate\Http\Request $request
     * @return JSON response
    */
    public function updateField(Request $request)
    {
        // For ACL, only allows supplied roles to access this method
        $this->authorizeRoles(['super']);

        return $this->updateModelField(
            $request,
            User::find($request->id),
            $this->validationFields
        );
    }

    /**
     * Changes a users password in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JSON response
     */
    public function changePassword(Request $request)
    {
        // For ACL, only allows supplied roles to access this method
        $this->authorizeRoles(['super']);

        // Validate request
        $this->validate($request, [
            'id' => 'required|numeric',
            'password' => 'required|min:6|confirmed',
        ]);
        // Find the user to change password of in storage
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
     * Remove a user from storage.
     *
     * @param Integer $id - ID of the user to remove.
     * @return JSON response
     */
    public function delete(Request $request)
    {
        // For ACL, only allows supplied roles to access this method
        $this->authorizeRoles(['super']);
                
        // Find user to remove with all of their potential invoices
        $user = User::with(['invoices'])->find($request->id);

        /* If the user has invoices created then it cannot be remove
        */
        if(count($user->invoices) <= 0) {
            // Return response for ajax call
            return response()->json([
                'result' => false,
                'error' => true,
                'message' => 'User has invoices saved. Cannot delete.'
            ], 404); 
        }

        /* If user has no invoices created then it can now be removed.
        */
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
