@extends('admin.layouts.master')
@section('content')

  <div class="page-header">
    <x-core::breadcrumb :items="[['title' => 'نقدی های دریافتی از مشتری']]" />
  </div>

  <x-core::card>
    <x-slot name="cardTitle">جستجوی پیشرفته</x-slot>
    <x-slot name="cardOptions"><x-core::card-options/></x-slot>
    <x-slot name="cardBody">
      <form action="{{ route("admin.sale-payments.cashes") }}">
        <div class="row">
          <div class="col-12 col-md-6 col-xl-3">
            <div class="form-group">
              <label for="customer_id">مشتری :</label>
              <select name="customer_id" id="customer_id" class="form-control select2">
                <option value="" class="text-muted">انتخاب</option>
                @foreach ($customers as $customer)
                  <option
                    value="{{ $customer->id }}"
                    @selected(request("customer_id") == $customer->id)>
                    {{ $customer->name }} - {{ $customer->mobile }}
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
            <a href="{{ route("admin.sale-payments.cashes") }}" class="btn btn-danger btn-block">حذف همه فیلتر ها <i class="fa fa-close"></i></a>
          </div>
        </div>
      </form>
    </x-slot>
  </x-core::card>

  <x-core::card>
    <x-slot name="cardTitle">نقدی های دریافتی از مشتری ({{ $cashPayments->total() }})</x-slot>
    <x-slot name="cardOptions"><x-core::card-options/></x-slot>
    <x-slot name="cardBody">
      <x-core::table>
        <x-slot name="tableTh">
          <tr>
            <th>ردیف</th>
            <th>مشتری</th>
            <th>مبلغ پرداختی (ریال)</th>
            <th>تاریخ پرداخت</th>
            <th>تاریخ ثبت</th>
            <th>عملیات</th>
          </tr>
        </x-slot>
        <x-slot name="tableTd">
          @forelse ($cashPayments as $payment)
          <tr>
            <td class="font-weight-bold">{{ $loop->iteration }}</td>
            <td>{{ $payment->customer->name }}</td>
            <td>{{ number_format($payment->amount) }}</td>
            <td>{{ $payment->getPaymentDate() }}</td>
            <td>@jalaliDateFormat($payment->created_at)</td>
            <td>
              <x-core::show-button route="admin.sale-payments.show" :model="$payment->customer"/>
              <x-sale::sale-payment-description-button target="#payment-description-modal{{$payment->id}}"/>
              @can('edit sale_payments')
                <x-core::edit-button target="#edit-payment-modal{{$payment->id}}"/>
              @endcan
              @can('delete sale_payments')
                <x-core::delete-button route="admin.sale-payments.destroy" :model="$payment"/>
              @endcan
            </td>
          </tr>
        @empty
          <x-core::data-not-found-alert :colspan="6"/>
        @endforelse
        </x-slot>
        <x-slot name="extraData">
          {{ $cashPayments->onEachSide(0)->links('vendor.pagination.bootstrap-4') }}
        </x-slot>
      </x-core::table>
    </x-slot>
  </x-core::card>

  <x-sale::edit-sale-payment-modal :payments="$cashPayments" idExtention="edit-payment-modal"/>
  <x-sale::sale-payment-description-modal :payments="$cashPayments" idExtention="payment-description-modal"/>

@endsection

@section('scripts')

  <x-sale::edit-sale-payment-scripts   
    :cashes="$cashPayments"
    :cheques="[]"
    :installments="[]"
  /> 

  <x-core::date-input-script textInputId="from_payment_date_show" dateInputId="from_payment_date"/>
  <x-core::date-input-script textInputId="to_payment_date_show" dateInputId="to_payment_date"/>

  <script>
    new CustomSelect('#customer_id', 'انتخاب مشتری'); 
    new CustomSelect('#status', 'انتخاب وضعیت'); 
  </script>

@endsection
