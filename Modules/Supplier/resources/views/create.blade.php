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
								<input type="text" id="name" class="form-control" name="name" placeholder="نام و نام خانوادگی را وارد کنید" value="{{ old('name') }}" required autofocus>
                <x-core::show-validation-error name="name" />
              </div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="mobile" class="control-label"> شماره موبایل: <span class="text-danger">&starf;</span></label>
								<input type="text" id="mobile" class="form-control" name="mobile" placeholder="شماره موبایل را وارد کنید" value="{{ old('mobile') }}" required>
                <x-core::show-validation-error name="mobile" />
              </div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<label for="address" class="control-label">محل سکونت:<span class="text-danger">&starf;</span></label>
								<textarea name="address" id="address" class="form-control" rows="3" placeholder="آدرس خود را وارد کنید" required>{{ old('address') }}</textarea>
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
