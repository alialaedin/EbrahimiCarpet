<?php

namespace Modules\Product\Http\Requests\Admin\Category;

use Illuminate\Foundation\Http\FormRequest;

class CategoryStoreRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'title' => ['required', 'string', 'min:3', 'max:30', 'unique:categories,title'],
			'parent_id' => ['nullable', 'integer', 'exists:categories,id'],
			'unit_type' => ['required', 'string', 'in:meter,number'],
			'status' => ['required', 'boolean']
		];
	}
	
	public function authorize(): bool
	{
		return true;
	}
}
