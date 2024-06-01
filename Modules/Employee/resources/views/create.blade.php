@extends('admin.layouts.master')

@section('content')

  <div class="col-12">
    <div class="col-xl-12 col-md-12 col-lg-12">
      <div class="page-header">
        <x-core::breadcrumb :items="$breadcrumbItems" />
    	</div>
			<div class="card">
				<div class="card-header">
					<h3 class="card-title">ثبت کارمند جدید</h3>
				</div>
				<div class="card-body">
					<form action="{{ route('admin.employees.store') }}" method="post" class="save">
						@csrf
						<div class="row">

							<div class="col-lg-4 col-md-6 col-12">
								<div class="form-group">
									<label for="name" class="control-label"> نام و نام خانوادگی: <span class="text-danger">&starf;</span></label>
									<input type="text" class="form-control" name="name" placeholder="نام و نام خانوادگی را وارد کنید" value="{{ old('name') }}" required autofocus>
									<x-core::show-validation-error name="name" />
								</div>
							</div>

							<div class="col-lg-4 col-md-6 col-12">
								<div class="form-group">
									<label for="label" class="control-label"> شماره موبایل: <span class="text-danger">&starf;</span></label>
									<input type="text" class="form-control" name="mobile" placeholder="شماره موبایل را وارد کنید" value="{{ old('mobile') }}" required>
									<x-core::show-validation-error name="mobile" />
								</div>
							</div>

							<div class="col-lg-4 col-md-6 col-12">
								<div class="form-group">
									<label for="label" class="control-label"> تلفن ثابت:</label>
									<input type="text" class="form-control" name="telephone" placeholder="تلفن ثابت را وارد کنید" value="{{ old('telephone') }}">
									<x-core::show-validation-error name="telephone" />
								</div>
							</div>
							
							<div class="col-lg-4 col-md-6 col-12">
								<div class="form-group">
									<label for="label" class="control-label"> کد ملی:</label>
									<input type="text" class="form-control" name="national_code" placeholder="کد ملی را وارد کنید" value="{{ old('national_code') }}" >
									<x-core::show-validation-error name="national_code" />
								</div>
							</div>

							<div class="col-lg-4 col-md-6 col-12">
								<div class="form-group">
									<label for="to_date_show" class="control-label">تاریخ استخدام : <span class="text-danger">&starf;</span></label>
									<input class="form-control fc-datepicker" id="deployment_date_show" type="text" autocomplete="off"/>
									<input name="employmented_at" id="deployment_date" type="hidden" required value="{{	old('employmented_at') }}"/>
									<x-core::show-validation-error name="employmented_at" />
								</div>
							</div>

							<div class="col-lg-4 col-md-6 col-12">
								<div class="form-group">
									<label for="label" class="control-label"> میزان حقوق (تومن): <span class="text-danger">&starf;</span></label>
									<input type="text" class="form-control comma" name="salary" placeholder="میزان حقوق را وارد کنید" value="{{ old('salary') }}" required>
									<x-core::show-validation-error name="salary" />
								</div>
							</div>

							<div class="col-12">
								<div class="form-group">
									<label class="control-label">محل سکونت:<span class="text-danger">&starf;</span></label>
									<textarea name="address" class="form-control" rows="3" required>{{ old('address') }}</textarea>
									<x-core::show-validation-error name="address" />
								</div>
							</div>
						</div>
							
						<div class="row">

							<div class="col-lg-4 col-md-6 col-12">
								<div class="form-group">
									<label for="label" class="control-label"> شماره کارت: <span class="text-danger">&starf;</span></label>
									<input type="text" class="form-control" name="card_number" placeholder="شماره کارت را وارد کنید" value="{{ old('card_number') }}" required>
									<x-core::show-validation-error name="card_number" />
								</div>
							</div>

							<div class="col-lg-4 col-md-6 col-12">
								<div class="form-group">
									<label for="label" class="control-label"> شماره شبا:</label>
									<input type="text" class="form-control" name="sheba_number" placeholder="شماره شبا را وارد کنید" value="{{ old('sheba_number') }}">
									<x-core::show-validation-error name="sheba_number" />
								</div>
							</div>

							<div class="col-lg-4 col-md-6 col-12">
								<div class="form-group">
									<label for="label" class="control-label"> نام بانک: <span class="text-danger">&starf;</span></label>
									<input type="text" class="form-control" name="bank_name" placeholder="نام بانک را وارد کنید" value="{{ old('bank_name') }}" required>
									<x-core::show-validation-error name="bank_name" />
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
@section('scripts')
  
  <script>   
    $('#deployment_date_show').MdPersianDateTimePicker({
      targetDateSelector: '#deployment_date',        
      targetTextSelector: '#deployment_date_show',
      englishNumber: false,        
      toDate:true,
      enableTimePicker: false,        
      dateFormat: 'yyyy-MM-dd',
      textFormat: 'yyyy-MM-dd',        
      groupId: 'rangeSelector1',
    });
  </script>

@endsection
