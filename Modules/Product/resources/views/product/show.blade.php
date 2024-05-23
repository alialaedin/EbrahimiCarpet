@extends('admin.layouts.master')
@section('content')
  <div class="col-12">

    <div class="col-xl-12 col-md-12 col-lg-12">

      <div class="page-header">
        <x-core::breadcrumb :items="$breadcrumbItems" />
    	</div>      
      
      <div class="row">

        <div class="col-md-6">
          <div class="card overflow-hidden">
            <div class="card-header border-0">
              <p class="card-title font-weight-bold" style="font-size: 24px;">اطلاعات اولیه</p>
            </div>
            <div class="card-body">
              <ul class="list-group">
                <li class="list-group-item"><span class="font-weight-bold text-muted ml-1">شناسه محصول: </span> {{ $product->id }} </li>
                <li class="list-group-item"><span class="font-weight-bold text-muted ml-1">عنوان: </span> {{ $product->title }} </li>
                <li class="list-group-item"><span class="font-weight-bold text-muted ml-1">دسته بندی: </span> {{ $product->category->title }} </li>
                <li class="list-group-item"><span class="font-weight-bold text-muted ml-1">قیمت پایه: </span> {{ number_format($product->price) }} تومان </li>
                <li class="list-group-item"><span class="font-weight-bold text-muted ml-1">مقدار تخفیف: </span> {{ number_format($product->getDiscount()) }} تومان </li>
                <li class="list-group-item">
                  <span class="font-weight-bold text-muted ml-1">وضعیت: </span>
                  <x-core::badge 
                    type="{{ $product->status ? 'success' : 'danger' }}" 
                    text="{{ $product->status ? 'فعال' : 'غیر فعال' }}" 
                  />
                </li>
                <li class="list-group-item"><span class="font-weight-bold text-muted ml-1">تاریخ ثبت: </span> {{ verta($product->created_at)->format('Y/m/d') }} </li>
                <li class="list-group-item"><span class="font-weight-bold text-muted ml-1">تاریخ آخرین ویرایش: </span> {{ verta($product->updated_at)->format('Y/m/d') }} </li>
              </ul>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card overflow-hidden">
            <div class="card-header border-0">
              <p class="card-title font-weight-bold" style="font-size: 24px;">تصویر</p>
            </div>
            <div class="card-body">
              <figure class="figure w-100">
                <a target="blank" href="{{ Storage::url($product->image) }}">
                  <img src="{{ Storage::url($product->image) }}" class="w-100" style="height: 377px;"/>
                </a>
              </figure>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card overflow-hidden">
            <div class="card-header border-0">
              <p class="card-title font-weight-bold" style="font-size: 24px;">توضیحات</p>
            </div>
            <div class="card-body">
              <p> {{ $product->description ?? '-' }} </p>
            </div>
          </div>
        </div>

      </div>

    </div>
  </div>
@endsection 