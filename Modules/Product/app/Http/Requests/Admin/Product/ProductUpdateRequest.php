<?php

namespace Modules\Product\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Helpers\Helpers;

class ProductUpdateRequest extends FormRequest
{
	protected function prepareForValidation(): void
	{
		$productDimensions = [];
		
		if ($this->filled('product_dimensions')) {
			foreach ($this->input('product_dimensions') as $productDimension) {
				
				$balance = $productDimension['initial_balance'];
				$purchasedPrice = $productDimension['purchased_price'];
				
				$hasInitialBalance = !is_null($balance);
				$hasPurchasedPrice = !is_null($purchasedPrice) && $purchasedPrice > 0;
				
				if ($hasInitialBalance && !$hasPurchasedPrice) {
					throw Helpers::makeWebValidationException('با وارد کردن موجودی اولیه باید حتما قیمت خرید را وارد کنید', 'initial_balance');
				}
				
				$productDimensions[] = [
					'sub_title' => $productDimension['dimensions'],
					'price' => Helpers::removeComma($productDimension['price']),
					'discount' => $productDimension['discount'] != null ? Helpers::removeComma($productDimension['discount']) : null,
					'initial_balance' => $productDimension['initial_balance'],
					'purchased_price' => $productDimension['purchased_price'] != null ? Helpers::removeComma($productDimension['purchased_price']) : null
				];
			}
		}

		$this->merge([
			'product_dimensions' => $productDimensions ?? null,
			'price' => Helpers::removeComma($this->input('price')),
			'discount' => $this->filled('discount') ? Helpers::removeComma($this->input('discount')) : null,
		]);
	}

	public function rules(): array
	{
		return [
			'title' => ['required', 'string', 'min:3', 'max:100'],
			'print_title' => ['required', 'string', 'min:3', 'max:100'],
			'category_id' => ['required', 'integer', 'exists:categories,id'],
			'price' => ['required', 'integer', 'min:1000'],
			'discount' => ['nullable', 'integer', 'min:1000'],
			'image' => ['nullable', 'image', 'mimes:png,jpg'],
			'description' => ['nullable', 'string'],
			'status' => ['required', 'boolean']
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
