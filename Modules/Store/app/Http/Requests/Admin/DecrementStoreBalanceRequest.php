<?php

namespace Modules\Store\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Modules\Core\Helpers\Helpers;
use Modules\Product\Models\Product;

class DecrementStoreBalanceRequest extends FormRequest
{
  /**
   * Get the validation rules that apply to the request.
   */
  public function rules(): array
  {
    return [
      'quantity' => ['required', 'min:1', 'integer'],
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
