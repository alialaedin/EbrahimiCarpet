<?php

namespace Modules\Customer\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Rules\IranMobile;

class CustomerStoreRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 */
	public function rules(): array
	{
		return [
			'name' => ['required', 'string', 'min:3', 'max:50'],
			'mobile' => ['required', 'numeric', 'unique:customers,mobile', 'digits:11', new IranMobile()],
			'telephone' => ['nullable', 'numeric', 'unique:customers,telephone', 'digits:11'],
			'address' => ['nullable', 'string'],
			'status' => ['nullable', 'in:1']
		];
	}

	public function validated($key = null, $default = null)
	{
		$validatedData = parent::validated();
		$validatedData['status'] = $this->filled('status') ? 1 : 0;

		return $validatedData;
	}

	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return true;
	}
}
