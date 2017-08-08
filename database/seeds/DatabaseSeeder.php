<?php

use Illuminate\Database\Seeder;

use Faker\Generator;

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
        $provinces = ['Alberta', 'Saskatchewan', 'British Colombia'];

        // Insert some fake companies
        for($i = 0; $i <= 40; $i++){
            // Data
            DB::table('projects')->insert([
                'province' => $provinces[rand(0, sizeof($provinces) -1)],
                'location' => $faker->city . ' ' . $faker->streetName . ' ' . $faker->latitude . ' ' . $faker->longitude,
                'details' => $faker->text(200),
                'client_company_name' => $companies[rand(0, sizeof($companies) -1)],
                'client_contact_name' => $faker->name,
                'client_contact_phone' => $faker->tollFreePhoneNumber,
                'first_contact_by' => $faker->name
            ]);             
        }
                              
    }
}
