<?php

namespace Modules\Sale\Http\Requests\Admin\SaleItem;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Modules\Core\Helpers\Helpers;
use Modules\Product\Models\Product;

class SaleItemStoreRequest extends FormRequest
{
  protected function prepareForValidation(): void
  {
    $this->merge([
      'price' => str_replace(',', '', $this->input('price')),
      'discount' => $this->filled('discount') ? str_replace(',', '', $this->input('discount')) : null,
    ]);
  }

  public function rules(): array
  {
    return [
      'sale_id' => ['required', 'integer', 'exists:sales,id'],
      'product_id' => [
        'required',
        'integer',
        Rule::exists('products', 'id'),
        Rule::unique('sale_items', 'product_id')
          ->where('sale_id', $this->input('sale_id'))],
      'quantity' => ['required', 'integer', 'min:1'],
      'discount' => ['nullable', 'integer'],
      'price' => ['required', 'integer', 'min:1000'],
    ];
  }

  /**
   * @throws ValidationException
   */
  public function passedValidation(): void
  {
    if ($this->filled('discount')) {
      if ($this->input('discount') > $this->input('price')) {
        throw Helpers::makeWebValidationException('مبلغ تخفیف نی تواند بیشتر از قیمت محصول باشد.', 'discount');
      }
    }

    $product = Product::query()->with('stores')->find($this->input('product_id'), 'id');
    $balance = $product->stores->sum('balance');

    if ($this->input('quantity') > $balance) {
      throw Helpers::makeWebValidationException('تعداد وارد شده از موجودی انبار بیشتر است.', 'quantity');
    }
  }

  public function authorize(): bool
  {
      return true;
  }
}
