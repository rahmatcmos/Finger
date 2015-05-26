<?php namespace ThunderID\Finger\seeds;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use ThunderID\Finger\Models\FingerPrint;
use ThunderID\Person\Models\Person;
use ThunderID\Organisation\Models\Branch;
use \Faker\Factory, Illuminate\Support\Facades\DB;

class FingerPrintTableSeeder extends Seeder
{
	function run()
	{

		DB::table('finger_prints')->truncate();
		try
		{

		}
		catch (Exception $e) 
		{
    		echo 'Caught exception: ',  $e->getMessage(), "\n";
		}	
	}
}