<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdditionalParameters extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Add the additional necessary parameters for database configuration
		Schema::table('multisite', function($table){
			$table->string('host', 255);
			$table->string('collation', 255);
			$table->string('charset', 255);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// Drop the added parameters
		Schema::table('multisite', function($table){
			$table->dropColumn('host');
			$table->dropColumn('collation');
			$table->dropColumn('charset');
		});
	}
}
