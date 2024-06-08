<?php

namespace Modules\Purchase\Http\Requests\Admin\Purchase;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Modules\Core\Helpers\Helpers;

class PurchaseStoreRequest extends FormRequest
{
	public function prepareForValidation(): void
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
			'products' => ['required', 'array'],
			'products.*.id' => ['required', 'integer', 'exists:products,id'],
			'products.*.quantity' => ['required', 'integer', 'min:1'],
			'products.*.discount' => ['nullable', 'integer', 'min:1000'],
			'products.*.price' => ['required', 'integer', 'min:1000'],
		];
	}

  /**
   * @throws ValidationException
   */
  public function passedValidation(): void
  {
		if ($this->input('purchased_at') > Carbon::now()) {
			throw Helpers::makeWebValidationException('تاریخ خرید نمی تواند از تاریخ امروز بزرگ تر باشد.');
		}

		foreach ($this->input('products') as $product) {
			if ($product['discount'] > $product['price']) {
				throw Helpers::makeWebValidationException('مبلغ تخفیف نی تواند بیشتر از قیمت محصول باشد.');
			}
		}
	}

	public function authorize(): bool
	{
		return true;
	}
}
