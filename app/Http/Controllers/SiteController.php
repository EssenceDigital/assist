<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

/** Controls the website views
*/
class SiteController extends Controller
{
    // Returns the associated view
    public function index(){
        return view('site.index');
    }

    // Returns the associated view
    public function profile(){
        return view('site.profile');
    }

    // Returns the associated view
    public function projects(){
        return view('site.projects');
    }

    // Returns the associated view
    public function services(){
        return view('site.services');
    }    

    // Returns the associated view
    public function team(){
        return view('site.team');
    }    

}
