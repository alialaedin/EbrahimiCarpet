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
        <a href="{{ route('admin.sales.index') }}">لیست فروش ها</a>
      </li>
      <li class="breadcrumb-item active">
        <a>جزئیات فروش</a>
      </li>
    </ol>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
      @can('edit sales')
        <a href="{{ route('admin.sales.edit', $sale) }}" class="btn btn-warning mx-1">
          ویرایش
          <i class="fa fa-pencil mr-1"></i>
        </a>
      @endcan
      @can('delete sales')
        <button
          onclick="confirmDelete('delete-{{ $sale->id }}')"
          class="btn btn-danger mx-1">
          حذف کل سفارش<i class="fa fa-trash-o mr-2"></i>
        </button>
        <form
          action="{{ route('admin.sales.destroy', $sale) }}"
          method="POST"
          id="delete-{{ $sale->id }}"
          style="display: none">
          @csrf
          @method('DELETE')
        </form>
      @endcan

    </div>
  </div>
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-lg-6">
          <p class="header fs-20 p-2 pr-2">اطلاعات مشتری</p>
          <ul class="list-group">
            <li class="list-group-item"><strong>کد: </strong> {{ $sale->customer->id }} </li>
            <li class="list-group-item">
              <strong>نام و نام خانوادگی: </strong>
              <a href="{{ route('admin.customers.show', $sale->customer) }}">{{ $sale->customer->name }}</a>
            </li>
            <li class="list-group-item"><strong>شماره موبایل: </strong> {{ $sale->customer->mobile }} </li>
            <li class="list-group-item"><strong>آدرس: </strong> {{ $sale->customer->address }} </li>
          </ul>
        </div>
        <div class="col-lg-6">
          <p class="header fs-20 p-2 pr-2">اطلاعات کارمند</p>
          <ul class="list-group">
            <li class="list-group-item"><strong>کد: </strong> {{ $sale->employee->id }} </li>
            <li class="list-group-item">
              <strong>نام و نام خانوادگی: </strong>
              <a href="{{ route('admin.employees.show', $sale->employee) }}">{{ $sale->employee->name }}</a>
            </li>
            <li class="list-group-item"><strong>شماره موبایل: </strong> {{ $sale->employee->mobile }} </li>
            <li class="list-group-item"><strong>آدرس: </strong> {{ $sale->employee->address }} </li>
          </ul>
        </div>
      </div>

    </div>
  </div>
  <div class="row">
    <div class="col-xl-3 col-lg-6 col-md-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-9">
              <div class="mt-0 text-right">
                <span class="fs-16 font-weight-semibold"> جمع اقلام (ریال) : </span>
                <h3 class="mb-0 mt-1 text-info fs-20"> {{ number_format($sale->getTotalAmount() - $sale->cost_of_sewing) }} </h3>
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
    <div class="col-xl-3 col-lg-6 col-md-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-9">
              <div class="mt-0 text-right">
                <span class="fs-16 font-weight-semibold"> هزینه دوخت / نصب (ریال) : </span>
                <h3 class="mb-0 mt-1 text-warning fs-20"> {{ number_format($sale->cost_of_sewing) }} </h3>
              </div>
            </div>
            <div class="col-3">
              <div class="icon1 bg-warning-transparent my-auto float-left">
                <i class="fa fa-money"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-9">
              <div class="mt-0 text-right">
                <span class="fs-16 font-weight-semibold"> تخفیف کل فاکتور (ریال) : </span>
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
    <div class="col-xl-3 col-lg-6 col-md-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-9">
              <div class="mt-0 text-right">
                <span class="fs-16 font-weight-semibold"> قیمت کل فاکتور (ریال) : </span>
                <h3
                  class="mb-0 mt-1 text-success fs-20"> {{ number_format($sale->getTotalAmountWithDiscount()) }}  </h3>
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
    <div class="card-header border-0 justify-content-between">
      <p class="card-title">اقلام فروش ({{ $sale->items->count() }})</p>
      @can('create sale_items')
        <button class="btn btn-indigo" data-target="#createSaleItemModal" data-toggle="modal">
          افزودن قلم جدید
          <i class="fa fa-plus mr-1"></i>
        </button>
      @endcan
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <div class="dataTables_wrapper dt-bootstrap4 no-footer">
          <div class="row">
            <table class="table table-vcenter table-striped text-nowrap table-bordered border-bottom">
              <thead class="thead-light">
              <tr>
                <th class="text-center">ردیف</th>
                <th class="text-center">نام محصول</th>
                <th class="text-center">تصویر</th>
                <th class="text-center">نوع واحد</th>
                <th class="text-center">تعداد</th>
                <th class="text-center">قیمت واحد (ریال)</th>
                <th class="text-center">تخفیف (ریال)</th>
                <th class="text-center">قیمت با تخفیف (ریال)</th>
                <th class="text-center">قیمت کل (ریال)</th>
                <th class="text-center">حذف</th>
              </tr>
              </thead>
              <tbody>
              @forelse ($sale->items as $item)
                <tr>
                  <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                  <td class="text-center">
                    <a href="{{ route('admin.products.show', $item->product) }}">
                      {{ $item->product->title .' '. $item->product->sub_title }}
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

  @include('sale::sale.includes._create-purchase-item-modal')

@endsection
