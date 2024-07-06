@extends('admin.layouts.master')
@section('content')
  <div class="page-header">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}">
          <i class="fe fe-home ml-1"></i> داشبورد
        </a>
      </li>
      <li class="breadcrumb-item active">لیست کارمندان</li>
    </ol>
    @can('create employees')
      <x-core::register-button route="admin.employees.create" title="ثبت کارمند جدید"/>
    @endcan
  </div>
  @include('employee::includes.filter-form')
  <div class="card">
    <div class="card-header border-0">
      <p class="card-title">لیست پرسنل ({{ $totalEmployees }})</p>
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
                <th class="text-center">نام و نام خانوادگی</th>
                <th class="text-center">شماره موبایل</th>
                <th class="text-center">کد ملی</th>
                <th class="text-center">میزان حقوق (ریال)</th>
                <th class="text-center">تاریخ استخدام</th>
                <th class="text-center">عملیات</th>
              </tr>
              </thead>
              <tbody>
              @forelse ($employees as $employee)
                <tr>
                  <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                  <td class="text-center">{{ $employee->name }}</td>
                  <td class="text-center">{{ $employee->mobile }}</td>
                  <td class="text-center">{{ $employee->national_code }}</td>
                  <td class="text-center">{{ number_format($employee->salary) }}</td>
                  <td class="text-center"> @jalaliDate($employee->employmented_at)</td>
                  <td class="text-center">
                    @can('view employees')
                      <x-core::show-button route="admin.employees.show" :model="$employee"/>
                    @endcan
                    @can('edit employees')
                      <x-core::edit-button route="admin.employees.edit" :model="$employee"/>
                    @endcan
                    @can('delete employees')
                      <x-core::delete-button
                        route="admin.employees.destroy"
                        :model="$employee"
                        disabled="{{ !$employee->isDeletable() }}"
                      />
                    @endcan
                  </td>
                </tr>
              @empty
                <x-core::data-not-found-alert :colspan="7"/>
              @endforelse
              </tbody>
            </table>
            {{ $employees->onEachSide(0)->links("vendor.pagination.bootstrap-4") }}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
