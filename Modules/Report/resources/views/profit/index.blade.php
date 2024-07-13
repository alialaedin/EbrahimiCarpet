@extends('admin.layouts.master')
@section('content')
  <div class="page-header d-print-none">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}">
          <i class="fe fe-home ml-1"></i> داشبورد
        </a>
      </li>
      <li class="breadcrumb-item active">گزارش سود و ضرر</li>
    </ol>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
      <x-core::print-button/>
    </div>
  </div>
  <div class="row justify-content-center d-none d-print-flex">
    <p class="fs-22">گزارش سود و ضرر</p>
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
                <th class="text-center">محصول</th>
                <th class="text-center">دسته بندی</th>
                <th class="text-center">مبلغ (ریال)</th>
                <th class="text-center">تخفیف (ریال)</th>
                <th class="text-center">مبلغ با تخفیف (ریال)</th>
                <th class="text-center">تاریخ فروش</th>
              </tr>
              </thead>
              <tbody>

              @php $counter = 0; @endphp

              @forelse ($sales as $sale)
                @foreach($sale->items as $saleItem)
                  <tr>
                    <td class="text-center font-weight-bold">{{ $counter }}</td>
                    <td class="text-center">{{ $saleItem->product->title }}</td>
                    <td class="text-center">{{ $saleItem->product->category->title }}</td>
                    <td class="text-center">{{ number_format($saleItem->price) }}</td>
                    <td class="text-center">{{ number_format($saleItem->discount) }}</td>
                    <td class="text-center">{{ number_format($saleItem->getPriceWithDiscount()) }}</td>
                    <td class="text-center"> @jalaliDate($saleItem->sale->sold_at) </td>
                  </tr>
                  @php($counter++)
                @endforeach
              @empty
                <x-core::data-not-found-alert :colspan="7"/>
              @endforelse
              <tr>
                <td class="text-center font-weight-bold" colspan="3">جمع کل</td>
                <td class="text-center font-weight-bold" colspan="4">{{ number_format($profit) }}</td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
