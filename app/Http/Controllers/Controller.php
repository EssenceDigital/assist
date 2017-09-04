<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;


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

    protected function tallyTimesheets($timesheets){
        $talliedTimesheets = [];

        forEach($timesheets as $timesheet){
            // Push tallied timesheet to array
            array_push($talliedTimesheets, $this->tallyTimesheet($timesheet));
       }

       return $talliedTimesheets;
    }

    protected function tallyTimesheet($timesheet){
        // Total hours on timesheet
        $totalHours = 0;
        // Calculate total hours
        forEach($timesheet->workJobs as $job){
            $totalHours += $job->hours_worked;
        }
        // Add total hours to timesheet
        $timesheet->total_hours = number_format($totalHours, 2, '.', '');
        // Total hour pay
        $totalHourPay = $totalHours * $timesheet->user->hourly_rate_one;
        // Add total pay to timesheet (rounding up and showing 2 decimal places)
        $timesheet->total_hours_pay = number_format(round($totalHourPay, 2, PHP_ROUND_HALF_UP), 2, '.', '');
        // Total travel distance on timesheet
        $totalTravel = 0;
        forEach($timesheet->travelJobs as $job){
            $totalTravel += $job->travel_distance;
        }
        // Add total travel to timesheet
        $timesheet->total_travel_distance = round($totalTravel, 0, PHP_ROUND_HALF_UP);
        // Total equipment rentals on timesheet
        $totalEquipment = 0;
        forEach($timesheet->equipmentRentals as $rental){
            $totalEquipment += $rental->rental_fee;
        }
        // Add total equipment rentals to timesheet (rounding up and showing 2 decimal places)
        $timesheet->total_equipment_cost = number_format(round($totalEquipment, 2, PHP_ROUND_HALF_UP), 2, '.', '');
        // Total other costs on timesheet
        $totalOther = 0;
        forEach($timesheet->otherCosts as $cost){
            $totalOther += $cost->cost;
        }
        // Add total other costs to timesheet (rounding up and showing 2 decimal places)
        $timesheet->total_other_costs = number_format(round($totalOther, 2, PHP_ROUND_HALF_UP), 2, '.', '');
        // Tally total of everything on timesheet
        $timesheetTotal = number_format(round($totalHourPay, 2, PHP_ROUND_HALF_UP), 2, '.', '') +
            number_format(round($totalEquipment, 2, PHP_ROUND_HALF_UP), 2, '.', '') +
            number_format(round($totalOther, 2, PHP_ROUND_HALF_UP), 2, '.', '') +
            $timesheet->per_diem;
        // Add timesheet total to timesheet
        $timesheet->timesheet_total = number_format(round($timesheetTotal, 2, PHP_ROUND_HALF_UP), 2, '.', '');

        return $timesheet;
    }





}
