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
        <a href="{{ route('admin.customers.index') }}">لیست مشتریان</a>
      </li>
      <li class="breadcrumb-item">
        <a href="{{ route('admin.customers.show', $salePayment->customer) }}">نمایش مشتری</a>
      </li>
      <li class="breadcrumb-item">
        <a href="{{ route('admin.sale-payments.show', $salePayment->customer) }}">پرداختی ها</a>
      </li>
      <li class="breadcrumb-item active">
        <a>ویرایش پرداختی</a>
      </li>
    </ol>
  </div>
  <div class="row">
    <div class="col-md-6">
      <div class="card overflow-hidden">
        <div class="card-header border-0">
          <p class="card-title">اطلاعات مشتری</p>
          <x-core::card-options/>
        </div>
        <div class="card-body">
          <ul class="list-group">
            <li class="list-group-item"><strong>کد : </strong> {{ $salePayment->customer->id }} </li>
            <li class="list-group-item"><strong>نام و نام خانوادگی : </strong> {{ $salePayment->customer->name }} </li>
            <li class="list-group-item"><strong>شماره موبایل : </strong> {{ $salePayment->customer->mobile }} </li>
            <li class="list-group-item"><strong>تاریخ ثبت : </strong>  @jalaliDate($salePayment->customer->created_at) </li>
            <li class="list-group-item"><strong>آدرس : </strong> {{ $salePayment->customer->address }} </li>
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
            <li class="list-group-item"><strong>تعداد خرید ها : </strong> {{ $salePayment->customer->countSales() }} </li>
            <li class="list-group-item"><strong>تعداد پرداختی ها : </strong> {{ $salePayment->customer->countPayments() }} </li>
            <li class="list-group-item"><strong>مبلغ کل خرید : </strong> {{ number_format($salePayment->customer->calcTotalSalesAmount()) }} ریال</li>
            <li class="list-group-item"><strong>جمع پرداخت شده ها : </strong> {{ number_format($salePayment->customer->calcTotalSalePaymentsAmount()) }} ریال</li>
            <li class="list-group-item"><strong>مبلغ باقی مانده : </strong> {{ number_format($salePayment->customer->getRemainingAmount()) }} ریال </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header border-0">
      <p class="card-title">ویرایش پرداختی</p>
    </div>
    <div class="card-body">
      <form action="{{ route('admin.sale-payments.update', $salePayment) }}" method="post" class="save" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="row mb-3">
          <div class="col-md-6 col-xl-4">
            <div class="form-group">
              <label for="type" class="control-label">نوع پرداخت: <span class="text-danger">&starf;</span></label>
              <input type="text" value="{{ config('payment.types.'.$salePayment->type) }}" readonly>
            </div>
          </div>
        </div>

        <p class="header fs-20 p-2 pr-2">نقد</p>
        <div class="row mb-3">
          <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="form-group">
              <label for="cash_amount" class="control-label">مبلغ پرداخت (ریال): <span class="text-danger">&starf;</span></label>
              <input type="text" id="cash_amount" class="form-control comma" name="cash_amount" placeholder="مبلغ پرداختی را به ریال وارد کنید" value="{{ old('cash_amount') }}">
              <x-core::show-validation-error name="cash_amount" />
            </div>
          </div>
          <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="form-group">
              <label for="cash_payment_date_show" class="control-label">تاریخ پرداخت:</label>
              <input class="form-control fc-datepicker" id="cash_payment_date_show" type="text" autocomplete="off" placeholder="تاریخ پرداخت را در صورت نیاز وارد کنید"/>
              <input name="cash_payment_date" id="cash_payment_date_hidden" type="hidden" required value="{{	old('cash_payment_date') }}"/>
              <x-core::show-validation-error name="cash_payment_date" />
            </div>
          </div>
          <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="form-group">
              <label for="image" class="control-label"> عکس رسید: </label>
              <input type="file" id="image" class="form-control" name="image" value="{{ old('image') }}">
              <x-core::show-validation-error name="image" />
            </div>
          </div>
        </div>

        <p class="header fs-20 p-2 pr-2">چک</p>
        <div class="row mb-3">
          <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="form-group">
              <label for="cheque_amount" class="control-label">مبلغ چک (ریال): <span class="text-danger">&starf;</span></label>
              <input type="text" id="cheque_amount" class="form-control comma" name="cheque_amount" placeholder="مبلغ پرداختی را به ریال وارد کنید" value="{{ old('cheque_amount') }}">
              <x-core::show-validation-error name="cheque_amount" />
            </div>
          </div>
          <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="form-group">
              <label for="cheque_serial" class="control-label">سریال چک: <span class="text-danger">&starf;</span></label>
              <input type="text"  id="cheque_serial" class="form-control comma" name="cheque_serial" placeholder="سریال چک وارد کنید" value="{{ old('cheque_serial') }}">
              <x-core::show-validation-error name="cheque_serial" />
            </div>
          </div>
          <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="form-group">
              <label for="cheque_holder" class="control-label">نام و نام خانوادگی صاحب چک: <span class="text-danger">&starf;</span></label>
              <input type="text"  id="cheque_holder" class="form-control comma" name="cheque_holder" placeholder="نام و نام خانوادگی صاحب وارد کنید" value="{{ old('cheque_holder') }}">
              <x-core::show-validation-error name="cheque_holder" />
            </div>
          </div>
          <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="form-group">
              <label for="bank_name" class="control-label">نام بانک: <span class="text-danger">&starf;</span></label>
              <input type="text"  id="bank_name" class="form-control comma" name="bank_name" placeholder="نام بانک وارد کنید" value="{{ old('bank_name') }}">
              <x-core::show-validation-error name="bank_name" />
            </div>
          </div>
          <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="form-group">
              <label for="pay_to" class="control-label">در وجه: <span class="text-danger">&starf;</span></label>
              <input type="text"  id="pay_to" class="form-control comma" name="pay_to" placeholder="چک در وجه چه کسی است" value="{{ old('pay_to') }}">
              <x-core::show-validation-error name="pay_to" />
            </div>
          </div>
          <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="form-group">
              <label for="cheque_payment_date_show" class="control-label">تاریخ پرداخت:</label>
              <input class="form-control fc-datepicker" id="cheque_payment_date_show" type="text" autocomplete="off" placeholder="تاریخ پرداخت را در صورت نیاز وارد کنید"/>
              <input name="cheque_payment_date" id="cheque_payment_date_hidden" type="hidden" required value="{{	old('cheque_payment_date') }}"/>
              <x-core::show-validation-error name="cheque_payment_date" />
            </div>
          </div>
          <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="form-group">
              <label for="cheque_due_date_show" class="control-label">تاریخ سررسید:</label>
              <input class="form-control fc-datepicker" id="cheque_due_date_show" type="text" autocomplete="off" placeholder="تاریخ سررسید را در صورت نیاز وارد کنید"/>
              <input name="cheque_due_date" id="cheque_due_date_hidden" type="hidden" required value="{{	old('cheque_due_date') }}"/>
              <x-core::show-validation-error name="cheque_due_date" />
            </div>
          </div>
          <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="form-group">
              <label class="control-label"> چک برای خودم است:<span class="text-danger">&starf;</span></label>
              <div class="custom-controls-stacked">
                <label class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input" name="is_mine" value="1" @checked(old('is_mine', 1) == '1')>
                  <span class="custom-control-label">بله</span>
                </label>
              </div>
              <x-core::show-validation-error name="status" />
            </div>
          </div>
        </div>

        <p class="header fs-20 p-2 pr-2">قسط</p>
        <div class="row mb-3">
          <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="form-group">
              <label for="number_of_installments" class="control-label">تعداد اقساط: <span class="text-danger">&starf;</span></label>
              <input
                type="number"
                id="number_of_installments"
                class="form-control"
                name="number_of_installments"
                placeholder="تعداد قسط را وارد کنید"
                value="{{ old('number_of_installments') }}"
              />
              <x-core::show-validation-error name="number_of_installments" />
            </div>
          </div>
          <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="form-group">
              <label for="installment_amount" class="control-label">مبلغ هر قسط: <span class="text-danger">&starf;</span></label>
              <input
                type="text"
                id="installment_amount"
                class="form-control comma"
                name="installment_amount"
                placeholder="مبلغ هر قسط را وارد کنید"
                value="{{ old('installment_amount') }}"
              />
              <x-core::show-validation-error name="installment_amount" />
            </div>
          </div>
          <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="form-group">
              <label for="installment_start_date_show" class="control-label">تاریخ شروع قسط:</label>
              <input class="form-control fc-datepicker" id="installment_start_date_show" type="text" autocomplete="off" placeholder="تاریخ سررسید را در صورت نیاز وارد کنید"/>
              <input name="installment_start_date" id="installment_start_date_hidden" type="hidden" required value="{{	old('installment_start_date') }}"/>
              <x-core::show-validation-error name="installment_start_date" />
            </div>
          </div>
        </div>
        <x-core::update-button/>
      </form>
      @if ($salePayment->image)
        <form
          action="{{ route('admin.sale-payments.image.destroy', $salePayment) }}"
          id="delete-image-{{$salePayment->id}}"
          method="POST"
          style="display: none;">
          @csrf
          @method("DELETE")
        </form>
      @endif
    </div>
  </div>
@endsection

@section('scripts')
  <x-core::date-input-script textInputId="salePayment_date_show" dateInputId="salePayment_date_hidden"/>
  <x-core::date-input-script textInputId="due_date_show" dateInputId="due_date_hidden"/>
@endsection
