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
					<h3 class="card-title">ثبت تامین کننده جدید</h3>
				</div>
				<div class="card-body">
					<form action="{{ route('admin.suppliers.store') }}" method="post" class="save">
						@csrf
						<div class="row">

							<div class="col-md-6">
								<div class="form-group">
									<label for="name" class="control-label"> نام و نام خانوادگی: <span class="text-danger">&starf;</span></label>
									<input type="text" class="form-control" name="name" placeholder="نام و نام خانوادگی را وارد کنید" value="{{ old('name') }}" required autofocus>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label for="label" class="control-label"> شماره موبایل: <span class="text-danger">&starf;</span></label>
									<input type="text" class="form-control" name="mobile" placeholder="شماره موبایل را وارد کنید" value="{{ old('mobile') }}" required>
								</div>
							</div>

							<div class="col-12">
								<div class="form-group">
									<label class="control-label">محل سکونت:<span class="text-danger">&starf;</span></label>
									<textarea name="address" class="form-control" rows="3" placeholder="آدرس خود را وارد کنید" required>{{ old('address') }}</textarea>
								</div>
							</div>

							<div class="col-12">
								<div class="form-group">
									<label for="label" class="control-label"> وضعیت: </label>
                  <label class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="status" value="1" checked>
                    <span class="custom-control-label">فعال</span>
                  </label>
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
