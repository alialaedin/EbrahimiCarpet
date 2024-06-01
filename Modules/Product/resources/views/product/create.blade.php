@extends('admin.layouts.master')

@section('content')

  <div class="col-12">
    <div class="col-xl-12 col-md-12 col-lg-12">
      <div class="page-header">
        <x-core::breadcrumb :items="$breadcrumbItems" />
    	</div>
			<div class="card">
				<div class="card-header">
					<h3 class="card-title">ثبت محصول جدید</h3>
				</div>
				<div class="card-body">
					<form action="{{ route('admin.products.store') }}" method="post" class="save" enctype="multipart/form-data">
						@csrf

						<div class="row">

							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label"> عنوان: <span class="text-danger">&starf;</span></label>
									<input type="text" class="form-control" name="title" placeholder="عنوان را وارد کنید" value="{{ old('title') }}" required autofocus>
									<x-core::show-validation-error name="title" />
								</div>
							</div>

							<div class="col-md-6">	
								<div class="form-group">
									<label class="control-label"> انتخاب دسته بندی: <span class="text-danger">&starf;</span></label>
									<select name="category_id" class="form-control">
										@foreach ($parentCategories as $category)
										<option value="{{ $category->id }}" class="text-muted" @selected(old('category_id') == $category->id)>{{ $category->title }}</option>
											@if ($category->has('children'))
												@foreach($category->children as $child)
													<option value="{{ $child->id }}" @selected(old('category_id') == $child->id)>&nbsp;&nbsp;{{ $child->title }}</option>
												@endforeach
											@endif	
										@endforeach
									</select>
									<x-core::show-validation-error name="category_id" />
								</div>
							</div>
								
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label"> قیمت (تومان): <span class="text-danger">&starf;</span></label>
									<input type="text" class="form-control comma" name="price" placeholder="قیمت را به تومان وارد کنید" value="{{ old('price') }}">
									<x-core::show-validation-error name="price" />
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label"> تخفیف (تومان): </label>
									<input type="text" class="form-control comma" name="discount" placeholder="تخفیف را به تومان وارد کنید" value="{{ old('discount') }}">
									<x-core::show-validation-error name="discount" />
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label"> انتخاب عکس </label>
									<input type="file" class="form-control" name="image" value="{{ old('image') }}">
									<x-core::show-validation-error name="image" />
								</div>
							</div>

							<div class="col-12">
								<div class="form-group">
									<label class="control-label">توضیحات :</label>
									<textarea name="description" class="form-control" rows="4"> {{ old('description') }} </textarea>
									<x-core::show-validation-error name="description" />
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label"> انتخاب وضعیت:<span class="text-danger">&starf;</span></label>
                  <div class="custom-controls-stacked">
										<label class="custom-control custom-radio">
											<input type="radio" class="custom-control-input" name="status" value="1" @checked(old('status') == '1')>
											<span class="custom-control-label">فعال</span>
										</label>
										<label class="custom-control custom-radio">
											<input type="radio" class="custom-control-input" name="status" value="0" @checked(old('status') == '0')>
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
