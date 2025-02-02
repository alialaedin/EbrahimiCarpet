<?php

namespace Modules\Purchase\Http\Requests\Admin\PurchaseItem;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Helpers\Helpers;

class PurchaseItemUpdateRequest extends FormRequest
{
	protected function prepareForValidation(): void
	{
		foreach (['price', 'discount'] as $key) {
			if ($this->filled($key)) {
				$this->merge([
					$key => (int) Helpers::removeComma($this->input($key))
				]);
			}
		}
	}

	public function rules(): array
	{
		return [
			'quantity' => ['nullable', 'integer', 'min:1'],
			'discount' => ['nullable', 'integer', 'min:1000'],
			'price' => ['nullable', 'integer', 'min:1000'],
		];
	}

	public function passedValidation()
	{
		$item = $this->route('purchase_item');
		$discount = $this->discount ?? $item->discount;
		$price = $this->price ?? $item->price;

		if ($discount > $price) {
			throw Helpers::makeWebValidationException('مبلغ تخفیف نی تواند بیشتر از قیمت محصول باشد.');
		}

		if ($this->filled('quantity') && $this->quantity == $item->quantity) {
			throw Helpers::makeWebValidationException('تعداد وارد شدهن نمی تواند برابر با تعداد فعلی باشد ');
		}
	}

	public function authorize(): bool
	{
		return true;
	}
}
