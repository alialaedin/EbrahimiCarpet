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
        <a>چک های پرداختی</a>
      </li>
    </ol>
  </div>

  <div class="card">
    <div class="card-header border-0">
      <p class="card-title">جستجوی پیشرفته</p>
      <x-core::card-options/>
    </div>
    <div class="card-body">
      <form action="{{ route("admin.payments.cheques") }}">
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
              <label for="type">نوع پرداخت :</label>
              <select name="type" id="type" class="form-control">
                <option value="" class="text-muted">انتخاب</option>
                @foreach(config('payment.types') as $name => $label)
                  <option
                    value="{{ $name }}"
                    @selected(request('type') == $name)>
                    {{ $label }}
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
        </div>
        <div class="row mt-3">
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
          <div class="col-12 col-lg-6 col-xl-3">
            <div class="form-group">
              <label for="from_due_date_show">سررسید از تاریخ : </label>
              <input class="form-control fc-datepicker" id="from_due_date_show" type="text" autocomplete="off"/>
              <input name="from_due_date" id="from_due_date" type="hidden" value="{{ request("from_due_date") }}"/>
              <x-core::show-validation-error name="from_due_date"/>
            </div>
          </div>
          <div class="col-12 col-lg-6 col-xl-3">
            <div class="form-group">
              <label for="to_due_date_show">تا تاریخ :</label>
              <input class="form-control fc-datepicker" id="to_due_date_show" type="text" autocomplete="off"/>
              <input name="to_due_date" id="to_due_date" type="hidden" value="{{ request("to_due_date") }}"/>
              <x-core::show-validation-error name="to_due_date"/>
            </div>
          </div>
        </div>
        <x-core::filter-buttons table="payments"/>
      </form>
    </div>
  </div>

  <div class="card">
    <div class="card-header border-0">
      <p class="card-title">چک های پرداختی به تامین کننده ({{ $chequePayments->total() }})</p>
      <x-core::card-options/>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <div class="dataTables_wrapper dt-bootstrap4 no-footer">
          <div class="row">
            <table class="table table-vcenter text-center table-striped text-nowrap table-bordered border-bottom">
              <thead class="thead-light">
              <tr>
                <th>ردیف</th>
                <th>سریال</th>
                <th>صاحب چک</th>
                <th>بانک</th>
                <th>در وجه</th>
                <th>مالک چک</th>
                <th>مبلغ (ریال)</th>
                <th>تاریخ سررسید</th>
                <th>تاریخ پرداخت</th>
                <th>تاریخ ثبت</th>
                <th>وضعیت</th>
              </tr>
              </thead>
              <tbody>
								@forelse ($chequePayments as $payment)
                <tr>
                  <td class="font-weight-bold">{{ $loop->iteration }}</td>
                  <td>{{ $payment->cheque_serial }}</td>
                  <td>{{ $payment->cheque_holder }}</td>
                  <td>{{ $payment->bank_name }}</td>
                  <td>{{ $payment->pay_to }}</td>
                  <td>{{ $payment->is_mine }}</td>
                  <td>{{ number_format($payment->amount) }}</td>
                  <td>@jalaliDateFormat($payment->due_dat)</td>
                  <td>{{ $payment->getPaymentDate() }}</td>
                  <td>@jalaliDate($payment->created_at)</td>
                  <td>
                    <x-core::light-badge
                      type="{{ $payment->status ? 'success' : 'danger' }}"
                      text="{{ $payment->status ? 'پاس شده' : 'پاس نشده' }}"
                    />
                  </td>
                </tr>
              @empty
                <x-core::data-not-found-alert :colspan="11"/>
              @endforelse
              </tbody>
            </table>
            {{ $chequePayments->onEachSide(0)->links("vendor.pagination.bootstrap-4") }}
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('scripts')
  <x-core::date-input-script textInputId="from_payment_date_show" dateInputId="from_payment_date"/>
  <x-core::date-input-script textInputId="to_payment_date_show" dateInputId="to_payment_date"/>
  <x-core::date-input-script textInputId="from_due_date_show" dateInputId="from_due_date"/>
  <x-core::date-input-script textInputId="to_due_date_show" dateInputId="to_due_date"/>
@endsection
