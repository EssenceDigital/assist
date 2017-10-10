<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Notification;

/** 
 * Controls all notification based actions.
*/
class NotificationsController extends Controller
{
	/**
	 * Gets the logged in users current notifications
	*/
	public function get(){
		// Find all current notifications for this user
		$notif = Notification::where('user_id', '=', Auth::id())->get();

        // Return response for ajax call
        return response()->json([
            'result' => 'success',
            'payload' => $notif
        ], 200); 		
	}

    /**
     * Removes a notification froms torage.
     *
     * @param Illuminate\Http\Request
     * @return JSON response
     */
    public function delete($id)
    {	
    	// Find notification
    	$notif = Notification::find($id);
    	// Attempt to remove
        $result = $notif->delete();
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

}
