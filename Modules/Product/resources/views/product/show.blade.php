@extends('admin.layouts.master')
@section('content')
  <div class="page-header">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}">
          <i class="fe fe-home ml-1"></i> داشبورد
        </a>
      </li>
      <li class="breadcrumb-item">
        <a href="{{ route('admin.products.index') }}">لیست محصولات</a>
      </li>
      <li class="breadcrumb-item active">نمایش محصول</li>
    </ol>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
      @can('edit products')
        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning mx-1">
          ویرایش محصول<i class="fa fa-pencil mr-2"></i>
        </a>
      @endcan
      @can('delete products')
        <button
          onclick="confirmDelete('delete-{{ $product->id }}')"
          class="btn btn-danger mx-1"
          @disabled(!$product->isDeletable())>
          حذف محصول<i class="fa fa-trash-o mr-2"></i>
        </button>
        <form
          action="{{ route('admin.products.destroy', $product) }}"
          method="POST"
          id="delete-{{ $product->id }}"
          style="display: none">
          @csrf
          @method('DELETE')
        </form>
      @endcan
      @can('view stores')
        <a
          href="{{ route('admin.stores.show', $product->store->id) }}"
          class="btn btn-info mx-1">
          مشاهده تراکنش ها<i class="fa fa-eye mr-2"></i>
        </a>
      @endcan
    </div>
  </div>
  <div class="card">
    <div class="card-header border-0">
      <p class="card-title">اطلاعات اولیه</p>
    </div>
    <div class="card-body">
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem">
        <div>
          <ul class="list-group">
            <li class="list-group-item"><strong>کد: </strong> {{ $product->id }} </li>
            <li class="list-group-item"><strong>عنوان: </strong> {{ $product->print_title }} </li>
            <li class="list-group-item"><strong>عوان پرینت: </strong> {{ $product->title }} </li>
            <li class="list-group-item"><strong>دسته بندی: </strong> {{ $product->category->title }} </li>
            <li class="list-group-item"><strong>موجودی انبار: </strong> {{ $product->store->balance . ' ' . $product->category->getUnitType()}} </li>
            <li class="list-group-item"><strong>قیمت پایه: </strong> {{ number_format($product->price) }} ریال </li>
            <li class="list-group-item"><strong>مقدار تخفیف: </strong> {{ number_format($product->getDiscount()) }} ریال </li>
            <li class="list-group-item">
              <strong>وضعیت: </strong>
              <x-core::badge
                type="{{ $product->status ? 'success' : 'danger' }}"
                text="{{ $product->status ? 'فعال' : 'غیر فعال' }}"
              />
            </li>
            <li class="list-group-item"><strong>تاریخ ثبت: </strong> @jalaliDate($product->created_at) </li>
            <li class="list-group-item"><strong>تاریخ آخرین ویرایش: </strong> @jalaliDate($product->updated_at) </li>
          </ul>
        </div>
        <div>
          <figure class="figure w-100 h-100 text-center m-0">
            <a target="blank" href="{{ Storage::url($product->image) }}">
              <img src="{{ Storage::url($product->image) }}" class="w-auto" style="height: 470px;" alt="{{ $product->title }}"/>
            </a>
          </figure>
        </div>
      </div>
    </div>
  </div>
  @if($product->description)
    <div class="card">
      <div class="card-header border-0">
        <p class="card-title">توضیحات</p>
      </div>
      <div class="card-body">
        <p> {{ $product->description }} </p>
      </div>
    </div>
  @endif
@endsection
