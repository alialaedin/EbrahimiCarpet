@extends('admin.layouts.master')
@section('content')

<div class="page-header">
  <x-core::breadcrumb
    :items="[
      ['title' => 'لیست تامین کنندگان', 'route_link' => 'admin.suppliers.index'],
      ['title' => 'نمایش تامین کننده', 'route_link' => 'admin.suppliers.show', 'parameter' => $supplier],
      ['title' => 'پرداختی ها', 'route_link' => 'admin.payments.show', 'parameter' => $supplier],
      ['title' => 'ثبت پرداختی جدید'],
    ]"
  />
</div>

  <div class="row">
    <div class="col-12 col-lg-6">
      <x-core::card>
        <x-slot name="cardTitle">اطلاعات تامین کننده</x-slot>
        <x-slot name="cardOptions"><x-core::card-options/></x-slot>
        <x-slot name="cardBody">
          <ul class="list-group">
            <li class="list-group-item"><strong class="ml-1">کد : </strong> {{ $supplier->id }} </li>
            <li class="list-group-item"><strong class="ml-1">نام و نام خانوادگی : </strong> {{ $supplier->name }} </li>
            <li class="list-group-item"><strong class="ml-1">شماره موبایل : </strong> {{ $supplier->mobile }} </li>
            <li class="list-group-item"><strong class="ml-1">تاریخ ثبت : </strong> @jalaliDate($supplier->created_at) </li>
            <li class="list-group-item"><strong class="ml-1">آدرس : </strong> {{ $supplier->address }} </li>
          </ul>
        </x-slot>
      </x-core::card>
    </div>
    <div class="col-12 col-lg-6">
      <x-core::card>
        <x-slot name="cardTitle">اطلاعات خرید</x-slot>
        <x-slot name="cardOptions"><x-core::card-options/></x-slot>
        <x-slot name="cardBody">
          <ul class="list-group">
            <li class="list-group-item"><strong class="ml-1">تعداد خرید ها : </strong> {{ $supplier->purchases_count }} </li>
            <li class="list-group-item"><strong class="ml-1">تعداد پرداختی ها : </strong> {{ $supplier->payments_count }} </li>
            <li class="list-group-item"><strong class="ml-1">مبلغ کل خرید : </strong> {{ number_format($supplier->total_purchases_amount) }} ریال</li>
            <li class="list-group-item"><strong class="ml-1">جمع پرداخت شده ها : </strong> {{ number_format($supplier->total_payments_amount) }} ریال</li>
            <li class="list-group-item"><strong class="ml-1">مبلغ باقی مانده : </strong> {{ number_format($supplier->remaining_amount) }} ریال </li>
          </ul>
        </x-slot>
      </x-core::card>
    </div>
  </div>

  <x-core::card>
    <x-slot name="cardTitle">ثبت پرداختی جدید - ابتدا نوع پرداخت را انتخاب کنید سپس فیلد های لازم را پر کنید</x-slot>
    <x-slot name="cardOptions"><x-core::card-options/></x-slot>
    <x-slot name="cardBody">
      <form action="{{ route('admin.payments.store') }}" method="post" class="save" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">

        <div class="row mb-3">
          <div class="col-md-6 col-xl-3">
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
        </div>
        <div class="row mb-3 d-none" id="cashInputsBox">
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
        <div class="row mb-3 d-none" id="chequeInputsBox">
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
              <input type="text"  id="cheque_serial" class="form-control" name="cheque_serial" placeholder="سریال چک وارد کنید" value="{{ old('cheque_serial') }}">
              <x-core::show-validation-error name="cheque_serial" />
            </div>
          </div>
          <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="form-group">
              <label for="cheque_holder" class="control-label">نام و نام خانوادگی صاحب چک: <span class="text-danger">&starf;</span></label>
              <input type="text"  id="cheque_holder" class="form-control" name="cheque_holder" placeholder="نام و نام خانوادگی صاحب وارد کنید" value="{{ old('cheque_holder') }}">
              <x-core::show-validation-error name="cheque_holder" />
            </div>
          </div>
          <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="form-group">
              <label for="bank_name" class="control-label">نام بانک: <span class="text-danger">&starf;</span></label>
              <input type="text"  id="bank_name" class="form-control" name="bank_name" placeholder="نام بانک وارد کنید" value="{{ old('bank_name') }}">
              <x-core::show-validation-error name="bank_name" />
            </div>
          </div>
          <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="form-group">
              <label for="pay_to" class="control-label">در وجه: <span class="text-danger">&starf;</span></label>
              <input type="text"  id="pay_to" class="form-control" name="pay_to" placeholder="چک در وجه چه کسی است" value="{{ old('pay_to') }}">
              <x-core::show-validation-error name="pay_to" />
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
              <label for="image" class="control-label"> عکس رسید: </label>
              <input type="file" id="image" class="form-control" name="image" value="{{ old('image') }}">
              <x-core::show-validation-error name="image" />
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
              <x-core::show-validation-error name="is_mine" />
            </div>
          </div>
        </div>
        <div class="row mb-3 d-none" id="installmentInputsBox">
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

        <x-core::store-button/>
      </form>
    </x-slot>
  </x-core::card>

@endsection

@section('scripts')

  <x-core::date-input-script textInputId="cash_payment_date_show" dateInputId="cash_payment_date_hidden"/>
  <x-core::date-input-script textInputId="cheque_due_date_show" dateInputId="cheque_due_date_hidden"/>
  <x-core::date-input-script textInputId="installment_start_date_show" dateInputId="installment_start_date_hidden"/>

  <script>

    $('#type').select2({
      placeholder: 'انتخاب نوع پرداخت'
    });

    $(document).ready(() => {
      
      const payType = $('#type');
      const cashInputsBox = $('#cashInputsBox');
      const chequeInputsBox = $('#chequeInputsBox');
      const installmentInputsBox = $('#installmentInputsBox');

      payType.on('change', () => {
        [cashInputsBox, chequeInputsBox, installmentInputsBox].forEach((box) => {
          if (box.attr('id').toLowerCase().includes(payType.val())) {
            box.removeClass('d-none');
          } else {
            box.addClass('d-none');
          }
        });
      });
    });
  </script>
@endsection
