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
        <a href="{{ route('admin.purchases.index') }}">لیست خرید ها</a>
      </li>
      <li class="breadcrumb-item active">
        <a>جزئیات خرید</a>
      </li>
    </ol>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
      @can('edit purchases')
        <a href="{{ route('admin.purchases.edit', $purchase) }}" class="btn btn-warning mx-1">
          ویرایش خرید
          <i class="fa fa-pencil"></i>
        </a>
      @endcan
      @can('delete purchases')
        <button
          onclick="confirmDelete('delete-{{ $purchase->id }}')"
          class="btn btn-danger mx-1">
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
        <button class="btn btn-indigo mx-1" data-target="#createPurchaseItemModal" data-toggle="modal">
          افزودن قلم جدید
          <i class="fa fa-plus font-weight-bolder"></i>
        </button>
      @endcan
    </div>
  </div>
  <div class="card">
    <div class="card-header border-0">
      <p class="card-title">اطلاعات خرید</p>
      <x-core::card-options/>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-xl-4 col-md-6 col-12 fs-17 my-1">
          <span><strong>تامین کننده : </strong><a href="{{ route('admin.suppliers.show', $purchase->supplier) }}" class="fs-14 mr-1"> {{ $purchase->supplier->name }} </a></span>
        </div>
        <div class="col-xl-4 col-md-6 col-12 fs-17 my-1">
          <span><strong>شماره موبایل : </strong>{{ $purchase->supplier->mobile }}</span>
        </div>
        <div class="col-xl-4 col-md-6 col-12 fs-17 my-1">
          <span><strong>تاریخ خرید : </strong>{{ verta($purchase->purchased_at)->format('Y/m/d') }}</span>
        </div>
        <div class="col-xl-4 col-md-6 col-12 fs-17 my-1">
          <span><strong>قیمت کل خرید : </strong>{{ number_format($purchase->getTotalPurchaseAmount()) }} ریال</span>
        </div>
        <div class="col-xl-4 col-md-6 col-12 fs-17 my-1">
          <span><strong>تخفیف کل خرید : </strong>{{ number_format($purchase->discount) }} ریال</span>
        </div>
        <div class="col-xl-4 col-md-6 col-12 fs-17 my-1">
          <span><strong>قیمت با تخفیف : </strong>{{ number_format($purchase->getTotalPurchaseAmount() - $purchase->discount) }} ریال</span>
        </div>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header border-0 ">
      <p class="card-title">اقلام خرید ({{ $purchase->items->count() }})</p>
      <x-core::card-options/>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <div class="dataTables_wrapper dt-bootstrap4 no-footer">
          <div class="row">
            <table class="table table-vcenter table-striped text-nowrap table-bordered border-bottom">
              <thead class="thead-light">
              <tr>
                <th class="text-center border-top">ردیف</th>
                <th class="text-center border-top">نام محصول</th>
                <th class="text-center border-top">تصویر</th>
                <th class="text-center border-top">نوع واحد</th>
                <th class="text-center border-top">تعداد</th>
                <th class="text-center border-top">قیمت (ریال)</th>
                <th class="text-center border-top">تخفیف (ریال)</th>
                <th class="text-center border-top">قیمت با تخفیف (ریال)</th>
                <th class="text-center border-top">قیمت کل (ریال)</th>
                <th class="text-center border-top">عملیات</th>
              </tr>
              </thead>
              <tbody>
              @forelse ($purchase->items as $item)
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
                    {{-- @can('edit purchase_items')
                      <button
                        class="btn btn-sm btn-icon btn-warning"
                        data-target="#editPurchaseItemModal{{$item->id}}"
                        data-toggle="modal"
                        data-original-title="ویرایش">
                        <i class="fa fa-pencil"></i>
                      </button>
                    @endcan --}}
                    @can('delete purchase_items')
                      <x-core::delete-button route="admin.purchase-items.destroy" :model="$item"/>
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
  @include('purchase::includes._create-purchase-item-modal')
  {{-- @include('purchase::includes._edit-purchase-item-modal') --}}
@endsection
