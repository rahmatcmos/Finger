<?php namespace ThunderID\Finger\Models\Relations\BelongsTo;

trait HasBranchTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasBranchTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN ORGANISATION PACKAGE -------------------------------------------------------------------*/
	public function Branch()
	{
		return $this->belongsTo('ThunderID\Organisation\Models\Branch');
	}
}