@extends('admin.layouts.master')
@section('content')
  <div class="col-12">
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
        <li class="breadcrumb-item active">
          <a>جزئیات خرید</a>
        </li>
      </ol>
    </div>

    <div class="row">

      <div class="card">

          <div class="card-header border-0">
            <p class="card-title ml-2">اطلاعات خرید</p>
          </div>

          <div class="card-body">
            <div class="row">

              <div class="col-xl-4 col-md-6 col-12">
                <div class="d-flex align-items-center my-1">
                  <span class="fs-16 font-weight-bold ml-1">شناسه خرید :</span>
                  <span class="fs-14 mr-1"> {{ $purchase->id }} </span>
                </div>
              </div>

              <div class="col-xl-4 col-md-6 col-12">
                <div class="d-flex align-items-center my-1">
                  <span class="fs-16 font-weight-bold ml-1">تامین کننده :</span>
                  <a href="{{ route('admin.suppliers.show', $purchase->supplier) }}" class="fs-14 mr-1"> {{ $purchase->supplier->name }} </a>
                </div>
              </div>

              <div class="col-xl-4 col-md-6 col-12">
                <div class="d-flex align-items-center my-1">
                  <span class="fs-16 font-weight-bold ml-1">شماره موبایل :</span>
                  <span class="fs-14 mr-1"> {{ $purchase->supplier->mobile }} </span>
                </div>
              </div>


              <div class="col-xl-4 col-md-6 col-12">
                <div class="d-flex align-items-center my-1">
                  <span class="fs-16 font-weight-bold ml-1">قیمت کل خرید :</span>
                  <span class="fs-14 mr-1"> {{ number_format($purchase->getTotalPurchaseAmount()) }} تومان</span>
                </div>
              </div>

              <div class="col-xl-4 col-md-6 col-12">
                <div class="d-flex align-items-center my-1">
                  <span class="fs-16 font-weight-bold ml-1">تخفیف کل خرید :</span>
                  @if ($purchase->discount)
                    <span class="fs-14 mr-1"> {{ number_format($purchase->discount) }} تومان</span>
                  @else
                    <span class="fs-14 mr-1 text-danger"> بدون تخفیف </span>
                  @endif
                </div>
              </div>

              <div class="col-xl-4 col-md-6 col-12">
                <div class="d-flex align-items-center my-1">
                  <span class="fs-16 font-weight-bold ml-1">تاریخ خرید :</span>
                  <span class="fs-14 mr-1"> {{ verta($purchase->purchased_at)->format('Y/m/d') }} </span>
                </div>
              </div>

            </div>
          </div>

      </div>

      <div class="card">

          <div class="card-header border-0 justify-content-between ">
            <div class="d-flex">
              <p class="card-title ml-2">اقلام خرید <span class="fs-15 ">({{ $purchase->items->count() }})</span></p>
              
              <div class="card-options">
                <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                <a href="#" class="card-options-fullscreen" data-toggle="card-fullscreen"><i class="fe fe-maximize"></i></a>
                <a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
              </div>
            </div>
            <button class="btn btn-indigo" data-target="#createPurchaseItemModal" data-toggle="modal">
              افزودن قلم جدید
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
                        <th class="text-center border-top">نام محصول</th>
                        <th class="text-center border-top">تصویر</th>
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
                          <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                          <td class="text-center">
                            <a href="{{ route('admin.products.show', $item->product) }}">
                              {{ $item->product->title }}
                            </a>
                          </td>
                          <td class="text-center m-0 p-0">
                            @if ($item->product->image)
                              <figure class="figure my-2">
                                <a target="_blank" href="{{ Storage::url($item->product->image) }}">
                                  <img 
                                    src="{{ Storage::url($item->product->image) }}" 
                                    class="img-thumbnail" 
                                    alt="image"
                                    width="50" 
                                    style="max-height: 32px;"
                                  />
                                </a>
                              </figure>
                            @else
                              <span> - </span>
                            @endif
                          </td>
                          <td class="text-center">{{ $item->quantity }}</td>
                          <td class="text-center">{{ number_format($item->price) }}</td>
                          <td class="text-center">{{ number_format($item->discount) }}</td>
                          <td class="text-center">{{ number_format($item->getPriceWithDiscount()) }}</td>
                          <td class="text-center">
                            @can('edit purchase_items')
                              <button
                                class="btn btn-sm btn-icon btn-warning"
                                data-target="#editPurchaseItemModal{{$item->id}}"
                                data-toggle="modal"
                                data-original-title="ویرایش">
                                <i class="fa fa-pencil"></i>
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
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
      </div>

    </div>
      
  </div>

  @include('purchase::includes._create-purchase-item-modal')
  @include('purchase::includes._edit-purchase-item-modal')

@endsection