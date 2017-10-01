<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            // Foreign key for user
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // Fields
            $table->string('title', 100);   
            $table->string('desc', 100);
            $table->string('link', 100)->nullable();
            // Optional
            $table->integer('project_id')->unsigned()->nullable();
            $table->string('project_company')->nullable();
            $table->integer('invoice_id')->nullable();
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
        Schema::drop('notifications');
    }
}
