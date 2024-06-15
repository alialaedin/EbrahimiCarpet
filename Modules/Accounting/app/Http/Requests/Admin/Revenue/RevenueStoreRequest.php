<?php

namespace Modules\Accounting\Http\Requests\Admin\Revenue;

use Illuminate\Foundation\Http\FormRequest;

class RevenueStoreRequest extends FormRequest
{
  public function prepareForValidation()
  {
    $this->merge([
      'amount' => str_replace(',', '', $this->input('amount'))
    ]);
  }

  public function rules(): array
  {
    return [
      'headline_id' => ['required', 'integer', 'exists:headlines,id'],
      'title' => ['required', 'string', 'min:3', 'max:190'],
      'amount' => ['required', 'integer', 'min:1000', 'max:9999999999'],
      'payment_date' => ['required', 'date'],
      'description' => ['nullable', 'string']
    ];
  }

  public function authorize(): bool
  {
    return true;
  }
}
