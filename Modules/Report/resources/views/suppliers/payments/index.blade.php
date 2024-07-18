@extends('admin.layouts.master')
@section('content')
  <div class="page-header d-print-none">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fe fe-home ml-1"></i> داشبورد</a></li>
      <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">گزارشات</a></li>
      <li class="breadcrumb-item active"><a href="{{ route('admin.reports.supplier-payments-filter') }}">فیلتر گزارش پرداختی به تامین کننده</a></li>
      <li class="breadcrumb-item active">گزارش پرداختی به تامین کننده</li>
    </ol>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
      <x-core::print-button/>
    </div>
  </div>
  <div class="row justify-content-center d-none d-print-flex">
    <p class="fs-22">گزارش پرداختی به تامین کننده با نام <strong>{{ $supplier->name }}</strong> و شماره
      همراه {{ $supplier->mobile }}</p>
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
                <th class="text-center">نوع پرداخت</th>
                <th class="text-center">تاریخ پرداختی</th>
                <th class="text-center">تاریخ سررسید</th>
                <th class="text-center">تاریخ ثبت</th>
                <th class="text-center">وضعیت</th>
                <th class="text-center">مبلغ (ریال)</th>
              </tr>
              </thead>
              <tbody>

              @forelse ($payments as $payment)

                <tr>
                  <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                  <td class="text-center">{{ config('payment.types.'.$payment->type) }}</td>
                  <td class="text-center">{{ $payment->getPaymentDate() }}</td>
                  <td class="text-center">{{ $payment->getDueDate() }}</td>
                  <td class="text-center">{{ verta($payment->created_at)->format('Y/m/d') }}</td>
                  <td class="text-center">{{ config('payment.statuses.'.$payment->type.'.'.$payment->status) }}</td>
                  <td class="text-center">{{ number_format($payment->amount) }}</td>
                </tr>

              @empty
                <x-core::data-not-found-alert :colspan="7"/>
              @endforelse
              @if($payments->isNotEmpty())
                <tr>
                  <td colspan="6" class="text-center font-weight-bold">جمع کل</td>
                  <td colspan="1" class="text-center">{{ number_format($payments->sum('amount')) }}</td>
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
