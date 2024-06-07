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

    <div class="row">
      <div class="col-lg-6">
        <div class="card overflow-hidden">
          <div class="card-header border-0">
            <p class="card-title">اطلاعات تامین کننده</p>
          </div>
          <div class="card-body">
            <ul class="list-group">
              <li class="list-group-item"><strong class="ml-1">نام و نام خانوادگی: </strong> {{ $supplier->name }} </li>
              <li class="list-group-item"><strong class="ml-1">شماره موبایل: </strong> {{ $supplier->mobile }} </li>
              <li class="list-group-item"><strong class="ml-1">محل سکونت: </strong> {{ $supplier->address }} </li>
              <li class="list-group-item">
                <strong class="ml-1">وضعیت: </strong>
                @if ($supplier->status)
                  <span class="text-success">فعال</span>
                @else
                  <span class="text-danger">غیر فعال</span>
                @endif
              </li>
              <li class="list-group-item"><strong class="ml-1">تاریخ ثبت: </strong> {{ verta($supplier->created_at)->format('d-m-Y') }} </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="card overflow-hidden">
          <div class="card-header border-0">
            <p class="card-title">اطلاعات خرید</p>
          </div>
          <div class="card-body">
            <ul class="list-group">
              <li class="list-group-item"><strong class="ml-1">تعداد خرید ها: </strong> {{ $numberOfPurchases }} </li>
              <li class="list-group-item"><strong class="ml-1">تعداد پرداختی ها: </strong> {{ $numberOfPayments }} </li>
              <li class="list-group-item"><strong class="ml-1">مبلغ کل خرید: </strong> {{ number_format($supplier->calcTotalPurchaseAmount()) }} تومان</li>
              <li class="list-group-item"><strong class="ml-1">مبلغ پرداختی: </strong> {{ number_format($supplier->calcTotalPaymentAmount()) }} تومان</li>
              <li class="list-group-item"><strong class="ml-1">مبلغ باقی مانده: </strong> {{ number_format($supplier->gerRemainingAmount()) }} تومان</li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="row">

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

          <a href="{{ route('admin.purchases.create') }}" class="btn btn-indigo">
            ثبت خرید جدید
            <i class="fa fa-plus font-weight-bolder"></i>
          </a>

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

                  @php
                    $totalPurchaseAount = 0;
                    $totalDiscountAount = 0;
                    $totalPurchaseWithDiscountAount = 0;
                  @endphp

                  <tbody>
                    @forelse ($supplier->purchases as $purchase)
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

                      @php
                        $totalPurchaseAount += $purchase->getTotalPurchaseAmount();
                        $totalDiscountAount += $purchase->discount;
                        $totalPurchaseWithDiscountAount += $purchase->getTotalAmountWithDiscount();
                      @endphp

                      @empty
                        <x-core::data-not-found-alert :colspan="7"/>
                    @endforelse
                  </tbody>
                  <tr>
                    <td class="text-center" colspan="1">جمع کل</td>
                    <td class="text-center" colspan="1">{{ number_format($totalPurchaseAount) }}</td>
                    <td class="text-center" colspan="1">{{ number_format($totalDiscountAount) }}</td>
                    <td class="text-center" colspan="1">{{ number_format($totalPurchaseWithDiscountAount) }}</td>
                  </tr>
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

          <a href="{{ route('admin.payments.create', $supplier) }}" class="btn btn-indigo">
            ثبت پرداختی جدید
            <i class="fa fa-plus font-weight-bolder"></i>
          </a>

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
