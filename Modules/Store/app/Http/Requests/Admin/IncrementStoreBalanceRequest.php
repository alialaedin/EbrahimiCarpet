<?php

namespace Modules\Store\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Helpers\Helpers;

class IncrementStoreBalanceRequest extends FormRequest
{
  protected function prepareForValidation(): void
  {
    if (!is_float((float)$this->quantity)) {
      throw Helpers::makeWebValidationException('تعداد وارد شده عدد معتبری نیست', 'quantity');
    }

    $this->merge([
      'purchased_price' => str_replace(',', '', $this->input('purchased_price')),
      'quantity' => number_format((float) $this->quantity, 2, '.', '')
    ]);
  }

  public function rules(): array
  {
    return [
      'quantity' => ['required', 'min:1', 'decimal:2'],
      'purchased_price' => ['required', 'integer', 'min:1000']
    ];
  }
  
  public function authorize(): bool
  {
    return true;
  }
}
