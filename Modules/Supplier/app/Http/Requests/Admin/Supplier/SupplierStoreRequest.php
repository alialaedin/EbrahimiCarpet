<?php

namespace Modules\Supplier\Http\Requests\Admin\Supplier;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Rules\IranMobile;
use Modules\Supplier\Models\Supplier;

class SupplierStoreRequest extends FormRequest
{
	private int $nationalCodeDigits = 10;
	public function prepareForValidation()
	{
		if ($this->input('type') == Supplier::TYPE_LEGAL) {
			$this->nationalCodeDigits = 11;
		}
	}

	public function rules(): array
	{
		return [
			'name' => ['required', 'string', 'min:3', 'max:50'],
			'mobile' => ['required', 'numeric', 'unique:suppliers,mobile', 'digits:11', new IranMobile],
			'telephone' => ['nullable', 'numeric', 'unique:suppliers,telephone', 'digits:' . $this->nationalCodeDigits],
			'national_code' => ['required', 'numeric', 'unique:suppliers,national_code', 'digits:10'],
			'postal_code' => ['required', 'numeric', 'unique:suppliers,postal_code', 'digits:10'],
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
