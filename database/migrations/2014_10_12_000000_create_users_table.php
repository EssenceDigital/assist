<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first', 25);
            $table->string('last', 25);
            $table->string('permissions', 10);
            $table->string('email')->unique();
            $table->string('password');
            $table->string('company_name');
            $table->decimal('hourly_rate_one', 13, 2);
            $table->decimal('hourly_rate_two', 13, 2)->nullable();
            $table->string('gst_number', 25);
            $table->boolean('change_password')->default(true);
            $table->softDeletes();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
