<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimelinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timelines', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->unsigned();
            $table->foreign('project_id')->references('id')->on('projects');
            $table->date('permit_application_date')->nullable();
            $table->boolean('permit_recieved')->default(false);
            $table->date('permit_recieved_date')->nullable();
            $table->string('permit_number', 50)->nullable();
            $table->date('site_number_application_date')->nullable();
            $table->date('site_number_recieved_date')->nullable();
            $table->string('site_number', 50)->nullable();
            $table->date('completion_target')->nullable();
            $table->date('field_completion_target')->nullable();
            $table->date('report_completion_target')->nullable();
            $table->boolean('fieldwork_scheduled')->default(false);
            $table->boolean('artifact_analysis')->default(false);
            $table->boolean('mapping')->default(false);
            $table->boolean('writing')->default(false);
            $table->boolean('draft_submitted')->default(false);
            $table->boolean('draft_accepted')->default(false);
            $table->boolean('final_approval')->default(false);
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
        Schema::dropIfExists('timelines');
    }
}
