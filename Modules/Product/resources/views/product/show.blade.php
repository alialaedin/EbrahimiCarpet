@extends('admin.layouts.master')
@section('content')
  <div class="col-12">

    <div class="col-xl-12 col-md-12 col-lg-12">

      <div class="page-header">
        <x-core::breadcrumb :items="$breadcrumbItems" />
    	</div>      
      
      <div class="row">

        <div class="col-lg-3 col-md-6">
          <div class="card">
            <div class="card-header border-0">
              <p class="card-title font-weight-bold">اطلاعات محصول</p>
            </div>
            <div class="card-body">
              <div class="row">
    
                <div class="col-12">
                  <div class="d-flex align-items-center justify-content-between my-1">
                    <span class="fs-16 font-weight-bold ml-1">شناسه محصول :</span>
                    <span class="fs-14 mr-1"> {{ $product->id }} </span>
                  </div>
                </div>
    
                <div class="col-12">
                  <div class="d-flex align-items-center justify-content-between my-1">
                    <span class="fs-16 font-weight-bold ml-1">عنوان :</span>
                    <span class="fs-14 mr-1"> {{ $product->title }} </span>
                  </div>
                </div>
    
                <div class="col-12">
                  <div class="d-flex align-items-center justify-content-between my-1">
                    <span class="fs-16 font-weight-bold ml-1">قیمت پایه (تومان) :</span>
                    <span class="fs-14 mr-1"> {{ number_format($product->price) }} </span>
                  </div>
                </div>
  
                <div class="col-12">
                  <div class="d-flex align-items-center justify-content-between my-1">
                    <span class="fs-16 font-weight-bold ml-1">تخفیف (تومان) :</span>
                    <span class="fs-14 mr-1"> {{ number_format($product->discount) }} </span>
                  </div>
                </div>
  
                <div class="col-12">
                  <div class="d-flex align-items-center justify-content-between my-1">
                    <span class="fs-16 font-weight-bold ml-1">وضعیت :</span>
                    <x-core::badge 
                      type="{{ $product->status ? 'success' : 'danger' }}" 
                      text="{{ $product->status ? 'فعال' : 'غیر فعال' }}" 
                    />
                  </div>
                </div>
    
                <div class="col-12">
                  <div class="d-flex align-items-center justify-content-between my-1">
                    <span class="fs-16 font-weight-bold ml-1">تاریخ ثبت :</span>
                    <span class="fs-14 mr-1"> {{ verta($product->created_at)->format('Y/m/d') }} </span>
                  </div>
                </div>
  
              </div>
            </div>
          </div>
        </div>
  
        <div class="col-lg-3 col-md-6">
          <div class="card">
      
            <div class="card-header border-0">
              <p class="card-title font-weight-bold">اطلاعات دسته بندی</p>
            </div>
    
            <div class="card-body">
              <div class="row">
    
                <div class="col-12">
                  <div class="d-flex align-items-center justify-content-between my-1">
                    <span class="fs-16 font-weight-bold ml-1">شناسه دسته بندی :</span>
                    <span class="fs-14 mr-1"> {{ $product->category->id }} </span>
                  </div>
                </div>
    
                <div class="col-12">
                  <div class="d-flex align-items-center justify-content-between my-1">
                    <span class="fs-16 font-weight-bold ml-1">عنوان :</span>
                    <span class="fs-14 mr-1"> {{ $product->category->title }} </span>
                  </div>
                </div>
    
                <div class="col-12">
                  <div class="d-flex align-items-center justify-content-between my-1">
                    <span class="fs-16 font-weight-bold ml-1"> والد:</span>
                    <span class="fs-14 mr-1"> {{ $product->category->parent->title }} </span>
                  </div>
                </div>
  
                <div class="col-12">
                  <div class="d-flex align-items-center justify-content-between my-1">
                    <span class="fs-16 font-weight-bold ml-1"> نوع واحد:</span>
                    <span class="fs-14 mr-1"> {{ $product->category->getUnitType() }} </span>
                  </div>
                </div>
  
                <div class="col-12">
                  <div class="d-flex align-items-center justify-content-between my-1">
                    <span class="fs-16 font-weight-bold ml-1">وضعیت :</span>
                    <x-core::badge 
                      type="{{ $product->category->status ? 'success' : 'danger' }}" 
                      text="{{ $product->category->status ? 'فعال' : 'غیر فعال' }}" 
                    />
                  </div>
                </div>
    
                <div class="col-12">
                  <div class="d-flex align-items-center justify-content-between my-1">
                    <span class="fs-16 font-weight-bold ml-1">تاریخ ثبت :</span>
                    <span class="fs-14 mr-1"> {{ verta($product->category->created_at)->format('Y/m/d') }} </span>
                  </div>
                </div>
  
              </div>
            </div>
      
          </div>
        </div>

      </div>

    </div>
    
  </div>
@endsection 