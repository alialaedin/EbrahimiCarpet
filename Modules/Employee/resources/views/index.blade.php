@extends('admin.layouts.master')
@section('content')

  <div class="page-header">
    <x-core::breadcrumb :items="[['title' => 'لیست کارمندان']]"/>
    @can('create employees')
      <x-core::create-button route="admin.employees.create" title="ثبت کارمند جدید"/>
    @endcan
  </div>

  <x-core::card>
    <x-slot name="cardTitle">جستجوی پیشرفته</x-slot>
    <x-slot name="cardOptions"><x-core::card-options/></x-slot>
    <x-slot name="cardBody">
      <form action="{{ route("admin.employees.index") }}" class="col-12">
        <div class="row">
          <div class="col-12 col-md-6 col-xl-3 ">
            <div class="form-group">
              <label for="full_name">نام و نام خانوادگی :</label>
              <input type="text" id="full_name" name="full_name" class="form-control" value="{{ request('full_name') }}">
            </div>
          </div>
          <div class="col-12 col-md-6 col-xl-3 ">
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
    </x-slot>
  </x-core::card>

  <x-core::card>
    <x-slot name="cardTitle">لیست پرسنل ({{ $totalEmployees }})</x-slot>
    <x-slot name="cardOptions"><x-core::card-options/></x-slot>
    <x-slot name="cardBody">
      <x-core::table>
        <x-slot name="tableTh">
          <tr>
            <th>ردیف</th>
            <th>نام و نام خانوادگی</th>
            <th>شماره موبایل</th>
            <th>کد ملی</th>
            <th>میزان حقوق (ریال)</th>
            <th>تاریخ استخدام</th>
            <th>تاریخ ثبت</th>
            <th>عملیات</th>
          </tr>
        </x-slot>
        <x-slot name="tableTd">
          @forelse ($employees as $employee)
            <tr>
              <td class="font-weight-bold">{{ $loop->iteration }}</td>
              <td>{{ $employee->name }}</td>
              <td>{{ $employee->mobile }}</td>
              <td>{{ $employee->national_code ?? '-' }}</td>
              <td>{{ number_format($employee->salary) }}</td>
              <td> @jalaliDateFormat($employee->employmented_at)</td>
              <td> @jalaliDate($employee->created_at)</td>
              <td>
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
            <x-core::data-not-found-alert :colspan="8"/>
          @endforelse
        </x-slot>
        <x-slot name="extraData">{{ $employees->onEachSide(0)->links("vendor.pagination.bootstrap-4") }}</x-slot>
      </x-core::table>
    </x-slot>
  </x-core::card>

@endsection

@section('scripts')
  <x-core::date-input-script textInputId="from_employmented_date_show" dateInputId="from_employmented_date"/>
  <x-core::date-input-script textInputId="to_employmented_date_show" dateInputId="to_employmented_date"/>
@endsection
