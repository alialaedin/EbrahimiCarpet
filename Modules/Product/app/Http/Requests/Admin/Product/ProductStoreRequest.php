<?php

namespace Modules\Product\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Helpers\Helpers;

class ProductStoreRequest extends FormRequest
{
	protected function prepareForValidation(): void
	{
		$productDimensions = [];	

		// $firstKey = array_keys($this->input('products_dimensions'))[0];  
		// $firstValue = $this->input('products_dimensions')[$firstKey];

		// dd($firstKey);

		if ($this->filled('product_dimensions')) {
			foreach ($this->input('product_dimensions') as $productDimension) {
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
			'product_dimensions' => $productDimensions,
			// 'price' => Helpers::removeComma($this->input('price')),
			// 'discount' => $this->filled('discount') ? Helpers::removeComma($this->input('discount')) : null,
			// 'purchased_price' => $this->filled('purchased_price') ? Helpers::removeComma($this->input('purchased_price')) : null,
		]);

	}

	public function rules(): array
	{
		// dd($this->all());
		return [
			'title' => ['required', 'string', 'min:3', 'max:100'],
			'print_title' => ['required', 'string', 'min:3', 'max:100'],
			'category_id' => ['required', 'integer', 'exists:categories,id'],
			// 'price' => ['required', 'integer', 'min:1000'],
			// 'discount' => ['nullable', 'integer', 'min:1000'],
			// 'initial_balance' => ['nullable', 'integer', 'min:1'],
			'image' => ['nullable', 'image', 'mimes:png,jpg'],
			'description' => ['nullable', 'string'],
			'status' => ['required', 'boolean']
		];
	}
	
	public function passedValidation()
	{
		foreach ($this->input('product_dimensions') as $productDimension) {

			$hasInitialBalance = !is_null($productDimension['initial_balance']);
			$hasPurchasedPrice = !is_null($productDimension['purchased_price']) && $productDimension['purchased_price'] > 0;

			if ($hasInitialBalance && !$hasPurchasedPrice) {
				throw Helpers::makeWebValidationException('با وارد کردن موجودی اولیه باید حتما قیمت خرید را وارد کنید', 'initial_balance');
			}

		}

		// if ($this->filled('initial_balance') && $this->isNotFilled('purchased_price')) {
		// 	throw Helpers::makeWebValidationException('با وارد کردن موجودی اولیه باید حتما قیمت خرید را وارد کنید', 'initial_balance');
		// }

	}

	public function authorize(): bool
	{
		return true;
	}
}
