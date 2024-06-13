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
        <li class="breadcrumb-item">لیست نقش ها</li>
      </ol>
      @can('create roles')
        <x-core::register-button route="admin.roles.create" title="ثبت نقش جدید"/>
      @endcan
    </div>

    <div class="card">
      <div class="card-header border-0">
        <p class="card-title">لیست نقش ها <span class="fs-15 ">({{ $rolesCount }})</span></p>
        <div class="card-options">
          <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
          <a href="#" class="card-options-fullscreen" data-toggle="card-fullscreen"><i class="fe fe-maximize"></i></a>
          <a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <div class="dataTables_wrapper dt-bootstrap4 no-footer">
            <div class="row">
              <table class="table table-vcenter text-nowrap table-bordered border-bottom">
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
                        <a
                          href="{{route('admin.roles.edit', $role)}}"
                          class="btn btn-sm btn-icon btn-warning text-white"
                          data-toggle="tooltip"
                          data-original-title="ویرایش"
                          @if ($role->name == 'super_admin') style="pointer-events: none;" @endif>
                          <i class="fa fa-pencil"></i>
                        </a>
                        <button
                          onclick="confirmDelete('delete-{{ $role->id }}')"
                          class="btn btn-sm btn-icon btn-danger"
                          data-toggle="tooltip"
                          data-original-title="حذف"
                          @disabled($role->name == 'super_admin')>
                          <i class="fa fa-trash-o"></i>
                        </button>
                        <form
                          action="{{ route('admin.roles.destroy', $role) }}"z
                          method="POST"
                          id="delete-{{ $role->id }}"
                          style="display: none">
                          @csrf
                          @method('DELETE')
                        </form>
                      </td>
                    </tr>
                  @empty
                    <x-core::data-not-found-alert :colspan="5"/>
                  @endforelse
                </tbody>
              </table>
              {{ $roles->onEachSide(0)->links("vendor.pagination.bootstrap-4") }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
