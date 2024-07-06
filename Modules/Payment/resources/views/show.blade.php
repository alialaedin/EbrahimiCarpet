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
        <a href="{{ route('admin.suppliers.index') }}">لیست تامین کنندگان</a>
      </li>
      <li class="breadcrumb-item">
        <a href="{{ route('admin.suppliers.show', $supplier) }}">نمایش تامین کننده</a>
      </li>
      <li class="breadcrumb-item active">
        <a>پرداختی ها</a>
      </li>
    </ol>
    @can('create payments')
      <a href="{{ route('admin.payments.create', $supplier) }}" class="btn btn-indigo">
        ثبت پرداختی جدید
        <i class="fa fa-plus mr-1"></i>
      </a>
    @endcan
  </div>
  <div class="card">
    <div class="card-header border-0">
      <p class="card-title">اطلاعات تامین کننده</p>
      <x-core::card-options/>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-xl-4 col-lg-6 col-12 fs-16 my-1">
          <span><strong>کد : </strong>{{ $supplier->id }}</span>
        </div>
        <div class="col-xl-4 col-lg-6 col-12 fs-16 my-1">
          <span><strong>نام و نام خانوادگی : </strong>{{ $supplier->name }}</span>
        </div>
        <div class="col-xl-4 col-lg-6 col-12 fs-16 my-1">
          <span><strong>شماره موبایل : </strong>{{ $supplier->mobile }}</span>
        </div>
        <div class="col-xl-4 col-lg-6 col-12 fs-16 my-1">
          <strong>وضعیت : </strong>
          @if ($supplier->status)
            <span class="text-success">فعال</span>
          @else
            <span class="text-danger">غیر فعال</span>
          @endif
        </div>
        <div class="col-xl-4 col-lg-6 col-12 fs-16 my-1">
          <span><strong>تعداد خرید ها : </strong>{{ number_format($supplier->countPurchases()) }}</span>
        </div>
        <div class="col-xl-4 col-lg-6 col-12 fs-16 my-1">
          <span><strong>تعداد پرداختی ها : </strong>{{ number_format($supplier->countPayments()) }}</span>
        </div>
        <div class="col-xl-4 col-lg-6 col-12 fs-16 my-1">
          <span><strong>تاریخ ثبت : </strong> @jalaliDate($supplier->created_at) </span>
        </div>
        <div class="col-xl-4 col-lg-6 col-12 fs-16 my-1">
          <span><strong>محل سکونت : </strong>{{ $supplier->address }}</span>
        </div>
      </div>
    </div>
  </div>
  @include('supplier::includes.purchase-statistics')
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
                  <td class="text-center"> @jalaliDate($payment->created_at) </td>
                  <td class="text-center">
                    <button
                      class="btn btn-sm btn-icon btn-primary"
                      onclick="showPaymentDescriptionModal('{{$payment->description}}')"
                      data-toggle="tooltip"
                      data-original-title="توضیحات">
                      <i class="fa fa-book" ></i>
                    </button>
                    @can('edit payments')
                      <x-core::edit-button route="admin.payments.edit" :model="$payment"/>
                    @endcan
                    @can('delete payments')
                      <x-core::delete-button route="admin.payments.destroy" :model="$payment"/>
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
                  <td class="text-center"> @jalaliDate($payment->due_date) </td>
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
                    @can('edit payments')
                      <x-core::edit-button route="admin.payments.edit" :model="$payment"/>
                    @endcan
                    @can('delete payments')
                      <x-core::delete-button route="admin.payments.destroy" :model="$payment"/>
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
      <p class="card-title">چک ها ({{ $chequePayments->count() }})</p>
      <x-core::card-options/>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <div class="dataTables_wrapper dt-bootstrap4 no-footer">
          <div class="row">
            <table class="table table-vcenter table-striped text-nowrap table-bordered border-bottom" >
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
              @forelse ($chequePayments as $payment)
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
                  <td class="text-center">@jalaliDate($payment->due_date)</td>
                  <td class="text-center">
                    <x-core::badge
                      type="{{ $payment->status ? 'success' : 'danger' }}"
                      text="{{ $payment->status ? 'پاس شده' : 'پاس نشده' }}"
                    />
                  </td>
                  <td class="text-center">@jalaliDate($payment->created_at)</td>
                  <td class="text-center">
                    <button
                      class="btn btn-sm btn-icon btn-primary"
                      onclick="showPaymentDescriptionModal('{{$payment->description}}')"
                      data-toggle="tooltip"
                      data-original-title="توضیحات">
                      <i class="fa fa-book" ></i>
                    </button>
                    @can('edit payments')
                      <x-core::edit-button route="admin.payments.edit" :model="$payment"/>
                    @endcan
                    @can('delete payments')
                      <x-core::delete-button route="admin.payments.destroy" :model="$payment"/>
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

