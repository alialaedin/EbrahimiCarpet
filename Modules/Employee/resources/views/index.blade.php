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
        <li class="breadcrumb-item active">لیست کارمندان </li>
      </ol>
      @can('create employees')
        <x-core::register-button route="admin.employees.create" title="ثبت کارمند جدید"/>
      @endcan
    </div>
    <div class="card">
      <div class="card-header border-0">
        <p class="card-title">جستجوی پیشرفته</p>
      </div>
      <div class="card-body">
        <div class="row">
          <form action="{{ route("admin.employees.index") }}" class="col-12">
            <div class="row">
              <div class="col-12 col-md-6 col-xl-3 col-xxl-2">
                <div class="form-group">
                  <label for="full_name">نام و نام خانوادگی :</label>
                  <input type="text" id="full_name" name="full_name" class="form-control" value="{{ request('full_name') }}">
                </div>
              </div>
              <div class="col-12 col-md-6 col-xl-3 col-xxl-2">
                <div class="form-group">
                  <label for="mobile">تلفن همراه :</label>
                  <input type="text" id="mobile" name="mobile" class="form-control" value="{{ request('mobile') }}">
                </div>
              </div>
              <div class="col-12 col-md-6 col-xl-3">
                <div class="form-group">
                  <label for="from_employmented_date_show">استخدام از تاریخ :</label>
                  <input class="form-control fc-datepicker" id="from_employmented_date_show" type="text" autocomplete="off"/>
                  <input name="from_employmented_at" id="from_employmented_date" type="hidden" value="{{ request("from_employmented_at") }}"/>
                </div>
              </div>
              <div class="col-12 col-md-6 col-xl-3">
                <div class="form-group">
                  <label for="to_employmented_date_show">تا تاریخ :</label>
                  <input class="form-control fc-datepicker" id="to_employmented_date_show" type="text" autocomplete="off"/>
                  <input name="to_employmented_at" id="to_employmented_date" type="hidden" value="{{ request("to_employmented_at") }}"/>
                </div>
              </div>
            </div>
            <x-core::filter-buttons table="employees"/>
          </form>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-header border-0">
        <p class="card-title ml-2">لیست پرسنل</p>
        <span class="fs-15 ">({{ $totalEmployees }})</span>
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
                    <th class="text-center border-top">شماره موبایل</th>
                    <th class="text-center border-top">کد ملی</th>
                    <th class="text-center border-top">میزان حقوق (تومان)</th>
                    <th class="text-center border-top">تاریخ استخدام</th>
                    <th class="text-center border-top">عملیات</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($employees as $employee)
                    <tr>
                      <td class="text-center">{{ $loop->iteration }}</td>
                      <td class="text-center">{{ $employee->name }}</td>
                      <td class="text-center">{{ $employee->mobile }}</td>
                      <td class="text-center">{{ $employee->national_code }}</td>
                      <td class="text-center">{{ number_format($employee->salary) }}</td>
                      <td class="text-center">{{ verta($employee->employmented_at)->formatDate() }}</td>
                      <td class="text-center">
                        @can('view employees')
                          <x-core::show-button route="admin.employees.show" :model="$employee"/>
                        @endcan
                        @can('edit employees')
                          <x-core::edit-button route="admin.employees.edit" :model="$employee"/>
                        @endcan
                        @can('delete employees')
                          <x-core::delete-button route="admin.employees.destroy" :model="$employee"/>
                        @endcan
                      </td>
                    </tr>
                    @empty
											<x-core::data-not-found-alert :colspan="7"/>
                  @endforelse
                </tbody>
              </table>
              {{ $employees->onEachSide(1)->links("vendor.pagination.bootstrap-4") }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')

  <script>
    $('#from_employmented_date_show').MdPersianDateTimePicker({
      targetDateSelector: '#from_employmented_date',
      targetTextSelector: '#from_employmented_date_show',
      englishNumber: false,
      toDate:true,
      enableTimePicker: false,
      dateFormat: 'yyyy-MM-dd',
      textFormat: 'yyyy-MM-dd',
      groupId: 'rangeSelector1',
    });

    $('#to_employmented_date_show').MdPersianDateTimePicker({
      targetDateSelector: '#to_employmented_date',
      targetTextSelector: '#to_employmented_date_show',
      englishNumber: false,
      toDate:true,
      enableTimePicker: false,
      dateFormat: 'yyyy-MM-dd',
      textFormat: 'yyyy-MM-dd',
      groupId: 'rangeSelector1',
    });
  </script>

@endsection
