@extends('admin.layouts.master')

@section('content')
  <div class="col-12">
    <div class="col-xl-12 col-md-12 col-lg-12">

			<div class="page-header">
        <x-core::breadcrumb :items="$breadcrumbItems" />
        @can('create roles')
          <x-core::register-button route="admin.roles.create" title="ثبت نقش جدید"/>
        @endcan
    	</div>

      <div class="card">

        <div class="card-header border-0 justify-content-between ">
          <div class="d-flex">
            <p class="card-title ml-2">لیست نقش ها</p>
            <span class="fs-15 ">({{ $rolesCount }})</span>
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
                      <th class="text-center border-top">نام</th>
                      <th class="text-center border-top">نام قابل مشاهده</th>
                      <th class="text-center border-top">تاریخ ثبت</th>
                      <th class="text-center border-top">عملیات</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($roles as $role)
                      <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $role->name }}</td>
                        <td class="text-center">{{ $role->label }}</td>
                        <td class="text-center">{{ verta($role->created_at) }}</td>
                        <td class="text-center">
                          <div class="d-flex justify-content-center">

                            @can('edit roles')
                              <a
                                href="{{route("admin.roles.edit", $role)}}"
                                class="action-btns1 bg-warning mx-1"
                                @if ($role->name == 'super_admin') style="pointer-events: none;" @endif>
                                <i class="fe fe-edit text-white py-1"></i>
                              </a>
                            @endcan

                            @can('delete roles')
                              <button
                                onclick="confirmDelete('delete-{{ $role->id }}')"
                                class="action-btns1 bg-danger mx-1"
                                @disabled($role->name == 'super_admin')>
                                <i class="fe fe-trash-2 text-white py-1"></i>
                              </button>
                              <form
                                action="{{ route("admin.roles.destroy", $role) }}"
                                method="POST"
                                id="delete-{{ $role->id }}"
                                style="display: none">
                                @csrf
                                @method('DELETE')
                              </form>
                            @endcan

                          </div>
                        </td>
                      </tr>
                      @empty
												<x-core::data-not-found-alert :colspan="5"/>
                    @endforelse
                  </tbody>
                </table>
                {{ $roles->onEachSide(1)->links("vendor.pagination.bootstrap-4") }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
