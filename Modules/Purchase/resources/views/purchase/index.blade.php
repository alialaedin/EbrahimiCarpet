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
        <li class="breadcrumb-item active">لیست خرید ها</li>
      </ol>
      @can('create purchases')
        <x-core::register-button route="admin.purchases.create" title="ثبت خرید جدید"/>
      @endcan
    </div>
    @include('purchase::includes._filter-form')
    <div class="card">
      <div class="card-header border-0">
        <p class="card-title ml-2">لیست خرید ها <span class="fs-15">({{ $purchasesCount }})</span></p>

        <x-core::card-options/>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <div id="hr-table-wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
            <div class="row">
              <table class="table table-vcenter text-nowrap table-bordered border-bottom" id="hr-table">
                <thead class="thead-light">
                  <tr>
                    <th class="text-center">ردیف</th>
                    <th class="text-center">نام تامین کننده</th>
                    <th class="text-center">شماره موبایل</th>
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
                      <td class="text-center">
                        <a href="{{ route('admin.suppliers.show', $purchase->supplier) }}" target="_blank">
                          {{ $purchase->supplier->name }}
                        </a>
                      </td>
                      <td class="text-center">{{ $purchase->supplier->mobile }}</td>
                      <td class="text-center">{{ number_format($purchase->getTotalPurchaseAmount()) }}</td>
                      <td class="text-center">
                        @if ($purchase->discount)
                        {{ number_format($purchase->discount) }}
                        @else
                        <span class="text-danger"> ندارد </span>
                        @endif
                      </td>
                      <td class="text-center">{{ number_format($purchase->getTotalPurchaseAmount() - $purchase->discount) }}</td>
                      <td class="text-center">{{ verta($purchase->purchased_at)->formatDate() }}</td>
                      <td class="text-center">
                        @can('view purchases')
                          <x-core::show-button route="admin.purchases.show" :model="$purchase"/>
                        @endcan
                        @can('edit purchases')
                          <x-core::edit-button route="admin.purchases.edit" :model="$purchase"/>
                        @endcan
                        @can('delete purchases')
                          <x-core::delete-button route="admin.purchases.destroy" :model="$purchase"/>
                        @endcan
                      </td>
                    </tr>
                    @empty
											<x-core::data-not-found-alert :colspan="8"/>
                  @endforelse
                </tbody>
              </table>
              {{ $purchases->onEachSide(0)->links("vendor.pagination.bootstrap-4") }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
