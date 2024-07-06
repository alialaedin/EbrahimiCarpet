@extends('admin.layouts.master')
@section('content')
  <div class="page-header">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}"><i class="fe fe-home ml-1"></i> داشبورد </a>
      </li>
      <li class="breadcrumb-item active">لیست حقوق ها</li>
    </ol>
    @can('create salaries')
      <x-core::register-button route="admin.salaries.create" title="ثبت حقوق جدید"/>
    @endcan
  </div>
  @include('accounting::salary.filter-form')
  <div class="card">
    <div class="card-header border-0">
      <p class="card-title"> لیست همه حقوق ها ({{ $totalSalaries }}) </p>
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
                <th class="text-center">نام پرسنل</th>
                <th class="text-center">مبلغ (ریال)</th>
                <th class="text-center">اضافه کاری (ساعت)</th>
                <th class="text-center">تاریخ پرداخت</th>
                <th class="text-center">عکس رسید</th>
                <th class="text-center">تاریخ ثبت</th>
                <th class="text-center">عملیات</th>
              </tr>
              </thead>
              <tbody>
              @forelse ($salaries as $salary)
                <tr>
                  <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                  <td class="text-center">
                    <a href="{{ route('admin.employees.show', $salary->employee) }}" target="_blank">
                      {{ $salary->employee->name }}
                    </a>
                  </td>
                  <td class="text-center">{{ number_format($salary->amount) }}</td>
                  <td class="text-center">{{ $salary->overtime ?? 0 }}</td>
                  <td class="text-center">{{ verta($salary->payment_date)->format('Y/m/d H:i') }}</td>
                  <td class="text-center m-0 p-0">
                    @if ($salary->receipt_image)
                      <figure class="figure my-2">
                        <a target="_blank" href="{{ Storage::url($salary->receipt_image) }}">
                          <img src="{{ Storage::url($salary->receipt_image) }}" class="img-thumbnail" alt="image" width="50" style="max-height: 32px;" />
                        </a>
                      </figure>
                    @else
                      <span> - </span>
                    @endif
                  </td>
                  <td class="text-center">{{ verta($salary->created_at)->format('Y/m/d H:i') }}</td>
                  <td class="text-center">
                    @can('view salaries')
                      <x-core::show-button route="admin.salaries.show" :model="$salary"/>
                    @endcan
                    @can('edit salaries')
                      <x-core::edit-button route="admin.salaries.edit" :model="$salary"/>
                    @endcan
                    @can('delete salaries')
                      <x-core::delete-button route="admin.salaries.destroy" :model="$salary"/>
                    @endcan
                  </td>
                </tr>
              @empty
                <x-core::data-not-found-alert :colspan="8"/>
              @endforelse
              </tbody>
            </table>
            {{ $salaries->onEachSide(0)->links("vendor.pagination.bootstrap-4") }}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
