@extends('admin.layouts.master')
@section('content')
  <div class="page-header d-print-none">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fe fe-home ml-1"></i> داشبورد</a></li>
      <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">گزارشات</a></li>
      <li class="breadcrumb-item active">گزارش مالی تامین کننده (کلی)</li>
    </ol>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
      <x-core::print-button/>
    </div>
  </div>
  <div class="row justify-content-center d-none d-print-flex">
    <p class="fs-22">گزارش مالی تامین کننده (کلی)</p>
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
                <th class="text-center">کل خرید (ریال)</th>
                <th class="text-center">پرداختی (ریال)</th>
                <th class="text-center">مانده (ریال)</th>
              </tr>
              </thead>
              <tbody>

              @php
                $totalPurchaseAmount = 0;
                $totalPaymentAmount = 0;
                $totalRemainingAmount = 0;
              @endphp

              @forelse ($suppliers as $supplier)

                <tr>
                  <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                  <td class="text-center">{{ $supplier->name }}</td>
                  <td class="text-center">{{ $supplier->mobile }}</td>
                  <td class="text-center">{{ number_format($supplier->calcTotalPurchaseAmount()) }}</td>
                  <td class="text-center">{{ number_format($supplier->calcTotalPaymentAmount()) }}</td>
                  <td class="text-center">{{ number_format($supplier->getRemainingAmount()) }}</td>
                </tr>

                @php
                  $totalPurchaseAmount += $supplier->calcTotalPurchaseAmount();
                  $totalPaymentAmount += $supplier->calcTotalPaymentAmount();
                  $totalRemainingAmount += $supplier->getRemainingAmount();
                @endphp

              @empty
                <x-core::data-not-found-alert :colspan="6"/>
              @endforelse
              @if($suppliers->isNotEmpty())
                <tr>
                  <td colspan="3" class="text-center font-weight-bold">جمع کل</td>
                  <td colspan="1" class="text-center">{{ number_format($totalPurchaseAmount) }}</td>
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
@section('scripts')
  <x-core::date-input-script textInputId="from_payment_date_show" dateInputId="from_payment_date"/>
  <x-core::date-input-script textInputId="to_payment_date_show" dateInputId="to_payment_date"/>
@endsection
