<?php

use Illuminate\Database\Seeder;

use Faker\Generator;

use App\Project;
use App\Timeline;

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
            'permissions' => 'admin',
            'email' => 'matt@admin.ca',
            'company_name' => "Asha'man",
            'hourly_rate_one' => 50.00,
            'gst_number' => 'A458EHE5',
            'password' => bcrypt(env('ADMIN_CREDENTIALS', false))
        ]);

        // Insert some fake users
        for($i = 0; $i <= 7; $i++){
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
                'hourly_rate_one' => $faker->numberBetween(35, 100),
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
        for($i = 0; $i <= 45; $i++){

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
            
        }
                              
    }
}
