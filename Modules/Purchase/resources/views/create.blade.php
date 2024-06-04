@extends('admin.layouts.master')

@section('content')

  <div class="col-12">
    <div class="col-xl-12 col-md-12 col-lg-12">
      <div class="page-header">
        <x-core::breadcrumb :items="$breadcrumbItems" />
    	</div>
			<div class="card">
				<div class="card-header justify-content-between">
					<p class="card-title">ثبت خرید جدید</p>
					<button id="addPurchaseItemButton" class="btn btn-indigo">
						<span>افزودن آیتم جدید</span>
						<i class="fa fa-plus-square mr-1"></i>
					</button>
				</div>
				<div class="card-body">
					<form action="{{ route('admin.purchases.store') }}" method="post" class="save">
						@csrf

						<div class="row">

							<div class="col-lg-4 col-md-6">
								<div class="form-group">
									<label for="supplier_id" class="control-label">انتخاب تامین کننده :<span class="text-danger">&starf;</span></label>
									<select name="supplier_id" id="supplier_id" class="form-control">
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
									<input class="form-control fc-datepicker" id="purchased_date_show" type="text" autocomplete="off" placeholder="تاریخ خرید را انتخاب کنید"/>
									<input name="purchased_at" id="purchased_date" type="hidden" value="{{ old("purchased_at") }}"/>
								</div>
							</div>

							<div class="col-lg-4 col-md-6">
								<div class="form-group">
									<label for="discount" class="control-label"> تخفیف کلی (تومان): </label>
									<input type="text" id="discount" class="form-control comma" name="discount" placeholder="تخفیف را به تومان وارد کنید" value="{{ old('discount') }}">
								</div>
							</div>

						</div>

						<div  id="contentArea"></div>

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
    </div>
  </div>
@endsection

@section('scripts')
	<script>
		$('#purchased_date_show').MdPersianDateTimePicker({
      targetDateSelector: '#purchased_date',
      targetTextSelector: '#purchased_date_show',
      englishNumber: false,
      toDate:true,
      enableTimePicker: false,
      dateFormat: 'yyyy-MM-dd',
      textFormat: 'yyyy-MM-dd',
      groupId: 'rangeSelector1',
    });
	</script>

	<script>
		$(document).ready(function() {

      let index = 0;

      $("#addPurchaseItemButton").on('click', function() {

        const newPurchaseItemInputs = $(`
					<div class="row">
						<div class="col-12 bg-light mb-2 py-2 d-flex justify-content-between align-items-center">
							<span style="font-size: 18px;">آیتم جدید </span>
							<button class="btn btn-danger deleteRowButton" type="button">
  							<i class="fa fa-trash-o" data-toggle="tooltip" data-original-title="حذف"></i>
							</button>
						</div>
						<div class="col-lg-3 col-md-6">
							<div class="form-group">
								<label for="products[${index + 1}][id]" class="control-label">انتخاب محصول :<span class="text-danger">&starf;</span></label>
								<select name="products[${index + 1}][id]" id="products[${index + 1}][id]" class="form-control">
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
            <div class="col-lg-3 col-md-6">
              <div class="form-group">
                <label for="products[${index + 1}][quantity]" class="control-label">تعداد:<span class="text-danger">&starf;</span></label>
                <input type="number" id="products[${index + 1}][quantity]" class="form-control" name="products[${index + 1}][quantity]" placeholder="تعداد محصول خریداری شده را وارد کنید" value="{{ old('products[${index + 1}][quantity]') }}">
							</div>
						</div>
						<div class="col-lg-3 col-md-6">
							<div class="form-group">
								<label for="products[${index + 1}][price]" class="control-label">قیمت (تومان):<span class="text-danger">&starf;</span></label>
								<input type="text" id="products[${index + 1}][price]" class="form-control comma" name="products[${index + 1}][price]" placeholder="قیمت محصول را به تومان وارد کنید" value="{{ old('products[${index + 1}][price]') }}">
							</div>
						</div>
						<div class="col-lg-3 col-md-6">
							<div class="form-group">
								<label for="products[${index + 1}][discount]" class="control-label">تخفیف (تومان): </label>
								<input type="text" id="products[${index + 1}][discount]" class="form-control comma" name="products[${index + 1}][discount]" placeholder="تخفیف را به تومان وارد کنید" value="{{ old('products[${index + 1}][discount]') }}">
							</div>
						</div>
					</div>
				`);

        $('#contentArea').append(newPurchaseItemInputs);
				index++;

				newPurchaseItemInputs.find('.deleteRowButton').on('click', function() {
          $(this).closest('.row').remove();
        });

			})
		});
	</script>
@endsection
