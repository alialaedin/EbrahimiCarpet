@extends('admin.layouts.master')
@section('content')
  <div class="page-header">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}">
          <i class="fe fe-home ml-1"></i> داشبورد
        </a>
      </li>
      <li class="breadcrumb-item active">لیست تامین کنندگان</li>
    </ol>
    @can('create suppliers')
      <x-core::register-button route="admin.suppliers.create" title="ثبت تامین کننده جدید"/>
    @endcan
  </div>
  @include('supplier::includes.filter-form')
  <div class="card">
    <div class="card-header border-0">
      <p class="card-title">لیست تامین کنندگان ({{ $totalSuppliers }})</p>
      <X-core::card-options/>
    </div>
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
                <th class="text-center">وضعیت</th>
                <th class="text-center">تاریخ ثبت</th>
                <th class="text-center">عملیات</th>
              </tr>
              </thead>
              <tbody>
              @forelse ($suppliers as $supplier)
                <tr>
                  <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                  <td class="text-center">{{ $supplier->name }}</td>
                  <td class="text-center">{{ $supplier->mobile }}</td>
                  <td class="text-center">
                    <x-core::badge
                      type="{{ $supplier->status ? 'success' : 'danger' }}"
                      text="{{ $supplier->status ? 'فعال' : 'غیر فعال' }}"
                    />
                  <td class="text-center"> @jalaliDate($supplier->created_at) </td>
                  <td class="text-center">
                    @can('view payments')
                      <a
                        href="{{ route('admin.payments.show', $supplier) }}"
                        class="btn btn-success btn-icon btn-sm"
                        data-toggle="tooltip"
                        data-original-title="پرداختی ها">
                        <i class="fa fa-money" ></i>
                      </a>
                    @endcan
                    @can('view suppliers')
                        <x-core::show-button route="admin.suppliers.show" :model="$supplier"/>
                      @endcan
                      @can('edit suppliers')
                        <x-core::edit-button route="admin.suppliers.edit" :model="$supplier"/>
                      @endcan
                      @can('delete suppliers')
                        <x-core::delete-button
                          route="admin.suppliers.destroy"
                          :model="$supplier"
                          disabled="{{ !$supplier->isDeletable() }}"
                        />
                      @endcan
                  </td>
                </tr>
              @empty
                <x-core::data-not-found-alert :colspan="6"/>
              @endforelse
              </tbody>
            </table>
            {{ $suppliers->onEachSide(0)->links("vendor.pagination.bootstrap-4") }}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
