@extends('admin.layouts.master')
@section('content')
  <div class="col-12">
    <div class="page-header">
      <x-core::breadcrumb :items="$breadcrumbItems" />
    </div>
		<div class="card">
			<div class="card-header">
				<p class="card-title">ویرایش دسته بندی</p>
			</div>
			<div class="card-body">
				<form action="{{ route('admin.categories.update', $category) }}" method="post" class="save">
					@csrf
          @method('PATCH')
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="title" class="control-label"> عنوان: <span class="text-danger">&starf;</span></label>
								<input type="text" id="title" class="form-control" name="title" placeholder="عنوان را وارد کنید" value="{{ old('title', $category->title) }}" required autofocus>
								<x-core::show-validation-error name="title" />
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="parent_id" class="control-label"> انتخاب دسته بندی والد:</label>
                <select name="parent_id" id="parent_id" class="form-control">
									<option value=""> بدون والد </option>
                  @foreach ($parentCategories as $parentCategory)
                    <option value="{{ $parentCategory->id }}" @selected(old('parent_id', $category->parent_id) == $parentCategory->id)> {{ $parentCategory->title }} </option>
                  @endforeach
                </select>
								<x-core::show-validation-error name="parent_id" />
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label"> نوع واحد:<span class="text-danger">&starf;</span></label>
                <div class="custom-controls-stacked">
									<label class="custom-control custom-radio">
										<input type="radio" class="custom-control-input" name="unit_type" value="meter" @checked(old('unit_type', $category->unit_type) == 'meter')>
										<span class="custom-control-label">متر</span>
									</label>
									<label class="custom-control custom-radio">
										<input type="radio" class="custom-control-input" name="unit_type" value="number" @checked(old('unit_type', $category->unit_type) == 'number')>
										<span class="custom-control-label">عدد</span>
									</label>
								</div>
								<x-core::show-validation-error name="unit_type" />
              </div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label"> انتخاب وضعیت:<span class="text-danger">&starf;</span></label>
                <div class="custom-controls-stacked">
									<label class="custom-control custom-radio">
										<input type="radio" class="custom-control-input" name="status" value="1" @checked(old('status', $category->status) == '1')>
										<span class="custom-control-label">فعال</span>
									</label>
									<label class="custom-control custom-radio">
										<input type="radio" class="custom-control-input" name="status" value="0" @checked(old('status', $category->status) == '0')>
										<span class="custom-control-label">غیر فعال</span>
									</label>
								</div>
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
@endsection
