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
        <a href="{{ route('admin.suppliers.index') }}">لیست تامین کنندگان</a>
      </li>
      <li class="breadcrumb-item active">ویرایش تامین کننده</li>
    </ol>
  </div>
  <div class="card">
    <div class="card-header">
      <p class="card-title">ویرایش تامین کننده - کد {{ $supplier->id }}</p>
    </div>
    <div class="card-body">
      <form action="{{ route('admin.suppliers.update', $supplier) }}" method="post" class="save">
        @csrf
        @method('PATCH')
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="name" class="control-label"> نام و نام خانوادگی: <span class="text-danger">&starf;</span></label>
              <input
                type="text"
                id="name"
                class="form-control"
                name="name"
                placeholder="نام و نام خانوادگی را وارد کنید"
                value="{{ old('name', $supplier->name) }}"
                required
                autofocus
              />
              <x-core::show-validation-error name="name" />
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="mobile" class="control-label"> شماره موبایل: <span class="text-danger">&starf;</span></label>
              <input
                type="text"
                id="mobile"
                class="form-control"
                name="mobile"
                placeholder="شماره موبایل را وارد کنید"
                value="{{ old('mobile', $supplier->mobile) }}"
                required
              />
              <x-core::show-validation-error name="mobile" />
            </div>
          </div>
          <div class="col-12">
            <div class="form-group">
              <label for="address" class="control-label">محل سکونت:<span class="text-danger">&starf;</span></label>
              <textarea
                name="address"
                id="address"
                class="form-control"
                rows="3"
                required>
                {{ old('address', $supplier->address) }}
              </textarea>
              <x-core::show-validation-error name="address" />
            </div>
          </div>
          <div class="col-12">
            <div class="form-group">
              <label for="status" class="control-label"> وضعیت: </label>
              <label class="custom-control custom-checkbox">
                <input type="checkbox" id="status" class="custom-control-input" name="status" value="1" @checked($supplier->status)>
                <span class="custom-control-label">فعال</span>
              </label>
              <x-core::show-validation-error name="status" />
            </div>
          </div>
        </div>
        <x-core::update-button/>
      </form>
    </div>
  </div>
@endsection
