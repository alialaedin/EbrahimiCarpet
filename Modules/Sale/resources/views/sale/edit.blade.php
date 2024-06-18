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
      <li class="breadcrumb-item active">ویرایش فروش</li>
    </ol>
  </div>
  <div class="card">
    <div class="card-header">
      <p class="card-title">ویرایش فروش - کد {{ $sale->id }} - مبلغ فروش : {{ number_format($sale->getTotalAmountWithDiscount()) . ' تومان'}}</p>
    </div>
    <div class="card-body">
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
              <label for="discount" class="control-label"> تخفیف کلی (تومان): </label>
              <input type="text" id="discount" class="form-control comma" name="discount" placeholder="تخفیف را به تومان وارد کنید" value="{{ old('discount', number_format($sale->discount)) }}">
              <x-core::show-validation-error name="discount" />
            </div>
          </div>
        </div>
        <x-core::update-button/>
      </form>
    </div>
  </div>
@endsection

@section('scripts')
  <x-core::date-input-script textInputId="sold_date_show" dateInputId="sold_date"/>
@endsection
