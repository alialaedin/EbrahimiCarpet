@extends('admin.layouts.master')

@section('content')

  <div class="col-12">
    <div class="col-xl-12 col-md-12 col-lg-12">

      <div class="page-header">
        <x-core::breadcrumb :items="$breadcrumbItems" />
    	</div>

			<div class="card">

				<div class="card-header">
					<p class="card-title">ویرایش مشتری</p>
				</div>

				<div class="card-body">

					<form action="{{ route('admin.customers.update', $customer) }}" method="post" class="save">

						@csrf
            @method('PATCH')

						<div class="row">

							<div class="col-lg-4 col-md-6">
								<div class="form-group">
									<label for="name" class="control-label"> نام و نام خانوادگی: <span class="text-danger">&starf;</span></label>
									<input type="text" id="name" class="form-control" name="name" placeholder="نام و نام خانوادگی را وارد کنید" value="{{ old('name', $customer->name) }}" required autofocus>
									<x-core::show-validation-error name="name" />
								</div>
							</div>

							<div class="col-lg-4 col-md-6">
								<div class="form-group">
									<label for="mobile" class="control-label"> شماره موبایل: <span class="text-danger">&starf;</span></label>
									<input type="text" id="mobile" class="form-control" name="mobile" placeholder="شماره موبایل را وارد کنید" value="{{ old('mobile', $customer->mobile) }}" required>
									<x-core::show-validation-error name="mpbile" />
								</div>
							</div>

							<div class="col-lg-4 col-md-6">
								<div class="form-group">
									<label for="telephone" class="control-label"> تلفن ثابت: <span class="text-danger">&starf;</span></label>
									<input type="text" id="telephone" class="form-control" name="telephone" placeholder="تلفن ثابت را وارد کنید" value="{{ old('telephone', $customer->telephone) }}" required>
									<x-core::show-validation-error name="telephone" />
								</div>
							</div>

							<div class="col-12">
								<div class="form-group">
									<label for="address" class="control-label">محل سکونت:<span class="text-danger">&starf;</span></label>
									<textarea name="address" id="address" class="form-control" rows="3" placeholder="محل سکونت مشتری را وارد کنید">{{ old('address', $customer->address) }}</textarea>
									<x-core::show-validation-error name="address" />
								</div>
							</div>

							<div class="col-12">
								<div class="form-group">
									<label for="label" class="control-label"> وضعیت: </label>
                  <label class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="status" value="1" @checked($customer->status)>
                    <span class="custom-control-label">فعال</span>
                  </label>
									<x-core::show-validation-error name="status" />
                </div>
							</div>

						</div>

						<div class="row">
							<div class="col">
								<div class="text-center">
									<button class="btn btn-warning" type="submit">بروزرسانی</button>
								</div>
							</div>
						</div>

					</form>
				</div>
      </div>
    </div>
  </div>
@endsection
