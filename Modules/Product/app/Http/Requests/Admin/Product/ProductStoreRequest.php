<?php

namespace Modules\Product\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
{
	protected function prepareForValidation(): void
	{
		$this->merge([
			'price' => str_replace(',', '', $this->input('price')),
			'discount' => !is_null($this->discount) ? str_replace(',', '', $this->discount) : null,
			'purchased_price' => !is_null($this->purchased_price) ? str_replace(',', '', $this->purchased_price) : null,
		]);
	}

	public function rules(): array
	{
		return [
			'title' => ['required', 'string', 'min:3', 'max:100', 'unique:products,title'],
			'print_title' => ['required', 'string', 'min:3', 'max:100', 'unique:products,print_title'],
			'category_id' => ['required', 'integer', 'exists:categories,id'],
			'price' => ['required', 'integer', 'min:1000'],
			'discount' => ['nullable', 'integer', 'min:1000'],
			'initial_balance' => ['nullable', 'integer', 'min:1'],
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
