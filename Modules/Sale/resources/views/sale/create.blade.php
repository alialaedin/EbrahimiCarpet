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
        <a href="{{ route('admin.sales.index') }}">لیست فروش ها</a>
      </li>
      <li class="breadcrumb-item active">ثبت فروش جدید</li>
    </ol>
  </div>
  <div class="card">
    <div class="card-header justify-content-between">
      <p class="card-title">ثبت فروش جدید</p>
      <button id="addSaleItemButtonTop" class="btn btn-indigo">افزودن آیتم جدید</button>
    </div>
    <div class="card-body">
      <form action="{{ route('admin.sales.store') }}" method="post" class="save">
        @csrf
        <div class="row">
          <div class="col-lg-4 col-md-6">
            <div class="form-group">
              <label for="customer_id" class="control-label">انتخاب مشتری :<span class="text-danger">&starf;</span></label>
              <select name="customer_id" id="customer_id" class="form-control" required>
                <option value="" class="text-muted">-- مشتری را انخاب کنید --</option>
                @foreach ($customers as $customer)
                  <option value="{{ $customer->id }}" @selected(old("customer_id") == $customer->id)>{{ $customer->name .' - '. $customer->mobile }}</option>
                @endforeach
              </select>
              <x-core::show-validation-error name="customer_id" />
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="form-group">
              <label for="sold_date_show" class="control-label">تاریخ فروش :<span class="text-danger">&starf;</span></label>
              <input class="form-control fc-datepicker" id="sold_date_show" type="text" autocomplete="off" placeholder="تاریخ خرید را انتخاب کنید" />
              <input name="sold_at" id="sold_date" type="hidden" value="{{ old("sold_at") }}" required/>
            </div>
            <x-core::show-validation-error name="sold_at" />
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="form-group">
              <label for="discount" class="control-label"> تخفیف کلی (ریال): </label>
              <input type="text" id="discount" class="form-control comma" name="discount" placeholder="تخفیف را به ریال وارد کنید" value="{{ old('discount') }}" min="1000">
              <x-core::show-validation-error name="discount" />
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
  <x-core::date-input-script textInputId="sold_date_show" dateInputId="sold_date"/>
	<script>

    function getProductStore(id) {

      let productId = $(id).val();
      let token = $('meta[name="csrf-token"]').attr('content');

      $.ajax({
        url: '{{ route("admin.sales.get-product-store") }}',
        type: 'POST',
        data: {product_id: productId},
        headers: {'X-CSRF-TOKEN': token},
        success: function(response) {
          $(id + '-balance').val(response.balance);
          $(id + '-price').val(response.price);
          $(id + '-discount').val(response.discount);
        }
      });
    }

		$(document).ready(function() {

      let index = 0;

      const addSaleItemButtonTop = $("#addSaleItemButtonTop");
      const addPurchaseItemButton = $("#addPurchaseItemButton");

      addSaleItemButtonTop.css({
        'display': 'none'
      });

      addPurchaseItemButton.on('click', function() {

        addSaleItemButtonTop.css({
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
								<select name="products[${index + 1}][id]" id="product-${index + 1}" class="form-control mt-1" required onchange="getProductStore('#product-${index + 1}')">
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
                <label class="control-label">موجودی:</label>
                <input type="text" class="form-control mt-1" id="product-${index + 1}-balance" name="products[${index + 1}][balance]" placeholder="ابتدا محصول را انتخاب کنید" readonly>
							</div>
						</div>

						<div class="col-2">
							<div class="form-group">
								<label class="control-label">قیمت واحد (ریال):</label>
								<input type="text" class="form-control mt-1" id="product-${index + 1}-price" name="products[${index + 1}][price]" placeholder="ابتدا محصول را انتخاب کنید" readonly>
							</div>
						</div>

						<div class="col-2">
							<div class="form-group">
								<label class="control-label">تخفیف (ریال): </label>
								<input type="text" class="form-control mt-1" id="product-${index + 1}-discount" name="products[${index + 1}][discount]" placeholder="ابتدا محصول را انتخاب کنید" readonly>
							</div>
						</div>

						<div class="col-2">
              <div class="form-group">
                <label class="control-label">تعداد / متر:<span class="text-danger">&starf;</span></label>
                <input type="number" class="form-control mt-1" name="products[${index + 1}][quantity]" placeholder="تعداد محصول خریداری شده را وارد کنید">
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

			});

      addSaleItemButtonTop.on('click', function () {
        const newPurchaseItemInputs = $(`
					<div class="row">

						<div class="col-3">
							<div class="form-group">
								<select name="products[${index + 1}][id]" id="product-${index + 1}" class="form-control mt-1" onchange="getProductStore('#product-${index + 1}')">
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
                <input type="text" class="form-control mt-1" id="product-${index + 1}-balance" name="products[${index + 1}][balance]" placeholder="ابتدا محصول را انتخاب کنید" readonly>
							</div>
						</div>

						<div class="col-2">
							<div class="form-group">
								<input type="text" class="form-control mt-1" id="product-${index + 1}-price" name="products[${index + 1}][price]" placeholder="ابتدا محصول را انتخاب کنید" readonly>
							</div>
						</div>

						<div class="col-2">
							<div class="form-group">
								<input type="text" class="form-control mt-1" id="product-${index + 1}-discount" name="products[${index + 1}][discount]" placeholder="ابتدا محصول را انتخاب کنید" readonly>
							</div>
						</div>

						<div class="col-2">
              <div class="form-group">
                <input type="number" class="form-control mt-1" name="products[${index + 1}][quantity]" placeholder="تعداد محصول خریداری شده را وارد کنید">
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

      });

		});
	</script>
@endsection
