@extends('admin.layouts.master')
@section('content')
  <div class="page-header d-print-none">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}">
          <i class="fe fe-home ml-1"></i> داشبورد
        </a>
      </li>
      <li class="breadcrumb-item active">گزارش مالی حقوق ها</li>
    </ol>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
      <x-core::print-button/>
    </div>
  </div>
  <div class="card d-print-none">

    <div class="card-header border-0">
      <p class="card-title">جستجوی پیشرفته</p>
      <x-core::card-options/>
    </div>

    <div class="card-body">
      <div class="row">
        <form action="{{ route("admin.reports.salaries") }}" class="col-12">
          <div class="row">

            <div class="col-12 col-md-6 col-xl-4">
              <div class="form-group">
                <label for="employee_id">انتخاب کارمند :</label>
                <select name="employee_id" id="employee_id" class="form-control select2">
                  <option value="">همه</option>
                  @foreach ($employees as $employee)
                    <option
                      value="{{ $employee->id }}"
                      @selected(request("employee_id") == $employee->id)>
                      {{ $employee->name }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="col-12 col-md-6 col-xl-4">
              <div class="form-group">
                <label for="from_payment_date_show">از تاریخ :</label>
                <input class="form-control fc-datepicker" id="from_payment_date_show" type="text" autocomplete="off"/>
                <input name="from_payment_date" id="from_payment_date" type="hidden" value="{{ request("from_payment_date") }}"/>
              </div>
            </div>

            <div class="col-12 col-md-6 col-xl-4">
              <div class="form-group">
                <label for="to_payment_date_show">تا تاریخ :</label>
                <input class="form-control fc-datepicker" id="to_payment_date_show" type="text" autocomplete="off"/>
                <input name="to_payment_date" id="to_payment_date" type="hidden" value="{{ request("to_payment_date") }}"/>
              </div>
            </div>

          </div>

          <div class="row">

            <div class="col-12 col-md-6 col-xl-8">
              <button class="btn btn-primary btn-block" type="submit">جستجو <i class="fa fa-search"></i></button>
            </div>

            <div class="col-12 col-md-6 col-xl-4">
              <a href="{{ route("admin.reports.salaries") }}" class="btn btn-danger btn-block">حذف همه فیلتر ها <i class="fa fa-close"></i></a>
            </div>

          </div>

        </form>
      </div>
    </div>
  </div>
  <div class="row justify-content-center d-none d-print-flex">
    <p class="fs-22">گزارش مالی حقوق ها</p>
  </div>
  <div class="card">
    <div class="card-body">
      <div class="table-responsive">
        <div class="dataTables_wrapper dt-bootstrap4 no-footer">
          <div class="row">
            <table class="table table-vcenter table-striped text-nowrap table-bordered border-bottom">
              <thead class="thead-light">
              <tr class="fs-10">
                <th class="text-center">ردیف</th>
                <th class="text-center">نام کارمند</th>
                <th class="text-center">شماره موبایل</th>
                <th class="text-center">اضافه کاری <span class="d-print-none">(ساعت)</span></th>
                <th class="text-center">تاریخ پرداخت</th>
                <th class="text-center">تاریخ ثبت</th>
                <th class="text-center">حقوق پایه (ریال)</th>
                <th class="text-center">حقوق پرداخت شده (ریال)</th>
              </tr>
              </thead>
              <tbody>
              @forelse ($salaries as $salary)
                <tr>
                  <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                  <td class="text-center">{{ $salary->employee->name }}</td>
                  <td class="text-center">{{ $salary->employee->mobile }}</td>
                  <td class="text-center">{{ $salary->overtime }}</td>
                  <td class="text-center"> @jalaliDate($salary->payment_date)</td>
                  <td class="text-center"> @jalaliDate($salary->created_at)</td>
                  <td class="text-center">{{ number_format($salary->employee->salary) }}</td>
                  <td class="text-center">{{ number_format($salary->amount) }}</td>
                </tr>
              @empty
                <x-core::data-not-found-alert :colspan="8"/>
              @endforelse
              <tr>
                <td class="text-center font-weight-bold" colspan="7">جمع کل</td>
                <td class="text-center font-weight-bold" colspan="1">{{ number_format($salaries->sum('amount')) }}</td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
  <x-core::date-input-script textInputId="from_payment_date_show" dateInputId="from_payment_date"/>
  <x-core::date-input-script textInputId="to_payment_date_show" dateInputId="to_payment_date"/>
@endsection
