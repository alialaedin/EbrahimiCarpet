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
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}">
          <i class="fe fe-home ml-1"></i> داشبورد
        </a>
      </li>
      <li class="breadcrumb-item">
        <a href="{{ route('admin.purchases.index') }}">لیست خرید ها</a>
      </li>
      <li class="breadcrumb-item active">ثبت خرید جدید</li>
    </ol>
  </div>
  <div class="card">
    <div class="card-header border-bottom-0">
      <p class="card-title">ثبت خرید جدید</p>
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
        <div class="row mb-5">
          <div class="col-12 d-flex justify-content-center" style="border-radius: 10px;">
            <button id="addPurchaseItemButton" class="btn btn-green d-flex justify-content-center align-items-center" type="button">
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
        <div class="row">
          <div class="col">
            <div class="text-center">
              <button id="submitButton" class="btn btn-pink mt-2" type="submit">ثبت و ذخیره</button>
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
      $('#supplier_id').select2({placeholder: 'تامین کننده را انتخاب کنید'});
      $('#submitButton').hide();
      $('#ProductsSection').hide();
      let index = 0;
      const addPurchaseItemButton = $("#addPurchaseItemButton");
      addPurchaseItemButton.click(() => {
        $('#submitButton').show();
        $('#ProductsSection').show();
        let tr = $(`
          <tr role="row">  
            <td>
              <select name="products[${index + 1}][id]" class="form-control product-select d-block" required>
                <option value="" class="text-muted">-- محصول مورد نظر را انتخاب کنید --</option>
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
              <input 
                type="number" 
                class="form-control" 
                name="products[${index + 1}][quantity]" 
                required 
                min="1"
              />
            </td>  
            <td>  
							<input 
                type="text" 
                class="form-control comma" 
                name="products[${index + 1}][price]" 
                required 
                min="1000"
              />
            </td>  
            <td class="product-discount">  
              <input 
                type="text" 
                class="form-control comma" 
                name="products[${index + 1}][discount]" 
              />
            </td>  
            <td>  
              <button type="button" class="positive-btn font-weight-bold btn btn-sm btn-icon btn-success ml-1">+</button>
              <button type="button" class="negative-btn font-weight-bold btn btn-sm btn-icon btn-danger ml-1">-</button>
            </td>  
          </tr>  
        `);
        comma();
        tr.find('.product-select').select2({placeholder: 'محصول مورد نظر را انتخاب کنید'});
        $('#ProductsTableBody').append(tr);
        tr.find('.negative-btn').click(function() {  
          $(this).closest('tr').remove(); 
        });  
        tr.find('.positive-btn').click(function() {  
          addPurchaseItemButton.trigger('click'); 
        });
        index++;
      });
		});
	</script>
@endsection
