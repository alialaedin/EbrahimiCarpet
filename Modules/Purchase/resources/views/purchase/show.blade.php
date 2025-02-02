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
          <li class="list-group-item"><strong>قیمت کل خرید : </strong> {{ number_format($purchase->total_items_amount_with_discount) }} ریال</li>
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
              @can('edit purchase_items')
                <x-core::edit-button target="#edit-item-modal-{{ $item->id }}"/>
              @endcan
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

    <div class="row mx-4 justify-content-center" style="margin-top: 50px">
      <div class="col-12 col-xl-4">
        <div class="card shadow-lg">
          <div class="card-body">
            <div class="row">
              <div class="col-12 my-1 d-flex justify-content-between align-items-center">
                <b>مجموع تعداد کالا ها</b>
                <span>{{ $purchase->items->sum('quantity') }}</span>
              </div>
              <div class="col-12 my-1 d-flex justify-content-between align-items-center">
                <b>مجموع قیمت کالا ها</b>
                <span>{{ number_format($purchase->total_items_amount) }} ریال</span>
              </div>
              <div class="col-12 my-1 d-flex justify-content-between align-items-center">
                <b>مجموع تخفیف روی کالا ها</b>
                <span>{{ number_format($purchase->total_items_discount_amount) }} ریال</span>
              </div>
              <div class="col-12 my-1 d-flex justify-content-between align-items-center">
                <b>تخفیف روی سفارش</b>
                <span>{{ number_format($purchase->discount) }} ریال</span>
              </div>
              <div class="col-12 my-1 d-flex justify-content-between align-items-center">
                <b>جمع کل</b>
                <span>{{ number_format($purchase->total_amount) }} ریال</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </x-slot>
</x-core::card>

@foreach ($purchase->items ?? [] as $item)
  <div class="modal fade" id="edit-item-modal-{{ $item->id }}" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content modal-content-demo">
        <div class="modal-header">
          <p class="modal-title" style="font-size: 20px;">ویرایش آیتم خرید</p>
          <button aria-label="Close" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('admin.purchase-items.update', $item) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="row mb-4">
              <div class="col-12">
                <p class="text-center fs-14 text-gray-200 bg-warning py-2"">تمام اینپوت ها اختیاری هستند</p>
              </div>
            </div>

            <x-core::table class="table-bordered">
              <x-slot name="tableTh">
                <tr>
                  <th>ویژگی</th>
                  <th>مقدار فعلی</th>
                  <th>مقدار جدید</th>
                </tr>
              </x-slot>
              <x-slot name="tableTd">
                <tr>
                  <td>تعداد</td>
                  <td>{{ $item->quantity }}</td>
                  <td><input type="number" class="form-control" name="quantity"></td>
                </tr>
                <tr>
                  <td>قیمت</td>
                  <td>{{ number_format($item->price) }} ریال</td>
                  <td><input type="text" class="form-control comma" name="price"></td>   
                </tr>
                <tr>
                  <td>تخفیف</td>
                  <td>{{ number_format($item->discount) }} ریال</td>
                  <td><input type="text" class="form-control comma" name="discount"></td>  
                </tr>
              </x-slot>
            </x-core::table>

            <div class="row">
              <div class="col-12 d-flex justify-content-center" style="gap: 8px">
                <button class="btn btn-sm btn-warning" type="submit">بروزرسانی</button>
                <button class="btn btn-sm btn-outline-danger" data-dismiss="modal">انصراف</button>
              </div>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
@endforeach

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
