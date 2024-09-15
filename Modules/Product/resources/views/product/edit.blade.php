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
      <li class="breadcrumb-item active">ویرایش محصول</li>
    </ol>
  </div>
  <div class="card">
    <div class="card-header border-0">
      <h1 class="card-title">ویرایش محصول</h3>
    </div>
    <div class="card-body">
      <form action="{{ route('admin.products.update', $product) }}" method="post" class="save" enctype="multipart/form-data">

        @csrf
        @method('PATCH')

        <div class="row my-5">

          <div class="col-md-6 col-lg-4">
            <div class="form-group">
              <label for="title" class="control-label"> عنوان: <span class="text-danger">&starf;</span></label>
              <input type="text" id="title" class="form-control" name="title" placeholder="عنوان را وارد کنید" value="{{ old('title', $product->title) }}">
              <x-core::show-validation-error name="title" />
            </div>
          </div>

          <div class="col-md-6 col-lg-4">
            <div class="form-group">
              <label for="print_title" class="control-label"> عنوان (پرینت فاکتور مشتری): <span class="text-danger">&starf;</span></label>
              <input type="text" id="print_title" class="form-control" name="print_title" placeholder="عنوان را به فارسی وارد کنید" value="{{ old('print_title', $product->print_title) }}" required autofocus>
              <x-core::show-validation-error name="print_title" />
            </div>
          </div>

          <div class="col-md-6 col-lg-4">
            <div class="form-group">
              <label for="category_id" class="control-label"> انتخاب دسته بندی: <span class="text-danger">&starf;</span></label>
              <select name="category_id" id="category_id" class="form-control">
                @foreach ($parentCategories as $category)<option value="{{ $category->id }}" class="text-muted" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->title }}</option>
                @if ($category->has('children'))
                  @foreach($category->children as $child)
                    <option value="{{ $child->id }}" @selected(old('category_id', $product->category_id) == $child->id)>&nbsp;&nbsp;{{ $child->title }}</option>
                  @endforeach
                @endif
                @endforeach
              </select>
              <x-core::show-validation-error name="category_id" />
            </div>
          </div>

          <div class="col-md-6 col-lg-4">
            <div class="form-group">
              <label for="price" class="control-label"> قیمت (ریال): <span class="text-danger">&starf;</span></label>
              <input type="text" id="price" class="form-control comma" name="price" placeholder="قیمت را به ریال وارد کنید" value="{{ old('price', number_format($product->price)) }}">
              <x-core::show-validation-error name="price" />
            </div>
          </div>

          <div class="col-md-6 col-lg-4">
            <div class="form-group">
              <label for="discount" class="control-label"> تخفیف (ریال): </label>
              <input type="text" id="discount" class="form-control comma" name="discount" placeholder="تخفیف را به ریال وارد کنید" value="{{ old('discount', $product->discount ? number_format($product->discount) : null) }}">
              <x-core::show-validation-error name="discount" />
            </div>
          </div>

          <div class="col-md-6 col-lg-4">
            <div class="form-group">
              <label for="image" class="control-label"> انتخاب عکس </label>
              <input type="file" id="image" class="form-control" name="image" value="{{ old('image') }}">
              <x-core::show-validation-error name="image" />
            </div>
          </div>

          @if ($product->image)
            <div class="col-12 text-center">
              <div class="img-holder my-4 img-show w-100 bg-light" style="max-height: 300px;">
                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('delete-image-{{ $product->id }}')">
                  <i class="fa fa-trash-o"></i>
                </button>
                <img src="{{ Storage::url($product->image) }}" style="max-height: 300px">
              </div>
            </div>
          @endif

          <div class="col-12">
            <div class="form-group">
              <label for="description" class="control-label">توضیحات :</label>
              <textarea name="description" id="description" class="form-control" rows="4"> {{ old('description', $product->description) }} </textarea>
              <x-core::show-validation-error name="description" />
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label"> انتخاب وضعیت:<span class="text-danger">&starf;</span></label>
              <div class="custom-controls-stacked">
                <label class="custom-control custom-radio">
                  <input type="radio" class="custom-control-input" name="status" value="1" @checked(old('status', $product->status) == '1')>
                  <span class="custom-control-label">فعال</span>
                </label>
                <label class="custom-control custom-radio">
                  <input type="radio" class="custom-control-input" name="status" value="0" @checked(old('status', $product->status) == '0')>
                  <span class="custom-control-label">غیر فعال</span>
                </label>
              </div>
              <x-core::show-validation-error name="status" />
            </div>
          </div>

        </div>

        <div class="row my-5">
          <div class="col bg-yellow" style="border-radius: 10px;">
              <ul class="mr-3">
                <li class="fs-16 text-gray-dark my-1">فیلد های <span class="text-danger">ستاره دار</span> اجباری هستند!</li>
                <li class="fs-16 text-gray-dark my-1">در صورت وارد کردن موجودی اولیه باید قیمت خرید را وارد کنید!</li>
                <li class="fs-16 text-gray-dark my-1">تمامی قیمت ها را به <span class="font-weight-bold">ریال</span> وارد کنید!</li>
              </ul>
          </div>
        </div>

        <div class="row mt-5">
          <div class="col-12 bg-gray-darker text-center py-3" style="border-radius: 10px;">
            <p class="fs-18 text-white font-weight-bold mb-0 ">ابعاد فعلی محصول</p>
          </div>
          <div class="col-12 table-responsive mt-4 px-0">
            <table role="table" class="table b-table table-bordered text-center border-top">
              <thead role="rowgroup">
              <tr role="row">
                <th class="fs-15">ابعاد</th>
                <th class="fs-15">موجودی</th>
                <th class="fs-15">قیمت فروش</th>
                <th class="fs-15">تخفیف</th>
                <th class="fs-15">وضعیت</th>
                <th class="fs-15">عملیات</th>
              </tr>
              </thead>
              <tbody role="rowgroup"> 
                @foreach ($product->children->sortByDesc('id') as $index => $childProduct)
                  <tr>  
                    <td class="p-3">{{ $childProduct->sub_title }}</td>  
                    <td class="p-3">{{ $childProduct->loadStoreBalance() }}</td>  
                    <td class="p-3">{{ number_format($childProduct->price) }}</td>  
                    <td class="p-3">{{ number_format($childProduct->discount) }}</td>  
                    <td class="p-3">
                      <x-core::light-badge
                        type="{{ $childProduct->status ? 'success' : 'danger' }}"
                        text="{{ $childProduct->status ? 'فعال' : 'غیر فعال' }}"
                      /> 
                    </td>  
                    <td>
                      <button
                        type="button"
                        class="btn btn-sm btn-icon btn-warning text-white"
                        data-toggle="modal"
                        data-target="#edit-product-form-{{ $childProduct->id }}">
                        ویرایش
                        <i class="fa fa-pencil mr-1"></i>
                      </button>
                      <button 
                        type="button" 
                        class="delete-btn btn btn-sm btn-icon btn-danger text-whitem" 
                        onclick="confirmDelete('delete-product-demenision-{{ $childProduct->id }}')">  
                        حذف
                        <i class="fa fa-trash-o mr-1"></i>  
                      </button>  
                      <button 
                        type="button"
                        onclick="$('#productStoreForm-' + @json($childProduct->id)).submit()" 
                        class="btn btn-sm btn-icon btn-info text-white">
                        موجودی
                        <i class="fa fa-database mr-1"></i>
                      </button>
                      <form
                        action="{{ route('admin.stores.index') }}"
                        method="GET"
                        id="productStoreForm-{{ $childProduct->id }}"
                        class="d-none">
                        <input type="hidden" name="product_id" value="{{ $childProduct->id }}">
                      </form>
                    </td>  
                  </tr>  
                @endforeach
              </tbody>
            </table>
          </div>
        </div>

        <div class="row mt-5 ">
          <div class="col text-center px-0">
            <button id="add-dimensions-btn" class="btn btn-success btn-block fs-16" type="button">ثبت ابعاد جدید</button>
          </div>
        </div>

        <div class="row" id="products-dimensions-section">
          <div class="col-12 mx-auto table-responsive mt-4 px-0">
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

        <div class="row my-5">
          <div class="col">
            <div class="text-center">
              <button class="btn btn-warning" type="submit">بروزرسانی</button>
            </div>
          </div>
        </div>

      </form>

      @if ($product->image)
        <form
          action="{{ route('admin.products.image.destroy', $product) }}"
          id="delete-image-{{$product->id}}"
          method="POST"
          style="display: none;">
          @csrf
          @method("DELETE")
        </form>
      @endif

      @include('product::product.includes.child-product-edit-from')

      @foreach ($product->children as $childProduct)
        <form
          action="{{ route('admin.products.destroy', $childProduct) }}"
          id="delete-product-demenision-{{$childProduct->id}}"
          method="POST"
          style="display: none;">
          @csrf
          @method("DELETE")
        </form>
      @endforeach

    </div>
  </div>
