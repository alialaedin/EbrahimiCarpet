@extends('admin.layouts.master')
@section('content')
  <div class="page-header d-print-none">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fe fe-home ml-1"></i> داشبورد</a>
      </li>
      <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">گزارشات</a></li>
      <li class="breadcrumb-item"><a href="{{ route('admin.reports.suppliers-finance-filter') }}">فیلتر گزارش مالی مشتری</a></li>
      <li class="breadcrumb-item active">گزارش مالی مشتری (جزئی)</li>
    </ol>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
      <x-core::print-button/>
    </div>
  </div>
  <div class="row justify-content-center d-flex">
    <p class="fs-22">گزارش مالی مشتری با نام <strong>{{ $customer->name }}</strong> و شماره
      همراه {{ $customer->mobile }}</p>
  </div>
  <div class="card">
    <div class="card-header border-0">
      <p class="card-title">فاکتور فروش ها</p>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <div class="dataTables_wrapper dt-bootstrap4 no-footer">
          <div class="row">
            <table class="table table-vcenter table-striped text-nowrap table-bordered border-bottom">
              <thead>
              <tr>
                <th class="text-center">ردیف</th>
                <th class="text-center">تاریخ فروش</th>
                <th class="text-center">تاریخ ثبت</th>
                <th class="text-center">مبلغ فروش (ریال)</th>
                <th class="text-center">تخفیف (ریال)</th>
                <th class="text-center">مبلغ با تخفیف (ریال)</th>
              </tr>
              </thead>
              <tbody>

              @forelse ($customer->sales as $sale)

                <tr>
                  <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                  <td class="text-center">{{ verta($sale->sold_at)->format('Y/m/d') }}</td>
                  <td class="text-center">{{ verta($sale->created_at)->format('Y/m/d') }}</td>
                  <td class="text-center">{{ number_format($sale->amount) }}</td>
                  <td class="text-center">{{ number_format($sale->discount) }}</td>
                  <td class="text-center">{{ number_format($sale->getTotalAmountWithDiscount()) }}</td>
                </tr>

              @empty
                <x-core::data-not-found-alert :colspan="6"/>
              @endforelse
              @if($customer->sales->isNotEmpty())

                @php
                  $totalSaleAmount = $customer->sales->sum('amount');
                  $totalSaleDiscount = $customer->sales->sum('discount');
                @endphp

                <tr>
                  <td colspan="3" class="text-center font-weight-bold">جمع کل</td>
                  <td colspan="1" class="text-center">{{ number_format($totalSaleAmount) }}</td>
                  <td colspan="1" class="text-center">{{ number_format($totalSaleDiscount) }}</td>
                  <td colspan="1"
                      class="text-center">{{ number_format($totalSaleAmount - $totalSaleDiscount) }}</td>
                </tr>

              @endif
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  @isset($cashPayments)
    <div class="row justify-content-center d-none d-print-flex">
      <p class="fs-22 mt-5">دریافتی های نقدی</p>
    </div>
    <div class="card">
      <div class="card-header border-0">
        <p class="card-title">نقدی ها</p>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <div class="dataTables_wrapper dt-bootstrap4 no-footer">
            <div class="row">
              <table class="table table-vcenter text-nowrap table-bordered border-bottom">
                <thead>
                <tr>
                  <th class="text-center">ردیف</th>
                  <th class="text-center">تاریخ پرداخت</th>
                  <th class="text-center">تاریخ ثبت</th>
                  <th class="text-center">مبلغ پرداختی (ریال)</th>
                </tr>
                </thead>
                <tbody>

                @foreach($cashPayments as $payment)
                  <tr>
                    <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ verta($payment->payment_date)->format('Y/m/d') }}</td>
                    <td class="text-center">{{ verta($payment->created_at)->format('Y/m/d') }}</td>
                    <td class="text-center">{{ number_format($payment->amount) }}</td>
                  </tr>
                @endforeach
                <tr>
                  <td colspan="3" class="text-center font-weight-bold">جمع کل</td>
                  <td colspan="1" class="text-center">{{ number_format($cashPayments->sum('amount')) }}</td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endisset
  @isset($chequePayments)
    <div class="row justify-content-center d-none d-print-flex">
      <p class="fs-22 mt-5">دریافتی های چکی</p>
    </div>
    <div class="card">
      <div class="card-header border-0">
        <p class="card-title">چک ها</p>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <div class="dataTables_wrapper dt-bootstrap4 no-footer">
            <div class="row">
              <table class="table table-vcenter text-nowrap table-bordered border-bottom">
                <thead>
                <tr>
                  <th class="text-center">ردیف</th>
                  <th class="text-center">صاحب چک</th>
                  <th class="text-center">سریال</th>
                  <th class="text-center">بانک</th>
                  <th class="text-center">سررسید</th>
                  <th class="text-center">وضعیت</th>
                  <th class="text-center">مبلغ (ریال)</th>
                </tr>
                </thead>
                <tbody>

                @foreach($chequePayments as $payment)
                  <tr>
                    <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ $payment->cheque_holder }}</td>
                    <td class="text-center">{{ $payment->cheque_serial }}</td>
                    <td class="text-center">{{ $payment->bank_name }}</td>
                    <td class="text-center">{{ verta($payment->due_date)->format('Y/m/d') }}</td>
                    <td class="text-center">{{ config('payment.statuses.'.$payment->type.'.'.$payment->status) }}</td>
                    <td class="text-center">{{ number_format($payment->amount) }}</td>
                  </tr>
                @endforeach
                <tr>
                  <td colspan="6" class="text-center font-weight-bold">جمع کل</td>
                  <td colspan="1" class="text-center">{{ number_format($chequePayments->sum('amount')) }}</td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endisset
  @isset($installmentPayments)
    <div class="row justify-content-center d-none d-print-flex">
      <p class="fs-22 mt-5">دریافتی های قسطی</p>
    </div>
    <div class="card">
      <div class="card-header border-0">
        <p class="card-title">اقساط</p>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <div class="dataTables_wrapper dt-bootstrap4 no-footer">
            <div class="row">
              <table class="table table-vcenter text-nowrap table-bordered border-bottom">
                <thead>
                <tr>
                  <th class="text-center">ردیف</th>
                  <th class="text-center">سررسید</th>
                  <th class="text-center">تاریخ پرداخت</th>
                  <th class="text-center">وضعیت</th>
                  <th class="text-center">مبلغ (ریال)</th>
                </tr>
                </thead>
                <tbody>

                @foreach($installmentPayments as $payment)
                  <tr>
                    <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ verta($payment->due_date)->format('Y/m/d') }}</td>
                    <td class="text-center">{{ verta($payment->created_at)->format('Y/m/d') }}</td>
                    <td class="text-center">{{ config('payment.statuses.'.$payment->type.'.'.$payment->status) }}</td>
                    <td class="text-center">{{ number_format($payment->amount) }}</td>
                  </tr>
                @endforeach
                <tr>
                  <td colspan="4" class="text-center font-weight-bold">جمع کل</td>
                  <td colspan="1" class="text-center">{{ number_format($installmentPayments->sum('amount')) }}</td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endisset
@endsection
