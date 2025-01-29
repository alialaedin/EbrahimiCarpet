@extends('admin.layouts.master')

@section('styles')
  <style>
    .select2-container {  
      width: 100% !important; 
    }
  </style>
@endsection

@section('content')

<div class="page-header">
  <x-core::breadcrumb
    :items="[
      ['title' => 'لیست خرید ها', 'route_link' => 'admin.purchases.index'],
      ['title' => 'ثبت خرید جدید'],
    ]"
  />
</div>

<x-core::card>
  <x-slot name="cardTitle">فاکتور خرید جدید</x-slot>
  <x-slot name="cardOptions"><x-core::card-options/></x-slot>
  <x-slot name="cardBody">
    <form action="{{ route('admin.purchases.store') }}" method="post" class="save">
      @csrf
      <div class="row mx-2">
        <div class="col-lg-4 col-md-6">
          <div class="form-group">
            <label for="supplier_id" class="control-label">انتخاب تامین کننده :<span class="text-danger">&starf;</span></label>
            <select name="supplier_id" id="supplier_id" class="form-control select2" required>
              <option value="" class="text-muted">-- تامین کننده را انخاب کنید --</option>
              @foreach ($suppliers as $supplier)
                <option value="{{ $supplier->id }}" @selected(request("supplier_id") == $supplier->id)>{{ $supplier->name .' - '. $supplier->mobile }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="form-group">
            <label for="purchased_date_show" class="control-label">تاریخ خرید :<span class="text-danger">&starf;</span></label>
            <input class="form-control fc-datepicker" id="purchased_date_show" type="text" autocomplete="off" placeholder="تاریخ خرید را انتخاب کنید" required/>
            <input name="purchased_at" id="purchased_date" type="hidden" value="{{ old("purchased_at") }}" required/>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="form-group">
            <label for="discount" class="control-label"> تخفیف کلی (ریال): </label>
            <input type="text" id="discount" class="form-control comma" name="discount" placeholder="تخفیف را به ریال وارد کنید" value="{{ old('discount') }}" min="1000">
          </div>
        </div>
      </div>
      <div class="row mx-2 mb-5">
        <div class="col-12 d-flex justify-content-center" style="border-radius: 10px;">
          <button id="addPurchaseItemButton" class="btn btn-green d-flex justify-content-center align-items-center" type="button">
            <span class="ml-1">افزودن آیتم به فاکتور</span>
            <i class="fa fa-plus font-weight-bold"></i>
          </button>
        </div>
      </div>
      <div class="row mx-4" id="ProductsSection">
        <div class="table-responsive">
          <div class="dataTables_wrapper dt-bootstrap4 no-footer">
              <table class="table text-center text-nowrap table-bordered border-bottom">
                <thead>
                <tr>
                  <th>انتخاب محصول <span class="text-danger">&starf;</span></th>
                  <th>تعداد / متر <span class="text-danger">&starf;</span></th>
                  <th>قیمت (ریال) <span class="text-danger">&starf;</span></th>
                  <th>تخفیف  (ریال)</th>
                  <th>عملیات</th>
                </tr>
                </thead>
                <tbody id="ProductsTableBody">
                </tbody>
              </table>
          </div>
        </div>
      </div>
      <div class="row mx-4 justify-content-center" style="margin-top: 50px">

        <div class="col-12 col-xl-4">
          <div class="card shadow-lg">
            <div class="card-body">
              <div class="row">
                <div class="col-12 my-1 d-flex justify-content-between align-items-center">
                  <b>مجموع تعداد کالا ها</b>
                  <span id="total-items-quantity">0</span>
                </div>
                <div class="col-12 my-1 d-flex justify-content-between align-items-center">
                  <b>مجموع قیمت کالا ها</b>
                  <span id="total-items-amount">0</span>
                </div>
                <div class="col-12 my-1 d-flex justify-content-between align-items-center">
                  <b>مجموع تخفیف روی کالا ها</b>
                  <span id="total-items-discount-amount">0</span>
                </div>
                <div class="col-12 my-1 d-flex justify-content-between align-items-center">
                  <b>تخفیف روی سفارش</b>
                  <span id="purchase-discount-amount">0</span>
                </div>
                <div class="col-12 my-1 d-flex justify-content-between align-items-center">
                  <b>جمع کل</b>
                  <span id="total-amount">0</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12" id="submitButton">
          <div class="text-center">
            <button  class="btn btn-pink mt-2" type="submit">ثبت و ذخیره</button>
          </div>
        </div>
      </div>
    </form>
  </x-slot>
</x-core::card>

@endsection

@section('scripts')

  <x-core::date-input-script textInputId="purchased_date_show" dateInputId="purchased_date"/>

	<script>

    $(document).ready(function() {

      $('#supplier_id').select2({placeholder: 'تامین کننده را انتخاب کنید'});

      $('#submitButton').hide();
      $('#ProductsSection').hide();

      let index = 0;

      const addPurchaseItemButton = $("#addPurchaseItemButton");
      const discountInput = $("#discount");

      const totalItemsQuantityTextBox = $('#total-items-quantity');
      const totalItemsAmountTextBox = $('#total-items-amount');
      const totalItemsDiscountAmountTextBox = $('#total-items-discount-amount');
      const purhcaseDiscountAmountTextBox = $('#purchase-discount-amount');
      const totalAmountTextBox = $('#total-amount');

      function calculateTotalPrice() {  

        let totalItemsAmount = 0;
        let totalItemsQuantity = 0;  
        let totalItemsDiscountAmount = 0;  
        let totalAmount = 0;
        let purhcaseDiscountAmount = discountInput.val().replace(/,/g, '') || 0;

        $('#ProductsTableBody tr').each(function() {  

          const quantityInput = $(this).find('.product-quantity');  
          const priceInput = $(this).find('.product-price');
          const discountInput = $(this).find('.product-discount');

          const quantity = Math.max(parseFloat(quantityInput.val()) || 0, 0);  

          const priceValue = priceInput.val().replace(/,/g, ''); 
          const discountValue = discountInput.val().replace(/,/g, ''); 

          const price = priceValue ? Math.max(parseInt(priceValue.replace(/,/g, '')) || 0, 0) : 0;
          const discount = discountValue ? Math.max(parseFloat(discountValue.replace(/,/g, '')) || 0, 0) : 0;

          totalItemsQuantity += quantity;  
          totalItemsDiscountAmount += (discount * quantity);
          totalItemsAmount += (price * quantity);
          totalAmount += (quantity * (price - discount));

        });  

        totalAmount -= purhcaseDiscountAmount;

        totalItemsQuantityTextBox.text(totalItemsQuantity); 
        totalItemsAmountTextBox.text(totalItemsAmount.toLocaleString() + ' ریال'); 
        totalItemsDiscountAmountTextBox.text(totalItemsDiscountAmount.toLocaleString() + ' ریال'); 
        purhcaseDiscountAmountTextBox.text(purhcaseDiscountAmount.toLocaleString() + ' ریال'); 
        totalAmountTextBox.text(totalAmount.toLocaleString() + ' ریال'); 

      } 

      discountInput.on('input', () => {
        if ($(this).val() !== null) {
          calculateTotalPrice();
        }
      });

      addPurchaseItemButton.click(() => {

        $('#submitButton').show();
        $('#ProductsSection').show();

        let tr = $(`
          <tr role="row">  
            <td>
              <select name="products[${index + 1}][id]" class="form-control product-select d-block" required>
                <option value=""></option>
                @foreach ($categories as $category)
                  @if ($category->products->isNotEmpty())
                    <optgroup label="{{ $category->title }}">
                      @foreach ($category->products->whereNotNull('parent_id') as $product)
                        <option value="{{ $product->id }}">{{ $product->title .' - '. $product->sub_title }}</option>
                      @endforeach
                    </optgroup>
                  @endif
                @endforeach
              </select>
            </td>  
            <td><input type="number" class="form-control product-quantity" name="products[${index + 1}][quantity]" required min="1"/></td>  
            <td><input type="text" class="form-control comma product-price" name="products[${index + 1}][price]" required min="1000"/></td>  
            <td><input type="text" class="form-control comma product-discount" name="products[${index + 1}][discount]" /></td>  
            <td>  
              <button type="button" class="positive-btn font-weight-bold btn btn-sm btn-icon btn-success ml-1">+</button>
              <button type="button" class="negative-btn font-weight-bold btn btn-sm btn-icon btn-danger ml-1">-</button>
            </td>  
          </tr>  
        `);
        tr.find('.product-select').select2({placeholder: 'محصول مورد نظر را انتخاب کنید'});
        $('#ProductsTableBody').append(tr);
        comma();
        tr.find('.negative-btn').click(function() {  
          $(this).closest('tr').remove(); 
          calculateTotalPrice();
        });  
        tr.find('.positive-btn').click(function() {  
          addPurchaseItemButton.trigger('click'); 
        });
        tr.find('.product-quantity, .product-price, .product-discount').on('input', function() {  
          calculateTotalPrice();
        });  
        index++;
      });
		});
	</script>
@endsection
