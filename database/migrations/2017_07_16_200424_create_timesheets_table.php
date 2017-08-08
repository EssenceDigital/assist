<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimesheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timesheets', function (Blueprint $table) {
            $table->increments('id');
            // Foreign key for project
            $table->integer('project_id')->unsigned();
            $table->foreign('project_id')->references('id')->on('projects');
            // Foreign key for user
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');   
            // Fields
            $table->date('date');    
            $table->decimal('per_diem', 13, 2)->default(0); 
            $table->string('comment', 255)->nullable(); 
            $table->boolean('is_approved')->default(false);
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
        Schema::drop('timesheets');
    }
}
