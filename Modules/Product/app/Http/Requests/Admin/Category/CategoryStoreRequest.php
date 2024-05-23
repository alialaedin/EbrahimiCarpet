<?php

namespace Modules\Product\Http\Requests\Admin\Category;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Helpers\Helpers;
use Modules\Product\Models\Category;

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

	public function passedValidation()
	{
		$parentId = $this->input('parent_id');
		if ($parentId) {
			$parentCategory = Category::findOrFail($parentId);
			$parentCategoryUnitType = $parentCategory->unit_type;

			if ($this->input('unit_type') != $parentCategoryUnitType) {
				throw Helpers::makeWebValidationException('نوع واحد انتخاب شده برابر با نوع واحد والد نمی باشد.');
			}
		}
	}
	
	public function authorize(): bool
	{
		return true;
	}
}
