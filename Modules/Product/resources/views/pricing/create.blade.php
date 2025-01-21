@extends('admin.layouts.master')
@section('content')

  <div class="page-header">
    <x-core::breadcrumb :items="[['title' => 'قیمت گذاری محصولات']]"/>
  </div>

  <x-core::card>
    <x-slot name="cardTitle">قیمت گذاری محصولات</x-slot>
    <x-slot name="cardOptions"><x-core::card-options/></x-slot>
    <x-slot name="cardBody">
      <form action="{{ route('admin.pricing.store') }}" method="POST">
        @csrf
        <div class="row">

          <div class="col-xl-3">
            <div class="fomr-group">
              <label>قیمت (ریال) <span class="text-danger">&starf;</span></label>
              <input type="text" class="comma form-control" name="price" value="{{ old('price') }}" required autofocus>
            </div>
          </div>

          <div class="col-xl-3">
            <div class="form-group">
              <label>دسته بندی</label>
              <select name="category_id" id="category-select-box" class="form-control" required>
                <option value=""> دسته بندی را انتخاب کنید</option>
                @foreach ($categories as $category)
                  <optgroup label="{{ $category->title }}">
                    @if ($category->has('children'))
                      @foreach($category->children as $child)
                        <option value="{{ $child->id }}" @selected(old('category_id') == $child->id)>{{ $child->title }}</option>
                      @endforeach
                    @endif
                  </optgroup>
                @endforeach
              </select>
            </div>
          </div>

          <div class="col-xl-3">
            <div class="form-group">
              <label>محصولات</label>
              <select id="product-select-box" class="form-control" name="product_id">
                <option value=""></option>
              </select>
            </div>
          </div>

        </div>
        <div class="row">
          <div class="col-12">
            <button class="btn btn-sm btn-pink">بروزرسانی قیمت</button>
          </div>
        </div>
      </form>
    </x-slot>
  </x-core::card>

@endsection

@section('scripts')
  <script>

  $('#category-select-box').select2({placeholder: 'انتخاب دسته بندی'});
  $('#product-select-box').select2({placeholder: 'ابتدا دسته بندی را انتخاب کنید'});

  $(document).ready(() => {

    const allParentProducts = @json($products);
    const allParentCategories = @json($categories);

    const categorySelectBox = $('#category-select-box');
    const productSelectBox = $('#product-select-box');

    categorySelectBox.change(() => {

      const categoryId = categorySelectBox.val();
      const products = allParentProducts.filter(product => product.category_id == categoryId);

      productSelectBox.empty();
      productSelectBox.append('<option value=""></option>');
      productSelectBox.select2({ placeholder: 'انتخاب محصولات' });
      products.forEach(product => {
        productSelectBox.append(`<option value="${product.id}">${product.title}</option>`)
      });
    });
  });
    
  </script>
@endsection
