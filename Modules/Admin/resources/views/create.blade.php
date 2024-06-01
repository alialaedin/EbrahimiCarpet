@extends('admin.layouts.master')

@section('content')

  <div class="col-12">
    <div class="col-xl-12 col-md-12 col-lg-12">
      <div class="page-header">
        <x-core::breadcrumb :items="$breadcrumbItems" />
    	</div>
			<div class="card">
				<div class="card-header">
					<h3 class="card-title">ثبت ادمین جدید</h3>
				</div>
				<div class="card-body">
					<form action="{{ route('admin.admins.store') }}" method="post" class="save">
						@csrf
						<div class="row">
							<div class="col-lg-4 col-md-6">
								<div class="form-group">
									<label for="name" class="control-label"> نام و نام خانوادگی: <span class="text-danger">&starf;</span></label>
									<input type="text" class="form-control" name="name" placeholder="نام و نام خانوادگی را وارد کنید" value="{{ old('name') }}" required autofocus>
									<x-core::show-validation-error name="name" />
								</div>
							</div>
							<div class="col-lg-4 col-md-6">
								<div class="form-group">
									<label for="label" class="control-label"> شماره موبایل: <span class="text-danger">&starf;</span></label>
									<input type="text" class="form-control" name="mobile" placeholder="شماره موبایل را وارد کنید" value="{{ old('mobile') }}" required>
									<x-core::show-validation-error name="mobile" />
								</div>
							</div>
							<div class="col-lg-4 col-md-6">
								<div class="form-group">
									<label for="label" class="control-label"> انتخاب نقش: <span class="text-danger">&starf;</span></label>
                  <select name="role" class="form-control">
                    @foreach ($roles as $role)
                      <option value="{{ $role->name }}"> {{ $role->label }} </option>
                    @endforeach
                  </select>
									<x-core::show-validation-error name="role" />
								</div>
							</div>
							<div class="col-lg-4 col-md-6">
								<div class="form-group">
									<label for="label" class="control-label"> کلمه عبور: <span class="text-danger">&starf;</span></label>
									<input type="password" class="form-control" name="password" placeholder="کلمه عبور را وارد کنید" required>
									<x-core::show-validation-error name="password" />
								</div>
							</div>
							<div class="col-lg-4 col-md-6">
								<div class="form-group">
									<label for="label" class="control-label"> تکرار کلمه عبور: <span class="text-danger">&starf;</span></label>
									<input type="password" class="form-control" name="password_confirmation" placeholder="تکرار کلمه عبور را وارد کنید" required>
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
  </div>
@endsection
