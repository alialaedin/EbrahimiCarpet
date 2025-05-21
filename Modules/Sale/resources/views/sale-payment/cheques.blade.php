@extends('admin.layouts.master')
@section('content')

  <div class="page-header">
    <x-core::breadcrumb :items="[['title' => 'چک های دریافتی از مشتری']]" />
  </div>

  <x-core::card>
    <x-slot name="cardTitle">جستجوی پیشرفته</x-slot>
    <x-slot name="cardOptions"><x-core::card-options/></x-slot>
    <x-slot name="cardBody">
      <form action="{{ route("admin.sale-payments.cheques") }}">
        <div class="row">
          <div class="col-12 col-md-6 col-xl-3">
            <div class="form-group">
              <label for="cheque_holder">صاحب چک :</label>
              <input type="text" id="cheque_holder" name="cheque_holder" value="{{ request('cheque_holder') }}" class="form-control">
            </div>
          </div>
          <div class="col-12 col-md-6 col-xl-3">
            <div class="form-group">
              <label for="cheque_serial">سریال چک :</label>
              <input type="text" id="cheque_serial" name="cheque_serial" value="{{ request('cheque_serial') }}" class="form-control">
            </div>
          </div>
          <div class="col-12 col-md-6 col-xl-3">
            <div class="form-group">
              <label for="customer_id">انتخاب مشتری :</label>
              <select name="customer_id" id="customer_id" class="form-control">
                <option value="" class="text-muted">انتخاب</option>
                @foreach ($customers as $customer)
                  <option value="{{ $customer->id }}" @selected(request('customer_id') == $customer->id)>{{ $customer->name }}</option>
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
            </div>
          </div>
          <div class="col-12 col-lg-6 col-xl-3">
            <div class="form-group">
              <label for="to_payment_date_show">تا تاریخ :</label>
              <input class="form-control fc-datepicker" id="to_payment_date_show" type="text" autocomplete="off"/>
              <input name="to_payment_date" id="to_payment_date" type="hidden" value="{{ request("to_payment_date") }}"/>
            </div>
          </div>
          <div class="col-12 col-lg-6 col-xl-3">
            <div class="form-group">
              <label for="from_due_date_show">سررسید از تاریخ : </label>
              <input class="form-control fc-datepicker" id="from_due_date_show" type="text" autocomplete="off"/>
              <input name="from_due_date" id="from_due_date" type="hidden" value="{{ request("from_due_date") }}"/>
            </div>
          </div>
          <div class="col-12 col-lg-6 col-xl-3">
            <div class="form-group">
              <label for="to_due_date_show">تا تاریخ :</label>
              <input class="form-control fc-datepicker" id="to_due_date_show" type="text" autocomplete="off"/>
              <input name="to_due_date" id="to_due_date" type="hidden" value="{{ request("to_due_date") }}"/>
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
    <x-slot name="cardOptions">
      <div id="operation-buttons-row" class="card-options" style="gap: 8px">
        <button class="btn btn-sm btn-outline-success">تغییر وضعیت به پرداخت شده</button>
        <form action="{{ route('admin.sale-payments.update-statuses') }}" class="d-none" method="POST">
          @method('PATCH')
          @csrf
        </form>
      </div>
    </x-slot>
    <x-slot name="cardBody">
      <x-core::table>
        <x-slot name="tableTh">
          <tr>
            <th>#</th>
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
            <th>عملیات</th>
          </tr>
        </x-slot>
        <x-slot name="tableTd">
          @forelse ($chequePayments as $payment)
          <tr>
            <td><input type="checkbox" class="checkbox payment-change-status-checkbox" value="{{ $payment->id }}" /></td>
            <td class="font-weight-bold">{{ $loop->iteration }}</td>
            <td>
              <a target="_blank" href="{{ route('admin.customers.show', $payment->customer) }}">{{ $payment->customer->name }}</a>
            </td>
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
          <x-core::data-not-found-alert :colspan="11"/>
        @endforelse
        </x-slot>
        <x-slot name="extraData">
          {{ $chequePayments->onEachSide(0)->links('vendor.pagination.bootstrap-4') }}
        </x-slot>
      </x-core::table>
    </x-slot>
  </x-core::card>

  <x-sale::edit-sale-payment-modal :payments="$chequePayments" idExtention="edit-payment-modal"/>
  <x-sale::sale-payment-description-modal :payments="$chequePayments" idExtention="payment-description-modal"/>

@endsection

@section('scripts')

  <x-sale::edit-sale-payment-scripts   
    :cashes="[]"
    :cheques="$chequePayments"
    :installments="[]"
  /> 

  <x-core::date-input-script textInputId="from_payment_date_show" dateInputId="from_payment_date"/>
  <x-core::date-input-script textInputId="to_payment_date_show" dateInputId="to_payment_date"/>
  <x-core::date-input-script textInputId="from_due_date_show" dateInputId="from_due_date"/>
  <x-core::date-input-script textInputId="to_due_date_show" dateInputId="to_due_date"/>

  <script>
    new CustomSelect('#customer_id', 'انتخاب مشتری'); 
    new CustomSelect('#status', 'انتخاب وضعیت'); 
  </script>

  <script>

    function hideOperationButtonsRow() {
      $('#operation-buttons-row').removeClass('d-flex');
      $('#operation-buttons-row').addClass('d-none');
    }

    function showOperationButtonsRow() {
      $('#operation-buttons-row').removeClass('d-none');
      $('#operation-buttons-row').addClass('d-flex');
    }

    function handleShowingOperationButtons() {  
      $('.payment-change-status-checkbox').each(function () {  
        $(this).change(() => {
          if ($('.payment-change-status-checkbox:checked').length > 0) {  
            showOperationButtonsRow();  
          }else {
            hideOperationButtonsRow();
          }
        });  
      });  
    }  

    function updateStatus() {
      const changeStatusBtn = $('#operation-buttons-row button');
      const changeStatusForm = $('#operation-buttons-row form');
      changeStatusBtn.click(() => {
        changeStatusBtn.prop('disabled', true);
        let counter = 0;
        $('.payment-change-status-checkbox:checked').each(function () {  
          const paymentId = $(this).val();
          const input = $(`<input hidden name="ids[${counter++}]" value="${paymentId}" />`);
          changeStatusForm.append(input);
        });  
        changeStatusForm.submit();
      });
    }

    $(document).ready(() => {
      hideOperationButtonsRow();
      handleShowingOperationButtons();
      updateStatus();
    });

  </script>

@endsection
