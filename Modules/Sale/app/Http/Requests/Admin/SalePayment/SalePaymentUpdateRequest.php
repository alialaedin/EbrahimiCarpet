<?php

namespace Modules\Sale\Http\Requests\Admin\SalePayment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Modules\Core\Helpers\Helpers;

class SalePaymentUpdateRequest extends FormRequest
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
      'cheque_serial' => ['nullable', 'integer'],
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
    $salePayment = $this->route('sale_payment');
    $customer = $salePayment->customer;
    $remainingAmount = $customer->calcTotalSalesAmount() - $customer->payments->sum('amount') + $salePayment->amount;
    $type = $this->input('type');

    if ($type == 'cheque') {

      if ($this->isNotFilled('amount')) {
        throw Helpers::makeWebValidationException('مبلغ چک را وارد کنید.', 'amount');
      }elseif ($this->isNotFilled('cheque_serial')) {
        throw Helpers::makeWebValidationException('شماره سریال چک الزامی است!', 'cheque_serial');
      }elseif ($this->isNotFilled('cheque_holder')) {
        throw Helpers::makeWebValidationException('نام و نام خانوادگی صاحب چک الزامی است!', 'cheque_holder');
      }elseif ($this->isNotFilled('bank_name')) {
        throw Helpers::makeWebValidationException('نام بانک الزامی است!', 'bank_name');
      }elseif ($this->isNotFilled('pay_to')) {
        throw Helpers::makeWebValidationException('در وجه چک الزامی است!', 'pay_to');
      }elseif ($this->isNotFilled('due_date')) {
        throw Helpers::makeWebValidationException('تاریخ موعد چک الزامی است!', 'due_date');
      }elseif ($this->input('amount') > $remainingAmount) {
        throw Helpers::makeWebValidationException('مبلغ پرداختی بیشتر از مبلغ قابل پرداخت است.', 'amount');
      }

    } elseif ($type == 'cash') {

      if ($this->isNotFilled('payment_date')) {
        throw Helpers::makeWebValidationException('تاریخ پرداخت را مشخص کنید.', 'payment_date');
      } elseif ($this->isNotFilled('amount')) {
        throw Helpers::makeWebValidationException('مبلغ پرداختی را وارد کنید.', 'amount');
      }elseif ($this->input('amount') > $remainingAmount) {
        throw Helpers::makeWebValidationException('مبلغ پرداختی بیشتر از مبلغ قابل پرداخت است.', 'amount');
      }

    }elseif ($type == 'installment') {

      if ($this->isNotFilled('due_date')) {
        throw Helpers::makeWebValidationException('تاریخ موعد قسط الزامی است.', 'due_date');
      } elseif ($this->isNotFilled('amount')) {
        throw Helpers::makeWebValidationException('مبلغ قسط الزامی است.', 'amount');
      }

    }

    $this->merge([
      'customer' => $customer,
      'status' => $this->filled('status') ? 1 : 0
    ]);
  }

  // public function validated($key = null, $default = null) {

  //   $validatedData = parent::validated();
  //   $validatedData['status'] = $this->filled('status') ? 1 : 0;

  //   return $validatedData;
  // }

  public function authorize(): bool
  {
    return true;
  }
}
