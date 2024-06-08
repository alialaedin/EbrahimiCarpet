<?php

namespace Modules\Product\Http\Requests\Admin\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Modules\Core\Helpers\Helpers;
use Modules\Product\Models\Category;

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

  /**
   * @throws ValidationException
   */
  public function passedValidation(): void
  {
		$category = $this->route('category');

		if ($this->filled('parent_id') && $category->children()->exists()) {
			throw Helpers::makeWebValidationException('دسته بندی دارای فرزند است و نمی تواند والد داشته باشد.');
		}
	}

	public function authorize(): bool
	{
		return true;
	}
}
