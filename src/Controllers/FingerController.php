<?php namespace ThunderID\Finger\Controllers;

use \App\Http\Controllers\Controller;
use \ThunderID\Finger\Models\Finger;
use \ThunderID\Log\Models\ErrorLog;
use \ThunderID\Person\Models\Person;
use \ThunderID\Organisation\Models\Organisation;
use \ThunderID\Commoquent\Getting;
use \ThunderID\Commoquent\Saving;
use \ThunderID\Commoquent\Deleting;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App, Response;

class FingerController extends Controller {

	protected $controller_name 		= 'finger';

	/**
	 * login form
	 *
	 * @return void
	 * @author 
	 **/
	public function store()
	{
		$attributes 							= Input::only('application', 'template');
		if(!$attributes['application'])
		{
			return Response::json(['message' => 'Server Error'], 500);
		}

		$api 									= $attributes['application']['api'];
		if($api['client']!='123456789' || $api['secret']!='123456789')
		{
			return Response::json(['message' => 'Not Found'], 404);
		}

		if(!$attributes['template'])
		{
			return Response::json(['message' => 'Server Error'], 500);
		}
		DB::beginTransaction();

		if(isset($attributes['template']))
		{
			$attributes['template']					= (array)$attributes['template'];
			foreach ($attributes['template'] as $key => $value) 
			{
				$log['left_thumb']					= $value[1];
				$log['left_index_finger']			= $value[2];
				$log['left_middle_finger']			= $value[3];
				$log['left_ring_finger']			= $value[4];
				$log['left_little_finger']			= $value[5];
				$log['right_thumb']					= $value[6];
				$log['right_index_finger']			= $value[7];
				$log['right_middle_finger']			= $value[8];
				$log['right_ring_finger']			= $value[9];
				$log['right_little_finger']			= $value[10];

				$data 							= $this->dispatch(new Getting(new Person, ['email' => $value[0]], [] ,1, 1));
				$person 						= json_decode($data);
				if(!$person->meta->success)
				{
					$log['email']				= $value[0];
					$log['name']				= 'enroll';
					$log['pc']					= $api['client'];
					$log['on']					= date('Y-m-d H:i:s');
					$log['message']				= json_encode($person->meta->errors);
					$saved_error_log 			= $this->dispatch(new Saving(new ErrorLog, $log, null, new Organisation, 1));
				}
				else
				{
					$data 						= $this->dispatch(new Getting(new Finger, ['personid' => $person->data->id, 'stillwork' => ''], [] ,1, 1));
					$finger 					= json_decode($data);
					if($finger->meta->success)
					{
						$saved_log 				= $this->dispatch(new Saving(new Finger, $log, $finger->data->id, new Person, $person->data->id));
						$is_success_2 			= json_decode($saved_log);
						if(!$is_success_2->meta->success)
						{
							$log['email']		= $value[0];
							$log['name']		= 'enroll';
							$log['pc']			= $api['client'];
							$log['on']			= date('Y-m-d H:i:s');
							$log['message']		= json_encode($is_success_2->meta->errors);
							$saved_error_log 	= $this->dispatch(new Saving(new ErrorLog, $log, null, new Organisation, 1));
						}
					}
					else
					{
						$log['email']			= $value[0];
						$log['name']			= 'enroll';
						$log['pc']				= $api['client'];
						$log['on']				= date('Y-m-d H:i:s');
						$log['message']			= json_encode($finger->meta->errors);
						$saved_error_log 		= $this->dispatch(new Saving(new ErrorLog, $log, null, new Organisation, 1));
					}
				}

			}
		}

		DB::commit();

		return Response::json(['message' => 'Sukses'], 200);
	}
}