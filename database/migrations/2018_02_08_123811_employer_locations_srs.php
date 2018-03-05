<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EmployerLocationsSrs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employer_locations', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('employer_id')->nullable();
			$table->integer('employer_locations')->nullable();
			$table->string('location_name', 255)->nullable();
			$table->string('latitude', 100)->nullable();
			$table->string('longitude', 100)->nullable();
			$table->text('location_address')->nullable();
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
        //
    }
}
