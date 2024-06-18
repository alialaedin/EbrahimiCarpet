@extends('admin.layouts.master')
@section('content')
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
      <li class="breadcrumb-item">ثبت حقوق جدید</li>
    </ol>
  </div>
  <div class="card">
    <div class="card-header border-bottom">
      <p class="card-title">ثبت حقوق جدید</p>
      <x-core::card-options/>
    </div>
    <div class="card-body">
      <form action="{{ route('admin.salaries.store') }}" method="post" class="save" enctype="multipart/form-data">
        @csrf
        <div class="row">
          <div class="col-xl-4 col-md-6 col-12">
            <div class="form-group">
              <label for="employee_id" class="control-label">انتخاب کارمند :<span class="text-danger">&starf;</span></label>
              <select name="employee_id" id="employee_id" class="form-control" onchange="getEmployeeSalary('#employee_id')">
                <option value="" class="text-muted">کارمند را انتخاب کنید</option>
                @foreach ($employees as $employee)
                  <option value="{{ $employee->id }}" @selected(old('employee_id') == $employee->id)>{{ $employee->name . ' - ' . $employee->mobile }}</option>
                @endforeach
              </select>
              <x-core::show-validation-error name="employee_id" />
            </div>
          </div>
          <div class="col-xl-4 col-md-6 col-12">
            <div class="form-group">
              <label for="amount" class="control-label">مبلغ حقوق (تومان) :<span class="text-danger">&starf;</span></label>
              <input type="text" name="amount" id="amount" placeholder="عنوان را وارد کنید" class="form-control comma" value="{{ old('amount') }}">
              <x-core::show-validation-error name="amount" />
            </div>
          </div>
          <div class="col-xl-4 col-md-6 col-12">
            <div class="form-group">
              <label for="overtime" class="control-label">اضافه کاری (ساعت) :</label>
              <input type="number" name="overtime" id="overtime" placeholder="تعداد ساعت اضافه کاری را وارد کنید" class="form-control" value="{{ old('overtime') }}">
              <x-core::show-validation-error name="overtime" />
            </div>
          </div>
          <div class="col-xl-4 col-md-6 col-12">
            <div class="form-group">
              <label for="payment_date_show" class="control-label">تاریخ پرداخت :<span class="text-danger">&starf;</span></label>
              <input class="form-control fc-datepicker" id="payment_date_show" type="text" autocomplete="off" placeholder="تاریخ پرداخت را انتخاب کنید" />
              <input name="payment_date" id="payment_date" type="hidden" value="{{ old("payment_date") }}" required/>
              <x-core::show-validation-error name="payment_date" />
            </div>
          </div>
          <div class="col-xl-4 col-md-6 col-12">
            <div class="form-group">
              <label for="receipt_image" class="control-label">عکس فیش :</label>
              <input type="file" name="receipt_image" id="receipt_image" class="form-control" value="{{ old('receipt_image') }}">
              <x-core::show-validation-error name="receipt_image" />
            </div>
          </div>
          <div class="col-12">
            <div class="form-group">
              <label for="description" class="control-label">توضیحات :</label>
              <textarea name="description" id="description" placeholder="توضیحات لازم را وارد کنید" class="form-control" rows="5"> {{ old('description') }} </textarea>
              <x-core::show-validation-error name="description" />
            </div>
          </div>
        </div>
        <x-core::store-button/>
      </form>
    </div>
  </div>
@endsection

@section('scripts')

  <script>
    function getEmployeeSalary(id) {
      let employeeId = $(id).val();
      let token = $('meta[name="csrf-token"]').attr('content');
      $.ajax({
        url: '{{ route("admin.salaries.get-employee-salary") }}',
        type: 'POST',
        data: {employee_id: employeeId},
        headers: {'X-CSRF-TOKEN': token},
        success: function(response) {
          $('#amount').val(response);
        }
      });
    }
  </script>

  <x-core::date-input-script textInputId="payment_date_show" dateInputId="payment_date"/>
@endsection
