<?php

namespace Modules\Product\Http\Requests\Admin\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryUpdateRequest extends FormRequest
{
	public function rules(): array
	{
		$categoryId = $this->route('category')->id;
		
		return [
			'title' => ['required', 'string', 'min:3', 'max:30', Rule::unique('categories', 'title')->ignore($categoryId)],
			'parent_id' => ['nullable', 'integer', 'exists:categories,id', 'not_in:'.$categoryId],
			'unit_type' => ['required', 'string', 'in:meter,number'],
			'status' => ['required', 'boolean']
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
