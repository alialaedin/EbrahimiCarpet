@extends('admin.layouts.master')
@section('content')

<div class="page-header">

  <x-core::breadcrumb
    :items="[
      ['title' => 'لیست خرید ها', 'route_link' => 'admin.purchases.index'],
      ['title' => 'جزئیات خرید'],
    ]"
  />

  <div class="d-flex align-items-center flex-wrap text-nowrap">
    @can('edit purchases')
      <a href="{{ route('admin.purchases.edit', $purchase) }}" class="btn btn-sm btn-warning mx-1">
        ویرایش خرید
        <i class="fa fa-pencil"></i>
      </a>
    @endcan
    @can('delete purchases')
      <button
        onclick="confirmDelete('delete-{{ $purchase->id }}')"
        class="btn btn-sm btn-danger mx-1">
        حذف خرید<i class="fa fa-trash-o mr-2"></i>
      </button>
      <form
        action="{{ route('admin.purchases.destroy', $purchase) }}"
        method="POST"
        id="delete-{{ $purchase->id }}"
        style="display: none">
        @csrf
        @method('DELETE')
      </form>
    @endcan
    @can('create purchase_items')
      <button class="btn btn-sm btn-indigo mx-1" data-target="#createPurchaseItemModal" data-toggle="modal">
        افزودن قلم جدید
        <i class="fa fa-plus font-weight-bolder"></i>
      </button>
    @endcan
  </div>

</div>

<div class="row">
  <div class="col-xl-6">
    <x-core::card>
      <x-slot name="cardTitle">اطلاعات خرید</x-slot>
      <x-slot name="cardOptions"><x-core::card-options/></x-slot>
      <x-slot name="cardBody">
        <ul class="list-group">
          <li class="list-group-item"><strong>قیمت کل خرید : </strong> {{ number_format($purchase->total_items_amount) }} ریال</li>
          <li class="list-group-item"><strong>تخفیف کل خرید : </strong> {{ number_format($purchase->discount) }} ریال</li>
          <li class="list-group-item"><strong>قیمت با تخفیف : </strong> {{ number_format($purchase->total_amount) }} ریال</li>
          <li class="list-group-item"><strong>تاریخ خرید : </strong> {{ verta($purchase->purchased_at)->format('Y/m/d') }} </li>
        </ul>
      </x-slot>
    </x-core::card>
  </div>
  <div class="col-xl-6">
    <x-core::card>
      <x-slot name="cardTitle">اطلاعات تامین کننده</x-slot>
      <x-slot name="cardOptions"><x-core::card-options/></x-slot>
      <x-slot name="cardBody">
        <ul class="list-group">
          <li class="list-group-item"><strong>تامین کننده : </strong> <a href="{{ route('admin.suppliers.show', $purchase->supplier) }}" class="fs-14 mr-1"> {{ $purchase->supplier->name }} </a> </li>
          <li class="list-group-item"><strong>شماره موبایل : </strong> {{ $purchase->supplier->mobile }} </li>
          <li class="list-group-item"><strong>کد ملی : </strong> {{ $purchase->supplier->national_code }} </li>
          <li class="list-group-item"><strong>کد پستی : </strong> {{ $purchase->supplier->postal_code }} </li>
        </ul>
      </x-slot>
    </x-core::card>
  </div>
</div>

<x-core::card>
  <x-slot name="cardTitle">اقلام خرید ({{ $purchase->items->count() }})</x-slot>
  <x-slot name="cardOptions"><x-core::card-options/></x-slot>
  <x-slot name="cardBody">
    <x-core::table>
      <x-slot name="tableTh">
        <tr>
          <th>ردیف</th>
          <th>نام محصول</th>
          <th>تصویر</th>
          <th>نوع واحد</th>
          <th>تعداد</th>
          <th>قیمت (ریال)</th>
          <th>تخفیف (ریال)</th>
          <th>قیمت با تخفیف (ریال)</th>
          <th>قیمت کل (ریال)</th>
          <th>عملیات</th>
        </tr>
      </x-slot>
      <x-slot name="tableTd">
        @forelse ($purchase->items as $item)
          <tr>
            <td class="font-weight-bold">{{ $loop->iteration }}</td>
            <td>
              <a href="{{ route('admin.products.show', $item->product) }}">
                {{ $item->product->title .' '. $item->product->sub_title }}
              </a>
            </td>
            <td class="m-0 p-0">
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
            <td>{{ $item->product->category->getUnitType() }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ number_format($item->price) }}</td>
            <td>{{ number_format($item->discount) }}</td>
            <td>{{ number_format($item->getPriceWithDiscount()) }}</td>
            <td>{{ number_format($item->getTotalItemPrice()) }}</td>
            <td>
              @can('delete purchase_items')
                <x-core::delete-button route="admin.purchase-items.destroy" :model="$item"/>
              @endcan
            </td>
          </tr>
        @empty
          <x-core::data-not-found-alert :colspan="9"/>
        @endforelse
      </x-slot>
    </x-core::table>
  </x-slot>
</x-core::card>

@include('purchase::includes._create-purchase-item-modal')

@endsection

@section('scripts')
  <script>
    $(document).ready(function() {
      $('#product_id').select2({
        placeholder: 'انتخاب محصول'
      });
    });
  </script>
@endsection
