<?php

namespace Modules\Accounting\Http\Requests\Admin\Expense;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseStoreRequest extends FormRequest
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

  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }
}
