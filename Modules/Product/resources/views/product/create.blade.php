@extends('admin.layouts.master')
@section('content')

  <div class="page-header">
    <x-core::breadcrumb :items="[['title' => 'لیست محصولات', 'route_link' => 'admin.products.index'], ['title' => 'ثبت محصول جدید']]"/>
  </div>

  <x-core::card>
    <x-slot name="cardTitle">ثبت محصول جدید</x-slot>
    <x-slot name="cardOptions"><x-core::card-options/></x-slot>
    <x-slot name="cardBody">
      <form action="{{ route('admin.products.store') }}" method="post" class="save" enctype="multipart/form-data">
        @csrf
        <div class="row">
          <div class="col-md-6 col-lg-4">
            <div class="form-group">
              <label for="title" class="control-label"> عنوان: <span class="text-danger">&starf;</span></label>
              <input type="text" id="title" class="form-control" name="title" placeholder="عنوان را به فارسی وارد کنید"
                     value="{{ old('title') }}" required autofocus>
              <x-core::show-validation-error name="title"/>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="form-group">
              <label for="print_title" class="control-label"> عنوان (پرینت فاکتور مشتری): <span class="text-danger">&starf;</span></label>
              <input type="text" id="print_title" class="form-control" name="print_title"
                     placeholder="عنوان را به فارسی وارد کنید" value="{{ old('print_title') }}" required autofocus>
              <x-core::show-validation-error name="print_title"/>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="form-group">
              <label for="category_id" class="control-label"> انتخاب دسته بندی: <span class="text-danger">&starf;</span></label>
              <select name="category_id" id="category_id" class="form-control">
                <option value=""> دسته بندی را انتخاب کنید</option>
                @foreach ($parentCategories as $category)
                  <optgroup label="{{ $category->title .' ('. $category->getUnitType() .')'  }}">
                    @if ($category->has('children'))
                      @foreach($category->children as $child)
                        <option value="{{ $child->id }}" @selected(old('category_id') == $child->id)>{{ $child->title }}</option>
                      @endforeach
                    @endif
                  </optgroup>
                @endforeach
              </select>
              <x-core::show-validation-error name="category_id"/>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="form-group">
              <label for="image" class="control-label"> انتخاب عکس </label>
              <input type="file" id="image" class="form-control" name="image" value="{{ old('image') }}">
              <x-core::show-validation-error name="image"/>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="form-group">
              <label for="price" class="control-label"> قیمت فروش پایه (ریال): <span
                  class="text-danger">&starf;</span></label>
              <input type="text" id="price" class="form-control comma" name="price"
                     placeholder="قیمت را به ریال وارد کنید" value="{{ old('price') }}">
              <x-core::show-validation-error name="price"/>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="form-group">
              <label for="discount" class="control-label"> تخفیف پایه (ریال): </label>
              <input type="text" id="discount" class="form-control comma" name="discount"
                     placeholder="تخفیف را به ریال وارد کنید" value="{{ old('discount') }}">
              <x-core::show-validation-error name="discount"/>
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
        <div class="row mb-5">
          <div class="col-12 d-flex justify-content-center" style="border-radius: 10px;">
            <button id="add-dimensions-btn" class="btn btn-green d-flex justify-content-center align-items-center" type="button">
              <span class="ml-1">ثبت ابعاد مختلف</span>
              <i class="fa fa-plus font-weight-bold"></i>
            </button>
          </div>
        </div>
        <div class="row hidden-part mb-5 justify-content-center" id="warning-messages-section">
          <div class="col bg-yellow p-2" style="border-radius: 10px;">
              <ul class="mr-3">
                <li class="fs-16 text-gray-dark my-1">فیلد های <span class="text-danger">ستاره دار</span> اجباری هستند!</li>
                <li class="fs-16 text-gray-dark my-1">در صورت وارد کردن موجودی اولیه باید قیمت خرید را وارد کنید!</li>
                <li class="fs-16 text-gray-dark my-1">تمامی قیمت ها را به <span class="font-weight-bold">ریال</span> وارد کنید!</li>
              </ul>
          </div>
        </div>
        <div class="row hidden-part">
          <div id="products-dimensions-section" class="col-12 mx-auto table-responsive mt-4">
            <table id="products-dimensions-table" role="table" class="table b-table table-bordered text-center border-top">
              <thead role="rowgroup">
              <tr role="row">
                <th class="fs-15">ابعاد <span class="text-danger">&starf;</span></th>
                <th class="fs-15">موجودی اولیه</th>
                <th class="fs-15">قیمت خرید</th>
                <th class="fs-15">قیمت فروش <span class="text-danger">&starf;</span></th>
                <th class="fs-15">تخفیف</th>
                <th class="fs-15">عملیات</th>
              </tr>
              </thead>
              <tbody role="rowgroup"> 
                
              </tbody>
            </table>
          </div>
        </div>
        <div class="row hidden-part" id="submit-btn-section">
          <div class="col">
            <div class="text-center">
              <button class="btn btn-pink" type="submit">ثبت و ذخیره</button>
            </div>
          </div>
        </div>
      </form>
    </x-slot>
  </x-core::card>

@endsection

@section('scripts')
  <script>
    $(document).ready(() => {

      let title = $('#title');
      let printTitle = $('#print_title');
      let initialBalance = $('#initial_balance');
      let purchasedPriceBox = $('#purchased_price_box');
      let purchasedPriceInput = $('#purchased_price');

      $('#category_id').select2({placeholder: 'انتخاب دسته بندی'});

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
      $('.hidden-part').hide();
      let counter = 1; 
      
      $('#add-dimensions-btn').click(() => {
        $('.hidden-part').slideDown('slow');
        let html = `
          <tr style="display: none;">  
            <td class="p-3"><input type="text" class="form-control text-center" name="product_dimensions[${counter}][dimensions]" required></td>  
            <td class="p-3"><input type="number" class="form-control text-center p-0" name="product_dimensions[${counter}][initial_balance]"></td>  
            <td class="p-3"><input type="text" class="form-control text-center p-0 comma" name="product_dimensions[${counter}][purchased_price]"></td>  
            <td class="p-3"><input type="text" class="form-control text-center p-0 comma" name="product_dimensions[${counter}][price]" required></td>  
            <td class="p-3"><input type="text" class="form-control text-center p-0 comma" name="product_dimensions[${counter}][discount]"></td>  
            <td>
              <div>
                <button type="button" class="delete-btn btn btn-sm btn-icon btn-danger text-whitem" style="margin-left: 1px;">  
                  <i class="fa fa-trash-o"></i>  
                </button>  
                <button type="button" class="add-btn btn btn-sm btn-icon btn-success text-whitem" style="margin-right: 1px;">  
                  <i class="fa fa-plus"></i>  
                </button>  
              </div>  
            </td>  
          </tr>  
        `;
        const $newRow = $(html);  
        $('#products-dimensions-table tbody').append($newRow); 
        $newRow.slideDown('slow');  
        comma();
        counter++;
      });

      $('#products-dimensions-table').on('click', '.delete-btn', function() {  
          $(this).closest('tr').remove();   
      });  

      $('#products-dimensions-table').on('click', '.add-btn', () => {  
        $('#add-dimensions-btn').click();  
      }); 

    });
  </script>
@endsection
