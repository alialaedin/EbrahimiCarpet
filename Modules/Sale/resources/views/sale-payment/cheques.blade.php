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
        <a>چک های دریافتی</a>
      </li>
    </ol>
  </div>

  <x-core::card>
    <x-slot name="cardTitle">جستجوی پیشرفته</x-slot>
    <x-slot name="cardOptions"><x-core::card-options/></x-slot>
    <x-slot name="cardBody">
      <form action="{{ route("admin.sale-payments.cheques") }}">
        <div class="row">
          <div class="col-12 col-md-6 col-xl-3">
            <div class="form-group">
              <label for="supplier_id">صاحب چک :</label>
              <input type="text" name="cheque_holder" value="{{ request('cheque_holder') }}" class="form-control">
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
            <a href="{{ route("admin.sale-payments.cheques") }}" class="btn btn-danger btn-block">حذف همه فیلتر ها <i class="fa fa-close"></i></a>
          </div>
        </div>
      </form>
    </x-slot>
  </x-core::card>

  <x-core::card>
    <x-slot name="cardTitle">چک های دریافتی از مشتری ({{ $chequePayments->total() }})</x-slot>
    <x-slot name="cardOptions"><x-core::card-options/></x-slot>
    <x-slot name="cardBody">
      <x-core::table>
        <x-slot name="tableTh">
          <tr>
            <th>ردیف</th>
            <th>مشتری</th>
            <th>سریال</th>
            <th>صاحب چک</th>
            <th>بانک</th>
            <th>مبلغ (ریال)</th>
            <th>تاریخ سررسید</th>
            <th>تاریخ پرداخت</th>
            <th>تاریخ ثبت</th>
            <th>وضعیت</th>
          </tr>
        </x-slot>
        <x-slot name="tableTd">
          @forelse ($chequePayments as $payment)
          <tr>
            <td class="font-weight-bold">{{ $loop->iteration }}</td>
            <td>{{ $payment->customer->name }}</td>
            <td>{{ $payment->cheque_serial }}</td>
            <td>{{ $payment->cheque_holder }}</td>
            <td>{{ $payment->bank_name }}</td>
            <td>{{ number_format($payment->amount) }}</td>
            <td>@jalaliDateFormat($payment->due_date)</td>
            <td>{{ $payment->getPaymentDate() }}</td>
            <td>@jalaliDate($payment->created_at)</td>
            <td>
              @php  
                $type = $payment->status ? 'success' : 'danger';  
                $text = $payment->status ? 'پاس شده' : 'پاس نشده';  
              @endphp  
              <x-core::light-badge :type="$type" :text="$text"/>  
            </td>
          </tr>
        @empty
          <x-core::data-not-found-alert :colspan="10"/>
        @endforelse
        </x-slot>
        <x-slot name="extraData">
          {{ $chequePayments->onEachSide(0)->links('vendor.pagination.bootstrap-4') }}
        </x-slot>
      </x-core::table>
    </x-slot>
  </x-core::card>

@endsection

@section('scripts')
  <x-core::date-input-script textInputId="from_payment_date_show" dateInputId="from_payment_date"/>
  <x-core::date-input-script textInputId="to_payment_date_show" dateInputId="to_payment_date"/>

  <script>
    $('#status').select2({
      placeholder: 'انتخاب وضعیت';
    });
  </script>

@endsection
