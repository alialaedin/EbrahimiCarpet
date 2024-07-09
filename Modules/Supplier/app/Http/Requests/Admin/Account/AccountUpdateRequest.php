<?php

namespace Modules\Supplier\Http\Requests\Admin\Account;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Modules\Core\Helpers\Helpers;
use Modules\Core\Rules\IranianCardNumber;

class AccountUpdateRequest extends FormRequest
{
  /**
   * Get the validation rules that apply to the request.
   */
  public function rules(): array
  {
    $accountId = $this->route('account')->id;

    return [
      'supplier_id' => ['required', 'integer', Rule::exists('suppliers', 'id')],
      'bank_name' => ['required' ,'string', 'min:3', 'max:50'],
      'account_number' => ['required' ,'string', 'max:20', Rule::unique('accounts', 'account_number')->ignore($accountId)],
      'card_number' => [
        'required' ,
        'integer',
        'digits:16',
        Rule::unique('accounts', 'card_number')->ignore($accountId),
        new IranianCardNumber()
      ]
    ];
  }

  /**
   * @throws ValidationException
   */
  public function passedValidation(): void
  {
    if (!is_numeric($this->input('account_number'))) {
      throw Helpers::makeWebValidationException('فیلد شماره حساب باید فقط عدد باشد.', 'account_number');
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
