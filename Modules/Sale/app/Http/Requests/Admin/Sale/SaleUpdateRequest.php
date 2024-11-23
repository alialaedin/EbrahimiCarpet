<?php

namespace Modules\Sale\Http\Requests\Admin\Sale;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaleUpdateRequest extends FormRequest
{
  public function prepareForValidation(): void
  {
    $this->merge([
      'discount' => $this->filled('discount') ? str_replace(',', '', $this->input('discount')) : null,
      'cost_of_sewing' => $this->filled('cost_of_sewing') ? str_replace(',', '', $this->input('cost_of_sewing')) : null,
    ]);
  }

  public function rules(): array
  {
    return [
      'sold_at' => ['required', 'date'],
      'discount' => ['nullable', 'integer'],
      'cost_of_sewing' => ['nullable', 'integer', 'min:1000'],
      'employee_id' => ['required', 'integer', Rule::exists('employees', 'id')],
      'discount_for' => ['nullable', 'string'],
    ];
  }

  public function authorize(): bool
  {
    return true;
  }
}
