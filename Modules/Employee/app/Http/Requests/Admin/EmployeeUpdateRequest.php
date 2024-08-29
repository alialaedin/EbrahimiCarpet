<?php

namespace Modules\Employee\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Core\Rules\IranianBankName;
use Modules\Core\Rules\IranianCardNumber;
use Modules\Core\Rules\IranianShebaNumber;
use Modules\Core\Rules\IranMobile;

class EmployeeUpdateRequest extends FormRequest
{
	protected function prepareForValidation(): void
	{
		$this->merge([
			'salary' => str_replace(',', '', $this->input('salary'))
		]);
	}
	public function rules(): array
	{
		$employee = $this->route('employee');
		return [
			'name' => ['required', 'string', 'min:3', 'max:60'],
			'mobile' => [
				'required',
				'numeric',
				'digits:11',
				Rule::unique('employees', 'mobile')->ignore($employee->id),
				new IranMobile
			],
			'telephone' => ['nullable', 'numeric', 'digits:11'],
			'address' => ['required', 'string'],
			'national_code' => [
				'nullable',
				'numeric',
				'digits:10',
				Rule::unique('employees', 'national_code')->ignore($employee->id)
			],
			'employment_at' => ['required', 'date'],
			'salary' => ['required', 'integer', 'min:1'],
			'card_number' => ['required', 'digits:16', 'numeric', new IranianCardNumber],
			'sheba_number' => ['nullable', 'numeric'],
			'bank_name' => ['required', 'string']
		];
	}
	public function authorize(): bool
	{
		return true;
	}
}
