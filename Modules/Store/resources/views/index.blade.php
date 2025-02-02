@extends('admin.layouts.master')
@section('content')

  <div class="page-header">
    <x-core::breadcrumb :items="[['title' => 'انبار']]"/>
  </div>

  <x-core::card>
    <x-slot name="cardTitle">جستجوی پیشرفته</x-slot>
    <x-slot name="cardOptions"><x-core::card-options/></x-slot>
    <x-slot name="cardBody">
      <form action="{{ route("admin.stores.index") }}" class="col-12">
        <div class="row">

          <div class="col-12 col-md-6 col-xl-3">
            <div class="form-group">
              <label for="product_id">انتخاب محصول :</label>
              <select name="product_id" id="product_id" class="form-control select2">
                <option value="">همه</option>
                @foreach ($productsToFilter as $product)
                  <option
                    value="{{ $product->id }}"
                    @selected(request("product_id") == $product->id)>
                    {{ $product->full_title }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="col-12 col-md-6 col-xl-3">
            <div class="form-group">
              <label for="unit_type">انتخاب نوع واحد :</label>
              <select name="unit_type" id="unit_type" class="form-control">
                <option value="">همه</option>
                @foreach (config('product.unit_types') as $name => $label)
                  <option value="{{ $name }}" @selected(request("unit_type") == $name)>{{ $label }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="col-12 col-md-6 col-xl-3">
            <div class="form-group">
              <label for="from_created_at_show">از تاریخ :</label>
              <input class="form-control fc-datepicker" id="from_created_at_show" type="text" autocomplete="off"/>
              <input name="from_created_at" id="from_created_at" type="hidden" value="{{ request("from_created_at") }}"/>
            </div>
          </div>

          <div class="col-12 col-md-6 col-xl-3">
            <div class="form-group">
              <label for="to_created_at_show">تا تاریخ :</label>
              <input class="form-control fc-datepicker" id="to_created_at_show" type="text" autocomplete="off"/>
              <input name="to_created_at" id="to_created_at" type="hidden" value="{{ request("to_created_at") }}"/>
            </div>
          </div>

        </div>

        <div class="row">

          <div class="col-12 col-md-6 col-xl-9">
            <button class="btn btn-primary btn-block" type="submit">جستجو <i class="fa fa-search"></i></button>
          </div>

          <div class="col-12 col-md-6 col-xl-3">
            <a href="{{ route("admin.stores.index") }}" class="btn btn-danger btn-block">حذف همه فیلتر ها <i class="fa fa-close"></i></a>
          </div>

        </div>

      </form>
    </x-slot>
  </x-core::card>

  <x-core::card>
    <x-slot name="cardTitle">انبار</x-slot>
    <x-slot name="cardOptions"><x-core::card-options/></x-slot>
    <x-slot name="cardBody">
      <x-core::table>
        <x-slot name="tableTh">
          <tr>
            <th>ردیف</th>
            <th>عنوان محصول</th>
            <th>دسته بندی</th>
            <th>تصویر محصول</th>
            <th>نوع واحد</th>
            <th>موجودی</th>
            <th>تاریخ ثبت</th>
            <th>عملیات</th>
          </tr>
        </x-slot>
        <x-slot name="tableTd">
          @forelse ($products as $product)
            <tr>
              <td class="font-weight-bold">{{ $loop->iteration }}</td>
              <td>{{ $product->full_title }}</td>
              <td>{{ $product->category->title }}</td>
              <td class="m-0 p-0">
                @if ($product->parent->image)
                  <figure class="figure my-2">
                    <a target="_blank" href="{{ Storage::url($product->parent->image) }}">
                      <img
                        src="{{ Storage::url($product->parent->image) }}"
                        class="img-thumbnail"
                        alt="image"
                        width="50"
                        style="max-height: 32px;"
                      />
                    </a>
                  </figure>
                @else
                  <span> - </span>
                @endif
              </td>
              <td>{{ $product->category->getUnitType() }}</td>
              <td>{{ $product->stores->sum('balance') }}</td>
              <td> @jalaliDate($product->created_at) </td>
              <td>
                <button
                  class="btn btn-sm btn-success text-white"
                  style="margin-left: 1px;"
                  data-target="#increaseStoreFormModal-{{ $product->id }}"
                  data-toggle="modal">
                  افزایش
                  <i class="fa fa-plus-circle mr-1"></i>
                </button>
                <button
                  class="btn btn-sm btn-danger text-white"
                  style="margin-left: 1px; margin-right: 1px;"
                  data-target="#decreaseStoreFormModal-{{ $product->id }}"
                  data-toggle="modal">
                  کاهش
                  <i class="fa fa-minus-circle mr-1"></i>
                </button>
{{--                    <a--}}
{{--                      href="{{route('admin.stores.show-transactions', $product)}}"--}}
{{--                      class="btn btn-sm btn-primary text-white"--}}
{{--                    style="margin-right: 1px;">--}}
{{--                      تراکنش ها--}}
{{--                      <i class="fa fa-eye mr-1"></i>--}}
{{--                    </a>--}}
              </td>
            </tr>
          @empty
            <x-core::data-not-found-alert :colspan="8"/>
          @endforelse
        </x-slot>
        <x-slot name="extraData">{{ $products->onEachSide(0)->links("vendor.pagination.bootstrap-4") }}</x-slot>
      </x-core::table>
    </x-slot>
  </x-core::card>


  @include('store::includes.increase-store-modal')
  @include('store::includes.decrease-store-modal')

@endsection
@section('scripts')
  <x-core::date-input-script textInputId="from_created_at_show" dateInputId="from_created_at"/>
  <x-core::date-input-script textInputId="to_created_at_show" dateInputId="to_created_at"/>
@endsection