@endsection

@section('scripts')
<script>
  $(document).ready(() => {

    let counter = 100; 

    $('#products-dimensions-section').hide(); 
    
    $('#add-dimensions-btn').click(() => {
      $('#products-dimensions-section').show(); 
      let html = `
        <tr>  
          <td class="p-3"><input type="text" class="form-control" name="product_dimensions[${counter}][dimensions]" required></td>  
          <td class="p-3"><input type="number" class="form-control p-0" name="product_dimensions[${counter}][initial_balance]"></td>  
          <td class="p-3"><input type="text" class="form-control p-0 comma" name="product_dimensions[${counter}][purchased_price]"></td>  
          <td class="p-3"><input type="text" class="form-control p-0 comma" name="product_dimensions[${counter}][price]" required></td>  
          <td class="p-3"><input type="text" class="form-control p-0 comma" name="product_dimensions[${counter}][discount]"></td>  
          <td>
            <div>
              <button type="button" class="delete-btn btn btn-sm btn-icon btn-danger text-whitem" style="margin-left: 1px;">  
                <i class="fa fa-minus"></i>  
              </button>  
              <button type="button" class="add-btn btn btn-sm btn-icon btn-success text-whitem" style="margin-right: 1px;">  
                <i class="fa fa-plus"></i>  
              </button>  
            </div>  
          </td>  
        </tr>  
      `;
      $('#products-dimensions-table tbody').append(html);
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