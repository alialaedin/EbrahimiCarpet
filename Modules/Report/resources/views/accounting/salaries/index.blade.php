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
  <div class="card print-box">
    <div class="card-body">
      <div class="table-responsive">
        <div class="dataTables_wrapper dt-bootstrap4 no-footer">
          <div class="row">
            <table class="table table-vcenter table-striped text-nowrap table-bordered border-bottom">
              <thead class="thead-light">
              <tr>
                <th class="text-center">ردیف</th>
                <th class="text-center">نام کارمند</th>
                <th class="text-center">شماره موبایل</th>
                <th class="text-center">حقوق پایه (تومان)</th>
                <th class="text-center">حقوق پرداخت شده (تومان)</th>
                <th class="text-center">اضافه کاری (ساعت)</th>
                <th class="text-center">تاریخ پرداخت</th>
                <th class="text-center">تاریخ ثبت</th>
              </tr>
              </thead>
              <tbody>
              @forelse ($salaries as $salary)
                <tr>
                  <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                  <td class="text-center">{{ $salary->employee->name }}</td>
                  <td class="text-center">{{ $salary->employee->mobile }}</td>
                  <td class="text-center">{{ number_format($salary->employee->salary) }}</td>
                  <td class="text-center">{{ number_format($salary->amount) }}</td>
                  <td class="text-center">{{ $salary->overtime }}</td>
                  <td class="text-center"> @jalaliDate($salary->payment_date)</td>
                  <td class="text-center"> @jalaliDate($salary->created_at)</td>
                </tr>
              @empty
                <x-core::data-not-found-alert :colspan="8"/>
              @endforelse
              <tr>
                <td class="text-center font-weight-bold" colspan="4">جمع کل</td>
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
