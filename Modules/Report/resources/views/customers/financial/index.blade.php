@extends('admin.layouts.master')
@section('content')
  <div class="page-header d-print-none">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fe fe-home ml-1"></i> داشبورد</a></li>
      <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">گزارشات</a></li>
      <li class="breadcrumb-item active">گزارش مالی مشتری (کلی)</li>
    </ol>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
      <x-core::print-button/>
    </div>
  </div>
  <div class="row justify-content-center d-none d-print-flex">
    <p class="fs-22">گزارش مالی مشتری (کلی)</p>
  </div>
  <div class="card">
    <div class="card-body">
      <div class="table-responsive">
        <div class="dataTables_wrapper dt-bootstrap4 no-footer">
          <div class="row">
            <table class="table table-vcenter table-striped text-nowrap table-bordered border-bottom">
              <thead>
              <tr>
                <th class="text-center">ردیف</th>
                <th class="text-center">نام و نام خانوادگی</th>
                <th class="text-center">شماره موبایل</th>
                <th class="text-center">کل فروش (ریال)</th>
                <th class="text-center">پرداختی (ریال)</th>
                <th class="text-center">مانده (ریال)</th>
              </tr>
              </thead>
              <tbody>

              @php
                $totalSaleAmount = 0;
                $totalPaymentAmount = 0;
                $totalRemainingAmount = 0;
              @endphp

              @forelse ($customers as $customer)

                <tr>
                  <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                  <td class="text-center">{{ $customer->name }}</td>
                  <td class="text-center">{{ $customer->mobile }}</td>
                  <td class="text-center">{{ number_format($customer->calcTotalSalesAmount()) }}</td>
                  <td class="text-center">{{ number_format($customer->calcTotalSalePaymentsAmount()) }}</td>
                  <td class="text-center">{{ number_format($customer->getRemainingAmount()) }}</td>
                </tr>

                @php
                  $totalSaleAmount += $customer->calcTotalSalesAmount();
                  $totalPaymentAmount += $customer->calcTotalSalePaymentsAmount();
                  $totalRemainingAmount += $customer->getRemainingAmount();
                @endphp

              @empty
                <x-core::data-not-found-alert :colspan="6"/>
              @endforelse
              @if($customers->isNotEmpty())
                <tr>
                  <td colspan="3" class="text-center font-weight-bold">جمع کل</td>
                  <td colspan="1" class="text-center">{{ number_format($totalSaleAmount) }}</td>
                  <td colspan="1" class="text-center">{{ number_format($totalPaymentAmount) }}</td>
                  <td colspan="1" class="text-center">{{ number_format($totalRemainingAmount) }}</td>
                </tr>
              @endif
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
