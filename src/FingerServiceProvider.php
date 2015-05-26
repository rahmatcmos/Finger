<?php namespace ThunderID\Finger;

use View, Validator, App, Route, Auth, Request, Redirect;
use Illuminate\Support\ServiceProvider;

class FingerServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		\ThunderID\Finger\Models\Finger::observe(new \ThunderID\Finger\Models\Observers\FingerObserver);
		\ThunderID\Finger\Models\FingerPrint::observe(new \ThunderID\Finger\Models\Observers\FingerPrintObserver);
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		\ThunderID\Finger\Models\Finger::observe(new \ThunderID\Finger\Models\Observers\FingerObserver);
		\ThunderID\Finger\Models\FingerPrint::observe(new \ThunderID\Finger\Models\Observers\FingerPrintObserver);
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
