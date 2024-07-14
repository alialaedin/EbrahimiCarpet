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
      <li class="breadcrumb-item active">ثبت تامین کننده جدید</li>
    </ol>
  </div>
  <div class="card">
    <div class="card-header">
      <p class="card-title">ثبت تامین کننده جدید</p>
    </div>
    <div class="card-body">
      <form action="{{ route('admin.suppliers.store') }}" method="post" class="save">
        @csrf
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
                value="{{ old('name') }}"
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
                value="{{ old('mobile') }}"
                required
                maxlength="11"
              />
              <x-core::show-validation-error name="mobile" />
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="telephone" class="control-label"> تلفن ثابت: </label>
              <input
                type="text"
                id="telephone"
                class="form-control"
                name="telephone"
                placeholder="تلفن ثابت را وارد کنید"
                value="{{ old('telephone') }}"
                maxlength="11"
              />
              <x-core::show-validation-error name="telephone" />
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="national_code" class="control-label"> کد ملی: <span class="text-danger">&starf;</span></label>
              <input
                type="text"
                id="national_code"
                class="form-control"
                name="national_code"
                placeholder="کد ملی را وارد کنید"
                value="{{ old('national_code') }}"
                required
                maxlength="10"
              />
              <x-core::show-validation-error name="national_code" />
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="postal_code" class="control-label"> کد پستی: <span class="text-danger">&starf;</span></label>
              <input
                type="text"
                id="postal_code"
                class="form-control"
                name="postal_code"
                placeholder="کد پستی را وارد کنید"
                value="{{ old('postal_code') }}"
                required
                maxlength="10"
              />
              <x-core::show-validation-error name="postal_code" />
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="type" class="control-label"> نوع تامین کننده: <span class="text-danger">&starf;</span></label>
              <select name="type" id="type" class="form-control">
                <option value="" class="text-muted">انتخاب نوع تامین کننده</option>
                @foreach (config('supplier.types') as $name => $label)
                  <option value="{{ $name }}" @selected(old('type') == $name)>{{ $label }}</option>
                @endforeach
              </select>
              <x-core::show-validation-error name="type" />
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label for="address" class="control-label">آدرس:<span class="text-danger">&starf;</span></label>
              <textarea
                name="address"
                id="address"
                class="form-control"
                rows="3"
                placeholder="آدرس تامین کننده را وارد کنید"
                required>
                {{ old('address') }}
              </textarea>
              <x-core::show-validation-error name="address" />
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label for="description" class="control-label">توضیحات:</label>
              <textarea
                name="description"
                id="description"
                class="form-control"
                rows="3"
                placeholder="توضیحات خود را وارد کنید">
                {{ old('description') }}
              </textarea>
              <x-core::show-validation-error name="description" />
            </div>
          </div>
          <div class="col-12">
            <div class="form-group">
              <label for="status" class="control-label"> وضعیت: </label>
              <label class="custom-control custom-checkbox">
                <input type="checkbox" id="status" class="custom-control-input" name="status" value="1" @checked(old('status', 1) == '1')>
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
