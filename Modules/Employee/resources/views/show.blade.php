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
      <li class="breadcrumb-item">
        <a href="{{ route('admin.employees.index') }}">لیست کارمندان</a>
      </li>
      <li class="breadcrumb-item active">نمایش کارمند</li>
    </ol>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
      @can('edit employees')
        <a href="{{ route('admin.employees.edit', $employee) }}" class="btn btn-warning mx-1">
          ویرایش کارمند<i class="fa fa-pencil mr-2"></i>
        </a>
      @endcan
      @can('delete employees')
        <button
          onclick="confirmDelete('delete-{{ $employee->id }}')"
          class="btn btn-danger mx-1"
          @disabled(!$employee->isDeletable())>
          حذف کارمند<i class="fa fa-trash-o mr-2"></i>
        </button>
        <form
          action="{{ route('admin.employees.destroy', $employee) }}"
          method="POST"
          id="delete-{{ $employee->id }}"
          style="display: none">
          @csrf
          @method('DELETE')
        </form>
      @endcan
      @can('create salaries')
        <button class="btn btn-indigo mx-1" data-target="#createSalaryModal" data-toggle="modal">
          پرداخت حقوق<i class="fa fa-plus mr-2"></i>
        </button>
      @endcan
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <div class="card overflow-hidden">
        <div class="card-header border-0">
          <p class="card-title">مشخصات کارمند</p>
          <x-core::card-options/>
        </div>
        <div class="card-body">
          <ul class="list-group">
            <li class="list-group-item fs-16">
              <strong>کد : </strong> {{ $employee->id }}
            </li>
            <li class="list-group-item fs-16">
              <strong>نام و نام خانوادگی : </strong> {{ $employee->name }}
            </li>
            <li class="list-group-item fs-16">
              <strong>شماره موبایل : </strong> {{ $employee->mobile }}
            </li>
            <li class="list-group-item fs-16">
              <strong>تلفن ثابت : </strong>{{ $employee->telephone }}
            </li>
            <li class="list-group-item fs-16">
              <strong>کد ملی : </strong>{{ $employee->national_code }}
            </li>
            <li class="list-group-item fs-16">
              <strong>محل سکونت : </strong>{{ $employee->address }}
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card overflow-hidden">
        <div class="card-header border-0">
          <p class="card-title">اطلاعات پایه</p>
          <x-core::card-options/>
        </div>
        <div class="card-body">
          <ul class="list-group">
            <li class="list-group-item fs-16">
              <strong>شماره کارت : </strong> {{ $employee->card_number }}
            </li>
            <li class="list-group-item fs-16">
              <strong>شماره شبا : </strong> {{ $employee->sheba_number }}
            </li>
            <li class="list-group-item fs-16">
              <strong>نام بانک : </strong> {{ $employee->bank_name }}
            </li>
            <li class="list-group-item fs-16">
              <strong>حقوق پایه : </strong>{{ number_format($employee->salary) . ' ریال' }}
            </li>
            <li class="list-group-item fs-16">
              <strong>تاریخ استخدام : </strong> @jalaliDate($employee->employmented_at)
            </li>
            <li class="list-group-item fs-16">
              <strong>تاریخ ثبت : </strong> @jalaliDate($employee->created_at)
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header border-0">
      <p class="card-title">لیست حقوق های پرداخت شده به کارمند</p>
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
                  <th class="text-center">مبلغ (ریال)</th>
                  <th class="text-center">اضافه کاری (ساعت)</th>
                  <th class="text-center">تاریخ پرداخت</th>
                  <th class="text-center">عکس رسید</th>
                  <th class="text-center">تاریخ ثبت</th>
                  <th class="text-center">عملیات</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($employee->salaries as $salary)
                  <tr>
                    <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ number_format($salary->amount) }}</td>
                    <td class="text-center">{{ $salary->overtime ?? 0 }}</td>
                    <td class="text-center"> @jalaliDate($salary->payment_date) </td>
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
                    <td class="text-center"> @jalaliDate($salary->created_at) </td>
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
                  <x-core::data-not-found-alert :colspan="7"/>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@include('employee::includes.create-salary-modal')
@endsection
