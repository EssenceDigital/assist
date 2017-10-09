<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\User;
use App\Project;
use App\Timesheet;

use Hash;

/** 
 * Handles some miscelaneous dashboard actions like user settings and personal password changing.
*/
class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Returns the central dashboard view
    public function index(){
        return view('app');
    }

    /**
     * Retrieves the logged in user from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLoggedInUser()
    {
        // Get logged in user
        $user = User::find(Auth::id());
        // Return failed response if collection empty
        if(! $user){
            // Return response for ajax call
            return response()->json([
                'result' => false,
            ], 404);
        }

        // Return response for ajax call
        return response()->json([
            'result' => 'success',
            'model' => $user
        ], 200);        
    }

    /** 
     * Gets all of the projects the authenticated user is a part of.
     *
     * @return JSON response
    */
    public function usersProjects()
    {
        // User id
        $userId = Auth::id();
        // Construct query to find all projects user is a part of
        $projects = Project::whereHas('users', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        // Now, only select the timesheets that belongs to this user
        })->with(['invoices' => function($q) use ($userId)  {
            $q->where('user_id', $userId);
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
            'models' => $projects
        ], 200);           
    }

    /**
     * Changes the logged in users personal password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JSON response
     */
    public function changePersonalPassword(Request $request){
        // Validate request
        $this->validate($request, [
        	'current_password' => 'required|string',
	        'password' => 'required|string|min:6|confirmed'
        ]);

        // Find logged in user
        $user = User::find(Auth::id());
        // Return failed response if collection empty
        if(! $user){
            // Return response for ajax call
            return response()->json([
                'result' => false
            ], 404);
        }

        /* Confirm the correct current password
        */
        if(! Hash::check($request->current_password, $user->password)){
            // Return response for ajax call
            return response()->json([
				'current_password' => ['Current password is incorrect']
            ], 422);
        }

        /* If old password was correct then Bcrypt new password and add to model
        */
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

}
