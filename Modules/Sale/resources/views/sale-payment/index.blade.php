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
        <a>دریافتی ها</a>
      </li>
    </ol>
  </div>
  <div class="card">
    <div class="card-header border-0">
      <p class="card-title">جستجوی پیشرفته</p>
      <x-core::card-options/>
    </div>
    <div class="card-body">
      <div class="row">
        <form action="{{ route("admin.sale-payments.index") }}" class="col-12">
          <div class="row">
            <div class="col-12 col-md-6 col-xl-3">
              <div class="form-group">
                <label for="customer_id">مشتری :</label>
                <select name="customer_id" id="customer_id" class="form-control">
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
                <label for="from_payment_date_show">پرداخت از تاریخ : <span class="text-danger">&starf;</span></label>
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
                <label for="from_due_date_show">سررسید از تاریخ : <span class="text-danger">&starf;</span></label>
                <input class="form-control fc-datepicker" id="from_due_date_show" type="text" autocomplete="off" required/>
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
  </div>
  <div class="card">
    <div class="card-header border-0">
      <p class="card-title">لیست تمام پرداختی ها ({{ $totalPayments }})</p>
      <x-core::card-options/>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <div class="dataTables_wrapper dt-bootstrap4 no-footer">
          <div class="row">
            <table class="table table-vcenter table-striped text-nowrap table-bordered border-bottom">
              <thead class="thead-light">
              <tr>
                <th class="text-center">ردیف</th>
                <th class="text-center">مشتری</th>
                <th class="text-center">مبلغ پرداختی (تومان)</th>
                <th class="text-center">نوع پرداخت</th>
                <th class="text-center">عکس رسید</th>
                <th class="text-center">تاریخ سررسید</th>
                <th class="text-center">تاریخ پرداخت</th>
                <th class="text-center">تاریخ ثبت</th>
                <th class="text-center">وضعیت</th>
                <th class="text-center">عملیات</th>
              </tr>
              </thead>
              <tbody>
              @forelse ($payments as $payment)
                <tr>
                  <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                  <td class="text-center">
                    <a href="{{ route('admin.suppliers.show', $payment->customer->id) }}">
                      {{ $payment->customer->name }}
                    </a>
                  </td>
                  <td class="text-center">{{ number_format($payment->amount) }}</td>
                  <td class="text-center">{{ config('payment.types.'.$payment->type) }}</td>
                  <td class="text-center m-0 p-0">
                    @if ($payment->image)
                      <figure class="figure my-2">
                        <a target="_blank" href="{{ Storage::url($payment->image) }}">
                          <img
                            src="{{ Storage::url($payment->image) }}"
                            class="img-thumbnail"
                            alt="image"
                            width="50"
                            style="max-height: 32px;"
                          />
                        </a>
                      </figure>
                    @else
                      <span> - </span>
                    @endif
                  </td>
                  <td class="text-center"> @jalaliDate($payment->due_date)</td>
                  <td class="text-center"> @jalaliDate($payment->payment_date)</td>
                  <td class="text-center"> @jalaliDate($payment->created_at)</td>
                  <td class="text-center">
                    <x-core::badge
                      type="{{ $payment->status ? 'success' : 'danger' }}"
                      text="{{ config('payment.statuses.'.$payment->type.'.'.$payment->status) }}"
                    />
                  </td>
                  <td class="text-center">
                    <a
                      class="btn btn-lime btn-sm btn-icon"
                      href="{{ route('admin.payments.create', $payment->supplier) }}"
                      data-toggle="tooltip"
                      data-original-title="ثبت پرداختی">
                      <i class="fa fa-plus-circle"></i>
                    </a>
                    <x-core::show-button route="admin.payments.show" :model="$payment->supplier"/>
                    <button
                      class="btn btn-sm btn-icon btn-teal "
                      onclick="showPaymentDescriptionModal('{{$payment->description}}')"
                      data-toggle="tooltip"
                      data-original-title="توضیحات">
                      <i class="fa fa-book"></i>
                    </button>
                    @can('edit payments')
                      <x-core::edit-button route="admin.payments.edit" :model="$payment"/>
                    @endcan
                    @can('delete payments')
                      <x-core::delete-button route="admin.payments.destroy" :model="$payment"/>
                    @endcan
                  </td>
                </tr>
              @empty
                <x-core::data-not-found-alert :colspan="10"/>
              @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  @include('payment::_show-description-modal')
@endsection

@section('scripts')

  <x-core::date-input-script textInputId="from_payment_date_show" dateInputId="from_payment_date"/>
  <x-core::date-input-script textInputId="to_payment_date_show" dateInputId="to_payment_date"/>
  <x-core::date-input-script textInputId="from_due_date_show" dateInputId="from_due_date"/>
  <x-core::date-input-script textInputId="to_due_date_show" dateInputId="to_due_date"/>

  <script>
    function showPaymentDescriptionModal(description) {
      let modal = $('#showDescriptionModal');
      modal.find('#description').text(description ?? '-');
      modal.modal('show');
    }
  </script>
@endsection
