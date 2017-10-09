<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Project;
use App\Timeline;

/** 
 * Handles project, project commment, and project crew related actions
*/
class TimelinesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    // Fields and their respective validation rules
    private $validationFields = [
        'project_id' => 'numeric',
        'permit_advised_submit' => 'date|nullable',
        'permit_applicant' => 'max:50|nullable',
        'permit_application_date' => 'date|nullable',
        'permit_recieved_date' => 'date|nullable',
        'permit_number' => 'max:50',
        'site_number_application_date' => 'date|nullable',
        'site_number_recieved_date' => 'date|nullable',
        'site_number' => 'max:50',
        'completion_target' => 'date|nullable',
        'field_completion_target' => 'date|nullable',
        'report_completion_target' => 'date|nullable',
        'fieldwork_scheduled' => 'boolean',
        'artifact_analysis' => 'boolean',
        'mapping' => 'boolean',
        'writing' => 'boolean',
        'draft_submitted' => 'boolean',
        'draft_accepted' => 'boolean',
        'final_approval' => 'boolean'
    ];

    /**
     * Save a timeline to storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JSON response
     */
    public function store(Request $request)
    {
        // For ACL, only allows supplied roles to access this method
        $this->authorizeRoles(['admin', 'super']);

        // Validate and populate the request
        $timeline = $this->validateAndPopulate($request, new Timeline, $this->validationFields);

        // Find parent project
        $project = Project::with('timeline')->findOrFail($request->project_id);

        // Attempt to store model
        $result = $project->timeline()->save($timeline);
        // Verify success on store
        if(! $result){
            // Return response for ajax call
            return response()->json([
                'result' => false,
            ], 404);
        }

        // Return response for ajax call
        return response()->json([
            'result' => 'success',
            'model' => $timeline
        ], 200);
    }

    /**
     * Updates a single field on a timeline.
     *
     * @param Illuminate\Http\Request
     * @return JSON response
    */
    public function updateField(Request $request)
    {
        // For ACL, only allows supplied roles to access this method
        $this->authorizeRoles(['admin', 'super']);
        
        // Return response for ajax call
        return $this->updateModelField(
            $request,
            Timeline::find($request->id),
            $this->validationFields
        );        
    } 

}
