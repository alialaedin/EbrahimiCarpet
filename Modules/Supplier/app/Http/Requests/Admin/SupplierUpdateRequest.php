<?php

namespace Modules\Supplier\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Core\Rules\IranMobile;

class SupplierUpdateRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'name' => ['required', 'string', 'min:3', 'max:50'],
			'mobile' => [
				'required', 
				'numeric',  
				Rule::unique('suppliers', 'mobile')->ignore($this->route('supplier')), 
				'digits:11', 
				new IranMobile
			],
			'address' => ['required', 'string'],
			'status' => ['nullable', 'in:1']
		];
	}

	public function validated($key = null, $default = null)
	{
		$validatedData = parent::validated();
		$validatedData['status'] = $this->filled('status') ? 1 : 0;

		return $validatedData;
	}
	public function authorize(): bool
	{
		return true;
	}
}
