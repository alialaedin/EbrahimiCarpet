@extends('admin.layouts.master')
@section('content')
  <div class="page-header">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}">
          <i class="fe fe-home ml-1"></i> داشبورد
        </a>
      </li>
      <li class="breadcrumb-item active">فیلتر گزارش فروش</li>
    </ol>
  </div>

  <div class="card">

    <div class="card-header border-0">
      <p class="card-title">فیلتر ها</p>
      <x-core::card-options/>
    </div>

    <div class="card-body">
      <div class="row">
        <form action="{{ route("admin.reports.sales-list") }}" class="col-12" method="POST">
          @csrf
          <div class="row">

            <div class="col-12 col-lg-6 col-xl-3">
              <div class="form-group">
                <label for="customer_id">انتخاب مشتری : <span class="text-danger">&starf;</span></label>
                <select name="customer_id" id="customer_id" class="form-control select2" required>
                  <option value="">مشتری را انتخاب کنید</option>
                  @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name .' - '. $customer->mobile }}</option>
                  @endforeach
                </select>
                <x-core::show-validation-error name="customer_id"/>
              </div>
            </div>

            <div class="col-12 col-lg-6 col-xl-3">
              <div class="form-group">
                <label for="has_discount">وضعیت تخفیف :</label>
                <select name="has_discount" id="has_discount" class="form-control">
                  <option value="">همه</option>
                  <option value="1">تخفیفدار</option>
                  <option value="0">بدون تخفیف</option>
                </select>
                <x-core::show-validation-error name="has_discount"/>
              </div>
            </div>

            <div class="col-12 col-lg-6 col-xl-3">
              <div class="form-group">
                <label for="from_sold_at_show">از تاریخ : <span class="text-danger">&starf;</span></label>
                <input class="form-control fc-datepicker" id="from_sold_at_show" type="text" autocomplete="off" required/>
                <input name="from_sold_at" id="from_sold_at" type="hidden" value="{{ request("from_sold_at") }}"/>
                <x-core::show-validation-error name="from_sold_at"/>
              </div>
            </div>

            <div class="col-12 col-lg-6 col-xl-3">
              <div class="form-group">
                <label for="to_sold_at_show">تا تاریخ :</label>
                <input class="form-control fc-datepicker" id="to_sold_at_show" type="text" autocomplete="off"/>
                <input name="to_sold_at" id="to_sold_at" type="hidden" value="{{ request("to_sold_at") }}"/>
                <x-core::show-validation-error name="to_sold_at"/>
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
  <x-core::date-input-script textInputId="from_sold_at_show" dateInputId="from_sold_at"/>
  <x-core::date-input-script textInputId="to_sold_at_show" dateInputId="to_sold_at"/>
@endsection
