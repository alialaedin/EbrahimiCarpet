@extends('admin.layouts.master')
@section('content')

<div class="page-header">
  <x-core::breadcrumb
    :items="[
      ['title' => 'لیست خرید ها', 'route_link' => 'admin.purchases.index'],
      ['title' => 'ویرایش خرید'],
    ]"
  />
</div>

<x-core::card>
  <x-slot name="cardTitle">ویرایش خرید</x-slot>
  <x-slot name="cardOptions"><x-core::card-options/></x-slot>
  <x-slot name="cardBody">
    <form action="{{ route('admin.purchases.update', $purchase) }}" method="POST" class="save">
      @csrf
      @method('PATCH')
      <div class="row">
        <div class="col-lg-4 col-md-6">
          <div class="form-group">
            <label for="supplier_id" class="control-label">انتخاب تامین کننده :<span class="text-danger">&starf;</span></label>
            <select name="supplier_id" id="supplier_id" class="form-control">
              <option value="" class="text-muted">-- تامین کننده را انخاب کنید --</option>
              @foreach ($suppliers as $supplier)
                <option
                  value="{{ $supplier->id }}"
                  @selected(old("supplier_id", $purchase->supplier_id) == $supplier->id)>
                  {{ $supplier->name .' - '. $supplier->mobile }}
                </option>
              @endforeach
            </select>
            <x-core::show-validation-error name="supplier_id" />
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="form-group">
            <label for="purchased_date_show" class="control-label">تاریخ خرید :<span class="text-danger">&starf;</span></label>
            <input class="form-control fc-datepicker" id="purchased_date_show" type="text" autocomplete="off" placeholder="تاریخ خرید را انتخاب کنید"/>
            <input name="purchased_at" id="purchased_date" type="hidden" value="{{ old("purchased_at", $purchase->purchased_at) }}"/>
            <x-core::show-validation-error name="purchased_at" />
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="form-group">
            <label for="discount" class="control-label"> تخفیف کلی (ریال): </label>
            @php
              $discount = $purchase->discount ? number_format($purchase->discount) : null;
            @endphp
            <input type="text" id="discount" class="form-control comma" name="discount" placeholder="تخفیف را به ریال وارد کنید" value="{{ old('discount', $discount) }}">
            <x-core::show-validation-error name="discount" />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12 text-center mb-5 bg-black-8 text-white-80 py-3 rounded" >
          <span class="fs-16">جمع مبلغ کل فاکتور : </span>
          <span class="font-weight-bold fs-16" id="totalPrice"></span>
          <span class="font-weight-bold fs-16">ریال</span>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <div class="text-center">
            <button class="btn btn-warning" type="submit">بروزرسانی</button>
          </div>
        </div>
      </div>
    </form>
  </x-slot>
</x-core::card>

@endsection

@section('scripts')
  <x-core::date-input-script textInputId="purchased_date_show" dateInputId="purchased_date"/>

  <script>

    const discountInput = $('#discount');
    const totalItemsAmount = parseInt(@json($purchase->total_items_amount_with_discount));

    function calculateTotalAmount() {
      let discountAmount = discountInput.val() != null && discountInput.val().length > 0 ? parseInt(discountInput.val().replace(/,/g, '')) : 0;
      let finalPrice = (totalItemsAmount - discountAmount).toLocaleString();
      $('#totalPrice').text(finalPrice);
    }

    $(document).ready(() => {
      calculateTotalAmount();
      discountInput.on('input', calculateTotalAmount);
      costOfSewingInput.on('input', calculateTotalAmount);
    });

  </script>

@endsection
