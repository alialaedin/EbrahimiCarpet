<?php

namespace Modules\Purchase\Http\Requests\Admin\Purchase;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Helpers\Helpers;

class PurchaseUpdateRequest extends FormRequest
{
	public function prepareForValidation()
	{
		$this->merge([
			'discount' => !is_null($this->input('discount')) ? str_replace(',', '', $this->input('discount')) : null,
		]);
	}

	public function rules(): array
	{
		return [
			'supplier_id' => ['required', 'integer', 'exists:suppliers,id'],
			'purchased_at' => ['required', 'date'],
			'discount' => ['nullable', 'integer', 'min:1000'],
		];
	}

	public function passedValidation()
	{
		if ($this->input('purchased_at') > now()) {
			throw Helpers::makeWebValidationException('تاریخ خرید نمی تواند از تاریخ امروز بزرگ تر باشد.');
		}
	}
	public function authorize(): bool
	{
		return true;
	}
}
