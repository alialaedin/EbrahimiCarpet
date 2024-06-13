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
      'amount' => ['required', 'integer', 'min:1000'],
      'type' => ['required', 'string', 'in:cash,cheque,installment'],
      'payment_date' => ['nullable', 'date'],
      'due_date' => ['nullable', 'date'],
      'image' => ['nullable', 'file', 'mimes:png,jpg'],
      'status' => ['required', 'boolean'],
      'description' => ['nullable', 'string']
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
    $status = $this->input('status');

    if ($this->filled('payment_date') && $this->input('amount') > $remainingAmount) {
      throw Helpers::makeWebValidationException('مبلغ پرداختی بیشتر از مبلغ قابل پرداخت است.', 'amount');
    }
    if ($type == 'cheque') {

      if ($this->isNotFilled('due_date')) {
        throw Helpers::makeWebValidationException('تاریخ موعد چک را مشخص کنید.', 'due_date');
      } elseif ($this->filled('payment_date') && $status == 0) {
        throw Helpers::makeWebValidationException('وضعیت چک درحالی که تاریخ پرداخت دارد نمی تواند غیرفعال باشد.', 'status');
      }
    } elseif ($type == 'cash') {

      if ($this->isNotFilled('payment_date')) {
        throw Helpers::makeWebValidationException('تاریخ پرداخت را مشخص کنید.', 'payment_date');
      } elseif ($status == 0) {
        throw Helpers::makeWebValidationException('وضعیت پرداخت نقدی باید فعال باشد.', 'status');
      }
    } elseif ($type == 'installment') {

      if ($this->isNotFilled('due_date')) {
        throw Helpers::makeWebValidationException('تاریخ موعد قسط را مشخص کنید.', 'due_date');
      } elseif ($status == 1 && $this->isNotFilled('payment_date')) {
        throw Helpers::makeWebValidationException('قسطی که وضعیت آن فعال است باید تاریخ پرداختش مشخص شود.', 'payment_date');
      }
    }
  }

  public function authorize(): bool
  {
    return true;
  }
}
