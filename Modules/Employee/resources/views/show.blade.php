@extends('admin.layouts.master')
@section('content')

  <div class="page-header">
    <x-core::breadcrumb :items="[['title' => 'لیست کارمندان', 'route_link' => 'admin.employees.index'], ['title' => 'نمایش کارمند']]"/>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
      @can('edit employees')
        <a href="{{ route('admin.employees.edit', $employee) }}" class="btn btn-sm btn-warning mx-1">
          ویرایش کارمند<i class="fa fa-pencil mr-2"></i>
        </a>
      @endcan
      @can('delete employees')
        <button
          onclick="confirmDelete('delete-{{ $employee->id }}')"
          class="btn btn-sm btn-danger mx-1"
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
        <button class="btn btn-sm btn-indigo mx-1" data-target="#createSalaryModal" data-toggle="modal">
          پرداخت حقوق<i class="fa fa-plus mr-2"></i>
        </button>
      @endcan
      </div>
  </div>

  <div class="row">
    <div class="col-md-6">
      <x-core::card>
        <x-slot name="cardTitle">مشخصات کارمند</x-slot>
        <x-slot name="cardOptions"><x-core::card-options/></x-slot>
        <x-slot name="cardBody">
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
        </x-slot>
      </x-core::card>
    </div>
    <div class="col-md-6">
      <x-core::card>
        <x-slot name="cardTitle">اطلاعات پایه</x-slot>
        <x-slot name="cardOptions"><x-core::card-options/></x-slot>
        <x-slot name="cardBody">
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
        </x-slot>
      </x-core::card>
    </div>
  </div>

  <x-core::card>
    <x-slot name="cardTitle">لیست حقوق های پرداخت شده به کارمند</x-slot>
    <x-slot name="cardOptions"><x-core::card-options/></x-slot>
    <x-slot name="cardBody">
      <x-core::table>
        <x-slot name="tableTh">
          <tr>
            <th>ردیف</th>
            <th>مبلغ (ریال)</th>
            <th>اضافه کاری (ساعت)</th>
            <th>تاریخ پرداخت</th>
            <th>عکس رسید</th>
            <th>تاریخ ثبت</th>
            <th>عملیات</th>
          </tr>
        </x-slot>
        <x-slot name="tableTd">
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
        </x-slot>
      </x-core::table>
    </x-slot>
  </x-core::card>

@include('employee::includes.create-salary-modal')
@endsection
