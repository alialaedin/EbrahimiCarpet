<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 */
	public function register(): void
	{
		app()->useLangPath(base_path('Modules/Core/resources/lang'));
	}

	/**
	 * Bootstrap any application services.
	 */
	public function boot(): void
	{
		Gate::before(function ($user, $ability) {
			return $user->hasRole('super_admin') ? true : null;
		});
	}
}
