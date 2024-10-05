<?php

namespace Modules\Purchase\Http\Requests\Admin\PurchaseItem;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
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
			'product_id' => [
        'required',
        'integer',
        Rule::exists('products', 'id'),
        Rule::unique('purchase_items', 'product_id')
          ->where('purchase_id', $this->input('purchase_id'))],
			'quantity' => ['required', 'integer', 'min:1'],
			'discount' => ['nullable', 'integer'],
			'price' => ['required', 'integer', 'min:1000'],
		];
	}

  /**
   * @throws ValidationException
   */
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
