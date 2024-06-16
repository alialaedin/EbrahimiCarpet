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
          <a href="{{ route('admin.admins.index') }}">لیست ادمین ها</a>
        </li>
        <li class="breadcrumb-item active">ویرایش ادمین</li>
      </ol>
    </div>
		<div class="card">
			<div class="card-header">
				<p class="card-title">ویرایش ادمین - کد {{ $admin->id }}</p>
        <x-core::card-options/>
			</div>
			<div class="card-body">
				<form action="{{ route('admin.admins.update', $admin) }}" method="post" class="save">
					@csrf
          @method('PATCH')
					<div class="row">

						<div class="col-lg-4 col-md-6">
							<div class="form-group">
								<label for="name" class="control-label"> نام و نام خانوادگی: <span class="text-danger">&starf;</span></label>
								<input type="text" id="name" class="form-control" name="name" placeholder="نام و نام خانوادگی را وارد کنید" value="{{ old('name', $admin->name) }}" required autofocus>
								<x-core::show-validation-error name="name" />
							</div>
						</div>

						<div class="col-lg-4 col-md-6">
							<div class="form-group">
								<label for="mobile" class="control-label"> شماره موبایل: <span class="text-danger">&starf;</span></label>
								<input type="text" id="mobile" class="form-control" name="mobile" placeholder="شماره موبایل را وارد کنید" value="{{ old('mobile', $admin->mobile) }}" required>
                <x-core::show-validation-error name="mobile" />
							</div>
						</div>

						<div class="col-lg-4 col-md-6">
							<div class="form-group">
								<label for="role" class="control-label"> انتخاب نقش: <span class="text-danger">&starf;</span></label>
                <select id="role" name="role" class="form-control">
                  <option class="text-muted"> نقش را انتخاب کنید </option>
                  @if ($admin->getRoleName() == 'super_admin')
                    <option value="super_admin" selected> مدیر ارشد </option>
                  @endif
                  @foreach ($roles as $role)
                    <option value="{{ $role->name }}" @selected($role->name == $admin->getRoleName())> {{ $role->label }} </option>
                  @endforeach
                </select>
								<x-core::show-validation-error name="role" />
							</div>
						</div>

						<div class="col-lg-4 col-md-6">
							<div class="form-group">
								<label for="password" class="control-label"> کلمه عبور: <span class="text-danger">&starf;</span></label>
								<input type="password" id="password" class="form-control" name="password" placeholder="کلمه عبور را وارد کنید">
								<x-core::show-validation-error name="password" />
							</div>
						</div>

						<div class="col-lg-4 col-md-6">
							<div class="form-group">
								<label for="password_confirmation" class="control-label"> تکرار کلمه عبور: <span class="text-danger">&starf;</span></label>
								<input type="password" id="password_confirmation" class="form-control" name="password_confirmation" placeholder="تکرار کلمه عبور را وارد کنید">
								<x-core::show-validation-error name="password_confirmation" />
							</div>
						</div>

						<div class="col-12">
							<div class="form-group">
								<label for="status" class="control-label"> وضعیت: </label>
                <label class="custom-control custom-checkbox">
                  <input type="checkbox" id="status" class="custom-control-input" name="status" value="1" @checked($admin->status)>
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
  </div>
@endsection
