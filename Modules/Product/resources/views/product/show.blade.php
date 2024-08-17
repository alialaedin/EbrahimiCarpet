@extends('admin.layouts.master')

@section('styles')
  <style>
    strong {
      font-size: 16px;
      margin-left: 4px;
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
        <a href="{{ route('admin.products.index') }}">لیست محصولات</a>
      </li>
      <li class="breadcrumb-item active">نمایش محصول</li>
    </ol>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
      @can('edit products')
        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning mx-1">
          ویرایش محصول<i class="fa fa-pencil mr-2"></i>
        </a>
      @endcan
      @can('delete products')
        <button
          onclick="confirmDelete('delete-{{ $product->id }}')"
          class="btn btn-danger mx-1"
          @disabled(!$product->isDeletable())>
          حذف محصول<i class="fa fa-trash-o mr-2"></i>
        </button>
        <form
          action="{{ route('admin.products.destroy', $product) }}"
          method="POST"
          id="delete-{{ $product->id }}"
          style="display: none">
          @csrf
          @method('DELETE')
        </form>
      @endcan
    </div>
  </div>

  <div class="card bg-warning">
    <div class="card-body">
      <h4 class="text-gray-400 font-weight-bold">اگر قابلیت حذف محصول را ندارید ممکن است به خاطر دلایل زیر باشد :</h4>
      <ul class="mr-3">
        @foreach ($product->loadDeletableMessages() as $message)
          <li class="fs-16 text-gray-dark my-1"><i class="fa fa-dot-circle-o ml-2"></i>{{ $message }}</li>
        @endforeach
      </ul>
    </div>
  </div>

  <div class="card">
    <div class="card-header border-0">
      <p class="card-title">اطلاعات اولیه</p>
    </div>
    <div class="card-body">
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem">
        <div>
          <ul class="list-group">
            <li class="list-group-item"><strong>کد: </strong> {{ $product->id }} </li>
            <li class="list-group-item"><strong>عنوان محصول: </strong> {{ $product->title }} </li>
            <li class="list-group-item"><strong>عوان پرینت: </strong> {{ $product->print_title }} </li>
            <li class="list-group-item"><strong>دسته بندی: </strong> {{ $product->category->title }} </li>
            <li class="list-group-item"><strong>موجودی کلی تمام ابعاد: </strong> {{ $product->getTotalDimensionsStoreBalance() .' '. $product->category->getUnitType()}} </li>
            <li class="list-group-item"><strong>قیمت پایه: </strong> {{ number_format($product->price) }} ریال </li>
            <li class="list-group-item"><strong>تخفیف پایه: </strong> {{ number_format($product->getDiscount()) }} ریال </li>
            <li class="list-group-item">
              <strong>وضعیت: </strong>
              <x-core::badge
                type="{{ $product->status ? 'success' : 'danger' }}"
                text="{{ $product->status ? 'فعال' : 'غیر فعال' }}"
              />
            </li>
            <li class="list-group-item"><strong>تاریخ ثبت: </strong> @jalaliDate($product->created_at) </li>
            <li class="list-group-item"><strong>تاریخ آخرین ویرایش: </strong> @jalaliDate($product->updated_at) </li>
          </ul>
        </div>
        <div>
          <figure class="figure w-100 h-100 text-center m-0">
            <a target="blank" href="{{ Storage::url($product->image) }}">
              <img src="{{ Storage::url($product->image) }}" class="w-auto" style="height: 490px;" alt="{{ $product->title }}"/>
            </a>
          </figure>
        </div>
      </div>
    </div>
  </div>

  <div class="card bg-warning">
    <div class="card-body">
      <h4 class="text-gray-400 font-weight-bold">برای افزایش یا کاهش موجودی روی دکمه 'موجودی' کلیک کنید!</h4>
    </div>
  </div>

  @if ($product->children->isNotEmpty())

    <div class="card">
      <div class="card-header border-0">
        <h1 class="card-title">محصولات زیر دسته</h1>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <div class="dataTables_wrapper dt-bootstrap4 no-footer">
            <div class="row">
              <table class="table table-vcenter text-center table-striped text-nowrap table-bordered border-bottom">
                <thead class="bg-light">
                <tr>
                  <th>ردیف</th>
                  <th>ابعاد</th>
                  <th>موجودی</th>
                  <th>شناسه</th>
                  <th>قیمت (ریال)</th>
                  <th>تخفیف (ریال)</th>
                  <th>عملیات</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($product->children as $childProduct)
                  <tr>
                    <td class="font-weight-bold">{{ $loop->iteration }}</td>
                    <td>{{ $childProduct->sub_title }}</td>
                    <td>{{ number_format($childProduct->loadStoreBalance()) }}</td>
                    <td>{{ $childProduct->id }}</td>
                    <td>{{ number_format($childProduct->price) }}</td>
                    <td>{{ number_format($childProduct->getDiscount()) }}</td>
                    <td>
                      @can('edit products')
                        <button
                          class="btn btn-sm btn-icon btn-warning text-white"
                          data-toggle="modal"
                          data-target="#edit-product-form-{{ $childProduct->id }}">
                          <i class="fa fa-pencil"></i>
                        </button>
                      @endcan
                      @can('delete products')
                        <x-core::delete-button
                          route="admin.products.destroy"
                          :model="$childProduct"
                          disabled="{{ !$childProduct->isDeletable() }}"
                        />
                      @endcan
                      <button 
                        onclick="$('#productStoreForm').submit()" 
                        class="btn btn-sm btn-icon btn-info text-white">
                        موجودی
                      </button>
                      <form
                        action="{{ route('admin.stores.index') }}"
                        method="GET"
                        id="productStoreForm"
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
        </div>
      </div>
    </div>

    @foreach ($product->children as $childProduct)
      <div class="modal fade" id="edit-product-form-{{ $childProduct->id }}" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
          <div class="modal-content modal-content-demo">
            <div class="modal-header">
              <p class="modal-title" style="font-size: 20px;">ویرایش محصول - کد {{ $childProduct->id }}</p>
              <button aria-label="Close" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
              <form action="{{ route('admin.products.update', $childProduct) }}" method="POST">

                @csrf
                @method('PATCH')

                <input type="hidden" name="title" value="{{ $product->title }}">
                <input type="hidden" name="print_title" value="{{ $product->print_title }}">
                <input type="hidden" name="category_id" value="{{ $product->category_id }}">  
                <input type="hidden" name="description" value="{{ $product->description }}">  

                <div class="row">

                  <div class="col-12">
                    <div class="form-group">
                      <label> قیمت (ریال): <span class="text-danger">&starf;</span></label>
                      <input type="text" class="form-control comma" name="price" value="{{ old('price', number_format($childProduct->price)) }}">
                      <x-core::show-validation-error name="price" />
                    </div>
                  </div>

                  <div class="col-12">
                    <div class="form-group">
                      <label> تخفیف (ریال): <span class="text-danger">&starf;</span></label>
                      <input type="text" class="form-control comma" name="discount" value="{{ old('discount', number_format($childProduct->discount)) }}">
                      <x-core::show-validation-error name="discount" />
                    </div>
                  </div>

                  <div class="col-md-12">
                    <div class="form-group">
                      <label> انتخاب وضعیت:<span class="text-danger">&starf;</span></label>
                      <div class="custom-controls-stacked">
                        <label class="custom-control custom-radio">
                          <input type="radio" class="custom-control-input" name="status" value="1" @checked(old('status', $childProduct->status) == '1')>
                          <span class="custom-control-label">فعال</span>
                        </label>
                        <label class="custom-control custom-radio">
                          <input type="radio" class="custom-control-input" name="status" value="0" @checked(old('status', $childProduct->status) == '0')>
                          <span class="custom-control-label">غیر فعال</span>
                        </label>
                      </div>
                      <x-core::show-validation-error name="status" />
                    </div>
                  </div>

                </div>

                <div class="justify-content-center d-flex">
                  <button class="btn btn-warning mx-1" type="submit">بروزرسانی</button>
                  <button class="btn btn-outline-danger mx-1" data-dismiss="modal">انصراف</button>
                </div>

              </form>
            </div>
          </div>
        </div>
      </div>
    @endforeach

  @endif

  @if($product->description)
    <div class="card">
      <div class="card-header border-0">
        <p class="card-title">توضیحات</p>
      </div>
      <div class="card-body">
        <p> {{ $product->description }} </p>
      </div>
    </div>
  @endif

@endsection