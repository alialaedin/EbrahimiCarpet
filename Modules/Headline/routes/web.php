<?php

use Illuminate\Support\Facades\Route;
use Modules\Headline\Http\Controllers\Admin\HeadlineController;

Route::middleware('auth')->prefix('/admin')->name('admin.')->group(function() {
  Route::apiResource('/headlines', HeadlineController::class)->except('show');
});
