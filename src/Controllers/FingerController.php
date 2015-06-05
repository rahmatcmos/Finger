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
use App, Response, Device;

class FingerController extends Controller {

	protected $controller_name 		= 'finger';

	/**
	 * finger store
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
		$checking 								= Device::checking($api['client'], $api['secret']);
		if($checking==false)
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
			$attributes['template']				= (array)$attributes['template'];
			foreach ($attributes['template'] as $key => $value) 
			{
				if(strtoupper($value[1])!='NULL')
				{
					$log['left_thumb']			= $value[1];
				}
				if(strtoupper($value[2])!='NULL')
				{
					$log['left_index_finger']	= $value[2];
				}
				if(strtoupper($value[3])!='NULL')
				{
					$log['left_middle_finger']	= $value[3];
				}
				if(strtoupper($value[4])!='NULL')
				{
					$log['left_ring_finger']	= $value[4];
				}
				if(strtoupper($value[5])!='NULL')
				{
					$log['left_little_finger']	= $value[5];
				}
				if(strtoupper($value[6])!='NULL')
				{
					$log['right_thumb']			= $value[6];
				}
				if(strtoupper($value[7])!='NULL')
				{
					$log['right_index_finger']	= $value[7];
				}
				if(strtoupper($value[8])!='NULL')
				{
					$log['right_middle_finger']	= $value[8];
				}
				if(strtoupper($value[9])!='NULL')
				{
					$log['right_ring_finger']	= $value[9];
				}
				if(strtoupper($value[10])!='NULL')
				{
					$log['right_little_finger']	= $value[10];
				}

				$data 							= $this->dispatch(new Getting(new Person, ['email' => $value[0]], [] ,1, 1));
				$person 						= json_decode($data);
				if(!$person->meta->success)
				{
					$log['email']				= $value[0];
					$log['name']				= 'enroll';
					$log['pc']					= $api['client'];
					$log['on']					= date('Y-m-d H:i:s');
					$log['message']				= json_encode($person->meta->errors);
					$saved_error_log 			= $this->dispatch(new Saving(new ErrorLog, $log, null, new Organisation, $checking));
				}
				else
				{
					$data 						= $this->dispatch(new Getting(new Finger, ['personid' => $person->data->id, 'stillwork' => $checking], [] ,1, 1));
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
							$saved_error_log 	= $this->dispatch(new Saving(new ErrorLog, $log, null, new Organisation, $checking));
						}
					}
					else
					{
						$log['email']			= $value[0];
						$log['name']			= 'enroll';
						$log['pc']				= $api['client'];
						$log['on']				= date('Y-m-d H:i:s');
						$log['message']			= json_encode($finger->meta->errors);
						$saved_error_log 		= $this->dispatch(new Saving(new ErrorLog, $log, null, new Organisation, $checking));
					}
				}

			}
		}

		DB::commit();

		return Response::json(['message' => 'Sukses'], 200);
	}

	/**
	 * finger store
	 *
	 * @return void
	 * @author 
	 **/
	public function update()
	{
		$attributes 							= Input::only('application', 'update', 'page', 'limit');
		if(!$attributes['application'])
		{
			return Response::json(['message' => 'Server Error'], 500);
		}

		$api 									= $attributes['application']['api'];
		$checking 								= Device::checking($api['client'], $api['secret']);
		if($checking==false)
		{
			return Response::json(['message' => 'Not Found'], 404);
		}

		if(!$attributes['update'])
		{
			return Response::json(['message' => 'Server Error'], 500);
		}

		if(!isset($attributes['page']))
		{
			$attributes['page'] 				= 1;
		}

		if(!isset($attributes['limit']))
		{
			$attributes['limit'] 				= 100;
		}

		if(isset($attributes['update']))
		{
			$search	 							= ['displayupdatedfinger' => date('Y-m-d H:i:s', strtotime($attributes['update']))];
			
			$sort 								= ['persons.created_at' => 'asc'];

			$results 							= $this->dispatch(new Getting(new Person, $search, $sort , $attributes['page'], $attributes['limit']));
		
			$contents 							= json_decode($results);

			if(!$contents->meta->success)
			{
				App::abort(404);
			}
			
			$data 								= json_decode(json_encode($contents->data), true);

			if(!count($data))
			{
				return Response::json(['current_page' => 0, 'total_page' => 0, 'data' => 'empty'], 200);
			}

			$returned 							= ['current_page' => $contents->pagination->page, 'total_page' => $contents->pagination->total_page, 'data' => $data];

			$returned 							= json_encode($returned);

			$returned 							= str_replace('\/', '/', $returned);

			return $returned; exit;
		}

		return Response::json(['message' => 'Sukses'], 200);
	}

	/**
	 * finger store
	 *
	 * @return void
	 * @author 
	 **/
	public function random()
	{
		$attributes 							= Input::only('application');
		if(!$attributes['application'])
		{
			return Response::json(['message' => 'Server Error'], 500);
		}

		$api 									= $attributes['application']['api'];
		$checking 								= Device::checking($api['client'], $api['secret']);
		if($checking==false)
		{
			return Response::json(['message' => 'Not Found'], 404);
		}

		$checking 								= Device::finger($api['client'], $api['secret']);
	
		return Response::json(['finger' => $checking], 200);
	}
}