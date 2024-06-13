<?php

namespace Modules\Sale\Http\Requests\Admin\Sale;

use Illuminate\Foundation\Http\FormRequest;

class SaleUpdateRequest extends FormRequest
{
  public function prepareForValidation()
  {
    $this->merge([
      'discount' => $this->filled('discount') ? str_replace(',', '', $this->input('discount')) : null,
    ]);
  }

  public function rules(): array
  {
    return [
      'sold_at' => ['required', 'date'],
      'discount' => ['nullable', 'integer', 'min:1000']
    ];
  }

  public function authorize(): bool
  {
    return true;
  }
}
