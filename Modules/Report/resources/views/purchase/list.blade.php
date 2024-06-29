@extends('admin.layouts.master')
@section('content')
  <div class="page-header">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}">
          <i class="fe fe-home ml-1"></i> داشبورد
        </a>
      </li>
      <li class="breadcrumb-item"><a href="{{ route('admin.reports.purchases-filter') }}">فیلتر گزارش خرید</a></li>
      <li class="breadcrumb-item active">خرید از {{ $supplier->name }}</li>
    </ol>
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
                <th class="text-center">شناسه خرید</th>
                <th class="text-center">مبلغ خرید (تومان)</th>
                <th class="text-center">تخفیف کلی (تومان)</th>
                <th class="text-center">مبلغ خرید با تخفیف (تومان)</th>
                <th class="text-center">تاریخ خرید</th>
                <th class="text-center">تاریخ ثبت</th>
              </tr>
              </thead>
              <tbody>

              @php $totalAmountWithDiscount = 0; @endphp

              @forelse ($purchases as $purchase)
                <tr>
                  <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                  <td class="text-center">{{ $purchase->id }}</td>
                  <td class="text-center">{{ number_format($purchase->getTotalPurchaseAmount()) }}</td>
                  <td class="text-center">{{ number_format($purchase->discount) }}</td>
                  <td class="text-center">{{ number_format($purchase->getTotalAmountWithDiscount()) }}</td>
                  <td class="text-center"> @jalaliDate($purchase->purchased_at)</td>
                  <td class="text-center"> @jalaliDate($purchase->created_at)</td>
                </tr>

                @php $totalAmountWithDiscount += $purchase->getTotalAmountWithDiscount(); @endphp

              @empty
                <x-core::data-not-found-alert :colspan="7"/>
              @endforelse
              <tr>
                <td class="text-center font-weight-bold" colspan="4">جمع کل</td>
                <td class="text-center font-weight-bold" colspan="1">{{ number_format($totalAmountWithDiscount) }}</td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
