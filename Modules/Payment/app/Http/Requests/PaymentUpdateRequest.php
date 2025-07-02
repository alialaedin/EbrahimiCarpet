<?php

namespace Modules\Payment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Modules\Core\Helpers\Helpers;

class PaymentUpdateRequest extends FormRequest
{
  protected function prepareForValidation(): void
  {
    $this->merge([
      'amount' => str_replace(',', '', $this->input('amount')),
    ]);
  }

  public function rules(): array
  {
    return [
      'description' => ['nullable', 'string'],
      'amount' => ['required', 'integer', 'min:1000'],
      'payment_date' => ['nullable', 'date'],
      'cheque_serial' => ['nullable', 'numeric'],
      'cheque_holder' => ['nullable', 'string', 'min:5', 'max:90'],
      'bank_name' => ['nullable', 'string', 'min:3', 'max:90'],
      'pay_to' => ['nullable', 'string', 'min:3', 'max:90'],
      'due_date' => ['nullable', 'date'],
      'is_mine' => ['nullable', 'boolean'],
      'status' => ['nullable', 'boolean'],
    ];
  }

  /**  
   * @throws ValidationException  
   */
  public function passedValidation(): void
  {
    $payment = $this->route('payment');
    $supplier = $payment->supplier;
    $type = $this->input('type');

    $requiredFields = $this->getRequiredFields($type);
    $this->checkRequiredFields($requiredFields);

    $this->mergeAdditionalData($supplier);
  }

  protected function getRequiredFields(string $type): array
  {
    $fields = [
      'cheque' => ['amount', 'cheque_serial', 'cheque_holder', 'bank_name', 'pay_to', 'due_date'],
      'cash' => ['amount', 'payment_date'],
      'installment' => ['amount', 'due_date'],
    ];

    return $fields[$type] ?? [];
  }

  protected function checkRequiredFields(array $requiredFields): void
  {
    foreach ($requiredFields as $field) {
      if ($this->isNotFilled($field)) {
        throw Helpers::makeWebValidationException($this->getErrorMessage($field), $field);
      }
    }
  }

  protected function getErrorMessage(string $field): string
  {
    $messages = [
      'amount' => 'مبلغ پرداختی را وارد کنید.',
      'cheque_serial' => 'شماره سریال چک الزامی است!',
      'cheque_holder' => 'نام و نام خانوادگی صاحب چک الزامی است!',
      'bank_name' => 'نام بانک الزامی است!',
      'pay_to' => 'در وجه چک الزامی است!',
      'due_date' => 'تاریخ موعد چک الزامی است!',
      'payment_date' => 'تاریخ پرداخت را مشخص کنید.',
    ];

    return $messages[$field] ?? 'فیلد الزامی است.';
  }

  protected function mergeAdditionalData($supplier): void
  {
    $this->merge([
      'supplier' => $supplier,
      'status' => $this->filled('status') ? 1 : 0,
      'is_mine' => $this->filled('is_mine') ? 1 : 0,
    ]);
  }

  public function authorize(): bool
  {
    return true;
  }
}
