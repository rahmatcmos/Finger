<?php namespace ThunderID\Finger\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	branch_id 						: Foreign Key From Branch, Integer, Required
 *	left_thumb						: 
 *	left_index_finger				: 
 *	left_middle_finger				: 
 *	left_ring_finger				: 
 *	left_little_finger				: 
 *	right_thumb						: 
 *	right_index_finger				: 
 *	right_middle_finger				: 
 *	right_ring_finger				: 
 *	right_little_finger				: 
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * 
/* ----------------------------------------------------------------------
 * Document Relationship :
 * 	//other package
 	1 Relationship belongsTo 
	{
		Branch
	}

 * ---------------------------------------------------------------------- */

use Str, Validator, DateTime, Exception;

class FingerPrint extends BaseModel {

	//use SoftDeletes;
	use \ThunderID\Finger\Models\Relations\BelongsTo\HasBranchTrait;

	public 		$timestamps 		= 	true;

	protected 	$table 				= 	'finger_prints';

	protected 	$fillable			= 	[
											'left_thumb' 				,
											'left_index_finger' 		,
											'left_middle_finger' 		,
											'left_ring_finger' 			,
											'left_little_finger' 		,
											'right_thumb' 				,
											'right_index_finger' 		,
											'right_middle_finger' 		,
											'right_ring_finger' 		,
											'right_little_finger' 		,
										];

	protected 	$rules				= 	[
											'left_thumb' 				=> 'boolean',
											'left_index_finger' 		=> 'boolean',
											'left_middle_finger' 		=> 'boolean',
											'left_ring_finger' 			=> 'boolean',
											'left_little_finger' 		=> 'boolean',
											'right_thumb' 				=> 'boolean',
											'right_index_finger' 		=> 'boolean',
											'right_middle_finger' 		=> 'boolean',
											'right_ring_finger' 		=> 'boolean',
											'right_little_finger' 		=> 'boolean',	
										];

	public $searchable 				= 	[
											'id' 						=> 'ID', 
											'branchid' 					=> 'BranchID', 
											'withattributes' 			=> 'WithAttributes'
										];

	public $sortable 				= ['created_at'];

	/* ---------------------------------------------------------------------------- CONSTRUCT ----------------------------------------------------------------------------*/
	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/
	static function boot()
	{
		parent::boot();

		Static::saving(function($data)
		{
			$validator = Validator::make($data->toArray(), $data->rules);

			if ($validator->passes())
			{
				return true;
			}
			else
			{
				$data->errors = $validator->errors();
				return false;
			}
		});
	}

	/* ---------------------------------------------------------------------------- QUERY BUILDER ---------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- MUTATOR ---------------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- ACCESSOR --------------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- FUNCTIONS -------------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- SCOPE -------------------------------------------------------------------------------*/
	public function scopeID($query, $variable)
	{
		return $query->where('id', $variable);
	}

	public function scopeBranchID($query, $variable)
	{
		return $query->where('branch_id', $variable);
	}

	public function scopeWithAttributes($query, $variable)
	{
		if(!is_array($variable))
		{
			$variable 			= [$variable];
		}
		return $query->with($variable);
	}
}
