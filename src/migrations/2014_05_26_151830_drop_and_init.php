<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropAndInit extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Drop all the tables in the current database
		//TODO

		// Add the configuration table necessary for multisite functionality
		Schema::create('multisite', function($table){

			$table->increments('id');
			$table->string('driver', 255);
			$table->string('database', 255);
			$table->string('prefix', 255)->nullable();
			$table->string('username', 255);
			$table->string('password', 255)->nullable();
			$table->string('sitename', 255);
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
		// Drop the table
		Schema::drop('multisite');
	}
}
