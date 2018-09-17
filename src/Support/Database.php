<?php

namespace HyveMobileTest\Support;

use Illuminate\Database\Capsule\Manager as Capsule;

class Database
{

	 /**
     * Database Wrapper.
     *
     * When instantiated, it will check if the required table exists and create it if not
     */
	public function __construct()
	{
		if (!$this->doesTableExist('contacts')) {
			$this->setUpRequiredTable();	
		}
	}

	/** Inserts data into a table
	 * @param  String $tableName Name of table
	 * @param  array $data Data to be inserted
	 * @return bool true/false
	 */
	public function insert($tableName, array $data)
	{
		return Capsule::table($tableName)->insert($data);
	}

	/** Checks if table exists
	 * @param  [String] $tableName [Name of table] 
	 * @return [bool] true/false
	 */
	private function doesTableExist($tableName)
	{
		return Capsule::schema()->hasTable($tableName);
	}

	/**
	 * Sets up required table
	 */
	private function setUpRequiredTable()
	{
		Capsule::schema()->create('contacts', function ($table) {
			$table->engine = 'InnoDB';
			$table->charset = 'utf8mb4';
			$table->collation = 'utf8mb4_unicode_ci';
		    $table->increments('id');
		    $table->string('title', 9);
		    $table->string('first_name', 32);
		    $table->string('last_name', 32);
		    $table->string('email', 64)->unique();
		    $table->string('domain_ip', 32)->nullable();
		    $table->string('tz', 32);
		    $table->timestamp('transaction_time');
		    $table->timestamp('local_transaction_time');
		    $table->string('contact_card')->nullable();
		    $table->text('note')->nullable();
		    $table->timestamp('created_at')->default(Capsule::raw('CURRENT_TIMESTAMP'));
		});
	}
}