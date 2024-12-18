@extends('admin.layouts.master')
@section('content')

<div class="page-header">

  <x-core::breadcrumb :items="[
    ['title' => 'لیست فروش ها', 'route_link' => 'admin.sales.index'],
    ['title' => 'جزئیات فروش']
  ]"/>

  <div class="d-flex align-items-center flex-wrap text-nowrap">
    @can('edit sales')
      <a href="{{ route('admin.sales.edit', $sale) }}" class="btn btn-warning btn-sm mx-1">
        ویرایش
        <i class="fa fa-pencil mr-1"></i>
      </a>
    @endcan
    @can('delete sales')
      <button
        onclick="confirmDelete('delete-{{ $sale->id }}')"
        class="btn btn-danger btn-sm mx-1">
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

<div class="row">
  <div class="col-xl-6">
    <x-core::card>
      <x-slot name="cardTitle">اطلاعات مشتری</x-slot>
      <x-slot name="cardOptions"><x-core::card-options/></x-slot>
      <x-slot name="cardBody">
        <ul class="list-group">
          <li class="list-group-item"><strong>کد: </strong> {{ $sale->customer->id }} </li>
          <li class="list-group-item">
            <strong>نام و نام خانوادگی: </strong>
            <a href="{{ route('admin.customers.show', $sale->customer) }}">{{ $sale->customer->name }}</a>
          </li>
          <li class="list-group-item"><strong>شماره موبایل: </strong> {{ $sale->customer->mobile }} </li>
          <li class="list-group-item"><strong>آدرس: </strong> {{ $sale->customer->address }} </li>
        </ul>
      </x-slot>
    </x-core::card>
  </div>
  <div class="col-xl-6">
    <x-core::card>
      <x-slot name="cardTitle">اطلاعات کارمند</x-slot>
      <x-slot name="cardOptions"><x-core::card-options/></x-slot>
      <x-slot name="cardBody">
        <ul class="list-group">
          <li class="list-group-item"><strong>کد: </strong> {{ $sale->employee->id }} </li>
          <li class="list-group-item">
            <strong>نام و نام خانوادگی: </strong>
            <a href="{{ route('admin.employees.show', $sale->employee) }}">{{ $sale->employee->name }}</a>
          </li>
          <li class="list-group-item"><strong>شماره موبایل: </strong> {{ $sale->employee->mobile }} </li>
          <li class="list-group-item"><strong>آدرس: </strong> {{ $sale->employee->address }} </li>
        </ul>
      </x-slot>
    </x-core::card>
  </div>
</div>

  <div class="row">
    <div class="col-xl-3 col-lg-6 col-md-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-9">
              <div class="mt-0 text-right">
                <span class="fs-14 font-weight-bold"> جمع اقلام : </span>
                <h3 class="mb-0 mt-1 text-info fs-16"> {{ number_format($sale->getTotalAmount() - $sale->cost_of_sewing) }} ریال</h3>
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
                <span class="fs-14 font-weight-bold"> هزینه دوخت / نصب : </span>
                <h3 class="mb-0 mt-1 text-warning fs-16"> {{ number_format($sale->cost_of_sewing) }} ریال</h3>
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
                <span class="fs-14 font-weight-bold"> تخفیف کل فاکتور : </span>
                <h3 class="mb-0 mt-1 text-danger fs-16"> {{ number_format($sale->discount) }} ریال</h3>
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
                <span class="fs-14 font-weight-bold"> قیمت کل فاکتور : </span>
                <h3
                  class="mb-0 mt-1 text-success fs-16"> {{ number_format($sale->getTotalAmountWithDiscount()) }} ریال</h3>
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

  <x-core::card>
    <x-slot name="cardTitle">اقلام فروش ({{ $sale->items->count() }})</x-slot>
    <x-slot name="cardOptions">
      <div class="card-options">
        @can('create sale_items')
          <button class="btn btn-indigo btn-sm" data-target="#createSaleItemModal" data-toggle="modal">
            افزودن قلم جدید
            <i class="fa fa-plus mr-1"></i>
          </button>
        @endcan
      </div>
    </x-slot>
    <x-slot name="cardBody">
      <x-core::table>
        <x-slot name="tableTh">
          <tr>
            <th>ردیف</th>
            <th>نام محصول</th>
            <th>تصویر</th>
            <th>نوع واحد</th>
            <th>تعداد</th>
            <th>قیمت واحد (ریال)</th>
            <th>تخفیف (ریال)</th>
            <th>قیمت با تخفیف (ریال)</th>
            <th>قیمت کل (ریال)</th>
            <th>حذف</th>
          </tr>
        </x-slot>
        <x-slot name="tableTd">
          @foreach ($sale->items as $item)
            <tr>
              <td class="font-weight-bold">{{ $loop->iteration }}</td>
              <td>
                <a href="{{ route('admin.products.show', $item->product) }}" target="_blank">{{ $item->product->title .' '. $item->product->sub_title }}</a>
              </td>
              <td class="m-0 p-0">
                @if ($item->product->parent->image)
                  <figure class="figure my-2">
                    <a target="_blank" href="{{ Storage::url($item->product->parent->image) }}">
                      <img
                        src="{{ Storage::url($item->product->parent->image) }}"
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
              <td>{{ $item->product->category->getUnitType() }}</td>
              <td>{{ $item->quantity }}</td>
              <td>{{ number_format($item->price) }}</td>
              <td>{{ number_format($item->discount) }}</td>
              <td>{{ number_format($item->getPriceWithDiscount()) }}</td>
              <td>{{ number_format($item->getTotalItemPrice()) }}</td>
              <td>
                @can('delete sale_items')
                  <x-core::delete-button route="admin.sale-items.destroy" :model="$item"/>
                @endcan
              </td>
            </tr>
          @endforeach
        </x-slot>
      </x-core::table>
    </x-slot>
  </x-core::card>

  @include('sale::sale.includes._create-purchase-item-modal')

@endsection
