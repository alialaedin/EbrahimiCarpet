@extends('admin.layouts.master')
@section('content')

  <div class="page-header">
    <x-core::breadcrumb :items="[
      ['title' => 'لیست فروش ها', 'route_link' => 'admin.sales.index'],
      ['title' => 'ویرایش فروش']
    ]"/>
  </div>

  <x-core::card>
    <x-slot name="cardTitle">ویرایش فروش - کد {{ $sale->id }} - مبلغ فروش : {{ number_format($sale->getTotalAmountWithDiscount()) . ' ریال'}}</x-slot>
    <x-slot name="cardOptions"><x-core::card-options/></x-slot>
    <x-slot name="cardBody">
      <form action="{{ route('admin.sales.update', $sale) }}" method="post" class="save">
        @csrf
        @method('PATCH')
        <div class="row">
          <div class="col-xl-4 col-lg-6">
            <div class="form-group">
              <label for="customer_id" class="control-label">نام مشتری :</label>
              <input type="text" class="form-control" id="customer_id" name="customer_id" value="{{ $sale->customer->name }}" readonly>
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="form-group">
              <label for="employee_id" class="control-label">پرسنل ارجاع :<span class="text-danger">&starf;</span></label>
              <select name="employee_id" id="employee_id" class="form-control select2" required>
                <option value="" class="text-muted">-- پرسنل را انخاب کنید --</option>
                @foreach ($employees as $employee)
                  <option value="{{ $employee->id }}" @selected(old("employee_id", $sale->employee_id) == $employee->id)>{{ $employee->name .' - '. $employee->mobile }}</option>
                @endforeach
              </select>
              <x-core::show-validation-error name="employee_id" />
            </div>
          </div>
          <div class="col-xl-4 col-lg-6">
            <div class="form-group">
              <label for="sold_date_show" class="control-label">تاریخ فروش :<span class="text-danger">&starf;</span></label>
              <input class="form-control fc-datepicker" id="sold_date_show" type="text" autocomplete="off" placeholder="تاریخ خرید را انتخاب کنید" />
              <input name="sold_at" id="sold_date" type="hidden" value="{{ old("sold_at", $sale->sold_at) }}" required/>
              <x-core::show-validation-error name="sold_at" />
            </div>
          </div>
          <div class="col-xl-4 col-lg-6">
            <div class="form-group">
              <label for="discount" class="control-label"> تخفیف کلی (ریال): </label>
              @php
                $discount = $sale->discount ? number_format($sale->discount) : null;
              @endphp
              <input type="text" id="discount" class="form-control comma" name="discount" placeholder="تخفیف را به ریال وارد کنید" value="{{ old('discount', $discount) }}">
              <x-core::show-validation-error name="discount" />
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="form-group">
              <label for="discount_for" class="control-label"> بابت تخفیف : </label>
              <input type="text" id="discount_for" class="form-control" name="discount_for" placeholder="بابت تخفیف را وارد کنید" value="{{ old('discount_for', $sale->discount_for) }}">
              <x-core::show-validation-error name="discount_for" />
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="form-group">
              <label for="cost_of_sewing" class="control-label"> هزینه دوخت / نصب : </label>
              @php
                $costOfSewing = $sale->cost_of_sewing ? number_format($sale->cost_of_sewing) : null;
              @endphp
              <input type="text" id="cost_of_sewing" class="form-control comma" name="cost_of_sewing" placeholder="هزینه دوخت را وارد کنید" value="{{ old('cost_of_sewing', $costOfSewing) }}">
              <x-core::show-validation-error name="cost_of_sewing" />
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
        <x-core::update-button/>
      </form>
    </x-slot>
  </x-core::card>

@endsection

@section('scripts')

  <x-core::date-input-script textInputId="sold_date_show" dateInputId="sold_date"/>

  <script>

    const discountInput = $('#discount');
    const costOfSewingInput = $('#cost_of_sewing');
    const totalItemsAmount = parseInt(@json($sale->total_items_amount));

    function calculateTotalAmount() {
      let discountAmount = discountInput.val() != null && discountInput.val().length > 0 ? parseInt(discountInput.val().replace(/,/g, '')) : 0;
      let costOfSewingAmount = costOfSewingInput.val() != null && costOfSewingInput.val().length > 0 ? parseInt(costOfSewingInput.val().replace(/,/g, '')) : 0;
      let finalPrice = (totalItemsAmount + costOfSewingAmount - discountAmount).toLocaleString();
      $('#totalPrice').text(finalPrice);
    }

    $(document).ready(() => {
      calculateTotalAmount();
      discountInput.on('input', calculateTotalAmount);
      costOfSewingInput.on('input', calculateTotalAmount);
    });

  </script>

  @endsection
