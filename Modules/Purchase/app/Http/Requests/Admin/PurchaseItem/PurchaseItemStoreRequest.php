<?php

namespace Modules\Purchase\Http\Requests\Admin\PurchaseItem;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Helpers\Helpers;

class PurchaseItemStoreRequest extends FormRequest
{
	protected function prepareForValidation(): void
	{
		$this->merge([
			'price' => str_replace(',', '', $this->input('price')),
			'discount' => $this->filled('discount') ? str_replace(',', '', $this->input('discount')) : null,
		]);
	}

	public function rules(): array
	{
		return [
			'purchase_id' => ['required', 'integer', 'exists:purchases,id'],
			'product_id' => ['required', 'integer', 'exists:products,id'],
			'quantity' => ['required', 'integer', 'min:1'],
			'discount' => ['nullable', 'integer', 'min:1000'],
			'price' => ['required', 'integer', 'min:1000'],
		];
	}

	public function passedValidation()
	{
		if ($this->filled('discount')) {
			if ($this->input('discount') > $this->input('price')) {
				throw Helpers::makeWebValidationException('مبلغ تخفیف نی تواند بیشتر از قیمت محصول باشد.');
			}
		}
	}


	public function authorize(): bool
	{
		return true;
	}
}
