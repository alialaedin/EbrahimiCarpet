@extends('admin.layouts.master')

@section('content')
  <div class="col-12">
    <div class="col-xl-12 col-md-12 col-lg-12">

			<div class="page-header">
        <x-core::breadcrumb :items="$breadcrumbItems" />
        @can('create purchases')
          <x-core::register-button route="admin.purchases.create" title="ثبت خرید جدید"/>
        @endcan
    	</div>

      @include('purchase::_filter-form')

      <div class="card">

        <div class="card-header border-0 justify-content-between ">
          <div class="d-flex">
            <p class="card-title ml-2" style="font-weight: bolder;">لیست خرید ها</p>
            <span class="fs-15 ">({{ $purchasesCount }})</span>
          </div>
        </div>
        
        <div class="card-body">
          <div class="table-responsive">
            <div id="hr-table-wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
              <div class="row">
                <table class="table table-vcenter text-nowrap table-bordered border-bottom" id="hr-table">
                  <thead class="thead-light">
                    <tr>
                      <th class="text-center">شناسه</th>
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
                        <td class="text-center">{{ $purchase->id }}</td>
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
                          @can('view payments')
                            <a href="{{ route('admin.purchases.payments.index', $purchase) }}" class="btn btn-success btn-icon btn-sm">
                              <i class="fa fa-money" data-toggle="tooltip" data-original-title="پرداختی ها"></i>
                            </a>
                          @endcan
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
												<x-core::data-not-found-alert :colspan="7"/>
                    @endforelse
                  </tbody>
                </table>
                {{ $purchases->onEachSide(1)->links("vendor.pagination.bootstrap-4") }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection