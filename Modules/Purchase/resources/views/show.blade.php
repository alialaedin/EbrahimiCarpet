@extends('admin.layouts.master')
@section('content')
  <div class="col-12">

    <div class="col-xl-12 col-md-12 col-lg-12">

      <div class="page-header">
        <x-core::breadcrumb :items="$breadcrumbItems" />
    	</div>     
      
      <div class="row">
        <div class="card">
  
          <div class="card-header border-0 justify-content-between ">
            <div class="d-flex">
              <p class="card-title ml-2" style="font-weight: bolder;">اطلاعات خرید</p>
            </div>
          </div>
  
          <div class="card-body">
            <div class="row">
  
              <div class="col-lg-4 col-md-6 col-12">
                <div class="d-flex align-items-center my-1">
                  <span class="fs-16 font-weight-bold text-muted ml-1">شناسه خرید :</span>
                  <span class="fs-14 mr-1"> {{ $purchase->id }} </span>
                </div>
              </div>

              <div class="col-lg-4 col-md-6 col-12">
                <div class="d-flex align-items-center my-1">
                  <span class="fs-16 font-weight-bold text-muted ml-1">تامین کننده :</span>
                  <a href="{{ route('admin.suppliers.show', $purchase->supplier) }}" class="fs-14 mr-1"> {{ $purchase->supplier->name }} </a>
                </div>
              </div>
  
              <div class="col-lg-4 col-md-6 col-12">
                <div class="d-flex align-items-center my-1">
                  <span class="fs-16 font-weight-bold text-muted ml-1">شماره موبایل :</span>
                  <span class="fs-14 mr-1"> {{ $purchase->supplier->mobile }} </span>
                </div>
              </div>

  
              <div class="col-lg-4 col-md-6 col-12">
                <div class="d-flex align-items-center my-1">
                  <span class="fs-16 font-weight-bold text-muted ml-1">قیمت کل خرید :</span>
                  <span class="fs-14 mr-1"> {{ number_format($purchase->getTotalPurchaseAmount()) }} تومان</span>
                </div>
              </div>

              <div class="col-lg-4 col-md-6 col-12">
                <div class="d-flex align-items-center my-1">
                  <span class="fs-16 font-weight-bold text-muted ml-1">تخفیف کل خرید :</span>
                  @if ($purchase->discount)
                    <span class="fs-14 mr-1"> {{ number_format($purchase->discount) }} تومان</span>
                  @else
                    <span class="fs-14 mr-1 text-danger"> بدون تخفیف </span>
                  @endif
                </div>
              </div>
  
              <div class="col-lg-4 col-md-6 col-12">
                <div class="d-flex align-items-center my-1">
                  <span class="fs-16 font-weight-bold text-muted ml-1">تاریخ خرید :</span>
                  <span class="fs-14 mr-1"> {{ verta($purchase->purchased_at)->format('Y/m/d') }} </span>
                </div>
              </div>
      
            </div>
          </div>
  
        </div>

        <div class="card">

          <div class="card-header border-0 justify-content-between ">
            <div class="d-flex">
              <p class="card-title ml-2" style="font-weight: bolder;">آیتم های خرید</p>
              <span class="fs-15 ">({{ $purchase->items->count() }})</span>
            </div>
            <button class="btn btn-indigo" data-target="#createPurchaseItemModal" data-toggle="modal">
              افزودن آیتم جدید
              <i class="fa fa-plus font-weight-bolder"></i>
            </button>
          </div>
          
          <div class="card-body">
            <div class="table-responsive">
              <div id="hr-table-wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <div class="row">
                  <table class="table table-vcenter text-nowrap table-bordered border-bottom" id="hr-table">
                    <thead class="thead-light">
                      <tr>
                        <th class="text-center border-top">ردیف</th>
                        <th class="text-center border-top">دسته بندی محصول</th>
                        <th class="text-center border-top">نام محصول</th>
                        <th class="text-center border-top">تعداد</th>
                        <th class="text-center border-top">قیمت (تومان)</th>
                        <th class="text-center border-top">تخفیف (تومان)</th>
                        <th class="text-center border-top">قیمت با تخفیف (تومان)</th>
                        <th class="text-center border-top">عملیات</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($purchase->items as $item)
                        <tr>
                          <td class="text-center">{{ $loop->iteration }}</td>
                          <td class="text-center">{{ $item->product->category->title }}</td>
                          <td class="text-center">
                            <a href="{{ route('admin.products.show', $item->product) }}">
                              {{ $item->product->title }}
                            </a>
                          </td>
                          <td class="text-center">{{ $item->quantity }}</td>
                          <td class="text-center">{{ number_format($item->price) }}</td>
                          <td class="text-center">
                            @if ($item->discount)
                              <span>{{ number_format($item->discount) }}</span>
                            @else
                              <span class="text-danger">ندارد</span>                                
                            @endif
                          </td>
                          <td class="text-center">{{ number_format($item->getPriceWithDiscount()) }}</td>
                          <td class="text-center">
                            @can('edit purchase_items')
                            <button class="btn btn-sm btn-icon btn-warning" data-target="#editPurchaseItemModal{{$item->id}}" data-toggle="modal">
                              <i class="fa fa-pencil" data-toggle="tooltip" data-original-title="ویرایش"></i>
                            </button>
                            @endcan
                            @can('delete purchase_items')
                              <x-core::delete-button route="admin.purchase-items.destroy" :model="$item"/>
                            @endcan
                          </td>
                        </tr>
                        @empty
                          <x-core::data-not-found-alert :colspan="8"/>
                      @endforelse

                      {{-- @php
                        $totalItemsPrice = $purchase->items->sum('price');
                        $totalItemsDiscount = $purchase->items->whereNotNull('discount')->sum('discount');
                      @endphp

                      <tr>
                        <td class="text-center font-weight-bold" colspan="4">جمع کل :</td>
                        <td class="text-center"> {{ number_format($totalItemsPrice) }} </td>
                        <td class="text-center"> {{ number_format($totalItemsDiscount) }} </td>
                        <td class="text-center"> {{ number_format($totalItemsPrice - $totalItemsDiscount) }} </td>
                      </tr> --}}

                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @include('purchase::_create-purchase-item-modal')
  @include('purchase::_edit-purchase-item-modal')

@endsection 