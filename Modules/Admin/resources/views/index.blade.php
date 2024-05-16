@extends('admin.layouts.master')

@section('content')
  <div class="col-12">
    <div class="col-xl-12 col-md-12 col-lg-12">

			<div class="page-header">
        <x-core::breadcrumb :items="$breadcrumbItems" />
        @can('create admins')
          <x-core::register-button route="admin.admins.create" title="ثبت ادمین جدید"/>
        @endcan
    	</div>

      <div class="card">

        <div class="card-header border-0 justify-content-between ">
          <div class="d-flex">
            <p class="card-title ml-2" style="font-weight: bolder;">لیست ادمین ها</p>
            <span class="fs-15 ">({{ $adminsCount }})</span>
          </div>
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
                        <td class="text-center">
                          <x-core::badge 
                            type="{{ $admin->status ? 'success' : 'danger' }}" 
                            text="{{ $admin->status ? 'فعال' : 'غیر فعال' }}" 
                          />
                        </td>
                        <td class="text-center">{{ verta($admin->created_at) }}</td>
                        <td class="text-center">
                          <div class="d-flex justify-content-center">

                            @can('view admins')
                              <a href="{{route("admin.admins.show", $admin)}}" class="action-btns1 bg-info mx-1">
                                <i class="fe fe-eye text-white py-1"></i>
                              </a>
                            @endcan

                            @can('edit admins')
                              <a href="{{route("admin.admins.edit", $admin)}}" class="action-btns1 bg-warning mx-1">
                                <i class="fe fe-edit text-white py-1"></i>
                              </a>
                            @endcan

                            @can('delete admins')
                              <button onclick="confirmDelete('delete-{{ $admin->id }}')" class="action-btns1 bg-danger mx-1">
                                <i class="fe fe-trash-2 text-white py-1"></i>
                              </button>
                              <form 
                                action="{{ route("admin.admins.destroy", $admin) }}" 
                                method="POST" 
                                id="delete-{{ $admin->id }}" 
                                style="display: none">
                                @csrf
                                @method('DELETE')
                              </form>
                            @endcan

                          </div>
                        </td>
                      </tr>
                      @empty
												<x-core::data-not-found-alert colspan="6"/>
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
  </div>
@endsection