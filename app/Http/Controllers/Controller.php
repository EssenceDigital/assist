<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

use App\Notification;

/** 
 * Base controller that contains some helper methods which can be used by controllers that extend this class.
*/
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Validates request data and then adds it to a model. Helper method used by store() and update().
     *
     * @param Illuminate\Http\Request $request
     * @param Illuminate\Database\Eloquent\Model $model   
     * @param Array - Formatted in a way that the validate method accepts $validationFields
     * @return App\Model
     */
    protected function validateAndPopulate(Request $request, Model $model, Array $validationFields)
    {
        // Validate or stop proccessing :)
        $this->validate($request, $validationFields);

        // Add request data to model
        foreach($validationFields as $key => $val){
            $model->$key = $request->$key;
        }

        return $model;
    }

    /**
     * Updates a single field on a model.
     *
     * Validates request data and then adds it to a model. Helper method used by store() and update()
     * @param Illuminate\Http\Request $request
     * @param Illuminate\Database\Eloquent\Model $model     
     * @param Array - Formatted in a way that the validate method accepts $validationFields  
     * @return App\Model
     */
    protected function updateModelField(Request $request, Model $model, Array $validationFields)
    {
        // Ensure request field is actually a model field
        if(array_key_exists($request->field, $validationFields)){
            // Validate field, constructing the validation array so that it only validates
            // the field we want to update and not the whole set of fields.
            $this->validate($request, [
                // Dynamically validate proper field
                $request->field => $validationFields[$request->field],
                'id' => 'required|integer'
            ]);

            // Return failed response if collection empty
            if(! $model){
                // Return response for ajax call
                return response()->json([
                    'result' => false
                ], 404);
            }  

            // Update model with new field data
            $model[$request->field] = $request[$request->field];
            // Attempt to store model
            $result = $model->save();
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
                'payload' => $model
            ], 200);      
        }
    }

    /**
     * Saves an entry to the notifactions table
     *
     * @param Integer $userId - The user the notification is for
     * @param String $title - The tile of the notification
     * @param String $desc - The description for the notification
     * @param String $link - A link for the 
     * @return Boolean
    */
    protected function notify($userId, $title, $desc, $link = null) 
    {
        // Create notification
        $notif = new Notification;
        // Add fields
        $notif->user_id = $userId;
        $notif->title = $title;
        $notif->desc = $desc;      
        $notif->link = $link;
        // Save notification
        $result = $notif->save();

        // Verify success on store
        if(! $result){
            // Return response for ajax call
            return response()->json([
                'result' => false
            ], 404);
        }

        return true;
    }


    /* ****************************************************
     * For User access control.
     * ****************************************************
    */

    /** 
     * Top level check. Ensures the user has the required role to access calling resource
     *
     * @param Array or String - The allowed roles
     * @return boolean
    */
    public function authorizeRoles($roles)
    {
        // Check if the supplied roles exist in role set
        if($this->hasAnyRole($roles)) {
            return true;
        } 
        // Not authorized
        else {
            return response()->json([
                'result' => 'false',
                'error' => 'true',
                'message' => "Not authorized."
            ], 401)->send(); // MUST send() to return full response
        }         
    }

    /** 
     * Checks all supplied roles to determine if they are allowed access
     *
     * @param Array or String - The allowed roles
     * @return Boolean
    */
    public function hasAnyRole($roles)
    {
        // If supplied roles is an array
        if(is_array($roles)) {
            // Itterate through supplied roles
            foreach($roles as $role) {
                // Check if the users permissions matches the supplied role or not
                if(auth()->user()->permissions === $role) {
                    // If matched...
                    return true;
                }
            }
        } 
        // Return false for all other conditions
        return false;
    }

}
