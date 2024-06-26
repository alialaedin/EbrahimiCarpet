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
        <a href="{{ route('admin.suppliers.index') }}">لیست تامین کنندگان</a>
      </li>
      <li class="breadcrumb-item">
        <a href="{{ route('admin.suppliers.show', $supplier) }}">نمایش تامین کننده</a>
      </li>
      <li class="breadcrumb-item">
        <a href="{{ route('admin.payments.index', $supplier) }}">پرداختی ها</a>
      </li>
      <li class="breadcrumb-item active">
        <a>ثبت پرداختی جدید</a>
      </li>
    </ol>
  </div>
  <div class="row">
    <div class="col-md-6">
      <div class="card overflow-hidden">
        <div class="card-header border-0">
          <p class="card-title">اطلاعات تامین کننده</p>
          <x-core::card-options/>
        </div>
        <div class="card-body">
          <ul class="list-group">
            <li class="list-group-item"><strong class="ml-1">کد : </strong> {{ $supplier->id }} </li>
            <li class="list-group-item"><strong class="ml-1">نام و نام خانوادگی : </strong> {{ $supplier->name }} </li>
            <li class="list-group-item"><strong class="ml-1">شماره موبایل : </strong> {{ $supplier->mobile }} </li>
            <li class="list-group-item"><strong class="ml-1">تاریخ ثبت : </strong> @jalaliDate($supplier->created_at) </li>
            <li class="list-group-item"><strong class="ml-1">آدرس : </strong> {{ $supplier->address }} </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card overflow-hidden">
        <div class="card-header border-0">
          <p class="card-title">اطلاعات خرید</p>
          <x-core::card-options/>
        </div>
        <div class="card-body">
          <ul class="list-group">
            <li class="list-group-item"><strong class="ml-1">تعداد خرید ها : </strong> {{ $supplier->countPurchases() }} </li>
            <li class="list-group-item"><strong class="ml-1">تعداد پرداختی ها : </strong> {{ $supplier->countPayments() }} </li>
            <li class="list-group-item"><strong class="ml-1">مبلغ کل خرید : </strong> {{ number_format($supplier->calcTotalPurchaseAmount()) }} تومان</li>
            <li class="list-group-item"><strong class="ml-1">جمع پرداخت شده ها : </strong> {{ number_format($supplier->calcTotalPaymentAmount()) }} تومان</li>
            <li class="list-group-item"><strong class="ml-1">مبلغ باقی مانده : </strong> {{ number_format($supplier->getRemainingAmount()) }} تومان </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header border-0">
      <p class="card-title">ثبت پرداختی جدید</p>
      <x-core::card-options/>
    </div>
    <div class="card-body">
      <form action="{{ route('admin.payments.store') }}" method="post" class="save" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="type" class="control-label">نوع پرداخت: <span class="text-danger">&starf;</span></label>
              <select name="type" id="type" class="form-control">
                <option value="" class="text-muted"> نوع پرداخت را انتخاب کنید </option>
                @foreach(config('core.payment_types') as $name => $label)
                  <option value="{{ $name }}" @selected(old('type') == $name)>{{ $label }}</option>
                @endforeach
              </select>
              <x-core::show-validation-error name="type" />
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="amount" class="control-label">مبلغ پرداخت (تومان): <span class="text-danger">&starf;</span></label>
              <input
                type="text"
                id="amount"
                class="form-control comma"
                name="amount"
                placeholder="مبلغ پرداختی را به تومان وارد کنید"
                value="{{ old('amount') }}"
              />
              <x-core::show-validation-error name="amount" />
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="payment_date_show" class="control-label">تاریخ پرداخت:</label>
              <input class="form-control fc-datepicker" id="payment_date_show" type="text" autocomplete="off" placeholder="تاریخ پرداخت را در صورت نیاز وارد کنید"/>
              <input name="payment_date" id="payment_date_hidden" type="hidden" required value="{{	old('payment_date') }}"/>
              <x-core::show-validation-error name="payment_date" />
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="due_date_show" class="control-label">تاریخ سررسید:</label>
              <input class="form-control fc-datepicker" id="due_date_show" type="text" autocomplete="off" placeholder="تاریخ سررسید را در صورت نیاز وارد کنید"/>
              <input name="due_date" id="due_date_hidden" type="hidden" required value="{{	old('due_date') }}"/>
              <x-core::show-validation-error name="due_date" />
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label"> انتخاب عکس </label>
              <input type="file" class="form-control" name="image" value="{{ old('image') }}">
              <x-core::show-validation-error name="image" />
            </div>
          </div>
          <div class="col-12">
            <div class="form-group">
              <label for="description" class="control-label">توضیحات :</label>
              <textarea name="description" id="description" class="form-control" rows="4"> {{ old('description') }} </textarea>
              <x-core::show-validation-error name="description" />
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label"> انتخاب وضعیت:<span class="text-danger">&starf;</span></label>
              <div class="custom-controls-stacked">
                <label class="custom-control custom-radio">
                  <input type="radio" class="custom-control-input" name="status" value="1" @checked(old('status') == '1')>
                  <span class="custom-control-label">فعال</span>
                </label>
                <label class="custom-control custom-radio">
                  <input type="radio" class="custom-control-input" name="status" value="0" @checked(old('status') == '0')>
                  <span class="custom-control-label">غیر فعال</span>
                </label>
              </div>
              <x-core::show-validation-error name="status" />
            </div>
          </div>
        </div>
        <x-core::store-button/>
      </form>
    </div>
  </div>
@endsection

@section('scripts')
  <x-core::date-input-script textInputId="payment_date_show" dateInputId="payment_date_hidden"/>
  <x-core::date-input-script textInputId="due_date_show" dateInputId="due_date_hidden"/>
@endsection
