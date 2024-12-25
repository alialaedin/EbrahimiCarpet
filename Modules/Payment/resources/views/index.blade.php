@extends('admin.layouts.master')
@section('content')

<div class="page-header">
  <x-core::breadcrumb :items="[['title' => 'پرداختی ها']]" />
</div>

  <x-core::card>
    <x-slot name="cardTitle">جستجوی پیشرفته</x-slot>
    <x-slot name="cardOptions"><x-core::card-options/></x-slot>
    <x-slot name="cardBody">
      <div class="row">
        <form action="{{ route("admin.payments.index") }}" class="col-12">
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
    </x-slot>
  </x-core::card>

  <x-core::card>
    <x-slot name="cardTitle">لیست تمام پرداختی ها ({{ $totalPayments }})</x-slot>
    <x-slot name="cardOptions"><x-core::card-options/></x-slot>
    <x-slot name="cardBody">
      <x-core::table>
        <x-slot name="tableTh">
          <tr>
            <th>ردیف</th>
            <th>تامین کننده</th>
            <th>مبلغ پرداختی (ریال)</th>
            <th>نوع پرداخت</th>
            <th>عکس رسید</th>
            <th>تاریخ سررسید</th>
            <th>تاریخ پرداخت</th>
            <th>تاریخ ثبت</th>
            <th>وضعیت</th>
            <th>عملیات</th>
          </tr>
        </x-slot>
        <x-slot name="tableTd">
          @forelse ($payments as $payment)
            <tr>
              <td class="font-weight-bold">{{ $loop->iteration }}</td>
              <td>
                <a href="{{ route('admin.suppliers.show', $payment->supplier->id) }}">
                  {{ $payment->supplier->name }}
                </a>
              </td>
              <td>{{ number_format($payment->amount) }}</td>
              <td>{{ config('payment.types.'.$payment->type) }}</td>
              <td class="m-0 p-0">
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
              <td> {{ $payment->getDueDate() }}</td>
              <td> {{ $payment->getPaymentDate() }}</td>
              <td> @jalaliDate($payment->created_at)</td>
              <td>
                <x-core::light-badge
                  type="{{ $payment->status ? 'success' : 'danger' }}"
                  text="{{ config('payment.statuses.'.$payment->type.'.'.$payment->status) }}"
                />
              </td>
              <td>
                <x-core::show-button route="admin.payments.show" :model="$payment->supplier"/>
                <x-payment::payment-description-button target="#payment-description-modal{{$payment->id}}"/>
                @can('edit payments')
                  <x-core::edit-button target="#edit-payment-modal{{$payment->id}}"/>
                @endcan
                @can('delete payments')
                  <x-core::delete-button route="admin.payments.destroy" :model="$payment"/>
                @endcan
              </td>
            </tr>
          @empty
            <x-core::data-not-found-alert :colspan="10"/>
          @endforelse
        </x-slot>
        <x-slot name="extraData">{{ $payments->onEachSide(0)->links("vendor.pagination.bootstrap-4") }}</x-slot>
      </x-core::table>
    </x-slot>
  </x-core::card>

  <x-payment::edit-payment-modal :payments="$payments" idExtention="edit-payment-modal"/>
  <x-payment::payment-description-modal :payments="$payments" idExtention="payment-description-modal"/>

@endsection

@section('scripts')

  <x-payment::edit-payment-scripts   
    :cashes="$cashPayments"
    :cheques="$chequePayments"
    :installments="$installmentPayments"
  />  

  <script>
    new CustomSelect('#type', 'انتخاب نوع پرداخت'); 
    new CustomSelect('#supplier_id', 'انتخاب تامین کننده'); 
    new CustomSelect('#status', 'انتخاب وضعیت'); 
  </script>

  <x-core::date-input-script textInputId="from_payment_date_show" dateInputId="from_payment_date"/>
  <x-core::date-input-script textInputId="to_payment_date_show" dateInputId="to_payment_date"/>
  <x-core::date-input-script textInputId="from_due_date_show" dateInputId="from_due_date"/>
  <x-core::date-input-script textInputId="to_due_date_show" dateInputId="to_due_date"/>

@endsection
