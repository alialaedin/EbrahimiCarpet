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
      <li class="breadcrumb-item">ویرایش حقوق</li>
    </ol>
  </div>
  <div class="card">
    <div class="card-header border-0">
      <p class="card-title">ویرایش حقوق - کد {{ $salary->id }}</p>
      <x-core::card-options/>
    </div>
    <div class="card-body">
      <form action="{{ route('admin.salaries.update', $salary) }}" method="post" class="save">
        @csrf
        @method('PATCH')
        <div class="row">
          <div class="col-xl-4 col-md-6 col-12">
            <div class="form-group">
              <label for="employee_id" class="control-label">نام کارمند :</label>
              <input readonly id="employee_id" class="form-control" value="{{ $salary->employee->name }}">
            </div>
          </div>
          <div class="col-xl-4 col-md-6 col-12">
            <div class="form-group">
              <label for="amount" class="control-label">مبلغ حقوق (تومان) :<span class="text-danger">&starf;</span></label>
              <input type="text" name="amount" id="amount" placeholder="مبلغ حقوق را وارد کنید" class="form-control comma" value="{{ old('amount', number_format($salary->amount)) }}">
              <x-core::show-validation-error name="amount" />
            </div>
          </div>
          <div class="col-xl-4 col-md-6 col-12">
            <div class="form-group">
              <label for="overtime" class="control-label">اضافه کاری (ساعت) :</label>
              <input type="number" name="overtime" id="overtime" placeholder="تعداد ساعت اضافه کاری را وارد کنید" class="form-control" value="{{ old('overtime', $salary->overtime) }}">
              <x-core::show-validation-error name="overtime" />
            </div>
          </div>
          <div class="col-xl-4 col-md-6 col-12">
            <div class="form-group">
              <label for="payment_date_show" class="control-label">تاریخ پرداخت :<span class="text-danger">&starf;</span></label>
              <input class="form-control fc-datepicker" id="payment_date_show" type="text" autocomplete="off" placeholder="تاریخ پرداخت را انتخاب کنید" />
              <input name="payment_date" id="payment_date" type="hidden" value="{{ old("payment_date", $salary->payment_date) }}" required/>
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
          @if ($salary->receipt_image)
            <div class="col-xl-4 col-md-6 col-12">
              <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('delete-image-{{ $salary->id }}')">
                <i class="fa fa-trash-o"></i>
              </button>
              <br>
              <figure class="figure">
                <a target="_blank" href="{{ Storage::url($salary->receipt_image) }}">
                  <img src="{{ Storage::url($salary->receipt_image) }}" class="img-thumbnail" alt="image" width="50" height="50" />
                </a>
              </figure>
            </div>
          @endif
          <div class="col-12">
            <div class="form-group">
              <label for="description" class="control-label">توضیحات :</label>
              <textarea name="description" id="description" placeholder="توضیحات لازم را وارد کنید" class="form-control" rows="5"> {{ old('description', $salary->description) }} </textarea>
              <x-core::show-validation-error name="description" />
            </div>
          </div>
        </div>
        <x-core::update-button/>
      </form>
      @if ($salary->receipt_image)
        <form
          action="{{ route('admin.salaries.image.destroy', $salary) }}"
          id="delete-image-{{$salary->id}}"
          method="POST"
          style="display: none;">
          @csrf
          @method("DELETE")
        </form>
      @endif
    </div>
  </div>
@endsection

@section('scripts')
  <x-core::date-input-script textInputId="payment_date_show" dateInputId="payment_date"/>
@endsection
