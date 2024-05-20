<?php

namespace Modules\Personnel\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Rules\IranianBankName;
use Modules\Core\Rules\IranianCardNumber;
use Modules\Core\Rules\IranianShebaNumber;
use Modules\Core\Rules\IranMobile;

class PersonnelStoreRequest extends FormRequest
{

	protected function prepareForValidation(): void
	{
		$this->merge([
			'salary' => str_replace(',', '', $this->input('salary'))
		]);
	}
	public function rules(): array
	{
		return [
			'name' => ['required', 'string', 'min:3', 'max:60'],
			'mobile' => ['required', 'numeric', 'unique:personnels,mobile', 'digits:11', new IranMobile],
			'telephone' => ['nullable', 'numeric', 'digits:11'],
			'address' => ['required', 'string'],
			'national_code' => ['nullable', 'string', 'numeric', 'digits:10', 'unique:personnels,national_code'],
			'employmented_at' => ['required', 'date'],
			'salary' => ['required', 'integer', 'min:1'],
			'card_number' => ['required', 'digits:16', 'numeric', new IranianCardNumber],
			'sheba_number' => ['nullable', 'numeric', new IranianShebaNumber],
			'bank_name' => ['required', 'string', new IranianBankName]
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
