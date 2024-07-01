@extends('admin.layouts.master')
@section('content')
  <div class="page-header d-print-none">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}">
          <i class="fe fe-home ml-1"></i> داشبورد
        </a>
      </li>
      <li class="breadcrumb-item active">گزارش مالی مشتریان</li>
    </ol>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
      <x-core::print-button/>
    </div>
  </div>
  <div class="card">
    <div class="card-body">
      <div class="table-responsive">
        <div class="dataTables_wrapper dt-bootstrap4 no-footer">
          <div class="row">

            <table class="table table-vcenter table-striped text-nowrap table-bordered border-bottom">
              <thead class="thead-light">
              <tr>
                <th class="text-center">ردیف</th>
                <th class="text-center">نام و نام خانوادگی</th>
                <th class="text-center">شماره موبایل</th>
                <th class="text-center">شماره تلفن</th>
                <th class="text-center">مبلغ خرید (تومان)</th>
                <th class="text-center">مبلغ چرداخت شده (تومان)</th>
                <th class="text-center">مبلغ باقی مانده (تومان)</th>
              </tr>
              </thead>
              <tbody>

              @php
                $totalSalesAmount = 0;
                $totalSalePaymentsAmount = 0;
                $remainingAmount = 0;
              @endphp

              @forelse ($customers as $customer)
                <tr>
                  <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                  <td class="text-center">{{ $customer->name }}</td>
                  <td class="text-center">{{ $customer->mobile }}</td>
                  <td class="text-center">{{ $customer->telephone }}</td>
                  <td class="text-center">{{ number_format($customer->calcTotalSalesAmount()) }}</td>
                  <td class="text-center">{{ number_format($customer->calcTotalSalePaymentsAmount()) }}</td>
                  <td class="text-center">{{ number_format($customer->getRemainingAmount()) }}</td>
                </tr>

                @php
                  $totalSalesAmount += $customer->calcTotalSalesAmount();
                  $totalSalePaymentsAmount += $customer->calcTotalSalePaymentsAmount();
                  $remainingAmount += $customer->getRemainingAmount();
                @endphp

              @empty
                <x-core::data-not-found-alert :colspan="7"/>
              @endforelse
              <tr>
                <td class="text-center font-weight-bold" colspan="4">جمع کل</td>
                <td class="text-center font-weight-bold" colspan="1">{{ number_format($totalSalesAmount) }}</td>
                <td class="text-center font-weight-bold" colspan="1">{{ number_format($totalSalePaymentsAmount) }}</td>
                <td class="text-center font-weight-bold" colspan="1">{{ number_format($remainingAmount) }}</td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
