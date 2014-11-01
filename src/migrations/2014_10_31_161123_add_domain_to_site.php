<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDomainToSite extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Add domain to the multisite table
		Schema::table('multisite', function ($table) {
			$table->string('domain', 255)->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// Drop the domain column from the multisite table
		Schema::table('multisite', function ($table) {
			$table->dropColumn('domain');
		});
	}
}
