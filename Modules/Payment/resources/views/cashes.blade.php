@extends('admin.layouts.master')
@section('content')
  <div class="page-header">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}">
          <i class="fe fe-home ml-1"></i> داشبورد
        </a>
      </li>
      <li class="breadcrumb-item active">
        <a>نقدی های پرداختی</a>
      </li>
    </ol>
  </div>

  <x-core::card>
    <x-slot name="cardTitle">جستجوی پیشرفته</x-slot>
    <x-slot name="cardOptions"><x-core::card-options/></x-slot>
    <x-slot name="cardBody">
      <form action="{{ route("admin.payments.cashes") }}">
        <div class="row">
          <div class="col-12 col-md-6 col-xl-3">
            <div class="form-group">
              <label for="supplier_id">تامین کننده :</label>
              <select name="supplier_id" id="supplier_id" class="form-control select2">
                <option value="" class="text-muted">انتخاب</option>
                @foreach ($suppliers as $supplier)
                  <option
                    value="{{ $supplier->id }}"
                    @selected(request("supplier_id") == $supplier->id)>
                    {{ $supplier->name }} - {{ $supplier->mobile }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-12 col-md-6 col-xl-3">
            <div class="form-group">
              <label for="status">وضعیت :</label>
              <select name="status" id="status" class="form-control">
                <option value="" class="text-muted">انتخاب</option>
                <option value="1" @selected(request("status") == "1")>پرداخت شده</option>
                <option value="0" @selected(request("status") == "0")>پرداخت نشده</option>
              </select>
            </div>
          </div>
          <div class="col-12 col-lg-6 col-xl-3">
            <div class="form-group">
              <label for="from_payment_date_show">پرداخت از تاریخ : </label>
              <input class="form-control fc-datepicker" id="from_payment_date_show" type="text" autocomplete="off"/>
              <input name="from_payment_date" id="from_payment_date" type="hidden" value="{{ request("from_payment_date") }}"/>
              <x-core::show-validation-error name="from_payment_date"/>
            </div>
          </div>
          <div class="col-12 col-lg-6 col-xl-3">
            <div class="form-group">
              <label for="to_payment_date_show">تا تاریخ :</label>
              <input class="form-control fc-datepicker" id="to_payment_date_show" type="text" autocomplete="off"/>
              <input name="to_payment_date" id="to_payment_date" type="hidden" value="{{ request("to_payment_date") }}"/>
              <x-core::show-validation-error name="to_payment_date"/>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-xl-9 col-lg-8 col-md-6 col-12">
            <button class="btn btn-primary btn-block" type="submit">جستجو <i class="fa fa-search"></i></button>
          </div>
          <div class="col-xl-3 col-lg-4 col-md-6 col-12">
            <a href="{{ route("admin.payments.cashes") }}" class="btn btn-danger btn-block">حذف همه فیلتر ها <i class="fa fa-close"></i></a>
          </div>
        </div>
      </form>
    </x-slot>
  </x-core::card>

  <x-core::card>
    <x-slot name="cardTitle">نقدی های پرداختی به تامین کننده ({{ $cashPayments->total() }})</x-slot>
    <x-slot name="cardOptions"><x-core::card-options/></x-slot>
    <x-slot name="cardBody">
      <x-core::table>
        <x-slot name="tableTh">
          <tr>
            <th>ردیف</th>
            <th>تامین کننده</th>
            <th>مبلغ پرداختی (ریال)</th>
            <th>تاریخ پرداخت</th>
            <th>تاریخ ثبت</th>
          </tr>
        </x-slot>
        <x-slot name="tableTd">
          @forelse ($cashPayments as $payment)
          <tr>
            <td class="font-weight-bold">{{ $loop->iteration }}</td>
            <td>{{ $payment->supplier->name }}</td>
            <td>{{ number_format($payment->amount) }}</td>
            <td>{{ $payment->getPaymentDate() }}</td>
            <td>@jalaliDateFormat($payment->created_at)</td>
          </tr>
        @empty
          <x-core::data-not-found-alert :colspan="5"/>
        @endforelse
        </x-slot>
        <x-slot name="extraData">
          {{ $cashPayments->onEachSide(0)->links('vendor.pagination.bootstrap-4') }}
        </x-slot>
      </x-core::table>
    </x-slot>
  </x-core::card>

@endsection

@section('scripts')
  <x-core::date-input-script textInputId="from_payment_date_show" dateInputId="from_payment_date"/>
  <x-core::date-input-script textInputId="to_payment_date_show" dateInputId="to_payment_date"/>
@endsection