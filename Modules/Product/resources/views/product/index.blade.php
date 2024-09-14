@extends('admin.layouts.master')
@section('content')
  <div class="page-header">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}">
          <i class="fe fe-home ml-1"></i> داشبورد
        </a>
      </li>
      <li class="breadcrumb-item active">لیست محصولات</li>
    </ol>
    @can('create products')
      <x-core::register-button route="admin.products.create" title="ثبت محصول جدید"/>
    @endcan
  </div>
  @include('product::product._filter-form')
  <div class="card">
    <div class="card-header border-0">
      <p class="card-title">لیست محصولات ({{ $productsCount }})</p>
      <x-core::card-options/>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <div class="dataTables_wrapper dt-bootstrap4 no-footer">
          <div class="row">
            <table class="table table-vcenter table-striped text-nowrap table-bordered border-bottom">
              <thead class="thead-light">
              <tr>
                <th class="text-center">ردیف</th>
                <th class="text-center">عنوان</th>
                <th class="text-center">تصویر</th>
                <th class="text-center">دسته بندی</th>
                <th class="text-center">تعداد ابعاد</th>
                <th class="text-center">موجودی کلی</th>
                <th class="text-center">قیمت (ریال)</th>
                <th class="text-center">تخفیف (ریال)</th>
                <th class="text-center">وضعیت</th>
                <th class="text-center">تاریخ ثبت</th>
                <th class="text-center">عملیات</th>
              </tr>
              </thead>
              <tbody>
              @forelse ($products as $product)
                <tr>
                  <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                  <td class="text-center">{{ $product->title }}</td>
                  <td class="text-center m-0 p-0">
                    @if ($product->image)
                      <figure class="figure my-2">
                        <a target="_blank" href="{{ Storage::url($product->image) }}">
                          <img src="{{ Storage::url($product->image) }}" class="img-thumbnail" alt="image"
                               width="50" style="max-height: 32px;"/>
                        </a>
                      </figure>
                    @else
                      <span> - </span>
                    @endif
                  </td>
                  <td class="text-center">{{ $product->category->title }}</td>
                  <td class="text-center">{{ $product->children_count }}</td>
                  <td class="text-center">{{ $product->calcAllDemenisionsStoreBalance() .' '. $product->category->getUnitType() }}</td>
                  <td class="text-center">{{ number_format($product->price) }}</td>
                  <td class="text-center">{{ number_format($product->getDiscount()) }}</td>
                  <td class="text-center">
                    <x-core::badge
                      type="{{ $product->status ? 'success' : 'danger' }}"
                      text="{{ $product->status ? 'فعال' : 'غیر فعال' }}"
                    />
                  </td>
                  <td class="text-center">@jalaliDate($product->created_at)</td>
                  <td class="text-center">
                    @can('view products')
                      <x-core::show-button route="admin.products.show" :model="$product"/>
                    @endcan
                    @can('edit products')
                      <x-core::edit-button route="admin.products.edit" :model="$product"/>
                    @endcan
                    @can('delete products')
                      <x-core::delete-button
                        route="admin.products.destroy"
                        :model="$product"
                        disabled="{{ !$product->isDeletable() }}"
                      />
                    @endcan
                  </td>
                </tr>
              @empty
                <x-core::data-not-found-alert :colspan="10"/>
              @endforelse
              </tbody>
            </table>
            {{ $products->onEachSide(0)->links("vendor.pagination.bootstrap-4") }}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
