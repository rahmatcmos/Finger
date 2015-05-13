<?php namespace ThunderID\Finger\Models\Observers;

use DB, Validator;
use ThunderID\Finger\Models\Finger;
use ThunderID\Organisation\Models\Chart;
use Illuminate\Support\MessageBag;

/* ----------------------------------------------------------------------
 * Event:
 * 	Creating						
 * 	Saving						
 * 	Updating						
 * 	Deleting						
 * ---------------------------------------------------------------------- */

class FingerObserver 
{
	public function creating($model)
	{
		//
	}

	public function saving($model)
	{
		$validator 				= Validator::make($model['attributes'], $model['rules']);

		if ($validator->passes())
		{
			if(isset($model['attributes']['chart_id']) && !is_null($model['attributes']['chart_id']) && $model['attributes']['chart_id']!=0)
			{
				$validator 		= Validator::make($model['attributes'], ['chart_id' => 'exists:charts,id']);
				if ($validator->passes())
				{
					return true;
				}
				else
				{
					$model['errors'] 	= $validator->errors();

					return false;
				}
				
			}

			if(isset($model['attributes']['calendar_id']) && !is_null($model['attributes']['calendar_id']) && $model['attributes']['calendar_id']!=0)
			{
				$validator 		= Validator::make($model['attributes'], ['calendar_id' => 'exists:calendars,id']);
				if ($validator->passes())
				{
					$check 		= Follow::CalendarID($model['attributes']['calendar_id'])->ChartID($model['attributes']['chart_id'])->get();
					if(count($check))
					{
						return true;
					}

					$errors 	= new MessageBag;
					$errors->add('notmatch', 'Posisi tidak memiliki jadwal tersebut.');
					$model['errors'] = $errors;
					
					return false;
				}
				else
				{
					$model['errors'] 	= $validator->errors();

					return false;
				}
				
			}

			return true;
		}
		else
		{
			$model['errors'] 	= $validator->errors();

			return false;
		}
	}

	public function saved($model)
	{
		if(isset($model['attributes']['chart_id']) && $model['attributes']['chart_id']!=0)
		{
			$current_employee 	= Finger::where('chart_id',$model['attributes']['chart_id'])->whereNull('end')->count();
			$updated 			= Chart::where('id', $model['attributes']['chart_id'])->update(['current_employee' => $current_employee]);
		}
		return true;
	}

	public function deleting($model)
	{
		//
	}
}
