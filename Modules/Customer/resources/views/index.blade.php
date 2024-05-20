@extends('admin.layouts.master')

@section('content')
  <div class="col-12">
    <div class="col-xl-12 col-md-12 col-lg-12">

			<div class="page-header">
        <x-core::breadcrumb :items="$breadcrumbItems" />
        @can('create customers')
          <x-core::register-button route="admin.customers.create" title="ثبت مشتری جدید"/>
        @endcan
    	</div>

      <x-core::filter route="admin.customers.index" :inputs="$filterInputs"/>

      <div class="card">

        <div class="card-header border-0 justify-content-between ">
          <div class="d-flex">
            <p class="card-title ml-2" style="font-weight: bolder;">لیست مشتری ها</p>
            <span class="fs-15 ">({{ $customersCount }})</span>
          </div>
        </div>
        
        <div class="card-body">
          <div class="table-responsive">
            <div id="hr-table-wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
              <div class="row">
                <table class="table table-vcenter text-nowrap table-bordered border-bottom" id="hr-table">
                  <thead class="thead-light">
                    <tr>
                      <th class="text-center border-top">شناسه</th>
                      <th class="text-center border-top">نام و نام خانوادگی</th>
                      <th class="text-center border-top">شماره موبایل</th>
                      <th class="text-center border-top">تلفن ثابت</th>
                      <th class="text-center border-top">وضعیت</th>
                      <th class="text-center border-top">تاریخ ثبت</th>
                      <th class="text-center border-top">عملیات</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($customers as $customer)
                      <tr>
                        <td class="text-center">{{ $customer->id }}</td>
                        <td class="text-center">{{ $customer->name }}</td>
                        <td class="text-center">{{ $customer->mobile }}</td>
                        <td class="text-center">{{ $customer->landline_phone }}</td>
                        <td class="text-center">
                          <x-core::badge 
                            type="{{ $customer->status ? 'success' : 'danger' }}" 
                            text="{{ $customer->status ? 'فعال' : 'غیر فعال' }}" 
                          />
                        </td>
                        <td class="text-center">{{ verta($customer->created_at) }}</td>
                        <td class="text-center">
                          <div class="d-flex justify-content-center">

                            @can('view customers')
                              <x-core::show-button route="admin.customers.show" :model="$customer"/>
                            @endcan

                            @can('edit customers')
                              <x-core::edit-button route="admin.customers.edit" :model="$customer"/>
                            @endcan

                            @can('delete customers')
                              <x-core::delete-button route="admin.customers.destroy" :model="$customer"/>
                            @endcan

                          </div>
                        </td>
                      </tr>
                      @empty
												<x-core::data-not-found-alert :colspan="7"/>
                    @endforelse
                  </tbody>
                </table>
                {{ $customers->onEachSide(1)->links("vendor.pagination.bootstrap-4") }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection