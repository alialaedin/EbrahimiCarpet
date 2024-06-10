<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Modules\Core\Helpers\Helpers;
use Modules\Core\Rules\IranMobile;
use Modules\Permission\Models\Role;

class AdminUpdateRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'name' => ['required', 'string'],
			'mobile' => ['required', 'numeric', Rule::unique('admins', 'mobile')->ignore($this->route('admin')), new IranMobile()],
			'role' => ['required', 'string'],
			'role.*' => ['required', 'exists:roles,name'],
			'password' => ['nullable', 'string', Password::min(6), 'confirmed'],
			'status' => ['nullable', 'in:1']
		];
	}

	public function passedValidation()
	{
		$superAdminRole = Role::SUPER_ADMIN;
		$oldRoleName = $this->route('admin')->getRoleName();
		$newRoleName = $this->input('role');

		if ($oldRoleName == $superAdminRole && $newRoleName != $superAdminRole) {
			throw Helpers::makeWebValidationException('نقش ادمینی که مدیر ارشد است نمی تواند عوض شود!', 'role');
		}
	}

	public function authorize(): bool
	{
		return true;
	}
}
