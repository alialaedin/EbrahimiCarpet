<?php

namespace Modules\Customer\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Customer\Models\Customer;

class CustomerUpdateRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'name' => ['required', 'string', 'min:3', 'max:50'],
			'mobile' => [
        'required',
        'numeric',
        Rule::unique('customers', 'mobile')->ignore($this->route('customer')),
        'digits:11',
        'starts_with:09'
      ],
			'telephone' => ['nullable', Rule::unique('customers', 'telephone')->ignore($this->route('customer')->id)],
			'address' => ['nullable', 'string'],
			'status' => ['nullable', 'in:1'],
      'gender' => ['required', 'string', Rule::in(Customer::GENDER_MALE,Customer::GENDER_FEMALE)],
      'birthday' => ['nullable', 'date'],
      'description' => ['nullable', 'string'],
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
