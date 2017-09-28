<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_items', function (Blueprint $table) {
            $table->increments('id');
            // Foreign key for project
            $table->integer('project_id')->unsigned();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            // Foreign key for invoice
            $table->integer('invoice_id')->unsigned();
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');   
            // Foreign key for user
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');                                   
            // Basic fields
            $table->date('from_date');
            $table->date('to_date');            
            $table->string('desc', 255)->nullable();
            $table->decimal('hours', 13, 2)->default(0);
            $table->decimal('hourly_rate', 13, 2);
            $table->decimal('per_diem', 13, 2)->default(0);
            $table->string('per_diem_desc', 45)->nullable();
            // Travel fields
            $table->integer('travel_mileage');
            $table->decimal('mileage_rate', 13, 2);
            // Lodging cost fields
            $table->string('lodging_desc', 75)->nullable();
            $table->decimal('lodging_cost', 13, 2)->nullable(); 
            // Equipment cost fields
            $table->string('equipment_desc', 75)->nullable();
            $table->decimal('equipment_cost', 13, 2)->nullable();
            // Timestamps                      
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
        Schema::drop('work_items');
    }
}
