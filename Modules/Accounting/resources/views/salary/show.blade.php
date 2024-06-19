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
          <a href="{{ route('admin.salaries.index') }}">لیست حقوق ها</a>
        </li>
        <li class="breadcrumb-item active">نمایش حقوق</li>
      </ol>
      <div class="d-flex align-items-center flex-wrap text-nowrap">
        @can('edit salaries')
          <a href="{{ route('admin.salaries.edit', $salary) }}" class="btn btn-warning mx-1">
            ویرایش حقوق<i class="fa fa-pencil mr-2"></i>
          </a>
        @endcan
        @can('delete salaries')
          <button onclick="confirmDelete('delete-{{ $salary->id }}')" class="btn btn-danger mx-1">
            حذف حقوق<i class="fa fa-trash-o mr-2"></i>
          </button>
          <form
            action="{{ route('admin.salaries.destroy', $salary) }}"
            method="POST"
            id="delete-{{ $salary->id }}"
            style="display: none">
            @csrf
            @method('DELETE')
          </form>
        @endcan
        @can('create salaries')
          <a href="{{ route('admin.salaries.create') }}" class="btn btn-indigo mx-1">
            پرداخت حقوق<i class="fa fa-plus mr-2"></i>
          </a>
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
                <strong>کد : </strong> {{ $salary->employee->id }}
              </li>
              <li class="list-group-item fs-16">
                <strong>نام و نام خانوادگی : </strong>
                <a href="{{ route('admin.employees.show', $salary->employee) }}" target="_blank">
                  {{ $salary->employee->name }}
                </a>
              </li>
              <li class="list-group-item fs-16">
                <strong>شماره موبایل : </strong> {{ $salary->employee->mobile }}
              </li>
              <li class="list-group-item fs-16">
                <strong>تلفن ثابت : </strong>{{ $salary->employee->telephone }}
              </li>
              <li class="list-group-item fs-16">
                <strong>کد ملی : </strong>{{ $salary->employee->national_code }}
              </li>
              <li class="list-group-item fs-16">
                <strong>محل سکونت : </strong>{{ $salary->employee->address }}
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card overflow-hidden">
          <div class="card-header border-0">
            <p class="card-title">اطلاعات حقوق</p>
            <x-core::card-options/>
          </div>
          <div class="card-body">
            <ul class="list-group">
              <li class="list-group-item fs-16">
                <strong>کد : </strong> {{ $salary->id }}
              </li>
              <li class="list-group-item fs-16">
                <strong>حقوق پرداخت شده : </strong> {{ number_format($salary->amount) . ' تومان' }}
              </li>
              <li class="list-group-item fs-16">
                <strong>اضافه کاری : </strong> {{ $salary->overtime . ' ساعت'}}
              </li>
              <li class="list-group-item fs-16">
                <strong>تاریخ پرداخت : </strong> @jalaliDate($salary->payment_date)
              </li>
              <li class="list-group-item fs-16">
                <strong>تاریخ ثبت : </strong> @jalaliDate($salary->created_at)
              </li>
              <li class="list-group-item fs-16">
                <strong>توضیحات : </strong> {{ $salary->description }}
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
