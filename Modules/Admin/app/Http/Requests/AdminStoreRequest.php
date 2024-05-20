<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Modules\Core\Rules\IranMobile;

class AdminStoreRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'name' => ['required', 'string'],
			'mobile' => ['required', 'numeric', 'unique:admins,mobile', new IranMobile()],
			'role' => ['required', 'string'],
			'role.*' => ['required', 'exists:roles,name'],
			'password' => ['required', 'string', Password::min(6), 'confirmed'],
			'status' => ['nullable', 'in:1']
		];
	}

	public function validated($key = null, $default = null) {
		
		$validatedData = parent::validated();
		$validatedData['status'] = $this->filled('status') ? 1 : 0;
		unset($validatedData['role']);

		return $validatedData;
	}	

	public function authorize(): bool
	{
		return true;
	}
}
