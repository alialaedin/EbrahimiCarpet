@extends('admin.layouts.master')
@section('content')
  
  <div class="page-header">
    <x-core::breadcrumb :items="[['title' => 'لیست محصولات']]"/>
    @can('create products')
      <x-core::create-button route="admin.products.create" title="ثبت محصول جدید"/>
    @endcan
  </div>

  <x-core::card>
    <x-slot name="cardTitle">جستجوی پیشرفته</x-slot>
    <x-slot name="cardOptions"><x-core::card-options/></x-slot>
    <x-slot name="cardBody">
      <form id="FilterForm" action="{{ route("admin.products.index") }}">
        <input type="hidden" name="perPage" value="{{ request('perPage', 15) }}">
        <div class="row">
          <div class="col-12 col-md-6 col-xl-3">
            <div class="form-group">
              <label for="title">عنوان :</label>
              <input type="text" id="title" name="title" class="form-control" value="{{ request('title') }}">
            </div>
          </div>
          <div class="col-12 col-md-6 col-xl-3">
            <div class="form-group">
              <label for="category_id">دسته بندی :</label>
              <select name="category_id" id="category_id" class="form-control select2">
                <option value="">همه</option>
                @foreach ($categories as $category)
                  <option value="{{ $category->id }}" @selected(request("category_id") == $category->id)>{{ $category->title }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-12 col-md-6 col-xl-3">
            <div class="form-group">
              <label for="has_discount">انتخاب تخفیف :</label>
              <select name="has_discount" id="has_discount" class="form-control">
                <option value="">همه</option>
                <option value="1" @selected(request("has_discount") == "1")>تخفیفدار</option>
                <option value="0" @selected(request("has_discount") == "0")>بدون تخفیف</option>
              </select>
            </div>
          </div>
          <div class="col-12 col-md-6 col-xl-3">
            <div class="form-group">
              <label for="status">وضعیت :</label>
              <select name="status" id="status" class="form-control">
                <option value="">همه</option>
                <option value="1" @selected(request("status") == "1")>فعال</option>
                <option value="0" @selected(request("status") == "0")>غیر فعال</option>
              </select>
            </div>
          </div>
        </div>
        <x-core::filter-buttons table="products"/>
      </form>
    </x-slot>
  </x-core::card>

  <x-core::card>
    <x-slot name="cardTitle">لیست محصولات ({{ $totalProducts }})</x-slot>
    <x-slot name="cardOptions">
      <div class="card-options">
        <x-core::paginate-form-select selectBoxId="PaginationSelectBox" paginateRequestName="perPage" :values="[15, 30, 50, 75, 100, 150]"/>
      </div>
    </x-slot>
    <x-slot name="cardBody">
      <x-core::table>
        <x-slot name="tableTh">
          <tr>
            <th>ردیف</th>
            <th>عنوان</th>
            <th>تصویر</th>
            <th>دسته بندی</th>
            <th>تعداد ابعاد</th>
            <th>موجودی کلی</th>
            <th>قیمت (ریال)</th>
            <th>تخفیف (ریال)</th>
            <th>وضعیت</th>
            <th>تاریخ ثبت</th>
            <th>عملیات</th>
          </tr>
        </x-slot>
        <x-slot name="tableTd">
          @forelse ($products as $product)
            <tr>
              <td class="font-weight-bold">{{ $loop->iteration }}</td>
              <td>{{ $product->title }}</td>
              <td class="m-0 p-0">
                @if ($product->image)
                  <figure class="figure my-2">
                    <a target="_blank" href="{{ Storage::url($product->image) }}">
                      <img src="{{ Storage::url($product->image) }}" class="img-thumbnail" alt="image" width="50" style="max-height: 32px;"/>
                    </a>
                  </figure>
                @else
                  <span> - </span>
                @endif
              </td>
              <td>{{ $product->category->title }}</td>
              <td>{{ $product->children_count }}</td>
              <td>{{ $product->demenisions_store_balance .' '. $product->category->getUnitType() }}</td>
              <td>{{ number_format($product->price) }}</td>
              <td>{{ number_format($product->discount) }}</td>
              <td>
                <x-core::light-badge
                  type="{{ $product->status ? 'success' : 'danger' }}"
                  text="{{ $product->status ? 'فعال' : 'غیر فعال' }}"
                />
              </td>
              <td>@jalaliDate($product->created_at)</td>
              <td>
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
        </x-slot>
        <x-slot name="extraData">{{ $products->onEachSide(0)->links("vendor.pagination.bootstrap-4") }}</x-slot>
      </x-core::table>
    </x-slot>
  </x-core::card>

@endsection

@section('scripts')
  <x-core::paginate-form-script formId="FilterForm" paginateRequestName="perPage" selectBoxId="PaginationSelectBox"/>
@endsection
