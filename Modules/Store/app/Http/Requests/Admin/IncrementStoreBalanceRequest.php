<?php

namespace Modules\Store\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class IncrementStoreBalanceRequest extends FormRequest
{
  protected function prepareForValidation(): void
  {
    $this->merge([
      'purchased_price' => str_replace(',', '', $this->input('purchased_price')),
    ]);
  }

  public function rules(): array
  {
    return [
      'quantity' => ['required', 'min:1', 'integer'],
      'purchased_price' => ['required', 'integer', 'min:1000']
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
