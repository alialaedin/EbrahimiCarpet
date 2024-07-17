@extends('admin.layouts.master')
@section('content')
  <div class="page-header">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fe fe-home ml-1"></i> داشبورد</a></li>
      <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">گزارشات</a>
      <li class="breadcrumb-item active">فیلتر گزارش مالی تامین کننده</li>
    </ol>
  </div>

  <div class="card">

    <div class="card-header border-0">
      <p class="card-title">فیلتر ها</p>
      <x-core::card-options/>
    </div>

    <div class="card-body">
      <div class="row">
        <form action="{{ route("admin.reports.supplier-finance") }}" class="col-12" method="POST">
          @csrf
          <div class="row">

            <div class="col-12 col-lg-6 col-xl-3">
              <div class="form-group">
                <label for="supplier_id">انتخاب تامین کننده : <span class="text-danger">&starf;</span></label>
                <select name="supplier_id" id="supplier_id" class="form-control select2" required>
                  <option value="">تامین کننده را انتخاب کنید</option>
                  @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name .' - '. $supplier->mobile }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="col-12 col-lg-6 col-xl-3">
              <div class="form-group">
                <label for="payment_type">انتخاب نوع پرداخت : </label>
                <select name="payment_type" id="payment_type" class="form-control">
                  <option value="">نوع پرداخت را انتخاب کنید</option>
                  @foreach ($paymentTypes as $paymentType)
                    <option value="{{ $paymentType }}">{{ config('payment.types.'.$paymentType) }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="col-12 col-lg-6 col-xl-3">
              <div class="form-group">
                <label for="from_date_show">از تاریخ : <span class="text-danger">&starf;</span></label>
                <input class="form-control fc-datepicker" id="from_date_show" type="text" autocomplete="off" required/>
                <input name="from_date" id="from_date_hidden" type="hidden" value="{{ request("from_date") }}"/>
              </div>
            </div>

            <div class="col-12 col-lg-6 col-xl-3">
              <div class="form-group">
                <label for="to_date_show">تا تاریخ :</label>
                <input class="form-control fc-datepicker" id="to_date_show" type="text" autocomplete="off"/>
                <input name="to_date" id="to_date_hidden" type="hidden" value="{{ request("to_date") }}"/>
              </div>
            </div>

          </div>

          <div class="row">
            <button class="btn btn-primary btn-block" type="submit">جستجو <i class="fa fa-search"></i></button>
          </div>

        </form>
      </div>
    </div>
  </div>

@endsection
@section('scripts')
  <x-core::date-input-script textInputId="from_date_show" dateInputId="from_date"/>
  <x-core::date-input-script textInputId="to_date_show" dateInputId="to_date"/>
@endsection
