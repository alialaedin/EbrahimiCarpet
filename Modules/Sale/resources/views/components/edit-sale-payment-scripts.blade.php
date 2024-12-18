@foreach ($cashPayments as $cashPayment)
  <x-core::date-input-script 
    textInputId="cash_payment_date_show-{{ $cashPayment->id }}" 
    dateInputId="cash_payment_date_hidden-{{ $cashPayment->id }}"
  />
@endforeach
@foreach ($chequePayments as $chequePayment)
  <x-core::date-input-script
    textInputId="cheque_due_date_show-{{ $chequePayment->id }}"
    dateInputId="cheque_due_date_hidden-{{ $chequePayment->id }}"
  />
  <x-core::date-input-script
    textInputId="cheque_payment_date_show-{{ $chequePayment->id }}"
    dateInputId="cheque_payment_date_hidden-{{ $chequePayment->id }}"
  />
@endforeach
@foreach ($installmentPayments as $installmentPayment)
  <x-core::date-input-script
    textInputId="installment_payment_date_show-{{ $installmentPayment->id }}"
    dateInputId="installment_payment_date_hidden-{{ $installmentPayment->id }}"
  />
  <x-core::date-input-script
    textInputId="installment_due_date_show-{{ $installmentPayment->id }}"
    dateInputId="installment_due_date_hidden-{{ $installmentPayment->id }}"
  />
@endforeach
