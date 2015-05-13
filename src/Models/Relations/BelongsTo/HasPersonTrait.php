<?php namespace ThunderID\Finger\Models\Relations\BelongsTo;

trait HasPersonTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasPersonTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN PERSON PACKAGE -------------------------------------------------------------------*/
	public function Person()
	{
		return $this->belongsTo('ThunderID\Person\Models\Person');
	}

	public function ScopeStillWork($query, $variable)
	{
		return $query->WhereHas('person', function($q)use($variable){$q->CurrentWork($variable);});
	}
}