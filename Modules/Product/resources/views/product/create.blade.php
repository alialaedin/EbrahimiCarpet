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
      <li class="breadcrumb-item active">ثبت محصول جدید</li>
    </ol>
  </div>
  <div class="card">
    <div class="card-header">
      <p class="card-title">ثبت محصول جدید</p>
    </div>
    <div class="card-body">
      <form action="{{ route('admin.products.store') }}" method="post" class="save" enctype="multipart/form-data">
        @csrf
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="title" class="control-label"> عنوان: <span class="text-danger">&starf;</span></label>
              <input type="text" id="title" class="form-control" name="title" placeholder="عنوان را به فارسی وارد کنید"
                     value="{{ old('title') }}" required autofocus>
              <x-core::show-validation-error name="title"/>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="print_title" class="control-label"> عنوان (پرینت فاکتور مشتری): <span class="text-danger">&starf;</span></label>
              <input type="text" id="print_title" class="form-control" name="print_title"
                     placeholder="عنوان را به فارسی وارد کنید" value="{{ old('print_title') }}" required autofocus>
              <x-core::show-validation-error name="print_title"/>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="category_id" class="control-label"> انتخاب دسته بندی: <span class="text-danger">&starf;</span></label>
              <select name="category_id" id="category_id" class="form-control">
                <option value=""> دسته بندی را انتخاب کنید</option>
                @foreach ($parentCategories as $category)
                  <option value="{{ $category->id }}"
                          class="text-muted" @selected(old('category_id') == $category->id)>{{ $category->title }}</option>
                  @if ($category->has('children'))
                    @foreach($category->children as $child)
                      <option value="{{ $child->id }}" @selected(old('category_id') == $child->id)>
                        &nbsp;&nbsp;{{ $child->title }}</option>
                    @endforeach
                  @endif
                @endforeach
              </select>
              <x-core::show-validation-error name="category_id"/>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="image" class="control-label"> انتخاب عکس </label>
              <input type="file" id="image" class="form-control" name="image" value="{{ old('image') }}">
              <x-core::show-validation-error name="image"/>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="price" class="control-label"> قیمت فروش (ریال): <span
                  class="text-danger">&starf;</span></label>
              <input type="text" id="price" class="form-control comma" name="price"
                     placeholder="قیمت را به ریال وارد کنید" value="{{ old('price') }}">
              <x-core::show-validation-error name="price"/>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="discount" class="control-label"> تخفیف (ریال): </label>
              <input type="text" id="discount" class="form-control comma" name="discount"
                     placeholder="تخفیف را به ریال وارد کنید" value="{{ old('discount') }}">
              <x-core::show-validation-error name="discount"/>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="initial_balance" class="control-label"> موجودی اولیه (متر / عدد): </label>
              <input
                type="number"
                id="initial_balance"
                class="form-control"
                name="initial_balance"
                placeholder="موجودی اولیه را به ریال وارد کنید"
                value="{{ old('initial_balance') }}"
              />
              <x-core::show-validation-error name="initial_balance"/>
            </div>
          </div>
          <div class="col-md-6 d-none" id="purchased_price_box">
            <div class="form-group">
              <label for="purchased_price" class="control-label"> قیمت خرید (ریال): <span
                  class="text-danger">&starf;</span></label>
              <input type="text" id="purchased_price" class="form-control comma" name="purchased_price"
                     placeholder="قیمت را به ریال وارد کنید" value="{{ old('purchased_price') }}">
              <x-core::show-validation-error name="purchased_price"/>
            </div>
          </div>
          <div class="col-12">
            <div class="form-group">
              <label for="description" class="control-label">توضیحات :</label>
              <textarea name="description" id="description" class="form-control" rows="4"
                        placeholder="توضیحات لازم را در صورت نیاز وارد کنید"> {{ old('description') }} </textarea>
              <x-core::show-validation-error name="description"/>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label"> انتخاب وضعیت:<span class="text-danger">&starf;</span></label>
              <div class="custom-controls-stacked">
                <label class="custom-control custom-radio">
                  <input type="radio" class="custom-control-input" name="status"
                         value="1" @checked(old('status', 1) == '1')>
                  <span class="custom-control-label">فعال</span>
                </label>
                <label class="custom-control custom-radio">
                  <input type="radio" class="custom-control-input" name="status"
                         value="0" @checked(old('status') == '0')>
                  <span class="custom-control-label">غیر فعال</span>
                </label>
              </div>
              <x-core::show-validation-error name="status"/>
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
@endsection

@section('scripts')
  <script>
    $(document).ready(() => {

      let title = $('#title');
      let printTitle = $('#print_title');
      let initialBalance = $('#initial_balance');
      let purchasedPriceBox = $('#purchased_price_box');
      let purchasedPriceInput = $('#purchased_price');

      title.on('input', () => {
        printTitle.val(title.val());
      });

      initialBalance.on('input', () => {
        if (initialBalance.val() > 0) {
          purchasedPriceBox.removeClass('d-none');
        }else {
          purchasedPriceBox.addClass('d-none');
          purchasedPriceInput.val(null)
        }
      });

    });
  </script>
@endsection
