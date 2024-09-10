@extends('admin.layouts.master')
@section('content')
  <div class="page-header">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}"><i class="fe fe-home ml-1"></i> داشبورد </a>
      </li>
      <li class="breadcrumb-item active">لیست فروش ها</li>
    </ol>
    @can('create sales')
      <x-core::register-button route="admin.sales.create" title="ثبت فروش جدید"/>
    @endcan
  </div>
  @include('sale::sale.includes._filter-form')
  <div class="card">
    <div class="card-header border-0">
      <p class="card-title"> لیست همه فروش ها ({{ $salesCount }}) </p>
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
                <th class="text-center">نام مشتری</th>
                <th class="text-center">شماره موبایل</th>
                <th class="text-center">پرسنل ارجاع</th>
                <th class="text-center">مبلغ خرید (ریال)</th>
                <th class="text-center">هزینه دوخت / نصب (ریال)</th>
                <th class="text-center">تخفیف کلی (ریال)</th>
                <th class="text-center">مبلغ کل فاکتور (ریال)</th>
                <th class="text-center">تاریخ خرید</th>
                <th class="text-center">عملیات</th>
              </tr>
              </thead>
              <tbody>
              @forelse ($sales as $sale)
                <tr>
                  <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                  <td class="text-center">
                    <a href="{{ route('admin.customers.show', $sale->customer) }}" target="_blank">
                      {{ $sale->customer->name }}
                    </a>
                  </td>
                  <td class="text-center">{{ $sale->customer->mobile }}</td>
                  <td class="text-center">{{ $sale->employee->name ?? null }}</td>
                  <td class="text-center">{{ number_format($sale->getTotalAmount() - $sale->cost_of_sewing) }}</td>
                  <td class="text-center">{{ number_format($sale->cost_of_sewing) }}</td>
                  <td class="text-center">{{ number_format($sale->discount) }}</td>
                  <td class="text-center">{{ number_format($sale->getTotalAmountWithDiscount()) }}</td>
                  <td class="text-center">{{ verta($sale->sold_at)->formatDate() }}</td>
                  <td class="text-center">
                    <a
                      href="{{ route('admin.sales.invoice.show', $sale) }}"
                      target="_blank"
                      class="btn btn-sm btn-purple btn-icon text-white p-0"
                      data-toggle="tooltip"
                      data-original-title="فاکتور">
                      <i class="fe fe-printer" style="margin: 1px 0; padding: 0 6px;"></i>
                    </a>
                    @can('view sales')
                      <x-core::show-button route="admin.sales.show" :model="$sale"/>
                    @endcan
                    @can('edit sales')
                      <x-core::edit-button route="admin.sales.edit" :model="$sale"/>
                    @endcan
                    @can('delete sales')
                      <x-core::delete-button route="admin.sales.destroy" :model="$sale"/>
                    @endcan
                  </td>
                </tr>
              @empty
                <x-core::data-not-found-alert :colspan="10"/>
              @endforelse
              </tbody>
            </table>
            {{ $sales->onEachSide(0)->links("vendor.pagination.bootstrap-4") }}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
