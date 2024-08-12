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
        <a href="{{ route('admin.customers.index') }}">لیست مشتری ها</a>
      </li>
      <li class="breadcrumb-item active">ثبت مشتری جدید</li>
    </ol>
  </div>
  <div class="card">
    <div class="card-header">
      <p class="card-title">ثبت مشتری جدید</p>
      <x-core::card-options/>
    </div>
    <div class="card-body">
      <form action="{{ route('admin.customers.store') }}" method="post" class="save">
        @csrf
        <div class="row">
          <div class="col-lg-4 col-md-6">
            <div class="form-group">
              <label for="name" class="control-label"> نام و نام خانوادگی: <span class="text-danger">&starf;</span></label>
              <input
                type="text"
                id="name"
                class="form-control"
                name="name"
                placeholder="نام و نام خانوادگی را وارد کنید"
                value="{{ old('name') }}"
                required
                autofocus
              />
              <x-core::show-validation-error name="name" />
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="form-group">
              <label for="gender" class="control-label"> جنسیت: <span class="text-danger">&starf;</span></label>
              <select class="form-control" name="gender" id="gender">
                <option value="" class="text-muted">انتخاب جنسیت</option>
                @foreach(config('customer.genders') as $name => $label)
                  <option value="{{ $name }}" @selected(request('gender') === $name)>{{ $label }}</option>
                @endforeach
              </select>
              <x-core::show-validation-error name="gender" />
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="form-group">
              <label for="mobile" class="control-label"> شماره موبایل: <span class="text-danger">&starf;</span></label>
              <input
                type="text"
                id="mobile"
                class="form-control"
                name="mobile"
                placeholder="شماره موبایل را وارد کنید"
                value="{{ old('mobile') }}"
                required
              />
              <x-core::show-validation-error name="mobile" />
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="form-group">
              <label for="telephone" class="control-label"> تلفن ثابت: </label>
              <input
                type="text"
                id="telephone"
                class="form-control"
                name="telephone"
                placeholder="تلفن ثابت را وارد کنید"
                value="{{ old('telephone') }}"
              />
              <x-core::show-validation-error name="telephone" />
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="form-group">
              <label for="birthday_show" class="control-label">تاریخ تولد :</label>
              <input class="form-control fc-datepicker" id="birthday_show" type="text" autocomplete="off" placeholder="تاریخ تولد را انتخاب کنید" />
              <input name="birthday" id="birthday" type="hidden" value="{{ old("birthday") }}"/>
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="form-group">
              <label for="address" class="control-label">آدرس:</label>
              <input
                type="text"
                name="address"
                id="address"
                class="form-control"
                placeholder="آدرس مشتری را وارد کنید"
                value="{{ old('address') }}"
              />
              <x-core::show-validation-error name="address" />
            </div>
          </div>
          <div class="col-12">
            <div class="form-group">
              <label for="status" class="control-label"> وضعیت: </label>
              <label class="custom-control custom-checkbox">
                <input type="checkbox" id="status" class="custom-control-input" name="status" value="1" checked>
                <span class="custom-control-label">فعال</span>
              </label>
              <x-core::show-validation-error name="status" />
            </div>
          </div>
        </div>
        <x-core::store-button/>
      </form>
    </div>
  </div>
@endsection
@section('scripts')
  <x-core::date-input-script textInputId="birthday_show" dateInputId="birthday"/>
@endsection
