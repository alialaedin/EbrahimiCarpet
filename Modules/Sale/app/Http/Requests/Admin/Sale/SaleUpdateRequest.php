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
      'discount' => ['nullable', 'integer'],
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
    if ($this->input('discount') > 0 && $this->input('discount') < 10000) {
      throw Helpers::makeWebValidationException('تخفیف باید بیشتر از 10000 ریال باشذ!.', 'discount');
    }
  }

  public function authorize(): bool
  {
    return true;
  }
}
