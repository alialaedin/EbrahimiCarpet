@extends('admin.layouts.master')
@section('content')

  <div class="page-header">
    <x-core::breadcrumb :items="[
      ['title' => 'لیست فروش ها', 'route_link' => 'admin.sales.index'],
      ['title' => 'ثبت فروش جدید']
    ]"/>
  </div>

  <x-core::card>
    <x-slot name="cardTitle">فاکتور فروش جدید</x-slot>
    <x-slot name="cardOptions"><x-core::card-options/></x-slot>
    <x-slot name="cardBody">
      <form id="SubmitForm" action="{{ route('admin.sales.store') }}" method="post" class="save">
        @csrf
        <div class="row">
          <div class="col-lg-4 col-md-6">
            <div class="form-group">
              <label for="customer_id" class="control-label">انتخاب مشتری :<span class="text-danger">&starf;</span></label>
              <select name="customer_id" id="customer_id" class="form-control select2" required>
                <option value="" class="text-muted">-- مشتری را انخاب کنید --</option>
                @foreach ($customers as $customer)
                  <option value="{{ $customer->id }}" @selected(request("customer_id") == $customer->id)>{{ $customer->name .' - '. $customer->mobile }}</option>
                @endforeach
              </select>
              <x-core::show-validation-error name="customer_id" />
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="form-group">
              <label for="employee_id" class="control-label">پرسنل ارجاع :<span class="text-danger">&starf;</span></label>
              <select name="employee_id" id="employee_id" class="form-control select2" required>
                <option value="" class="text-muted">-- پرسنل را انخاب کنید --</option>
                @foreach ($employees as $employee)
                  <option value="{{ $employee->id }}" @selected(old("employee_id") == $employee->id)>{{ $employee->name .' - '. $employee->mobile }}</option>
                @endforeach
              </select>
              <x-core::show-validation-error name="employee_id" />
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="form-group">
              <label for="sold_date_show" class="control-label">تاریخ فروش :<span class="text-danger">&starf;</span></label>
              <input class="form-control fc-datepicker" id="sold_date_show" type="text" autocomplete="off" placeholder="تاریخ فروش را انتخاب کنید" />
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
          <div class="col-lg-4 col-md-6">
            <div class="form-group">
              <label for="discount_for" class="control-label"> بابت تخفیف : </label>
              <input type="text" id="discount_for" class="form-control" name="discount_for" placeholder="بابت تخفیف را وارد کنید" value="{{ old('discount_for') }}">
              <x-core::show-validation-error name="discount_for" />
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="form-group">
              <label for="cost_of_sewing" class="control-label"> هزینه دوخت / نصب : </label>
              <input type="text" id="cost_of_sewing" class="form-control comma" name="cost_of_sewing" placeholder="هزینه دوخت را وارد کنید" value="{{ old('cost_of_sewing') }}">
              <x-core::show-validation-error name="cost_of_sewing" />
            </div>
          </div>
        </div>
        <div class="row mb-5">
          <div class="col-12 d-flex justify-content-center" style="border-radius: 10px;">
            <button id="addSaleItemButton" class="btn btn-green d-flex justify-content-center align-items-center" type="button">
              <span class="ml-1">افزودن آیتم به فاکتور</span>
              <i class="fa fa-plus font-weight-bold"></i>
            </button>
          </div>
        </div>
        <div class="row mx-4 my-5" id="ProductsSection">
          <div class="table-responsive">
            <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                <table class="table text-center text-nowrap table-bordered border-bottom">
                  <thead>
                  <tr>
                    <th>انتخاب محصول <span class="text-danger">&starf;</span></th>
                    <th>موجودی</th>
                    <th>قیمت واحد (ریال)</th>
                    <th>تخفیف (ریال)</th>
                    <th>تعداد / متر <span class="text-danger">&starf;</span></th>
                    <th>عملیات</th>
                  </tr>
                  </thead>
                  <tbody id="ProductsTableBody">
                  </tbody>
                </table>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12 text-center mb-5 bg-black-8 text-white-80 py-3 rounded" >
            <span class="fs-16">جمع مبلغ کل فاکتور : </span>
            <span class="font-weight-bold fs-16" id="totalPrice">0</span>
            <span class="font-weight-bold fs-16">ریال</span>
          </div>
          <div class="col-12">
            <div class="text-center">
              <button id="submitButton" class="btn btn-pink mt-2" type="submit">ثبت و ذخیره</button>
            </div>
          </div>
        </div>
      </form>    
    </x-slot>
  </x-core::card>

