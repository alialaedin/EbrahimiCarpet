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
    $products = [];
    foreach ($this->input('products') as $index => $product) {
      $products[] = [
        'id' => $product['id'],
        'quantity' => $product['quantity'],
        'price' => str_replace(',', '', $product['price']),
        'discount' => $this->filled('products.' . $index . '.discount') ? str_replace(',', '', $product['discount']) : null,
      ];
    }

    $this->merge([
      'discount' => $this->filled('discount') ? str_replace(',', '', $this->input('discount')) : null,
      'products' => $products
    ]);

	}

	public function rules(): array
	{
		return [
			'supplier_id' => ['required', 'integer', 'exists:suppliers,id'],
			'purchased_at' => ['required', 'date'],
			'discount' => ['nullable', 'integer'],
			'products' => ['required', 'array'],
			'products.*.id' => ['required', 'integer', 'exists:products,id'],
			'products.*.quantity' => ['required', 'integer', 'min:1'],
			'products.*.discount' => ['nullable', 'integer'],
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
