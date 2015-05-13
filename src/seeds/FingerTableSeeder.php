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
		$faker 										= Factory::create();
		$total_persons  							= Person::count();
		$total_positions 	 						= Chart::count();
		$status 									= ['contract', 'trial', 'internship', 'permanent', 'previous'];
		$position 									= ['Manager', 'Staff'];
		try
		{
			foreach(range(1, $total_positions) as $index)
			{
				$calendar 							= Chart::where('id', $index)->wherehas('calendars', function($q){$q;})->with('calendars')->first();
				$data 								= new Finger;
				$data->fill([
					'chart_id'						=> $index,
					'calendar_id'					=> $calendar->calendars[rand(0, count($calendar->calendars)-1)]->id,
					'status'						=> $status[rand(0,3)],
					'start'							=> $faker->date($format = 'Y-m-d', $max = 'now'),
				]);
				if($index==1)
				{
					$person 						= Person::find(1);
				}
				else
				{
					$person 						= Person::find(rand(1,$total_persons));
				}

				if (!$data->save())
				{
					print_r($data->getError());
					exit;
				}

				$data->Person()->associate($person);
				$data->save();
			} 

			foreach(range(1, 300) as $index)
			{
				$data 								= new Finger;
				$data->fill([
					'status'						=> $status[rand(0,3)],
					'start'							=> $faker->date($format = 'Y-m-d', $max = 'now'),
					'end'							=> $faker->date($format = 'Y-m-d', $max = '-3 days'),
					'reason_end_job'				=> $faker->word,
					'position'						=> $position[rand(0,1)],
					'organisation'					=> $faker->company,
				]);

				$person 							= Person::find(rand(1,$total_persons));

				if (!$data->save())
				{
					print_r($data->getError());
					exit;
				}

				$data->Person()->associate($person);
				$data->save();
			} 
		}
		catch (Exception $e) 
		{
    		echo 'Caught exception: ',  $e->getMessage(), "\n";
		}	
	}
}