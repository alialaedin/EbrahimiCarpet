<?php

namespace Modules\Sale\Http\Requests\Admin\SaleItem;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Modules\Core\Helpers\Helpers;

class SaleItemUpdateRequest extends FormRequest
{
  public function rules(): array
  {
    return [
      'quantity' => ['required', 'integer', 'min:1'],
    ];
  }

  /**
   * @throws ValidationException
   */

  public function passedValidation()
  {
    $quantity = $this->input('quantity');
    $saleItem = $this->route('sale_item');

    if ($quantity > $saleItem->product->store->balance + $quantity) {
      throw Helpers::makeWebValidationException('تعداد انتخاب شده بیشتر از موجودی انبار است.');
    }
  }

  public function authorize(): bool
  {
      return true;
  }
}
