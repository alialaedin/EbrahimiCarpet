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
          <a href="{{ route('admin.suppliers.index') }}">لیست تامین کنندگان</a>
        </li>
        <li class="breadcrumb-item active">
          <a>نمایش تامین کننده</a>
        </li>
      </ol>
    </div>

    <div class="card">
      <div class="card-header border-0">
        <p class="card-title">اطلاعات تامین کننده</p>
        <div class="card-options">
          <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
          <a href="#" class="card-options-fullscreen" data-toggle="card-fullscreen"><i class="fe fe-maximize"></i></a>
          <a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-xl-4 col-lg-6 col-12 fs-17 my-1">
            <span><strong>کد : </strong>{{ $supplier->id }}</span>
          </div>
          <div class="col-xl-4 col-lg-6 col-12 fs-17 my-1">
            <span><strong>نام و نام خانوادگی : </strong>{{ $supplier->name }}</span>
          </div>
          <div class="col-xl-4 col-lg-6 col-12 fs-17 my-1">
            <span><strong>شماره موبایل : </strong>{{ $supplier->mobile }}</span>
          </div>
          <div class="col-xl-4 col-lg-6 col-12 fs-17 my-1">
            <strong>وضعیت : </strong>
            @if ($supplier->status)
              <span class="text-success">فعال</span>
            @else
              <span class="text-danger">غیر فعال</span>
            @endif
          </div>
          <div class="col-xl-4 col-lg-6 col-12 fs-17 my-1">
            <span><strong>تعداد خرید ها : </strong>{{ number_format($numberOfPurchases) }}</span>
          </div>
          <div class="col-xl-4 col-lg-6 col-12 fs-17 my-1">
            <span><strong>تعداد پرداختی ها : </strong>{{ number_format($numberOfPayments) }}</span>
          </div>
          <div class="col-xl-4 col-lg-6 col-12 fs-17 my-1">
            <span><strong>تاریخ ثبت : </strong>{{ verta($supplier->created_at)->format('d-m-Y') }}</span>
          </div>
          <div class="col-xl-4 col-lg-6 col-12 fs-17 my-1">
            <span><strong>محل سکونت : </strong>{{ $supplier->address }}</span>
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
                  <h3 class="mb-0 mt-1 text-info fs-20"> {{ number_format($supplier->calcTotalPurchaseAmount()) }} </h3>
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
                  <span class="fs-16 font-weight-semibold"> جمع پرداختی ها (تومان) : </span>
                  <h3 class="mb-0 mt-1 text-danger fs-20"> {{ number_format($supplier->calcTotalPaymentAmount()) }} </h3>
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
                  <h3 class="mb-0 mt-1 text-success fs-20"> {{ number_format($supplier->getRemainingAmount()) }}  </h3>
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
      <div class="card-header border-0 justify-content-between">

        <div class="d-flex align-items-center">
          <p class="card-title ml-1">خرید ها <span class="fs-15 ">({{ $numberOfPurchases }})</span></p>

          <div class="card-options">
            <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
            <a href="#" class="card-options-fullscreen" data-toggle="card-fullscreen"><i class="fe fe-maximize"></i></a>
            <a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
          </div>
        </div>
        @can('create purchases')
          <x-core::register-button route="admin.purchases.create" title="ثبت خرید جدید"/>
        @endcan
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <div class="dataTables_wrapper dt-bootstrap4 no-footer">
            <div class="row">
              <table class="table table-vcenter text-nowrap table-bordered border-bottom">
                <thead class="thead-light">
                  <tr>
                    <th class="text-center">ردیف</th>
                    <th class="text-center">مبلغ خرید (تومان)</th>
                    <th class="text-center">تخفیف کلی (تومان)</th>
                    <th class="text-center">مبلغ خرید با تخفیف (تومان)</th>
                    <th class="text-center">تاریخ خرید</th>
                    <th class="text-center">عملیات</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($purchases as $purchase)
                    <tr>
                      <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                      <td class="text-center">{{ number_format($purchase->getTotalPurchaseAmount()) }}</td>
                      <td class="text-center">{{ number_format($purchase->discount) }}</td>
                      <td class="text-center">{{ number_format($purchase->getTotalAmountWithDiscount()) }}</td>
                      <td class="text-center">{{ verta($purchase->purchased_at)->formatDate() }}</td>
                      <td class="text-center">
                        @can('view purchases')
                          <a
                            href="{{route('admin.purchases.show', $purchase)}}"
                            class="btn btn-sm btn-cyan">
                            جزئیات خرید
                          </a>
                        @endcan
                      </td>
                    </tr>
                    @empty
                      <x-core::data-not-found-alert :colspan="7"/>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header border-0 justify-content-between">
        <div class="d-flex align-items-center">
          <p class="card-title ml-2">پرداختی ها <span class="fs-15">({{ $numberOfPayments }})</span></p>
          <div class="card-options">
            <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
            <a href="#" class="card-options-fullscreen" data-toggle="card-fullscreen"><i class="fe fe-maximize"></i></a>
            <a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
          </div>
        </div>
        @can('create purchases')
          <a href="{{ route('admin.payments.create', $supplier) }}" class="btn btn-indigo">
            ثبت پرداختی جدید
            <i class="fa fa-plus font-weight-bolder"></i>
          </a>
        @endcan
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <div id="hr-table-wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
            <div class="row">
              <table class="table table-vcenter text-nowrap table-bordered border-bottom">
                <thead class="thead-light">
                  <tr>
                    <th class="text-center border-top">ردیف</th>
                    <th class="text-center border-top">نوع پراخت</th>
                    <th class="text-center border-top">مبلغ (تومان)</th>
                    <th class="text-center border-top">تاریخ پرداخت</th>
                    <th class="text-center border-top">عکس رسید</th>
                    <th class="text-center border-top">تاریخ سررسید</th>
                    <th class="text-center border-top">وضعیت</th>
                    <th class="text-center border-top">تاریخ ثبت</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($payments as $payment)
                    <tr>
                      <td class="text-center">{{ $loop->iteration }}</td>
                      <td class="text-center">{{ $payment->getType() }}</td>
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
