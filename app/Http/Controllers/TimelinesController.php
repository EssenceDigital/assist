<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Project;
use App\Timeline;

class TimelinesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    // Fields and their respective validation rules
    private $validationFields = [
        'project_id' => 'numeric',
        'permit_application_date' => 'required|date|nullable',
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
     * Updates a single field on a timeline
     *
     * @param Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function updateField(Request $request){
        return $this->updateModelField(
            $request,
            Timeline::find($request->id),
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
        $timeline = Timeline::find($request->id);

        // Return failed response if collection empty
        if(! $timeline){
            // Return response for ajax call
            return response()->json([
                'result' => false
            ], 404);
        }

        // Validate and populate the request
        $timeline = $this->validateAndPopulate($request, $timeline);

        // Attempt to store model
        $result = $timeline->save();

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
            'model' => $timeline
        ], 200);

    } 

}
