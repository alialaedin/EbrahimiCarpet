@extends('admin.layouts.master')

@section('content')

  <div class="col-12">
    <div class="col-xl-12 col-md-12 col-lg-12">
      <div class="page-header">
        <x-core::breadcrumb :items="$breadcrumbItems" />
    	</div>
			@include('core::includes.validation-errors')
			<div class="card">
				<div class="card-header">
					<h3 class="card-title">ویرایش مشتری</h3>
				</div>
				<div class="card-body">
					<form action="{{ route('admin.customers.update', $customer) }}" method="post" class="save">
						@csrf
            @method('PATCH')
						<div class="row">

							<div class="col-lg-4 col-md-6">
								<div class="form-group">
									<label for="name" class="control-label"> نام و نام خانوادگی: <span class="text-danger">&starf;</span></label>
									<input type="text" class="form-control" name="name" placeholder="نام و نام خانوادگی را وارد کنید" value="{{ old('name') ?? $customer->name}}" required autofocus>
								</div>
							</div>

							<div class="col-lg-4 col-md-6">
								<div class="form-group">
									<label for="label" class="control-label"> شماره موبایل: <span class="text-danger">&starf;</span></label>
									<input type="text" class="form-control" name="mobile" placeholder="شماره موبایل را وارد کنید" value="{{ old('mobile') ?? $customer->mobile}}" required>
								</div>
							</div>

							<div class="col-lg-4 col-md-6">
								<div class="form-group">
									<label for="label" class="control-label"> تلفن ثابت: <span class="text-danger">&starf;</span></label>
									<input type="text" class="form-control" name="landline_phone" placeholder="تلفن ثابت را وارد کنید" value="{{ old('landline_phone') ?? $customer->landline_phone}}" required>
								</div>
							</div>

							<div class="col-12">
								<div class="form-group">
									<label class="control-label">محل سکونت:<span class="text-danger">&starf;</span></label>
									<textarea name="address" class="form-control" rows="3">{{ old('address') ?? $customer->address}}</textarea>
								</div>
							</div>
							
							<div class="col-12">
								<div class="form-group">
									<label for="label" class="control-label"> وضعیت: </label>
                  <label class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="status" value="1" @checked($customer->status)>
                    <span class="custom-control-label">فعال</span>
                  </label>
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
