<?php

namespace Modules\Auth\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Http\Requests\Admin\LoginRequest;
use Modules\Auth\Http\Requests\Admin\LogoutRequest;

class AuthController extends Controller
{
	public function showLoginForm()
	{
		return view("auth::admin.login");
	}

	public function login(LoginRequest $request): RedirectResponse
	{
		if (Auth::guard('web')->attempt($request->except("_token"))) {
			$request->session()->regenerate();
			return redirect()->intended('admin/dashboard');
		}
		return back()->withErrors([
			'mobile' => 'چنین شماره ای در پایگاه داده ثبت نشده است.',
		])->onlyInput('mobile');
	}

	public function logout(LogoutRequest $request): RedirectResponse
	{
		$request->session()->invalidate();
		$request->session()->regenerateToken();
		return redirect()->route("admin.login.form");
	}
}
