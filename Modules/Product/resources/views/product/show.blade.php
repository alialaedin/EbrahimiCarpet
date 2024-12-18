@extends('admin.layouts.master')

@section('styles')
  <style>
    strong {
      font-size: 16px;
      margin-left: 4px;
    }
  </style>
@endsection

@section('content')

<div class="page-header">
  <x-core::breadcrumb :items="[['title' => 'لیست محصولات', 'route_link' => 'admin.products.index'], ['title' => 'نمایش محصول']]"/>
  <div class="d-flex align-items-center flex-wrap text-nowrap">
    @can('edit products')
      <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning mx-1">
        ویرایش محصول<i class="fa fa-pencil mr-2"></i>
      </a>
    @endcan
    @can('delete products')
      <button
        onclick="confirmDelete('delete-{{ $product->id }}')"
        class="btn btn-sm btn-danger mx-1"
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
  </div>
</div>

  <div class="card bg-secondary" style="opacity: .8">
    <div class="card-body">
      <h4 class="text-white font-weight-bold">اگر قابلیت حذف محصول را ندارید ممکن است به خاطر دلایل زیر باشد :</h4>
      <ul class="mr-3">
        @foreach ($product->loadDeletableMessages() as $message)
          <li class="fs-16 text-gray-dark my-1"><i class="fa fa-dot-circle-o ml-2"></i>{{ $message }}</li>
        @endforeach
      </ul>
    </div>
  </div>

  <x-core::card>
    <x-slot name="cardTitle">اطلاعات اولیه</x-slot>
    <x-slot name="cardOptions"><x-core::card-options/></x-slot>
    <x-slot name="cardBody">
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem">
        <div>
          <ul class="list-group">
            <li class="list-group-item"><strong>کد: </strong> {{ $product->id }} </li>
            <li class="list-group-item"><strong>عنوان محصول: </strong> {{ $product->title }} </li>
            <li class="list-group-item"><strong>عوان پرینت: </strong> {{ $product->print_title }} </li>
            <li class="list-group-item"><strong>دسته بندی: </strong> {{ $product->category->title }} </li>
            <li class="list-group-item"><strong>موجودی کلی تمام ابعاد: </strong> {{ $product->demenisions_store_balance .' '. $product->category->getUnitType()}} </li>
            <li class="list-group-item"><strong>قیمت پایه: </strong> {{ number_format($product->price) }} ریال </li>
            <li class="list-group-item"><strong>تخفیف پایه: </strong> {{ number_format($product->discount) }} ریال </li>
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
              <img src="{{ Storage::url($product->image) }}" class="w-auto" style="height: 490px;" alt="{{ $product->title }}"/>
            </a>
          </figure>
        </div>
      </div>
    </x-slot>
  </x-core::card>

  @if ($product->children->isNotEmpty())

    <div class="card bg-warning">
      <div class="card-body">
        <p class="text-gray-200 font-weight-bold">برای افزایش یا کاهش موجودی روی دکمه <span class="text-info font-weight-bold">موجودی</span> کلیک کنید!</p>
      </div>
    </div>

    <x-core::card>
      <x-slot name="cardTitle">محصولات زیر دسته</x-slot>
      <x-slot name="cardOptions"><x-core::card-options/></x-slot>
      <x-slot name="cardBody">
        <x-core::table>
          <x-slot name="tableTh">
            <tr>
              <th>ردیف</th>
              <th>ابعاد</th>
              <th>موجودی</th>
              <th>شناسه</th>
              <th>قیمت (ریال)</th>
              <th>تخفیف (ریال)</th>
              <th>عملیات</th>
            </tr>
          </x-slot>
          <x-slot name="tableTd">
            @foreach ($product->children->sortByDesc('id') as $childProduct)
              <tr>
                <td class="font-weight-bold">{{ $loop->iteration }}</td>
                <td>{{ $childProduct->sub_title }}</td>
                <td>{{ number_format($childProduct->store_balance) }}</td>
                <td>{{ $childProduct->id }}</td>
                <td>{{ number_format($childProduct->price) }}</td>
                <td>{{ number_format($childProduct->discount) }}</td>
                <td>
                  @can('edit products')
                    <button
                      class="btn btn-sm btn-icon btn-warning text-white"
                      data-toggle="modal"
                      data-target="#edit-product-form-{{ $childProduct->id }}">
                       ویرایش
                       <i class="fa fa-pencil"></i>
                    </button>
                  @endcan
                  @can('delete products')
                    <x-core::delete-button
                      route="admin.products.destroy"
                      title="حذف"
                      :model="$childProduct"
                      disabled="{{ !$childProduct->isDeletable() }}"
                    />
                  @endcan
                  <button 
                    onclick="$('#productStoreForm-' + @json($childProduct->id)).submit()" 
                    class="btn btn-sm btn-icon btn-info text-white">
                    موجودی
                    <i class="fa fa-database"></i>
                  </button>
                  <form
                    action="{{ route('admin.stores.index') }}"
                    method="GET"
                    id="productStoreForm-{{ $childProduct->id }}"
                    class="d-none">
                    <input type="hidden" name="product_id" value="{{ $childProduct->id }}">
                  </form>
                </td>
              </tr>
            @endforeach
          </x-slot>
        </x-core::table>
      </x-slot>
    </x-core::card>

    @include('product::product.includes.child-product-edit-from')

  @endif

  @if($product->description)
    <x-core::card>
      <x-slot name="cardTitle">توضیحات</x-slot>
      <x-slot name="cardOptions"><x-core::card-options/></x-slot>
      <x-slot name="cardBody"><p>{{ $product->description }}</p></x-slot>
    </x-core::card>
  @endif

@endsection