<?php

namespace Modules\Sale\Http\Requests\Admin\Sale;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Modules\Core\Helpers\Helpers;

class SaleUpdateRequest extends FormRequest
{
  public function prepareForValidation(): void
  {
    $this->merge([
      'discount' => $this->filled('discount') ? str_replace(',', '', $this->input('discount')) : null,
    ]);
  }

  public function rules(): array
  {
    return [
      'sold_at' => ['required', 'date'],
      'discount' => ['nullable', 'integer', 'min:1000'],
      'employee_id' => ['required', 'integer', Rule::exists('employees', 'id')],
      'discount_for' => ['nullable', 'string'],
    ];
  }

  /**
   * @throws ValidationException
   */
  public function passedValidation(): void
  {
    if ($this->filled('discount') && $this->isNotFilled('discount_for')) {
      throw Helpers::makeWebValidationException('فیلد بابت تخفیف الزامی است!.', 'discount_for');
    }
  }

  public function authorize(): bool
  {
    return true;
  }
}
