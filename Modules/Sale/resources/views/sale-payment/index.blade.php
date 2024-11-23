@extends('admin.layouts.master')
@section('content')
  <div class="page-header">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}">
          <i class="fe fe-home ml-1"></i> داشبورد
        </a>
      </li>
      <li class="breadcrumb-item active">
        <a>دریافتی ها</a>
      </li>
    </ol>
  </div>
  <div class="card">
    <div class="card-header border-0">
      <p class="card-title">جستجوی پیشرفته</p>
      <x-core::card-options/>
    </div>
    <div class="card-body">
      <div class="row">
        <form action="{{ route("admin.sale-payments.index") }}" class="col-12">
          <div class="row">
            <div class="col-12 col-md-6 col-xl-3">
              <div class="form-group">
                <label for="customer_id">مشتری :</label>
                <select name="customer_id" id="customer_id" class="form-control select2">
                  <option value="" class="text-muted">انتخاب</option>
                  @foreach ($customers as $customer)
                    <option
                      value="{{ $customer->id }}"
                      @selected(request("customer_id") == $customer->id)>
                      {{ $customer->name }} - {{ $customer->mobile }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
              <div class="form-group">
                <label for="type">نوع پرداخت :</label>
                <select name="type" id="type" class="form-control">
                  <option value="" class="text-muted">انتخاب</option>
                  @foreach(config('payment.types') as $name => $label)
                    <option
                      value="{{ $name }}"
                      @selected(request('type') == $name)>
                      {{ $label }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
              <div class="form-group">
                <label for="status">وضعیت :</label>
                <select name="status" id="status" class="form-control">
                  <option value="" class="text-muted">انتخاب</option>
                  <option value="1" @selected(request("status") == "1")>پرداخت شده</option>
                  <option value="0" @selected(request("status") == "0")>پرداخت نشده</option>
                </select>
              </div>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-12 col-lg-6 col-xl-3">
              <div class="form-group">
                <label for="from_payment_date_show">پرداخت از تاریخ : </label>
                <input class="form-control fc-datepicker" id="from_payment_date_show" type="text" autocomplete="off"/>
                <input name="from_payment_date" id="from_payment_date" type="hidden" value="{{ request("from_payment_date") }}"/>
                <x-core::show-validation-error name="from_payment_date"/>
              </div>
            </div>
            <div class="col-12 col-lg-6 col-xl-3">
              <div class="form-group">
                <label for="to_payment_date_show">تا تاریخ :</label>
                <input class="form-control fc-datepicker" id="to_payment_date_show" type="text" autocomplete="off"/>
                <input name="to_payment_date" id="to_payment_date" type="hidden" value="{{ request("to_payment_date") }}"/>
                <x-core::show-validation-error name="to_payment_date"/>
              </div>
            </div>
            <div class="col-12 col-lg-6 col-xl-3">
              <div class="form-group">
                <label for="from_due_date_show">سررسید از تاریخ : </label>
                <input class="form-control fc-datepicker" id="from_due_date_show" type="text" autocomplete="off"/>
                <input name="from_due_date" id="from_due_date" type="hidden" value="{{ request("from_due_date") }}"/>
                <x-core::show-validation-error name="from_due_date"/>
              </div>
            </div>
            <div class="col-12 col-lg-6 col-xl-3">
              <div class="form-group">
                <label for="to_due_date_show">تا تاریخ :</label>
                <input class="form-control fc-datepicker" id="to_due_date_show" type="text" autocomplete="off"/>
                <input name="to_due_date" id="to_due_date" type="hidden" value="{{ request("to_due_date") }}"/>
                <x-core::show-validation-error name="to_due_date"/>
              </div>
            </div>
          </div>
          <x-core::filter-buttons table="payments"/>
        </form>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header border-0">
      <p class="card-title">لیست تمام پرداختی ها ({{ $totalPayments }})</p>
      <x-core::card-options/>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <div class="dataTables_wrapper dt-bootstrap4 no-footer">
          <div class="row">
            <table class="table table-vcenter table-striped text-nowrap table-bordered border-bottom">
              <thead class="thead-light">
              <tr>
                <th class="text-center">ردیف</th>
                <th class="text-center">مشتری</th>
                <th class="text-center">مبلغ پرداختی (ریال)</th>
                <th class="text-center">نوع پرداخت</th>
                <th class="text-center">عکس رسید</th>
                <th class="text-center">تاریخ سررسید</th>
                <th class="text-center">تاریخ پرداخت</th>
                <th class="text-center">تاریخ ثبت</th>
                <th class="text-center">وضعیت</th>
                <th class="text-center">عملیات</th>
              </tr>
              </thead>
              <tbody>
              @forelse ($payments as $payment)
                <tr>
                  <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                  <td class="text-center">
                    <a href="{{ route('admin.customers.show', $payment->customer->id) }}">
                      {{ $payment->customer->name }}
                    </a>
                  </td>
                  <td class="text-center">{{ number_format($payment->amount) }}</td>
                  <td class="text-center">{{ config('payment.types.'.$payment->type) }}</td>
                  <td class="text-center m-0 p-0">
                    @if ($payment->image)
                      <figure class="figure my-2">
                        <a target="_blank" href="{{ Storage::url($payment->image) }}">
                          <img
                            src="{{ Storage::url($payment->image) }}"
                            class="img-thumbnail"
                            alt="image"
                            width="50"
                            style="max-height: 32px;"
                          />
                        </a>
                      </figure>
                    @else
                      <span> - </span>
                    @endif
                  </td>
                  <td class="text-center"> {{ $payment->getDueDate() }}</td>
                  <td class="text-center"> {{ $payment->getPaymentDate() }}</td>
                  <td class="text-center"> @jalaliDate($payment->created_at)</td>
                  <td class="text-center">
                    <x-core::badge
                      type="{{ $payment->status ? 'success' : 'danger' }}"
                      text="{{ config('payment.statuses.'.$payment->type.'.'.$payment->status) }}"
                    />
                  </td>
                  <td class="text-center">
                    <x-core::show-button route="admin.sale-payments.show" :model="$payment->customer"/>
                    <button
                      class="btn btn-sm btn-icon btn-teal "
                      onclick="showPaymentDescriptionModal('{{$payment->description}}')"
                      data-toggle="tooltip"
                      data-original-title="توضیحات">
                      <i class="fa fa-book"></i>
                    </button>
                    @can('edit sale_payments')
                      <button
                        data-target="#editSalePaymentModal-{{$payment->id}}"
                        class="btn btn-sm btn-icon btn-warning text-white"
                        data-toggle="modal">
                        <i class="fa fa-pencil" ></i>
                      </button>
                    @endcan
                    @can('delete sale_payments')
                      <x-core::delete-button route="admin.sale-payments.destroy" :model="$payment"/>
                    @endcan
                  </td>
                </tr>
              @empty
                <x-core::data-not-found-alert :colspan="10"/>
              @endforelse
              </tbody>
            </table>
            {{ $payments->onEachSide(0)->links("vendor.pagination.bootstrap-4") }}
          </div>
        </div>
      </div>
    </div>
  </div>
  @include('payment::_show-description-modal')
  @foreach ($payments as $payment)
    <div class="modal fade" id="editSalePaymentModal-{{ $payment->id }}" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-content-demo">
          <div class="modal-header">
            <p class="modal-title" style="font-size: 20px;">ویرایش پرداختی - کد {{ $payment->id }}</p><button aria-label="Close" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
          </div>
          <div class="modal-body">
            <form action="{{ route('admin.sale-payments.update', $payment) }}" method="post" class="save">
              @csrf
              @method('PATCH')
              <div class="row">

                @if($payment->type === 'cash')
                  <div class="col-12">
                    <div class="form-group">
                      <label for="type" class="control-label">نوع پرداخت: <span class="text-danger">&starf;</span></label>
                      <input class="form-control" type="text" value="{{ config('payment.types.'.$payment->type) }}" readonly>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-group">
                      <label for="amount" class="control-label">مبلغ پرداخت (ریال): <span class="text-danger">&starf;</span></label>
                      <input
                        type="text"
                        id="amount"
                        class="form-control comma"
                        name="amount"
                        placeholder="مبلغ پرداختی را به ریال وارد کنید"
                        value="{{ old('amount', number_format($payment->amount)) }}"
                      />
                      <x-core::show-validation-error name="amount" />
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-group">
                      <label for="cash_payment_date_show" class="control-label">تاریخ پرداخت:</label>
                      <input class="form-control fc-datepicker" id="cash_payment_date_show-{{ $payment->id }}" type="text" autocomplete="off" placeholder="تاریخ پرداخت را در صورت نیاز وارد کنید"/>
                      <input name="payment_date" id="cash_payment_date_hidden-{{ $payment->id }}" type="hidden" required value="{{	old('payment_date', $payment->payment_date) }}"/>
                      <x-core::show-validation-error name="payment_date" />
                    </div>
                  </div>
                @elseif($payment->type === 'cheque')
                  <div class="col-12 col-lg-6">
                    <div class="form-group">
                      <label for="amount" class="control-label">مبلغ چک (ریال): <span class="text-danger">&starf;</span></label>
                      <input
                        type="text"
                        id="amount"
                        class="form-control comma"
                        name="amount"
                        placeholder="مبلغ پرداختی را به ریال وارد کنید"
                        value="{{ old('amount', number_format($payment->amount)) }}"
                      />
                      <x-core::show-validation-error name="amount" />
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
                      <x-core::show-validation-error name="cheque_serial" />
                    </div>
                  </div>
                  <div class="col-12 col-lg-6">
                    <div class="form-group">
                      <label for="cheque_holder" class="control-label">نام و نام خانوادگی صاحب چک: <span class="text-danger">&starf;</span></label>
                      <input
                        type="text"
                        id="cheque_holder"
                        class="form-control"
                        name="cheque_holder"
                        placeholder="نام و نام خانوادگی صاحب وارد کنید"
                        value="{{ old('cheque_holder', $payment->cheque_holder) }}"
                      />
                      <x-core::show-validation-error name="cheque_holder" />
                    </div>
                  </div>
                  <div class="col-12 col-lg-6">
                    <div class="form-group">
                      <label for="bank_name" class="control-label">نام بانک: <span class="text-danger">&starf;</span></label>
                      <input

                        type="text"
                        id="bank_name"
                        class="form-control"
                        name="bank_name"
                        placeholder="نام بانک وارد کنید"
                        value="{{ old('bank_name', $payment->bank_name) }}"
                      />
                      <x-core::show-validation-error name="bank_name" />
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
                      <x-core::show-validation-error name="pay_to" />
                    </div>
                  </div>
                  <div class="col-12 col-lg-6">
                    <div class="form-group">
                      <label for="cheque_payment_date_show" class="control-label">تاریخ پرداخت:</label>
                      <input class="form-control fc-datepicker" id="cheque_payment_date_show-{{ $payment->id }}" type="text" autocomplete="off" placeholder="تاریخ پرداخت را در صورت نیاز وارد کنید"/>
                      <input name="payment_date" id="cheque_payment_date_hidden-{{ $payment->id }}" type="hidden" required value="{{	old('payment_date', $payment->payment_date) }}"/>
                      <x-core::show-validation-error name="payment_date" />
                    </div>
                  </div>
                  <div class="col-12 col-lg-6">
                    <div class="form-group">
                      <label for="cheque_due_date_show" class="control-label">تاریخ سررسید:</label>
                      <input class="form-control fc-datepicker" id="cheque_due_date_show-{{ $payment->id }}" type="text" autocomplete="off" placeholder="تاریخ سررسید را در صورت نیاز وارد کنید"/>
                      <input name="due_date" id="cheque_due_date_hidden-{{ $payment->id }}" type="hidden" required value="{{	old('due_date', $payment->due_date) }}"/>
                      <x-core::show-validation-error name="due_date" />
                    </div>
                  </div>
                  <div class="col-12 col-lg-6">
                    <div class="form-group">
                      <label class="control-label"> چک برای خودم است:<span class="text-danger">&starf;</span></label>
                      <div class="custom-controls-stacked">
                        <label class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" name="is_mine" value="1" @checked(old('is_mine', $payment->is_mine) == '1')>
                          <span class="custom-control-label">بله</span>
                        </label>
                      </div>
                      <x-core::show-validation-error name="is_mine" />
                    </div>
                  </div>
                @elseif($payment->type === 'installment')
                  <div class="col-12">
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
                      <x-core::show-validation-error name="amount" />
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-group">
                      <label for="installment_payment_date_show" class="control-label">تاریخ پرداخت:</label>
                      <input class="form-control fc-datepicker" id="installment_payment_date_show-{{ $payment->id }}" type="text" autocomplete="off" placeholder="تاریخ پرداخت را در صورت نیاز وارد کنید"/>
                      <input name="payment_date" id="installment_payment_date_hidden-{{ $payment->id }}" type="hidden" required value="{{	old('payment_date', $payment->payment_date) }}"/>
                      <x-core::show-validation-error name="payment_date" />
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-group">
                      <label for="installment_due_date_show" class="control-label">تاریخ سررسید:</label>
                      <input class="form-control fc-datepicker" id="installment_due_date_show-{{ $payment->id }}" type="text" autocomplete="off" placeholder="تاریخ سررسید را در صورت نیاز وارد کنید"/>
                      <input name="due_date" id="installment_due_date_hidden-{{ $payment->id }}" type="hidden" required value="{{	old('due_date', $payment->due_date) }}"/>
                      <x-core::show-validation-error name="due_date" />
                    </div>
                  </div>
                @endif

                <div class="col-12">
                  <div class="form-group">
                    <label for="label" class="control-label"> وضعیت: </label>
                    <label class="custom-control custom-checkbox">
                      <input type="checkbox" class="custom-control-input" name="status" value="1" @checked(old('status', $payment->status))>
                      @if ($payment->type === 'cheque')
                        <span class="custom-control-label">پاس شده</span>
                      @else
                        <span class="custom-control-label">پرداخت شده</span>
                      @endif
                    </label>
                    <x-core::show-validation-error name="status"/>
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
@endsection

@section('scripts')

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

  <x-core::date-input-script textInputId="from_payment_date_show" dateInputId="from_payment_date"/>
  <x-core::date-input-script textInputId="to_payment_date_show" dateInputId="to_payment_date"/>
  <x-core::date-input-script textInputId="from_due_date_show" dateInputId="from_due_date"/>
  <x-core::date-input-script textInputId="to_due_date_show" dateInputId="to_due_date"/>

  {{-- <x-core::date-input-script textInputId="installment_payment_date_show" dateInputId="installment_payment_date_hidden"/> --}}
  {{-- <x-core::date-input-script textInputId="installment_due_date_show" dateInputId="installment_due_date_hidden"/> --}}
  {{-- <x-core::date-input-script textInputId="cheque_payment_date_show" dateInputId="cheque_payment_date_hidden"/> --}}
  {{-- <x-core::date-input-script textInputId="cheque_due_date_show" dateInputId="cheque_due_date_hidden"/> --}}

  <script>
    function showPaymentDescriptionModal(description) {
      let modal = $('#showDescriptionModal');
      modal.find('#description').text(description ?? '-');
      modal.modal('show');
    }
  </script>
@endsection
