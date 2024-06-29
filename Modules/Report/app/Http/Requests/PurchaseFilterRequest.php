<?php

namespace Modules\Report\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PurchaseFilterRequest extends FormRequest
{
  /**
   * Get the validation rules that apply to the request.
   */
  public function rules(): array
  {
    return [
      'supplier_id' => ['required', 'integer', Rule::exists('suppliers', 'id')],
      'has_discount' => ['nullable', 'boolean'],
      'from_purchased_at' => ['required', 'date'],
      'to_purchased_at' => ['nullable', 'date']
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
