<?php

namespace Modules\Payment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Modules\Core\Helpers\Helpers;
use Modules\Purchase\Models\Purchase;
use Modules\Supplier\Models\Supplier;

class PaymentStoreRequest extends FormRequest
{
	protected function prepareForValidation(): void
	{
    $this->merge([
      'cash_amount' => $this->input('cash_amount') ? str_replace(',', '', $this->input('cash_amount')) : null,
      'cheque_amount' => $this->input('cheque_amount') ? str_replace(',', '', $this->input('cheque_amount')) : null,
      'installment_amount' => $this->input('installment_amount') ? str_replace(',', '', $this->input('installment_amount')) : null,
    ]);
	}

	public function rules(): array
	{
		return [
      'supplier_id' => ['required', 'integer', 'exists:suppliers,id'],
      'type' => ['required', 'string', 'in:cash,cheque,installment'],
      'description' => ['nullable', 'string'],

      // Cash Payment
      'cash_amount' => ['nullable', 'integer', 'min:1000'],
      'cash_payment_date' => ['nullable', 'date'],
      'image' => ['nullable', 'file', 'mimes:png,jpg', 'max:2048'],

      // Cheque Payment
      'cheque_amount' => ['nullable', 'integer', 'min:1000'],
      'cheque_serial' => ['nullable', 'numeric'],
      'cheque_holder' => ['nullable', 'string', 'min:5', 'max:90'],
      'bank_name' => ['nullable', 'string', 'min:3', 'max:90'],
      'pay_to' => ['nullable', 'string', 'min:3', 'max:90'],
      'cheque_due_date' => ['nullable', 'date'],
      'is_mine' => ['nullable', 'boolean'],

      // Installment Payment
      'installment_amount' => ['nullable', 'integer', 'min:1000'],
      'number_of_installments' => ['nullable', 'integer', 'min:1'],
      'installment_start_date' => ['nullable', 'date'],
		];
	}

	/**
	 * @throws ValidationException
	 */
	public function passedValidation(): void
	{
		$supplier = Supplier::query()->findOrFail($this->input('supplier_id'));
		$type = $this->input('type');
    // $remainingAmount = $supplier->remaining_amount;

    if ($type == 'cheque') {

      if ($this->isNotFilled('cheque_amount')) {
        throw Helpers::makeWebValidationException('مبلغ چک را وارد کنید.', 'cheque_amount');
      }elseif ($this->isNotFilled('cheque_serial')) {
        throw Helpers::makeWebValidationException('شماره سریال چک الزامی است!', 'cheque_serial');
      }elseif ($this->isNotFilled('cheque_holder')) {
        throw Helpers::makeWebValidationException('نام و نام خانوادگی صاحب چک الزامی است!', 'cheque_holder');
      }elseif ($this->isNotFilled('bank_name')) {
        throw Helpers::makeWebValidationException('نام بانک الزامی است!', 'bank_name');
      }elseif ($this->isNotFilled('pay_to')) {
        throw Helpers::makeWebValidationException('در وجه چک الزامی است!', 'pay_to');
      }elseif ($this->isNotFilled('cheque_due_date')) {
        throw Helpers::makeWebValidationException('تاریخ موعد چک الزامی است!', 'cheque_due_date');
      }/*elseif ($this->input('cheque_amount') > $remainingAmount) {
        throw Helpers::makeWebValidationException('مبلغ پرداختی بیشتر از مبلغ قابل پرداخت است.', 'cheque_amount');
      }*/

    } elseif ($type == 'cash') {

      if ($this->isNotFilled('cash_payment_date')) {
        throw Helpers::makeWebValidationException('تاریخ پرداخت را مشخص کنید.', 'cash_payment_date');
      } elseif ($this->isNotFilled('cash_amount')) {
        throw Helpers::makeWebValidationException('مبلغ پرداختی را وارد کنید.', 'cash_amount');
      }/*elseif ($this->input('cash_amount') > $remainingAmount) {
        throw Helpers::makeWebValidationException('مبلغ پرداختی بیشتر از مبلغ قابل پرداخت است.', 'cash_amount');
      }*/

    }elseif ($type == 'installment') {

      if ($this->isNotFilled('number_of_installments')) {
        throw Helpers::makeWebValidationException('تعداد قسط الزامی است.', 'number_of_installments');
      } elseif ($this->isNotFilled('installment_amount')) {
        throw Helpers::makeWebValidationException('مبلغ قسط را وارد کنید.', 'installment_amount');
      }elseif ($this->isNotFilled('installment_start_date')) {
        throw Helpers::makeWebValidationException('تاریخ شروع قسط الزامی است.', 'installment_start_date');
      }

    }

    $this->merge([
      'supplier' => $supplier
    ]);
	}

	public function authorize(): bool
	{
		return true;
	}
}
