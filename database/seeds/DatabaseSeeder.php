<?php

use Illuminate\Database\Seeder;

use Faker\Generator;

use App\Project;
use App\Timeline;
use App\User;
use App\Invoice;
use App\WorkItem;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        DB::table('users')->insert([
            'first' => "Matt",
            'last' => "Shanks",
            'permissions' => 'super',
            'email' => 'matt@super.ca',
            'company_name' => "Asha'man",
            'gst_number' => 'A458EHE5',
            'password' => bcrypt(env('ADMIN_CREDENTIALS', false))
        ]);

        // Insert some fake users
        for($i = 0; $i <= 9; $i++){
            // Name
            $first = $faker->firstName;
            $last = $faker->lastName;

            // Data
            DB::table('users')->insert([
                'first' => $first,
                'last' => $last,
                'permissions' => 'user',
                'email' => $first . $last . '@gmail.com',
                'company_name' => $faker->company,
                'gst_number' => $faker->randomNumber(8),
                'password' => bcrypt('password')
            ]);
        }
        
        // Make eight random companies and store in an array
        $companies = [];
        for($i = 0; $i <= 8; $i++){
            array_push($companies, $faker->company);
        }

        // Provinices stored in an array
        $provinces = ['Alberta', 'Saskatchewan', 'British Columbia'];
        $landOwnership = ['Crown', 'Freehold'];
        $workType = ['HRIA', 'Archaeology', 'Palaeontology'];

        // Insert some fake projects
        for($i = 0; $i <= 25; $i++){

            $project = new Project;
            // Seed
            $project->client_company_name = $companies[rand(0, sizeof($companies) -1)];
            $project->province = $provinces[rand(0, sizeof($provinces) -1)];
            $project->location = $faker->city . ' ' . $faker->streetName . ' ' . $faker->latitude . ' ' . $faker->longitude;
            $project->details = $faker->text(200);
            $project->client_contact_name = $faker->name;
            $project->client_contact_phone = $faker->tollFreePhoneNumber;
            $project->client_contact_email = 'example@email.com';
            $project->first_contact_by = $faker->name;
            // Create date to base other dates off
            $baseDate = $faker->dateTimeBetween($startDate = '-2 years', $endDate = 'now', $timezone = date_default_timezone_get());
            // Seed
            $project->first_contact_date = $baseDate->format('Y-m-d');
            $project->response_by = $baseDate->modify('+ 10 days')->format('Y-m-d');
            $project->plans = $faker->text(450);
            $project->work_type = $workType[rand(0, sizeof($workType) -1)];
            $project->work_overview = $faker->text(565);
            $project->land_ownership = $landOwnership[rand(0, sizeof($landOwnership) -1)];
            $project->land_access_granted = 1;
            $project->land_access_granted_by = $faker->name;
            $project->land_access_contact = $faker->name;
            $project->land_access_phone = $faker->tollFreePhoneNumber;
            $project->estimate = rand(5000, 17500);
            $project->approval_date = $baseDate->modify('+ 18 days')->format('Y-m-d');
            $invoicedDate = [$baseDate->modify('+ 60 days')->format('Y-m-d'), null];
            $project->invoiced_date = $invoicedDate[rand(0, sizeof($invoicedDate) -1)];

            if($project->invoiced_date != null){
                // Set up possible invoice amounts
                $invoiceAmounts = [$project->estimate, $project->estimate + rand(200, 1500), $project->estimate - rand(200, 1500)];
                $project->invoice_amount = $invoiceAmounts[rand(0, sizeof($invoiceAmounts) -1)];
                // Set up invoice paid or not
                $invoicePaid = [$baseDate->modify('+ 60 days')->format('Y-m-d'), null];
                $project->invoice_paid_date = $invoicePaid[rand(0, sizeof($invoicePaid) -1)];
            }

            // Save project
            $project->save();
            $project->timeline()->save(new Timeline);

            //Create an array of numbers from which the randoms
            //should be choosen. (For raffle: the list of user id's)
            $usersArray = range(1, 10);
             
            //Initialize the random generator
            srand ((double)microtime()*1000000);
             
            //A for-loop which selects every run a different random number
            for($x = 0; $x < 5; $x++){
                 //Generate a random number
                 $y = rand(1, count($usersArray))-1;
             
                 //Take the random number as index for the array
                 $result[] = $usersArray[$y];
             
                 //The chosen number will be removed from the array
                 //so it can't be taken another time
                 array_splice($usersArray, $y, 1);
            }

            forEach($usersArray as $q){
                $user = User::find($q);  
                $project->users()->save($user);
            }           
        }

        // For invoice  dates
        $InvYear = '2017';
        $InvMonth = 1;
        $InvFromDay = '01';
        $InvToDay = '28';

        for($t = 0; $t < rand(7, 15); $t++){
            // Insert some fake invoices
            $invoice = new Invoice;
            $invoiceUserId = rand(1, 10);
            $invoice->user_id = $invoiceUserId;
            // Adjust month if needed
            if($InvMonth == 13){
                $InvMonth = 1;
                $InvYear = '2016';
            }
            // Populate years
            $invoice->from_date = $InvYear . '-' . sprintf("%02d", $InvMonth) . '-' . $InvFromDay;
            $invoice->to_date = $InvYear . '-' . sprintf("%02d", $InvMonth) . '-' . $InvToDay;
            // Publish or not
            $invoice->is_published = rand(0,1);
            $invoice->save(); 

            // Add some work items
            for($z = 0; $z < rand(1, 5); $z++){
                $item = new WorkItem;
                $item->project_id = rand(1, 25);
                $item->invoice_id = $invoice->id;
                $item->user_id = $invoiceUserId;
                // For the dates
                $itemFromDay = rand(1, 23);
                // Populate dates based on invoice date rang
                $item->from_date = $InvYear . '-' . sprintf("%02d", $InvMonth) . '-' . $itemFromDay;
                $item->to_date = $InvYear . '-' . sprintf("%02d", $InvMonth) . '-' . ($itemFromDay + rand(2, 5));
                $item->desc = $faker->text(rand(10, 25));
                $item->hours = rand(15, 65);
                $item->hourly_rate = 27.5;
                $item->per_diem = rand(75, 200);
                $item->per_diem_desc = $faker->text(rand(10, 12));
                $item->travel_mileage = rand(225, 1400);
                $item->mileage_rate = 0.65;
                $item->lodging_desc = $faker->text(rand(10, 15));
                $item->lodging_cost = rand(400, 1000);
                $item->save();
            }

            $InvMonth++;
        }         
                          
    }
}
