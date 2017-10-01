<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

use App\Notification;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Validates request data and then adds it to a model. Helper method used by store() and update()
     * @param Illuminate\Http\Request
     * @param App\Project (model)
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

    protected function updateModelField(Request $request, Model $model, Array $validationFields){
        // Ensure request field is actually a project field
        if(array_key_exists($request->field, $validationFields)){
            // Validate field
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

    protected function storeInvoiceAsset(Request $request, $model, $validationFields) {
        // Validate and populate the request
        $asset = $this->validateAndPopulate($request, $model, $validationFields);
        // Attempt to store model
        $result = $asset->save();
        // Verify success on store
        if(! $result){
            // Return response for ajax call
            return response()->json([
                'result' => false
            ], 404);
        }  

        return $asset;      
    }

    protected function updateInvoiceAsset(Request $request, $model, $validationFields) {
        // Validate and populate the request
        $asset = $this->validateAndPopulate($request, $model, $validationFields);
        // Attempt to store model
        $result = $asset->save();
        // Verify success on store
        if(! $result){
            // Return response for ajax call
            return response()->json([
                'result' => false
            ], 404);
        }

        return $asset;        
    }

    protected function deleteInvoiceAsset($model) {      
        // Attempt to remove 
        $result = $model->delete();
        // Verify success on store
        if(! $result){
            // Return response for ajax call
            return response()->json([
                'result' => false
            ], 404);
        }   

        return;    
    }

    protected function notify($userId, $title, $desc, $link = null) {
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

        return;
    }



}
