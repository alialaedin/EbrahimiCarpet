@extends('admin.layouts.master')

@section('content')

	<div class="col-12">
    <div class="col-xl-12 col-md-12 col-lg-12">
      <div class="page-header">
				<ol class="breadcrumb align-items-center">
					<li class="breadcrumb-item">
						<a href="{{ route('admin.dashboard') }}">
							<i class="fe fe-home ml-1"></i> داشبورد
						</a>
					</li>
					<li class="breadcrumb-item">
						<a href="{{ route('admin.roles.index') }}">لیست نقش ها</a>
					</li>
					<li class="breadcrumb-item active">ویرایش نقش</li>
				</ol>
    	</div>
			<div class="card">
				<div class="card-header">
					<p class="card-title">ویرایش نقش</p>
				</div>
				<div class="card-body">
					<form action="{{ route('admin.roles.update', $role) }}" method="post" class="save">

						@csrf
						@method('PATCH')

						<div class="row">

							<div class="col-md-6">
								<div class="form-group">
									<label for="name" class="control-label">نام (به انگلیسی) <span class="text-danger">&starf;</span></label>
									<input type="text" class="form-control" name="name" id="name" placeholder="نام را به انگلیسی اینجا وارد کنید" value="{{ old('name', $role->name) }}" required autofocus>
									<x-core::show-validation-error name="name" />
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label for="label" class="control-label">نام قابل مشاهده (به فارسی) <span class="text-danger">&starf;</span></label>
									<input type="text" class="form-control" name="label" id="label" placeholder="نام قابل مشاهده را به فارسی اینجا وارد کنید" value="{{ old('label', $role->label) }}" required>
									<x-core::show-validation-error name="label" />
								</div>
							</div>

						</div>
						@if($role->name !== 'super_admin')
              <h4 class="header p-2">مجوزها</h4>
              @foreach($permissions->chunk(4) as $chunk)
                <div class="row">
                  @foreach($chunk as $permission)
                    <div class="col-3">
                      <div class="form-group">
                        <label class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" name="permissions[]" value="{{ $permission->name }}" @checked($role->permissions->contains($permission->id))>
                          <span class="custom-control-label">{{ $permission->label }}</span>
                        </label>
												<x-core::show-validation-error name="permissions" />
                      </div>
                    </div>
                  @endforeach
                </div>
              @endforeach
            @endif
						<div class="row">
							<div class="col">
								<div class="text-center">
									<button class="btn btn-warning" type="submit">به روزرسانی</button>
								</div>
							</div>
						</div>
					</form>
				</div>
      </div>
    </div>
  </div>
@endsection
