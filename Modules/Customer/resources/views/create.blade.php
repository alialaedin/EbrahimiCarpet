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
          <a href="{{ route('admin.customers.index') }}">لیست مشتری ها</a>
        </li>
        <li class="breadcrumb-item active">ثبت مشتری جدید</li>
      </ol>
    </div>
		<div class="card">
			<div class="card-header">
				<p class="card-title">ثبت مشتری جدید</p>
			</div>
			<div class="card-body">
				<form action="{{ route('admin.customers.store') }}" method="post" class="save">
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
								<x-core::show-validation-error name="mpbile" />
							</div>
						</div>
						<div class="col-lg-4 col-md-6">
							<div class="form-group">
								<label for="telephone" class="control-label"> تلفن ثابت: <span class="text-danger">&starf;</span></label>
								<input type="text" id="telephone" class="form-control" name="telephone" placeholder="تلفن ثابت را وارد کنید" value="{{ old('telephone') }}" required>
								<x-core::show-validation-error name="telephone" />
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<label for="address" class="control-label">محل سکونت:<span class="text-danger">&starf;</span></label>
								<textarea name="address" id="address" class="form-control" rows="3" placeholder="محل سکونت مشتری را وارد کنید">{{ old('address') }}</textarea>
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
					<div class="row">
						<div class="col">
							<div class="text-center">
								<button class="btn btn-pink" type="submit">ثبت و ذخیره</button>
							</div>
						</div>
					</div>
				</form>
			</div>
    </div>
  </div>
@endsection
