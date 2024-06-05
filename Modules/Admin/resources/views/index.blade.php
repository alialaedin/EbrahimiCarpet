@extends('admin.layouts.master')
@section('content')
  <div class="col-12">
		<div class="page-header">
      <ol class="breadcrumb align-items-center">
        <li class="breadcrumb-item">
          <a href="{{ route('admin.dashboard') }}"><i class="fe fe-home ml-1"></i> داشبورد</a>
        </li>
        <li class="breadcrumb-item active">لیست ادمین ها</li>
      </ol>
      @can('create admins')
        <x-core::register-button route="admin.admins.create" title="ثبت ادمین جدید"/>
      @endcan
    </div>
    <div class="card">
      <div class="card-header border-0">
        <p class="card-title ml-2">لیست ادمین ها</p>
        <span class="fs-15 ">({{ $adminsCount }})</span>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <div id="hr-table-wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
            <div class="row">
              <table class="table table-vcenter text-nowrap table-bordered border-bottom" id="hr-table">
                <thead class="thead-light">
                  <tr>
                    <th class="text-center border-top">ردیف</th>
                    <th class="text-center border-top">نام و نام خانوادگی</th>
                    <th class="text-center border-top">شناسه</th>
                    <th class="text-center border-top">شماره موبایل</th>
                    <th class="text-center border-top">نقش</th>
                    <th class="text-center border-top">وضعیت</th>
                    <th class="text-center border-top">تاریخ ثبت</th>
                    <th class="text-center border-top">عملیات</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($admins as $admin)
                    <tr>
                      <td class="text-center">{{ $loop->iteration }}</td>
                      <td class="text-center">{{ $admin->name }}</td>
                      <td class="text-center">{{ $admin->id }}</td>
                      <td class="text-center">{{ $admin->mobile }}</td>
                      <td class="text-center">{{ $admin->getRoleLabel() }}</td>
                      <td class="text-center">
                        <x-core::badge
                          type="{{ $admin->status ? 'success' : 'danger' }}"
                          text="{{ $admin->status ? 'فعال' : 'غیر فعال' }}"
                        />
                      </td>
                      <td class="text-center">{{ verta($admin->created_at)->formatDate() }}</td>
                      <td class="text-center">
                        @can('view admins')
                          <x-core::show-button route="admin.admins.show" :model="$admin"/>
                        @endcan
                        @can('edit admins')
                          <x-core::edit-button route="admin.admins.edit" :model="$admin"/>
                        @endcan
                        @can('delete admins')
                          <x-core::delete-button route="admin.admins.destroy" :model="$admin"/>
                        @endcan
                      </td>
                    </tr>
                    @empty
											<x-core::data-not-found-alert :colspan="6"/>
                  @endforelse
                </tbody>
              </table>
              {{ $admins->onEachSide(1)->links("vendor.pagination.bootstrap-4") }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
