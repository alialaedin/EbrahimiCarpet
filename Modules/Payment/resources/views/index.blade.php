@extends('admin.layouts.master')
@section('content')
  <div class="col-12">
    <div class="page-header">
      <ol class="breadcrumb align-items-center">
        <li class="breadcrumb-item">
          <a href="{{ route('admin.dashboard') }}">
            <i class="fe fe-home ml-1"></i> داشبورد
          </a>
        </li>
        <li class="breadcrumb-item">
          <a href="{{ route('admin.purchases.index') }}">لیست خرید ها</a>
        </li>
        <li class="breadcrumb-item">
          <a href="{{ route('admin.purchases.show', $purchase) }}">جزئیات خرید</a>
        </li>
        <li class="breadcrumb-item active">
          <a>پرداختی ها</a>
        </li>
      </ol>
      @can('create payments')
        <a href="{{ route('admin.purchases.payments.create', $purchase) }}" class="btn btn-indigo">
          ثبت پرداختی جدید
          <i class="fa fa-plus font-weight-bolder"></i>
        </a>
      @endcan
    </div>
    <div class="card">
      <div class="card-header border-0 justify-content-between ">
        <div class="d-flex">
          <p class="card-title ml-2" style="font-weight: bolder;">اطلاعات پراخت</p>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          @php
            $totalAmount = $purchase->getTotalAmountWithDiscount();
            $paymentAmount = $purchase->getTotalPaymentAmount();
            $remainingAmount = $totalAmount - $paymentAmount;
          @endphp
          <div class="col-lg-4 col-md-6 col-12">
            <div class="d-flex align-items-center my-1">
              <span class="fs-16 font-weight-bold ml-1">مبلغ خرید :</span>
              <span class="fs-14 mr-1"> {{ number_format($totalAmount) }} تومان</span>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 col-12">
            <div class="d-flex align-items-center my-1">
              <span class="fs-16 font-weight-bold ml-1">مبلغ پرداخت شده :</span>
              <span class="fs-14 mr-1"> {{ number_format($paymentAmount) }} تومان</span>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 col-12">
            <div class="d-flex align-items-center my-1">
              <span class="fs-16 font-weight-bold ml-1">مبلغ باقی مانده :</span>
              <span class="fs-14 mr-1"> {{ number_format($remainingAmount) }} تومان</span>
            </div>
          </div>
        </div>
      </div>
    </div>
		<div class="card">
      <div class="card-header border-0">
        <p class="card-title ml-2">پرداختی های نقدی</p>
        <span class="fs-15 ">({{ $cashPayments->count() }})</span>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <div id="hr-table-wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
            <div class="row">
              <table class="table table-vcenter text-nowrap table-bordered border-bottom" id="hr-table">
                <thead class="thead-light">
                  <tr>
                    <th class="text-center border-top">ردیف</th>
                    <th class="text-center border-top">مبلغ پرداختی (تومان)</th>
                    <th class="text-center border-top">تاریخ پرداخت</th>
                    <th class="text-center border-top">عکس رسید</th>
                    <th class="text-center border-top">تاریخ ثبت</th>
                    <th class="text-center border-top">عملیات</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($cashPayments as $payment)
                    <tr>
                      <td class="text-center">{{ $loop->iteration }}</td>
                      <td class="text-center">{{ number_format($payment->amount) }}</td>
                      <td class="text-center">{{ verta($payment->payment_date)->formatDate() }}</td>
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
                      <td class="text-center">{{ verta($payment->created_at)->formatDate() }}</td>
                      <td class="text-center">
                        <button
                          class="btn btn-sm btn-icon btn-primary"
                          onclick="showPaymentDescriptionModal('{{$payment->description}}')"
                          data-toggle="tooltip"
                          data-original-title="توضیحات">
                          <i class="fa fa-book" ></i>
                        </button>
                        @can('edit payments')
                          <x-core::edit-button route="admin.purchases.payments.edit" :model="$payment"/>
                        @endcan
                        @can('delete payments')
                          <x-core::delete-button route="admin.purchases.payments.destroy" :model="$payment"/>
                        @endcan
                      </td>
                    </tr>
                    @empty
                      <x-core::data-not-found-alert :colspan="6"/>
                  @endforelse
                  <tr>
                    <td class="text-center" colspan="1">جمع کل</td>
                    <td class="text-center" colspan="1"> {{ number_format($cashPayments->sum('amount')) }} </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-header border-0">
        <p class="card-title ml-2">اقساط</p>
        <span class="fs-15">({{ $installmentPayments->count() }})</span>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <div id="hr-table-wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
            <div class="row">
              <table class="table table-vcenter text-nowrap table-bordered border-bottom" id="hr-table">
                <thead class="thead-light">
                  <tr>
                    <th class="text-center border-top">ردیف</th>
                    <th class="text-center border-top">مبلغ (تومان)</th>
                    <th class="text-center border-top">تاریخ پرداخت</th>
                    <th class="text-center border-top">عکس رسید</th>
                    <th class="text-center border-top">تاریخ سررسید</th>
                    <th class="text-center border-top">وضعیت</th>
                    <th class="text-center border-top">تاریخ ثبت</th>
                    <th class="text-center border-top">عملیات</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($installmentPayments as $payment)
                    <tr>
                      <td class="text-center">{{ $loop->iteration }}</td>
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
                      <td class="text-center">{{ verta($payment->due_date)->formatDate() }}</td>
                      <td class="text-center">
                        <x-core::badge
                          type="{{ $payment->status ? 'success' : 'danger' }}"
                          text="{{ $payment->status ? 'پرداخت شده' : 'پرداخت نشده' }}"
                        />
                      </td>
                      <td class="text-center">{{ verta($payment->created_at)->formatDate() }}</td>
                      <td class="text-center">
                        <button
                          class="btn btn-sm btn-icon btn-primary"
                          onclick="showPaymentDescriptionModal('{{$payment->description}}')"
                          data-toggle="tooltip"
                          data-original-title="توضیحات">
                          <i class="fa fa-book" ></i>
                        </button>
                        @can('edit payments')
                          <x-core::edit-button route="admin.purchases.payments.edit" :model="$payment"/>
                        @endcan
                        @can('delete payments')
                          <x-core::delete-button route="admin.purchases.payments.destroy" :model="$payment"/>
                        @endcan
                      </td>
                    </tr>
                    @empty
                      <x-core::data-not-found-alert :colspan="8"/>
                  @endforelse
                  <tr>
                    <td class="text-center" colspan="1">جمع کل</td>
                    <td class="text-center" colspan="1"> {{ number_format($installmentPayments->sum('amount')) }} </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-header border-0">
        <p class="card-title ml-2">چک ها</p>
        <span class="fs-15">({{ $chequePayments->count() }})</span>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <div id="hr-table-wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
            <div class="row">
              <table class="table table-vcenter text-nowrap table-bordered border-bottom" id="hr-table">
                <thead class="thead-light">
                  <tr>
                    <th class="text-center border-top">ردیف</th>
                    <th class="text-center border-top">مبلغ (تومان)</th>
                    <th class="text-center border-top">تاریخ پرداخت</th>
                    <th class="text-center border-top">عکس رسید</th>
                    <th class="text-center border-top">تاریخ سررسید</th>
                    <th class="text-center border-top">وضعیت</th>
                    <th class="text-center border-top">تاریخ ثبت</th>
                    <th class="text-center border-top">عملیات</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($chequePayments as $payment)
                    <tr>
                      <td class="text-center">{{ $loop->iteration }}</td>
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
                      <td class="text-center">{{ verta($payment->due_date)->formatDate() }}</td>
                      <td class="text-center">
                        <x-core::badge
                          type="{{ $payment->status ? 'success' : 'danger' }}"
                          text="{{ $payment->status ? 'پاس شده' : 'پاس نشده' }}"
                        />
                      </td>
                      <td class="text-center">{{ verta($payment->created_at)->formatDate() }}</td>
                      <td class="text-center">
                        <button
                          class="btn btn-sm btn-icon btn-primary"
                          onclick="showPaymentDescriptionModal('{{$payment->description}}')"
                          data-toggle="tooltip"
                          data-original-title="توضیحات">
                          <i class="fa fa-book" ></i>
                        </button>
                        @can('edit payments')
                          <x-core::edit-button route="admin.purchases.payments.edit" :model="$payment"/>
                        @endcan
                        @can('delete payments')
                          <x-core::delete-button route="admin.purchases.payments.destroy" :model="$payment"/>
                        @endcan
                      </td>
                    </tr>
                    @empty
                      <x-core::data-not-found-alert :colspan="8"/>
                  @endforelse
                  <tr>
                    <td class="text-center" colspan="1">جمع کل</td>
                    <td class="text-center" colspan="1"> {{ number_format($chequePayments->sum('amount')) }} </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @include('payment::_show-description-modal')

@endsection

@section('scripts')
  <script>
    function showPaymentDescriptionModal (description) {
      let modal = $('#showDescriptionModal');
      modal.find('#description').text(description ?? '-');
      modal.modal('show');
    }
  </script>
@endsection
