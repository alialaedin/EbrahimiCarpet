<?php

namespace Modules\Accounting\Http\Requests\Admin\Salary;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class SalaryUpdateRequest extends FormRequest
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
      'amount' => ['required', 'integer', 'min:1000'],
      'overtime' => ['nullable', 'integer', 'min:1'],
      'payment_date' => ['required', 'date'],
      'receipt_image' => ['nullable', File::image()->max(2024), 'mimes:' . implode(',', config('core.accept_image_mimes'))],
      'description' => ['nullable', 'string']
    ];
  }

  public function authorize(): bool
  {
    return true;
  }
}
