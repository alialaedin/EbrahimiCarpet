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
        <a href="{{ route('admin.categories.index') }}">لیست دسته بندی ها</a>
      </li>
      <li class="breadcrumb-item active">ثبت دسته بندی جدید</li>
    </ol>
  </div>
  <div class="card">
    <div class="card-header">
      <p class="card-title">ثبت دسته بندی جدید</p>
    </div>
    <div class="card-body">
      <form action="{{ route('admin.categories.store') }}" method="post" class="save">
        @csrf
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="title" class="control-label"> عنوان: <span class="text-danger">&starf;</span></label>
              <input
                type="text"
                id="title"
                class="form-control"
                name="title"
                placeholder="عنوان را وارد کنید"
                value="{{ old('title') }}"
                required
                autofocus
              />
              <x-core::show-validation-error name="title" />
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="parent_id" class="control-label"> انتخاب دسته بندی والد:</label>
              <select name="parent_id" id="parent_id" class="form-control">
                <option value=""> بدون والد </option>
                @foreach ($parentCategories as $category)
                  <option value="{{ $category->id }}" @selected(old('parent_id') == $category->id)> {{ $category->title }} </option>
                @endforeach
              </select>
              <x-core::show-validation-error name="parent_id" />
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label"> نوع واحد:<span class="text-danger">&starf;</span></label>
              <div class="custom-controls-stacked">
                @foreach(config('core.category_unit_types') as $name => $label)
                  <label class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" name="unit_type" value="meter" @checked(old('unit_type') == $name)>
                    <span class="custom-control-label">{{ $label }}</span>
                  </label>
                @endforeach
              </div>
              <x-core::show-validation-error name="unit_type" />
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label"> انتخاب وضعیت:<span class="text-danger">&starf;</span></label>
              <div class="custom-controls-stacked">
                <label class="custom-control custom-radio">
                  <input type="radio" class="custom-control-input" name="status" value="1" @checked(old('status') == '1')>
                  <span class="custom-control-label">فعال</span>
                </label>
                <label class="custom-control custom-radio">
                  <input type="radio" class="custom-control-input" name="status" value="0" @checked(old('status') == '0')>
                  <span class="custom-control-label">غیر فعال</span>
                </label>
              </div>
              <x-core::show-validation-error name="status" />
            </div>
          </div>
        </div>
        <x-core::store-button/>
      </form>
    </div>
  </div>
@endsection
