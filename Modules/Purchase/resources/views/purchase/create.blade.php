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
        <a href="{{ route('admin.purchases.index') }}">لیست خرید</a>
      </li>
      <li class="breadcrumb-item active">ثبت خرید جدید</li>
    </ol>
  </div>
  <div class="card">
    <div class="card-header justify-content-between">
      <p class="card-title">ثبت خرید جدید</p>
      <button id="addPurchaseItemButtonTop" class="btn btn-indigo">افزودن آیتم جدید</button>
    </div>
    <div class="card-body">
      <form action="{{ route('admin.purchases.store') }}" method="post" class="save">
        @csrf
        <div class="row">
          <div class="col-lg-4 col-md-6">
            <div class="form-group">
              <label for="supplier_id" class="control-label">انتخاب تامین کننده :<span class="text-danger">&starf;</span></label>
              <select name="supplier_id" id="supplier_id" class="form-control select2" required>
                <option value="" class="text-muted">-- تامین کننده را انخاب کنید --</option>
                @foreach ($suppliers as $supplier)
                  <option value="{{ $supplier->id }}" @selected(old("supplier_id") == $supplier->id)>{{ $supplier->name .' - '. $supplier->mobile }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="form-group">
              <label for="purchased_date_show" class="control-label">تاریخ خرید :<span class="text-danger">&starf;</span></label>
              <input class="form-control fc-datepicker" id="purchased_date_show" type="text" autocomplete="off" placeholder="تاریخ خرید را انتخاب کنید" />
              <input name="purchased_at" id="purchased_date" type="hidden" value="{{ old("purchased_at") }}" required/>
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="form-group">
              <label for="discount" class="control-label"> تخفیف کلی (تومان): </label>
              <input type="text" id="discount" class="form-control comma" name="discount" placeholder="تخفیف را به تومان وارد کنید" value="{{ old('discount') }}" min="1000">
            </div>
          </div>
        </div>
        <div  id="contentArea"></div>
        <div class="row">
          <div class="col">
            <div class="text-center">
              <button id="submitButton" class="btn btn-pink d-none mt-2" type="submit">ثبت و ذخیره</button>
              <button id="addPurchaseItemButton" class="btn btn-indigo" type="button">افزودن آیتم جدید</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection

@section('scripts')

  <x-core::date-input-script textInputId="purchased_date_show" dateInputId="purchased_date"/>

	<script>
		$(document).ready(function() {
      let index = 0;

      const addPurchaseItemButtonTop = $("#addPurchaseItemButtonTop");
      const addPurchaseItemButton = $("#addPurchaseItemButton");

      addPurchaseItemButtonTop.css({
        'display': 'none'
      });

      addPurchaseItemButton.on('click', function() {

        addPurchaseItemButtonTop.css({
          'display': 'block'
        });

        addPurchaseItemButton.css({
          'display': 'none'
        });

        const newPurchaseItemInputs = $(`
					<div class="row">
						<div class="col-12 bg-light mb-2 py-2">
							<span style="font-size: 18px;"> افزودن اقلام خرید </span>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label class="control-label">انتخاب محصول :<span class="text-danger">&starf;</span></label>
								<select name="products[${index + 1}][id]" class="form-control mt-1" required>
									<option value="" class="text-muted">-- محصول مورد نظر را انتخاب کنید --</option>
                  @foreach ($categories as $category)
                    @if ($category->products()->exists())
                      <optgroup label="{{ $category->title }}" class="text-muted">
                        @foreach ($category->products as $product)
                          <option value="{{ $product->id }}" class="text-dark" @selected(old('product_id') == $product->id)>{{ $product->title }}</option>
                        @endforeach
                      </optgroup>
                   @endif
                  @endforeach
                </select>
              </div>
             </div>
            <div class="col-2">
              <div class="form-group">
                <label class="control-label">تعداد / متر:<span class="text-danger">&starf;</span></label>
                <input type="number" class="form-control mt-1" name="products[${index + 1}][quantity]" placeholder="تعداد محصول خریداری شده را وارد کنید" value="{{ old('products[${index + 1}][quantity]') }} required min="1">
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label class="control-label">قیمت (تومان):<span class="text-danger">&starf;</span></label>
								<input type="text" class="form-control comma mt-1" name="products[${index + 1}][price]" placeholder="قیمت محصول را به تومان وارد کنید" value="{{ old('products[${index + 1}][price]') }}" required min="1000">
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label class="control-label">تخفیف (تومان): </label>
								<input type="text" class="form-control comma mt-1" name="products[${index + 1}][discount]" placeholder="تخفیف را به تومان وارد کنید" value="{{ old('products[${index + 1}][discount]') }}">
							</div>
						</div>

					</div>
				`);

        $('#submitButton').removeClass('d-none');

        $('#contentArea').append(newPurchaseItemInputs);
				index++;

				newPurchaseItemInputs.find('.deleteRowButton').on('click', function() {
          $(this).closest('.row').remove();
        });

        comma();

			});

      addPurchaseItemButtonTop.on('click', function () {
        const newPurchaseItemInputs = $(`
					<div class="row">
						<div class="col-3">
							<div class="form-group">
								<select name="products[${index + 1}][id]" class="form-control mt-1">
									<option value="" class="text-muted">-- محصول مورد نظر را انتخاب کنید --</option>
                  @foreach ($categories as $category)
                    @if ($category->products()->exists())
                      <optgroup label="{{ $category->title }}" class="text-muted">
                        @foreach ($category->products as $product)
                          <option value="{{ $product->id }}" class="text-dark" @selected(old('product_id') == $product->id)>{{ $product->title }}</option>
                        @endforeach
                      </optgroup>
                    @endif
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-2">
              <div class="form-group">
                <input type="number" class="form-control mt-1" name="products[${index + 1}][quantity]" placeholder="تعداد محصول خریداری شده را وارد کنید" value="{{ old('products[${index + 1}][quantity]') }}">
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<input type="text" class="form-control comma mt-1" name="products[${index + 1}][price]" placeholder="قیمت محصول را به تومان وارد کنید" value="{{ old('products[${index + 1}][price]') }}">
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<input type="text" class="form-control comma mt-1" name="products[${index + 1}][discount]" placeholder="تخفیف را به تومان وارد کنید" value="{{ old('products[${index + 1}][discount]') }}">
							</div>
						</div>
						<div class="col-1 text-left">
						  <button class="btn btn-danger mt-1 deleteRowButton" type="button">
  						  <i class="fa fa-trash-o" data-toggle="tooltip" data-original-title="حذف"></i>
							</button>
						</div>
					</div>
				`);

        $('#contentArea').append(newPurchaseItemInputs);
        index++;

        newPurchaseItemInputs.find('.deleteRowButton').on('click', function() {
          $(this).closest('.row').remove();
        });

        comma();
      });
		});
	</script>
@endsection
