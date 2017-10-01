<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Notification;

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
}
