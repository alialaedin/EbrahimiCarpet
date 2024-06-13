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
          <a href="{{ route('admin.sales.index') }}">لیست فروش ها</a>
        </li>
        <li class="breadcrumb-item active">
          <a>جزئیات فروش</a>
        </li>
      </ol>

      <div class="d-flex align-items-center flex-wrap text-nowrap">
        @can('edit sales')
          <a href="{{ route('admin.sales.edit', $sale) }}" class="btn btn-warning mx-1">
            ویرایش فروش
            <i class="fa fa-pencil"></i>
          </a>
        @endcan
        @can('create sale_items')
          <button class="btn btn-indigo" data-target="#createSaleItemModal" data-toggle="modal">
            افزودن قلم جدید
            <i class="fa fa-plus font-weight-bolder"></i>
          </button>
        @endcan
      </div>
    </div>

    <div class="card">

      <div class="card-header border-0">
        <p class="card-title">اطلاعات مشتری</p>
        <x-core::card-options/>
      </div>

      <div class="card-body">
        <div class="row">
          <div class="col-xl-4 col-md-6 col-12 fs-17 my-1">
            <span><strong>شناسه مشتری : </strong>{{ $sale->customer->id }}</span>
          </div>
          <div class="col-xl-4 col-lg-6 col-12 fs-17 my-1">
            <span>
              <strong>نام و نام خانوادگی : </strong>
              <a href="{{ route('admin.customers.show', $sale->customer) }}" class="fs-14">
                {{ $sale->customer->name }}
              </a>
            </span>
          </div>
          <div class="col-xl-4 col-lg-6 col-12 fs-17 my-1">
            <span><strong>شماره موبایل : </strong>{{ $sale->customer->mobile }}</span>
          </div>
          <div class="col-xl-4 col-lg-6 col-12 fs-17 my-1">
            <span><strong>تلفن ثابت : </strong>{{ $sale->customer->telephone }}</span>
          </div>
          <div class="col-xl-4 col-lg-6 col-12 fs-17 my-1">
            <span>
              <strong>وضعیت : </strong>
              <x-core::badge
                type="{{ $sale->customer->status ? 'success' : 'danger' }}"
                text="{{ $sale->customer->status ? 'فعال' : 'غیر فعال' }}"
              />
            </span>
          </div>
          <div class="col-xl-4 col-lg-6 col-12 fs-17 my-1">
            <span><strong>آدرس : </strong>{{ $sale->customer->address }}</span>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-xl-4 col-lg-6 col-md-12">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-9">
                <div class="mt-0 text-right">
                  <span class="fs-16 font-weight-semibold"> قیمت کل فروش (تومان) : </span>
                  <h3 class="mb-0 mt-1 text-info fs-20"> {{ number_format($sale->getTotalAmount()) }} </h3>
                </div>
              </div>
              <div class="col-3">
                <div class="icon1 bg-info-transparent my-auto float-left">
                  <i class="fa fa-money"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-4 col-lg-6 col-md-12">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-9">
                <div class="mt-0 text-right">
                  <span class="fs-16 font-weight-semibold"> تخفیف کل فروش (تومان) : </span>
                  <h3 class="mb-0 mt-1 text-danger fs-20"> {{ number_format($sale->discount) }} </h3>
                </div>
              </div>
              <div class="col-3">
                <div class="icon1 bg-danger-transparent my-auto float-left">
                  <i class="fa fa-money"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-4 col-lg-6 col-md-12">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-9">
                <div class="mt-0 text-right">
                  <span class="fs-16 font-weight-semibold"> قیمت کل با تخفیف (تومان) : </span>
                  <h3 class="mb-0 mt-1 text-success fs-20"> {{ number_format($sale->getTotalAmountWithDiscount()) }}  </h3>
                </div>
              </div>
              <div class="col-3">
                <div class="icon1 bg-success-transparent my-auto float-left">
                  <i class="fa fa-money"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header border-0 justify-content-between ">
        <div class="d-flex">
          <p class="card-title ml-2">اقلام خرید <span class="fs-15 ">({{ $sale->items->count() }})</span></p>
          <x-core::card-options/>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <div class="dataTables_wrapper dt-bootstrap4 no-footer">
            <div class="row">
              <table class="table table-vcenter text-nowrap table-bordered border-bottom">
                <thead class="thead-light">
                  <tr>
                    <th class="text-center border-top">ردیف</th>
                    <th class="text-center border-top">نام محصول</th>
                    <th class="text-center border-top">تصویر</th>
                    <th class="text-center border-top">نوع واحد</th>
                    <th class="text-center border-top">تعداد</th>
                    <th class="text-center border-top">قیمت واحد (تومان)</th>
                    <th class="text-center border-top">تخفیف (تومان)</th>
                    <th class="text-center border-top">قیمت با تخفیف (تومان)</th>
                    <th class="text-center border-top">قیمت کل (تومان)</th>
                    <th class="text-center border-top">عملیات</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($sale->items as $item)
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
                      <td class="text-center">{{ $item->product->category->getUnitType() }}</td>
                      <td class="text-center">{{ $item->quantity }}</td>
                      <td class="text-center">{{ number_format($item->price) }}</td>
                      <td class="text-center">{{ number_format($item->discount) }}</td>
                      <td class="text-center">{{ number_format($item->getPriceWithDiscount()) }}</td>
                      <td class="text-center">{{ number_format($item->getTotalItemPrice()) }}</td>
                      <td class="text-center">
                        @can('edit sale_items')
                          <button
                            class="btn btn-sm btn-icon btn-warning"
                            data-target="#editSaleItemModal{{$item->id}}"
                            data-toggle="modal"
                            data-original-title="ویرایش">
                            <i class="fa fa-pencil"></i>
                          </button>
                        @endcan
                        @can('delete sale_items')
                          <x-core::delete-button route="admin.sale-items.destroy" :model="$item"/>
                        @endcan
                      </td>
                    </tr>
                    @empty
                      <x-core::data-not-found-alert :colspan="9"/>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  @include('sale::sale.includes._create-purchase-item-modal')
  @include('sale::sale.includes._edit-purchase-item-modal')

@endsection
