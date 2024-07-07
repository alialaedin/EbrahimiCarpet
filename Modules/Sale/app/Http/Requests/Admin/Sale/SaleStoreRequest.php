<?php

namespace Modules\Sale\Http\Requests\Admin\Sale;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Modules\Core\Helpers\Helpers;

class SaleStoreRequest extends FormRequest
{
  public function prepareForValidation(): void
  {
    $products = [];

    foreach ($this->input('products') as $index => $product) {
      $products[] = [
        'id' => $product['id'],
        'quantity' => $product['quantity'],
        'price' => str_replace(',', '', $product['price']),
        'discount' => str_replace(',', '', $product['discount']),
        'balance' => $product['balance']
      ];
    }

    $this->merge([
      'discount' => $this->filled('discount') ? str_replace(',', '', $this->input('discount')) : null,
      'products' => $products
    ]);

  }

  public function rules(): array
  {
    return [
      'customer_id' => ['required', 'integer', 'exists:customers,id'],
      'sold_at' => ['required', 'date'],
      'employee_id' => ['required', 'integer', Rule::exists('employees', 'id')],
      'discount_for' => ['nullable', 'string'],
      'discount' => ['nullable', 'integer', 'min:1000'],
      'products' => ['required', 'array'],
      'products.*.id' => ['required', 'integer', 'exists:products,id'],
      'products.*.quantity' => ['required', 'integer', 'min:1'],
    ];
  }

  /**
   * @throws ValidationException
   */
  public function passedValidation(): void
  {
    if ($this->input('sold_at') > Carbon::now()) {
      throw Helpers::makeWebValidationException('تاریخ خرید نمی تواند از تاریخ امروز بزرگ تر باشد.', 'sold_at');
    }
    if ($this->filled('discount') && $this->isNotFilled('discount_for')) {
      throw Helpers::makeWebValidationException('فیلد بابت تخفیف الزامی است!.', 'discount_for');
    }

    foreach ($this->input('products') as $index => $product) {
      if ($product['quantity'] > $product['balance']) {
        throw Helpers::makeWebValidationException('تعداد محصول انتخاب بیشتر از موجودی است.', "products[$index][quantity]");
      }
    }
  }
    public function authorize(): bool
    {
        return true;
    }
}
