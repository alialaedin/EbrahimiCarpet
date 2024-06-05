<?php

namespace Modules\Product\Http\Requests\Admin\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Core\Helpers\Helpers;

class CategoryUpdateRequest extends FormRequest
{
	private $category;

	public function __construct() {
		$this->category = $this->route('category');
	}

	public function rules(): array
	{
		$categoryId = $this->category->id;
		
		return [
			'title' => ['required', 'string', 'min:3', 'max:30', Rule::unique('categories', 'title')->ignore($categoryId)],
			'parent_id' => ['nullable', 'integer', 'exists:categories,id', 'not_in:'.$categoryId],
			'unit_type' => ['required', 'string', 'in:meter,number'],
			'status' => ['required', 'boolean']
		];
	}

	public function passedValidation()
	{
		$category = $this->category;

		if ($this->filled('parent_id') && $category->children()->exists()) {
			throw Helpers::makeWebValidationException('دسته بندی دارای فرزند است و نمی تواند والد داشته باشد.');
		}
	}

	public function authorize(): bool
	{
		return true;
	}
}
