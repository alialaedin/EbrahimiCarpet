@extends('admin.layouts.master')

@section('content')
  <div class="col-12">
    <div class="col-xl-12 col-md-12 col-lg-12">

			<div class="page-header">
        <x-core::breadcrumb :items="$breadcrumbItems" />
        @can('create products')
          <x-core::register-button route="admin.products.create" title="ثبت محصول جدید"/>
        @endcan
    	</div>

      @include('product::product._fliter-form')

      <div class="card">

        <div class="card-header border-0 justify-content-between ">
          <div class="d-flex">
            <p class="card-title ml-2" style="font-weight: bolder;">لیست محصولات</p>
            <span class="fs-15 ">({{ $productsCount }})</span>
          </div>
        </div>
        
        <div class="card-body">
          <div class="table-responsive">
            <div id="hr-table-wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
              <div class="row">
                <table class="table table-vcenter text-nowrap table-bordered border-bottom" id="hr-table">
                  <thead class="thead-light">
                    <tr>
                      <th class="text-center">شناسه</th>
                      <th class="text-center">عنوان</th>
                      <th class="text-center">دسته بندی</th>
                      <th class="text-center">قیمت (تومان)</th>
                      <th class="text-center">تخفیف (تومان)</th>
                      <th class="text-center">قیمت با تخفیف (تومان)</th>
                      <th class="text-center">وضعیت</th>
                      <th class="text-center">تاریخ ثبت</th>
                      <th class="text-center">عملیات</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($products as $product)
                      <tr>
                        <td class="text-center">{{ $product->id }}</td>
                        <td class="text-center">{{ $product->title }}</td>
                        <td class="text-center">{{ $product->category->title }}</td>
                        <td class="text-center">{{ number_format($product->price) }}</td>
                        <td class="text-center">{{ number_format($product->getDiscount()) }}</td>
                        <td class="text-center">{{ number_format($product->getTotalPriceWithDiscount()) }}</td>
                        <td class="text-center">
                          <x-core::badge 
                            type="{{ $product->status ? 'success' : 'danger' }}" 
                            text="{{ $product->status ? 'فعال' : 'غیر فعال' }}" 
                          />
                        </td>
                        <td class="text-center">{{ verta($product->created_at)->format('Y/m/d') }}</td>
                        <td class="text-center">
                          @can('view products')
                            <x-core::show-button route="admin.products.show" :model="$product"/>
                          @endcan
                          @can('edit products')
                            <x-core::edit-button route="admin.products.edit" :model="$product"/>
                          @endcan
                          @can('delete products')
                            <x-core::delete-button route="admin.products.destroy" :model="$product"/>
                          @endcan
                        </td>
                      </tr>
                      @empty
												<x-core::data-not-found-alert :colspan="8"/>
                    @endforelse
                  </tbody>
                </table>
                {{ $products->onEachSide(1)->links("vendor.pagination.bootstrap-4") }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection