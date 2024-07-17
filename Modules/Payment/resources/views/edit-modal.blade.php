@foreach ($payments as $payment)
  <div class="modal fade" id="editPaymentModal-{{ $payment->id }}" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="margin-top: 15vh;">
      <div class="modal-content modal-content-demo">
        <div class="modal-header">
          <p class="modal-title" style="font-size: 20px;">ویرایش پرداختی - کد {{ $payment->id }}</p>
          <button aria-label="Close" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('admin.payments.update', $payment) }}" method="post" class="save">
            @csrf
            @method('PATCH')
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="type" class="control-label">نوع پرداخت: <span class="text-danger">&starf;</span></label>
                  <input class="form-control" id="type" type="text"
                         value="{{ config('payment.types.'.$payment->type) }}" readonly>
                </div>
              </div>
              @if($payment->type === 'cash')
                <div class="col-12">
                  <div class="form-group">
                    <label for="amount" class="control-label">مبلغ پرداخت (ریال): <span
                        class="text-danger">&starf;</span></label>
                    <input
                      type="text"
                      id="amount"
                      class="form-control comma"
                      name="amount"
                      placeholder="مبلغ پرداختی را به ریال وارد کنید"
                      value="{{ old('amount', number_format($payment->amount)) }}"
                    />
                    <x-core::show-validation-error name="amount"/>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label for="cash_payment_date_show" class="control-label">تاریخ پرداخت:</label>
                    <input class="form-control fc-datepicker" id="cash_payment_date_show-{{ $payment->id }}" type="text"
                           autocomplete="off"
                           placeholder="تاریخ پرداخت را در صورت نیاز وارد کنید"/>
                    <input name="payment_date" id="cash_payment_date_hidden-{{ $payment->id }}" type="hidden" required
                           value="{{	old('payment_date', $payment->payment_date) }}"/>
                    <x-core::show-validation-error name="payment_date"/>
                  </div>
                </div>
              @elseif($payment->type === 'cheque')
                <div class="col-12 col-lg-6">
                  <div class="form-group">
                    <label for="amount" class="control-label">مبلغ چک (ریال): <span
                        class="text-danger">&starf;</span></label>
                    <input
                      type="text"
                      id="amount"
                      class="form-control comma"
                      name="amount"
                      placeholder="مبلغ پرداختی را به ریال وارد کنید"
                      value="{{ old('amount', number_format($payment->amount)) }}"
                    />
                    <x-core::show-validation-error name="amount"/>
                  </div>
                </div>
                <div class="col-12 col-lg-6">
                  <div class="form-group">
                    <label for="cheque_serial" class="control-label">سریال چک: <span class="text-danger">&starf;</span></label>
                    <input
                      type="text"
                      id="cheque_serial"
                      class="form-control"
                      name="cheque_serial"
                      placeholder="سریال چک وارد کنید"
                      value="{{ old('cheque_serial', $payment->cheque_serial) }}"
                    />
                    <x-core::show-validation-error name="cheque_serial"/>
                  </div>
                </div>
                <div class="col-12 col-lg-6">
                  <div class="form-group">
                    <label for="cheque_holder" class="control-label">نام و نام خانوادگی صاحب چک: <span
                        class="text-danger">&starf;</span></label>
                    <input
                      type="text"
                      id="cheque_holder"
                      class="form-control"
                      name="cheque_holder"
                      placeholder="نام و نام خانوادگی صاحب وارد کنید"
                      value="{{ old('cheque_holder', $payment->cheque_holder) }}"
                    />
                    <x-core::show-validation-error name="cheque_holder"/>
                  </div>
                </div>
                <div class="col-12 col-lg-6">
                  <div class="form-group">
                    <label for="bank_name" class="control-label">نام بانک: <span
                        class="text-danger">&starf;</span></label>
                    <input

                      type="text"
                      id="bank_name"
                      class="form-control"
                      name="bank_name"
                      placeholder="نام بانک وارد کنید"
                      value="{{ old('bank_name', $payment->bank_name) }}"
                    />
                    <x-core::show-validation-error name="bank_name"/>
                  </div>
                </div>
                <div class="col-12 col-lg-6">
                  <div class="form-group">
                    <label for="pay_to" class="control-label">در وجه: <span class="text-danger">&starf;</span></label>
                    <input
                      type="text"
                      id="pay_to"
                      class="form-control"
                      name="pay_to"
                      placeholder="چک در وجه چه کسی است"
                      value="{{ old('pay_to', $payment->pay_to) }}"
                    />
                    <x-core::show-validation-error name="pay_to"/>
                  </div>
                </div>
                <div class="col-12 col-lg-6">
                  <div class="form-group">
                    <label for="cheque_payment_date_show" class="control-label">تاریخ پرداخت:</label>
                    <input class="form-control fc-datepicker" id="cheque_payment_date_show-{{ $payment->id }}"
                           type="text"
                           autocomplete="off" placeholder="تاریخ پرداخت را در صورت نیاز وارد کنید"/>
                    <input name="payment_date" id="cheque_payment_date_hidden-{{ $payment->id }}" type="hidden" required
                           value="{{	old('payment_date', $payment->payment_date) }}"/>
                    <x-core::show-validation-error name="payment_date"/>
                  </div>
                </div>
                <div class="col-12 col-lg-6">
                  <div class="form-group">
                    <label for="cheque_due_date_show" class="control-label">تاریخ سررسید:<span class="text-danger">&starf;</span></label>
                    <input class="form-control fc-datepicker" id="cheque_due_date_show-{{ $payment->id }}" type="text"
                           autocomplete="off"
                           placeholder="تاریخ سررسید را در صورت نیاز وارد کنید"/>
                    <input name="due_date" id="cheque_due_date_hidden-{{ $payment->id }}" type="hidden" required
                           value="{{	old('due_date', $payment->due_date) }}"/>
                    <x-core::show-validation-error name="due_date"/>
                  </div>
                </div>
                <div class="col-12 col-lg-3">
                  <div class="form-group">
                    <label class="control-label"> چک برای خودم است:</label>
                    <div class="custom-controls-stacked">
                      <label class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="is_mine"
                               value="1" @checked(old('is_mine', $payment->is_mine) == '1')>
                        <span class="custom-control-label">بله</span>
                      </label>
                    </div>
                    <x-core::show-validation-error name="is_mine"/>
                  </div>
                </div>
                <div class="col-12 col-lg-3">
                  <div class="form-group">
                    <label for="label" class="control-label"> وضعیت: </label>
                    <label class="custom-control custom-checkbox">
                      <input type="checkbox" class="custom-control-input" name="status"
                             value="1" @checked(old('status', $payment->status))>
                      <span class="custom-control-label">پاس شده</span>
                    </label>
                    <x-core::show-validation-error name="status"/>
                  </div>
                </div>
              @elseif($payment->type === 'installment')
                <div class="col-12 col-lg-6">
                  <div class="form-group">
                    <label for="amount" class="control-label">مبلغ قسط: <span class="text-danger">&starf;</span></label>
                    <input
                      type="text"
                      id="amount"
                      class="form-control comma"
                      name="amount"
                      placeholder="مبلغ هر قسط را وارد کنید"
                      value="{{ old('amount', number_format($payment->amount)) }}"
                    />
                    <x-core::show-validation-error name="amount"/>
                  </div>
                </div>
                <div class="col-12 col-lg-6">
                  <div class="form-group">
                    <label for="installment_payment_date_show" class="control-label">تاریخ پرداخت:</label>
                    <input class="form-control fc-datepicker" id="installment_payment_date_show-{{ $payment->id }}"
                           type="text"
                           autocomplete="off" placeholder="تاریخ پرداخت را در صورت نیاز وارد کنید"/>
                    <input name="payment_date" id="installment_payment_date_hidden-{{ $payment->id }}" type="hidden"
                           required
                           value="{{	old('payment_date', $payment->payment_date) }}"/>
                    <x-core::show-validation-error name="payment_date"/>
                  </div>
                </div>
                <div class="col-12 col-lg-6">
                  <div class="form-group">
                    <label for="installment_due_date_show" class="control-label">تاریخ سررسید:<span class="text-danger">&starf;</span></label>
                    <input class="form-control fc-datepicker" id="installment_due_date_show-{{ $payment->id }}"
                           type="text"
                           autocomplete="off" placeholder="تاریخ سررسید را در صورت نیاز وارد کنید"/>
                    <input name="due_date" id="installment_due_date_hidden-{{ $payment->id }}" type="hidden" required
                           value="{{	old('due_date', $payment->due_date) }}"/>
                    <x-core::show-validation-error name="due_date"/>
                  </div>
                </div>
                <div class="col-12 col-lg-6">
                  <div class="form-group">
                    <label for="label" class="control-label"> وضعیت: </label>
                    <label class="custom-control custom-checkbox">
                      <input type="checkbox" class="custom-control-input" name="status"
                             value="1" @checked($payment->status)>
                      <span class="custom-control-label">پرداخت شده</span>
                    </label>
                    <x-core::show-validation-error name="status"/>
                  </div>
                </div>
              @endif
              <div class="col-12">
                <div class="form-group">
                  <label for="description" class="control-label"> توضیحات: </label>
                  <textarea id="description" class="form-control" rows="2" name="description">
                      {{ $payment->description }}
                    </textarea>
                  <x-core::show-validation-error name="description"/>
                </div>
              </div>

            </div>

            <div class="modal-footer">
              <button class="btn btn-warning" type="submit">بروزرسانی</button>
              <button class="btn btn-danger" data-dismiss="modal">انصراف</button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
@endforeach

