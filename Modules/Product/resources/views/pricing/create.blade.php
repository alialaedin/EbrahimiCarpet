@extends('admin.layouts.master')
@section('content')

  <div class="page-header">
    <x-core::breadcrumb :items="[['title' => 'قیمت گذاری محصولات']]"/>
  </div>

  <form id="submit-form" action="{{ route('admin.pricing.store') }}" method="POST">
    @csrf
  </form>

  <x-core::card>
    <x-slot name="cardTitle">قیمت گذاری محصولات</x-slot>
    <x-slot name="cardOptions"><x-core::card-options/></x-slot>
    <x-slot name="cardBody">
      <div class="row justify-content-center">
        <div class="col-xl-4">
          <div class="form-group">
            <label>دسته بندی</label>
            <select id="category-select-box" class="form-control">
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
        <div class="col-xl-4">
          <div class="form-group">
            <label>محصولات</label>
            <select id="product-select-box" class="form-control">
              <option value=""></option>
            </select>
          </div>
        </div>
      </div>
      <div class="row justify-content-center mt-5">
        <div class="col-xl-8">
          <div class="table-responsive">
            <table id="product-prices-table" class="table table-bordered table-striped text-nowrap text-center border-bottom">
              <thead class="border">
                <th>محصول</th>
                <th>دسته بندی</th>
                <th>ابعاد</th>
                <th>قیمت فعلی (ریال)</th>
                <th>قیمت جدید (ریال)</th>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
        <div class="col-12 text-center">
          <button class="btn btn-sm btn-pink" id="updatePrices">بروزرسانی قیمت</button>
        </div>
      </div>
    </x-slot>
  </x-core::card>

  <div id="Examples">
		<table>
			<tbody>
				<tr id="example-tr" class="product-tr">
          <td class="d-none">
            <input hidden class="parent-product-id-input" value="">
            <input hidden class="child-product-id-input" value="">
          </td>
					<td class="parent-product-title"></td>
					<td class="parent-product-category-title"></td>
					<td class="child-product-sub-title"></td>
					<td class="child-product-old-price"></td>
					<td class="child-product-new-price">
            <input type="text" class="form-control price-input comma" value="">
          </td>
				</tr>
			</tbody>
		</table>
	</div>

@endsection

@section('scripts')
  <script>

    const exampleTr = $('#example-tr').clone().removeAttr('id');
    const productsPriceTable = $('#product-prices-table');
    const categorySelectBox = $('#category-select-box');
    const productSelectBox = $('#product-select-box');

    const allParentProducts = @json($products);
    const allParentCategories = @json($categories);

    const removeExamplesFromDOM = () => $('#Examples').remove();
    const hideproductsPriceTable = () => productsPriceTable.hide();
    const showproductsPriceTable = () => productsPriceTable.show();

    categorySelectBox.select2({placeholder: 'انتخاب دسته بندی'});
    productSelectBox.select2({placeholder: 'ابتدا دسته بندی را انتخاب کنید'});

    function categoriesChangeEvent() {
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
    }

    function productsChangeEvent() {
      productSelectBox.on('select2:select', (event) => {
        showproductsPriceTable();
        
        const productId = event.target.value;
        const isProductExists = productsPriceTable.find('tbody tr').filter(() => {  
          return $(this).find('.parent-product-id-input').val() == productId;
        });  

        if (isProductExists.length) {
          return
        };

        const parentProduct = allParentProducts.find(p => p.id == productId);
        parentProduct.children.forEach(childProduct => {
          const tr = exampleTr.clone();
          tr.find('.parent-product-id-input').val(parentProduct.id);
          tr.find('.child-product-id-input').val(childProduct.id);
          tr.find('.parent-product-title').text(parentProduct.title);
          tr.find('.parent-product-category-title').text(parentProduct.category.title);
          tr.find('.child-product-sub-title').text(childProduct.sub_title);
          tr.find('.child-product-old-price').text(childProduct.price.toLocaleString());
          productsPriceTable.append(tr);
          comma();
        });

      });
    } 

    function updatePrices() {
      $('#updatePrices').click(() => {
        let index = 0;
        $('.product-tr').each(function() {
          if ($(this).find('.child-product-new-price .price-input').val().trim() != '') {
            
            const productId = $(this).find('.child-product-id-input').val();
            const price = $(this).find('.price-input').val().replace(/,/g, "");

            const idInput = $(`<input hidden name="products[${index}][id]" value="${productId}" />`);
            const priceInput = $(`<input hidden name="products[${index}][price]" value="${price}" />`);

            $('#submit-form').append(idInput).append(priceInput);
            index++;
          }
        });
        $('#submit-form').submit();
      });
    }

    $(document).ready(() => {
      hideproductsPriceTable();
      removeExamplesFromDOM();
      categoriesChangeEvent();
      productsChangeEvent();
      updatePrices();
    });
    
  </script>
@endsection
