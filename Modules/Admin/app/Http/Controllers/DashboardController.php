<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DashboardController extends Controller
{
	public function index()
	{
		return 'Dashboard';
		return view('admin::index');
	}
}
