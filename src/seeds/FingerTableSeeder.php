<?php namespace ThunderID\Finger\seeds;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use ThunderID\Finger\Models\Finger;
use ThunderID\Person\Models\Person;
use ThunderID\Organisation\Models\Chart;
use \Faker\Factory, Illuminate\Support\Facades\DB;

class FingerTableSeeder extends Seeder
{
	function run()
	{

		DB::table('fingers')->truncate();

		try
		{
			foreach(range(1, 1) as $index)
			{
				
			} 

		}
		catch (Exception $e) 
		{
    		echo 'Caught exception: ',  $e->getMessage(), "\n";
		}	
	}
}