<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('client_company_name', 65);
            $table->string('client_contact_name', 65)->nullable();
            $table->string('client_contact_phone', 18)->nullable();
            $table->string('client_contact_email', 75)->nullable();
            $table->string('first_contact_by', 65)->nullable();
            $table->date('first_contact_date')->nullable();            
            $table->string('province', 20)->nullable();
            $table->string('location', 500)->nullable();
            $table->string('details', 750)->nullable();
            $table->string('land_ownership', 30)->nullable();
            $table->boolean('land_access_granted')->default(false);
            $table->string('land_access_granted_by', 65)->nullable();
            $table->string('land_access_contact', 65)->nullable();
            $table->string('land_access_phone', 14)->nullable();
            $table->string('plans', 750)->nullable();
            $table->string('work_type', 30)->nullable();
            $table->string('work_overview', 750)->nullable();
            $table->date('response_by')->nullable();
            $table->decimal('estimate', 13, 2)->default(0);
            $table->date('approval_date')->nullable(); 
            $table->date('invoiced_date')->nullable();
            $table->date('invoice_paid_date')->nullable(); 
            $table->decimal('invoice_amount', 13, 2)->default(0);           
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
