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
          <i class="fa fa-plus font-weight-bolder"></i>
        </a>
      @endcan
    </div>

    <div class="card">

      <div class="card-header border-0">
        <p class="card-title">اطلاعات مشتری</p>
        <x-core::card-options/>
      </div>

      <div class="card-body">
        <div class="row">
          <div class="col-xl-4 col-lg-6 col-12 fs-17 my-1">
            <span><strong>کد : </strong>{{ $customer->id }}</span>
          </div>
          <div class="col-xl-4 col-lg-6 col-12 fs-17 my-1">
            <span><strong>نام و نام خانوادگی : </strong>{{ $customer->name }}</span>
          </div>
          <div class="col-xl-4 col-lg-6 col-12 fs-17 my-1">
            <span><strong>شماره موبایل : </strong>{{ $customer->mobile }}</span>
          </div>
          <div class="col-xl-4 col-lg-6 col-12 fs-17 my-1">
            <strong>وضعیت : </strong>
            @if ($customer->status)
              <span class="text-success">فعال</span>
            @else
              <span class="text-danger">غیر فعال</span>
            @endif
          </div>
          <div class="col-xl-4 col-lg-6 col-12 fs-17 my-1">
            <span><strong>تعداد خرید ها : </strong>{{ number_format($customer->countSales()) }}</span>
          </div>
          <div class="col-xl-4 col-lg-6 col-12 fs-17 my-1">
            <span><strong>تعداد پرداختی ها : </strong>{{ number_format($customer->countPayments()) }}</span>
          </div>
          <div class="col-xl-4 col-lg-6 col-12 fs-17 my-1">
            <span><strong>تاریخ ثبت : </strong>{{ verta($customer->created_at)->format('d-m-Y') }}</span>
          </div>
          <div class="col-xl-8 col-lg-6 col-12 fs-17 my-1">
            <span><strong>محل سکونت : </strong>{{ $customer->address }}</span>
          </div>
        </div>
      </div>

    </div>

    <div class="row">
      <div class="col-xl-4 col-lg-6 col-md-12">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-9">
                <div class="mt-0 text-right">
                  <span class="fs-16 font-weight-semibold"> مبلغ کل خرید (تومان) : </span>
                  <h3 class="mb-0 mt-1 text-info fs-20"> {{ number_format($customer->calcTotalSalesAmount()) }} </h3>
                </div>
              </div>
              <div class="col-3">
                <div class="icon1 bg-info-transparent my-auto float-left">
                  <i class="fa fa-money"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-4 col-lg-6 col-md-12">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-9">
                <div class="mt-0 text-right">
                  <span class="fs-16 font-weight-semibold"> جمع پرداخت شده ها (تومان) : </span>
                  <h3 class="mb-0 mt-1 text-danger fs-20"> {{ number_format($customer->calcTotalSalePaymentsAmount()) }} </h3>
                </div>
              </div>
              <div class="col-3">
                <div class="icon1 bg-danger-transparent my-auto float-left">
                  <i class="fa fa-money"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-4 col-lg-6 col-md-12">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-9">
                <div class="mt-0 text-right">
                  <span class="fs-16 font-weight-semibold"> مبلغ باقی مانده (تومان) : </span>
                  <h3 class="mb-0 mt-1 text-success fs-20"> {{ number_format($customer->getRemainingAmount()) }}  </h3>
                </div>
              </div>
              <div class="col-3">
                <div class="icon1 bg-success-transparent my-auto float-left">
                  <i class="fa fa-money"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


    <div class="card">
      <div class="card-header border-0">
        <p class="card-title">پرداختی های نقدی <span class="fs-15 ">({{ $cashPayments->count() }})</span></p>
        <x-core::card-options/>
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
                        @can('edit sale_payments')
                          <x-core::edit-button route="admin.sale-payments.edit" :model="$payment"/>
                        @endcan
                        @can('delete sale_payments')
                          <x-core::delete-button route="admin.sale-payments.destroy" :model="$payment"/>
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
        <p class="card-title">اقساط <span class="fs-15">({{ $installmentPayments->count() }})</span></p>
        <x-core::card-options/>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <div class="dataTables_wrapper dt-bootstrap4 no-footer">
            <div class="row">
              <table class="table table-vcenter text-nowrap table-bordered border-bottom">
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
                        @can('edit sale_payments')
                          <x-core::edit-button route="admin.sale-payments.edit" :model="$payment"/>
                        @endcan
                        @can('delete sale_payments')
                          <x-core::delete-button route="admin.sale-payments.destroy" :model="$payment"/>
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
        <p class="card-title">چک ها <span class="fs-15">({{ $chequePayments->count() }})</span></p>
        <x-core::card-options/>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <div class="dataTables_wrapper dt-bootstrap4 no-footer">
            <div class="row">
              <table class="table table-vcenter text-nowrap table-bordered border-bottom">
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
                        @can('edit sale_payments')
                          <x-core::edit-button route="admin.sale-payments.edit" :model="$payment"/>
                        @endcan
                        @can('delete sale_payments')
                          <x-core::delete-button route="admin.sale-payments.destroy" :model="$payment"/>
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
