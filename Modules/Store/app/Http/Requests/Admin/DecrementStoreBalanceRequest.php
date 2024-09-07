<?php

namespace Modules\Store\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Modules\Core\Helpers\Helpers;

class DecrementStoreBalanceRequest extends FormRequest
{
  public function prepareForValidation(): void
  {
    if (!is_float((float)$this->quantity)) {
      throw Helpers::makeWebValidationException('تعداد وارد شده عدد معتبری نیست', 'quantity');
    }

    $this->merge([ 
      'quantity' => number_format((float) $this->quantity, 2, '.', '')
    ]);
  }
  
  public function rules(): array
  {
    return [
      'quantity' => ['required', 'min:0.01', 'decimal:2'],
    ];
  }

  /**
   * @throws ValidationException
   */
  protected function passedValidation(): void
  {
    $quantity = $this->input('quantity');
    $balance = $this->input('balance');

    if ($quantity > $balance) {
      throw Helpers::makeWebValidationException('مقدار وارد شده بیشتر از موجودی انبار است!.', 'quantity');
    }
  }

  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }
}
