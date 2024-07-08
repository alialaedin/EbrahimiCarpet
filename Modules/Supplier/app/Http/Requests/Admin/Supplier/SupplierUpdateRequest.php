<?php

namespace Modules\Supplier\Http\Requests\Admin\Supplier;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Core\Rules\IranMobile;

class SupplierUpdateRequest extends FormRequest
{
	public function rules(): array
	{
    $supplierId = $this->route('supplier')->id;

		return [
			'name' => ['required', 'string', 'min:3', 'max:50'],
			'mobile' => [
				'required',
				'numeric',
				Rule::unique('suppliers', 'mobile')->ignore($this->route('supplier')),
				'digits:11',
				new IranMobile
			],
      'telephone' => ['nullable', 'numeric', Rule::unique('suppliers', 'telephone')->ignore($supplierId), 'digits:11'],
      'national_code' => ['required', 'numeric', Rule::unique('suppliers', 'national_code')->ignore($supplierId), 'digits:10'],
      'postal_code' => ['required', 'numeric', Rule::unique('suppliers', 'postal_code')->ignore($supplierId), 'digits:10'],
      'description' => ['nullable', 'string'],
      'type' => ['required', 'in:legal,real'],
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
