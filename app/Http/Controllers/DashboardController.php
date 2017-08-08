<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\User;
use App\Project;
use App\Timesheet;

use Hash;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Returns the associated view
    public function index(){
        return view('app.dashboard');
    }

    // Returns the associated view
    public function userSettings(){
        return view('app.user-settings');
    }

    /**
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

    public function usersProjects(){
        // User id
        $userId = Auth::id();
        // Construct query to find all projects user is a part of
        $projects = Project::whereHas('users', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        // Now, only select the timesheets that belongs to this user
        })->with(['timesheets' => function($q) use ($userId)  {
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

    public function projectTimesheets($projectId){
        // Get the users timesheets for the requested project
        $timesheets = Timesheet::where([
            ['user_id', '=', Auth::id()],
            ['project_id', '=', $projectId],
        ])->with(['workJobs', 'travelJobs', 'equipmentRentals', 'otherCosts'])->orderBy('date', 'desc')->get();

        // Return failed response if collection empty
        if(! $timesheets){
            // Return response for ajax call
            return response()->json([
                'result' => false,
            ], 404);
        }   

        // Return response for ajax call
        return response()->json([
            'result' => 'success',
            'models' => $timesheets
        ], 200);                  
    }

    /**
     * Updates the logged in users basic info.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateUserInfo(Request $request){
        // Validate or stop proccessing :)
        $this->validate($request, [
	        'email' => 'required|string|email|max:255|unique:users' . ',id,' . Auth::id(),
	        'company_name' => 'required|string|max:25',
	        'gst_number' => 'required|string|max:25'
        ]);

        // Find the logged in user
        $user = User::find(Auth::id());

        // Return failed response if collection empty
        if(! $user){
            // Return response for ajax call
            return response()->json([
                'result' => false,
            ], 404);
        }

        // Populate model with new data
        $user->email = $request->email;
        $user->company_name = $request->company_name;
        $user->gst_number = $request->gst_number;

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
     * Changes the logged in users personal password
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changePersonalPassword(Request $request){
        // Validate or stop proccessing :)
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

        // Confirm the correct current password
        if(! Hash::check($request->current_password, $user->password)){
            // Return response for ajax call
            return response()->json([
				'current_password' => ['Current password is incorrect']
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

}