@endsection

@section('scripts')
  <x-core::date-input-script textInputId="sold_date_show" dateInputId="sold_date"/>
	<script>

    new CustomSelect('#customer_id', 'انتخاب مشتری');
    new CustomSelect('#employee_id', 'انتخاب کارمند');

    function getProductStore(id) {

      let productId = $(id).val();
      let token = $('meta[name="csrf-token"]').attr('content');

      $.ajax({
        url: @json(route("admin.sales.get-product-store")),
        type: 'POST',
        data: {product_id: productId},
        headers: {'X-CSRF-TOKEN': token},
        success: function(response) {
          $(id + '-balance').val(response.balance);
          $(id + '-balance-text').text(response.balance);
          $(id + '-price').val(response.price);
          $(id + '-discount').val(response.discount);
        }
      });
    }

		$(document).ready(function() {

      let index = 0;
      const addSaleItemButton = $("#addSaleItemButton");
      const submitButton = $("#submitButton");
      const productsSection = $("#ProductsSection");
      const productsTableBody = $("#ProductsTableBody");
      const totalDiscountInput = $("#discount");
      const costOfSewingInput = $("#cost_of_sewing");
      const totalPriceBox = $("#totalPrice");
      
      submitButton.hide();
      productsSection.hide();

      function calculateTotalPrice() {  

        let total = 0;  

        let totalDiscount = totalDiscountInput.val().replace(/,/g, '') || 0;
        let costOfSewing = parseInt(costOfSewingInput.val().replace(/,/g, '')) || 0;

        productsTableBody.find('tr').each(function() {

          const quantity = Math.max(parseFloat($(this).find('.product-quantity').val()) || 0, 0); 
          const price = Math.max(parseFloat($(this).find('.product-price').val().replace(/,/g, '')) || 0, 0); 
          const discount = Math.max(parseFloat($(this).find('.product-discount').val().replace(/,/g, '')) || 0, 0);

          total += (quantity * (price - discount));  
        });  

        let totalPrice = total + costOfSewing - totalDiscount;

        totalPriceBox.text(totalPrice.toLocaleString()); 
      }

      totalDiscountInput.on('input', () => {
        if ($(this).val() !== null) {
          calculateTotalPrice();
        }
      });

      costOfSewingInput.on('input', () => {
        if ($(this).val() !== null) {
          calculateTotalPrice();
        }
      });

      addSaleItemButton.click(() => {

        submitButton.show();
        productsSection.show();

        let tr = $(`
          <tr role="row">  
            <td>
              <select name="products[${index + 1}][id]" id="product-${index + 1}" class="form-control product-select d-block" onchange="getProductStore('#product-${index + 1}')" required>
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
            <td>
              <span id="product-${index + 1}-balance-text">-</span>
              <input name="products[${index + 1}][balance]" id="product-${index + 1}-balance" hidden>
            </td>  
            <td><input type="text" class="form-control text-center product-price comma" name="products[${index + 1}][price]" id="product-${index + 1}-price"></td>  
            <td><input type="text" class="form-control text-center product-discount comma" name="products[${index + 1}][discount]"></td>  
            <td><input type="number" step="0.01" class="form-control text-center product-quantity" name="products[${index + 1}][quantity]"></td>  
            <td>  
              <button type="button" class="positive-btn font-weight-bold btn btn-sm btn-icon btn-success ml-1">+</button>
              <button type="button" class="negative-btn font-weight-bold btn btn-sm btn-icon btn-danger ml-1">-</button>
            </td>  
          </tr>  
        `);

        tr.find('.product-quantity').on('input', calculateTotalPrice);

        // comma();
        tr.find('.product-select').select2({placeholder: 'محصول مورد نظر را انتخاب کنید'});
        productsTableBody.append(tr);
        comma();
        tr.find('.negative-btn').click(function() {  
          $(this).closest('tr').remove(); 
          calculateTotalPrice();
        });  
        tr.find('.positive-btn').click(function() {  
          addSaleItemButton.trigger('click'); 
        });
        index++;
      });

      submitButton.click(() => $('#SubmitForm').submit());

		});
	</script>
@endsection
