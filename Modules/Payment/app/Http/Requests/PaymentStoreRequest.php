<?php

namespace Modules\Payment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Helpers\Helpers;
use Modules\Purchase\Models\Purchase;

class PaymentStoreRequest extends FormRequest
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
			'purchase_id' => ['required', 'integer', 'exists:purchases,id'],
			'amount' => ['required', 'integer', 'min:1000'],
			'type' => ['required', 'string', 'in:cash,cheque,installment'],
			'payment_date' => ['nullable', 'date'],
			'due_date' => ['nullable', 'date'],
			'image' => ['nullable', 'file', 'mimes:png,jpg'],
			'status' => ['required', 'boolean'],
			'description' => ['nullable', 'string']
		];
	}

	public function passedValidation()
	{
		$purchase = Purchase::findOrFail($this->input('purchase_id'));

		$totalAmountWithDiscount = $purchase->getTotalAmountWithDiscount();
		$totalPaymentAmount = $purchase->getTotalPaymentAmount();
		$remainingAmount = $totalAmountWithDiscount - $totalPaymentAmount;

		$type = $this->input('type');
		$status = $this->input('status');

		if ($status == 1 && $this->input('amount') > $remainingAmount) {
			throw Helpers::makeWebValidationException('مبلغ پرداختی بیشتر از مبلغ قابل پرداخت است.');
		}

		if ($type == 'cheque') {

			if ($this->isNotFilled('due_date')) {
				throw Helpers::makeWebValidationException('تاریخ موعد چک را مشخص کنید.');
			}elseif ($this->filled('payment_date') && $status == 0) {
				throw Helpers::makeWebValidationException('وضعیت چک درحالی که تاریخ پرداخت دارد نمی تواند غیرفعال باشد.');
			}

		}elseif ($type == 'cash') {

			if ($this->isNotFilled('payment_date')) {
				throw Helpers::makeWebValidationException('تاریخ پرداخت را مشخص کنید.');
			}elseif ($status == 0) {
				throw Helpers::makeWebValidationException('وضعیت پرداخت نقدی باید فعال باشد.');
			}

		}elseif ($type == 'installment') {

			if ($this->isNotFilled('due_date')) {
				throw Helpers::makeWebValidationException('تاریخ موعد قسط را مشخص کنید.');
			}elseif ($status == 1 && $this->isNotFilled('payment_date')) {
				throw Helpers::makeWebValidationException('قسطی که وضعیت وضعیت آن فعال است باید تاریخ پرداختش مشخص شود.');
			}

		}

		$this->merge([
			'purchase' => $purchase
		]);
	}

	public function authorize(): bool
	{
		return true;
	}
}