@extends('admin.layouts.master')
@section('content')
  <div class="page-header">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}">
          <i class="fe fe-home ml-1"></i> داشبورد
        </a>
      </li>
      <li class="breadcrumb-item active">فیلتر گزارش خرید</li>
    </ol>
  </div>

  <div class="card">

    <div class="card-header border-0">
      <p class="card-title">فیلتر ها</p>
      <x-core::card-options/>
    </div>

    <div class="card-body">
      <div class="row">
        <form action="{{ route("admin.reports.purchases-list") }}" class="col-12" method="POST">
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
                <x-core::show-validation-error name="supplier_id"/>
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
                <label for="from_purchased_date_show">از تاریخ : <span class="text-danger">&starf;</span></label>
                <input class="form-control fc-datepicker" id="from_purchased_date_show" type="text" autocomplete="off" required/>
                <input name="from_purchased_at" id="from_purchased_date" type="hidden" value="{{ request("from_purchased_at") }}"/>
                <x-core::show-validation-error name="from_purchased_at"/>
              </div>
            </div>

            <div class="col-12 col-lg-6 col-xl-3">
              <div class="form-group">
                <label for="to_purchased_date_show">تا تاریخ :</label>
                <input class="form-control fc-datepicker" id="to_purchased_date_show" type="text" autocomplete="off"/>
                <input name="to_purchased_at" id="to_purchased_date" type="hidden" value="{{ request("to_purchased_at") }}"/>
                <x-core::show-validation-error name="to_purchased_at"/>
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
  <x-core::date-input-script textInputId="from_purchased_date_show" dateInputId="from_purchased_date"/>
  <x-core::date-input-script textInputId="to_purchased_date_show" dateInputId="to_purchased_date"/>
@endsection
