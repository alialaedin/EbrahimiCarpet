@extends('admin.layouts.master')
@section('content')
  <div class="page-header">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}">
          <i class="fe fe-home ml-1"></i> داشبورد
        </a>
      </li>
      <li class="breadcrumb-item active">گزارش مالی هزینه ها </li>
    </ol>
  </div>
  <div class="card">
    <div class="card-body">
      <div class="table-responsive">
        <div class="dataTables_wrapper dt-bootstrap4 no-footer">
          <div class="row">
            <table class="table table-vcenter table-striped text-nowrap table-bordered border-bottom">
              <thead class="thead-light">
              <tr>
                <th class="text-center">ردیف</th>
                <th class="text-center">عنوان سرفصل</th>
                <th class="text-center">عنوان</th>
                <th class="text-center">مبلغ پرداخت شده (تومان)</th>
                <th class="text-center">تاریخ پرداخت</th>
                <th class="text-center">تاریخ ثبت</th>
              </tr>
              </thead>
              <tbody>

              @forelse ($expenses as $expense)
                <tr>
                  <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                  <td class="text-center">{{ $expense->headline->title }}</td>
                  <td class="text-center">{{ $expense->title }}</td>
                  <td class="text-center">{{ number_format($expense->amount) }}</td>
                  <td class="text-center"> @jalaliDate($expense->payment_date)</td>
                  <td class="text-center"> @jalaliDate($expense->created_at)</td>
                </tr>
              @empty
                <x-core::data-not-found-alert :colspan="6"/>
              @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
