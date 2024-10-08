@extends('admin.layouts.master')
@section('content')
  <div class="page-header">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}">
          <i class="fe fe-home ml-1"></i> داشبورد
        </a>
      </li>
      <li class="breadcrumb-item">
        <a href="{{ route('admin.customers.index') }}">لیست مشتریان</a>
      </li>
      <li class="breadcrumb-item">
        <a href="{{ route('admin.customers.show', $customer) }}">نمایش مشتری</a>
      </li>
      <li class="breadcrumb-item active">
        <a>پرداختی ها</a>
      </li>
    </ol>
    @can('create sale_payments')
      <a href="{{ route('admin.sale-payments.create', $customer) }}" class="btn btn-indigo">
        ثبت پرداختی جدید
        <i class="fa fa-plus mr-1"></i>
      </a>
    @endcan
  </div>
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-12">
          <p class="header fs-20 px-5">اطلاعات مشتری</p>
        </div>
        <div class="col-lg-6">
          <ul class="list-group">
            <li class="list-group-item"><strong>کد : </strong> {{ $customer->id }} </li>
            <li class="list-group-item">
              <strong>نام و نام خانوادگی : </strong>
              <a href="{{ route('admin.customers.show', $customer) }}">{{ $customer->name }}</a>
            </li>
            <li class="list-group-item">
              <strong>وضعیت : </strong>
              @if ($customer->status)
                <span class="text-success">فعال</span>
              @else
                <span class="text-danger">غیر فعال</span>
              @endif
            </li>
            <li class="list-group-item"><strong>شماره موبایل: </strong> {{ $customer->mobile }} </li>
          </ul>
        </div>
        <div class="col-lg-6">
          <ul class="list-group">
            <li class="list-group-item"><strong>آدرس: </strong> {{ $customer->address }} </li>
            <li class="list-group-item"><strong>تعداد خرید ها : </strong>{{ number_format($customer->countSales()) }} </li>
            <li class="list-group-item"><strong>تعداد پرداختی ها : </strong>{{ number_format($customer->countPayments()) }} </li>
            <li class="list-group-item"><strong>تاریخ ثبت : </strong> @jalaliDate($customer->created_at) </li>
          </ul>
        </div>
      </div>

    </div>
  </div>
  @include('customer::includes.helper-boxes')
  <div class="card">
    <div class="card-header border-0">
      <p class="card-title">پرداختی های نقدی ({{ $cashPayments->count() }})</p>
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
                <th class="text-center">مبلغ پرداختی (ریال)</th>
                <th class="text-center">تاریخ پرداخت</th>
                <th class="text-center">عکس رسید</th>
                <th class="text-center">تاریخ ثبت</th>
                <th class="text-center">عملیات</th>
              </tr>
              </thead>
              <tbody>
              @forelse ($cashPayments as $payment)
                <tr>
                  <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                  <td class="text-center">{{ number_format($payment->amount) }}</td>
                  <td class="text-center"> @jalaliDate($payment->payment_date) </td>
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
                  <td class="text-center"> @jalaliDate($payment->created_at)</td>
                  <td class="text-center">
                    <button
                      class="btn btn-sm btn-icon btn-primary"
                      onclick="showPaymentDescriptionModal('{{$payment->description}}')"
                      data-toggle="tooltip"
                      data-original-title="توضیحات">
                      <i class="fa fa-book" ></i>
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
                <x-core::data-not-found-alert :colspan="6"/>
              @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header border-0">
      <p class="card-title">اقساط ({{ $installmentPayments->count() }})</p>
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
                <th class="text-center">مبلغ (ریال)</th>
                <th class="text-center">تاریخ پرداخت</th>
                <th class="text-center">عکس رسید</th>
                <th class="text-center">تاریخ سررسید</th>
                <th class="text-center">وضعیت</th>
                <th class="text-center">تاریخ ثبت</th>
                <th class="text-center">عملیات</th>
              </tr>
              </thead>
              <tbody>
              @forelse ($installmentPayments as $payment)
                <tr>
                  <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                  <td class="text-center">{{ number_format($payment->amount) }}</td>
                  <td class="text-center">{{ $payment->getPaymentDate() }}</td>
                  <td class="text-center m-0 p-0">
                    @if ($payment->image)
                      <figure class="figure my-2">
                        <a target="_blank" href="{{ Storage::url($payment->image) }}">
                          <img src="{{ Storage::url($payment->image) }}" class="img-thumbnail" alt="image" width="50" style="max-height: 32px;" />
                        </a>
                      </figure>
                    @else
                      <span> - </span>
                    @endif
                  </td>
                  <td class="text-center"> @jalaliDateFormat($payment->due_date) </td>
                  <td class="text-center">
                    <x-core::badge
                      type="{{ $payment->status ? 'success' : 'danger' }}"
                      text="{{ $payment->status ? 'پرداخت شده' : 'پرداخت نشده' }}"
                    />
                  </td>
                  <td class="text-center"> @jalaliDate($payment->created_at) </td>
                  <td class="text-center">
                    <button
                      class="btn btn-sm btn-icon btn-primary"
                      onclick="showPaymentDescriptionModal('{{$payment->description}}')"
                      data-toggle="tooltip"
                      data-original-title="توضیحات">
                      <i class="fa fa-book" ></i>
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
                <x-core::data-not-found-alert :colspan="8"/>
              @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header border-0">
      <p class="card-title">چک ها ({{ $chequePayments->count() }})</p>
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
                <th class="text-center">سریال</th>
                <th class="text-center">صاحب چک</th>
                <th class="text-center">بانک</th>
                <th class="text-center">در وجه</th>
                <th class="text-center">مالک چک</th>
                <th class="text-center">مبلغ (ریال)</th>
                <th class="text-center">تاریخ سررسید</th>
                <th class="text-center">تاریخ پرداخت</th>
                <th class="text-center">عکس رسید</th>
                <th class="text-center">وضعیت</th>
                <th class="text-center">عملیات</th>
              </tr>
              </thead>
              <tbody>
              @forelse ($chequePayments as $payment)
                <tr>
                  <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                  <td class="text-center">{{ $payment->cheque_serial }}</td>
                  <td class="text-center">{{ $payment->cheque_holder }}</td>
                  <td class="text-center">{{ $payment->bank_name }}</td>
                  <td class="text-center">{{ $payment->pay_to }}</td>
                  <td class="text-center">{{ $payment->is_mine }}</td>
                  <td class="text-center">{{ number_format($payment->amount) }}</td>
                  <td class="text-center"> {{ verta($payment->due_date)->format('Y/m/d') }} </td>
                  <td class="text-center">{{ $payment->getPaymentDate() }}</td>
                  <td class="text-center m-0 p-0">
                    @if ($payment->image)
                      <figure class="figure my-2">
                        <a target="_blank" href="{{ Storage::url($payment->image) }}">
                          <img src="{{ Storage::url($payment->image) }}" class="img-thumbnail" alt="image" width="50" style="max-height: 32px;" />
                        </a>
                      </figure>
                    @else
                      <span> - </span>
                    @endif
                  </td>
                  <td class="text-center">
                    <x-core::badge
                      type="{{ $payment->status ? 'success' : 'danger' }}"
                      text="{{ $payment->status ? 'پاس شده' : 'پاس نشده' }}"
                    />
                  </td>
                  <td class="text-center">
                    <button
                      class="btn btn-sm btn-icon btn-primary"
                      onclick="showPaymentDescriptionModal('{{$payment->description}}')"
                      data-toggle="tooltip"
                      data-original-title="توضیحات">
                      <i class="fa fa-book" ></i>
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
                <x-core::data-not-found-alert :colspan="12"/>
              @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  @include('sale::sale-payment._show-description-modal')
  @include('sale::sale-payment.edit-modal')
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

  <script>
    function showPaymentDescriptionModal (description) {
      let modal = $('#showDescriptionModal');
      modal.find('#description').text(description ?? '-');
      modal.modal('show');
    }
  </script>
  <script>
    $(document).ready(function() {
      console.log(@json($customer->all_payments_amount));
    });
  </script>
@endsection
