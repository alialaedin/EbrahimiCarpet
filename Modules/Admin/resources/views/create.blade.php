@extends('admin.layouts.master')
@section('content')

  <div class="page-header">
    <x-core::breadcrumb :items="[
      ['title' => 'لیست ادمین ها', 'route_link' => 'admin.admins.index'],
      ['title' => 'ثبت ادمین جدید']
    ]"/>
  </div>

  <x-core::card>
    <x-slot name="cardTitle">ثبت ادمین جدید</x-slot>
    <x-slot name="cardOptions"><x-core::card-options/></x-slot>
    <x-slot name="cardBody">
      <form action="{{ route('admin.admins.store') }}" method="post" class="save">
        @csrf
        <div class="row">
          <div class="col-lg-4 col-md-6">
            <div class="form-group">
              <label for="name" class="control-label"> نام و نام خانوادگی: <span class="text-danger">&starf;</span></label>
              <input type="text" id="name" class="form-control" name="name" placeholder="نام و نام خانوادگی را وارد کنید" value="{{ old('name') }}" required autofocus>
              <x-core::show-validation-error name="name" />
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="form-group">
              <label for="mobile" class="control-label"> شماره موبایل: <span class="text-danger">&starf;</span></label>
              <input type="text" id="mobile" class="form-control" name="mobile" placeholder="شماره موبایل را وارد کنید" value="{{ old('mobile') }}" required>
              <x-core::show-validation-error name="mobile" />
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="form-group">
              <label for="role" class="control-label"> انتخاب نقش: <span class="text-danger">&starf;</span></label>
              <select name="role" id="role" class="form-control">
                <option class="text-muted"> نقش را انتخاب کنید </option>
                @foreach ($roles as $role)
                  <option value="{{ $role->name }}"> {{ $role->label }} </option>
                @endforeach
              </select>
              <x-core::show-validation-error name="role" />
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="form-group">
              <label for="password" class="control-label"> کلمه عبور: <span class="text-danger">&starf;</span></label>
              <input type="password" id="password" class="form-control" name="password" placeholder="کلمه عبور را وارد کنید" required>
              <x-core::show-validation-error name="password" />
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="form-group">
              <label for="password_confirmation" class="control-label"> تکرار کلمه عبور: <span class="text-danger">&starf;</span></label>
              <input type="password" id="password_confirmation" class="form-control" name="password_confirmation" placeholder="تکرار کلمه عبور را وارد کنید" required>
              <x-core::show-validation-error name="password_confirmation" />
            </div>
          </div>
          <div class="col-12">
            <div class="form-group">
              <label for="label" class="control-label"> وضعیت: </label>
              <label class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" name="status" value="1" checked>
                <span class="custom-control-label">فعال</span>
              </label>
              <x-core::show-validation-error name="status" />
            </div>
          </div>
        </div>
        <x-core::store-button/>
      </form>
    </x-slot>
  </x-core::card>

@endsection
