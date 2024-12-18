@extends('admin.layouts.master')
@section('content')

  <div class="page-header">

    <x-core::breadcrumb
      :items="[
        ['title' => 'لیست تامین کنندگان', 'route_link' => 'admin.suppliers.index'],
        ['title' => 'نمایش تامین کننده', 'route_link' => 'admin.suppliers.show', 'parameter' => $supplier],
        ['title' => 'پرداختی ها'],
      ]"
    />

    @can('create payments')
      <x-core::create-button route="admin.payments.create" :param="$supplier" title="ثبت پرداختی جدید"/>
    @endcan

  </div>

  <x-core::card>
    <x-slot name="cardTitle">اطلاعات تامین کننده</x-slot>
    <x-slot name="cardOptions"><x-core::card-options/></x-slot>
    <x-slot name="cardBody">
      <div class="row">
        <div class="col-xl-6">
          <ul class="list-group">
            <li class="list-group-item"><strong>کد : </strong> {{ $supplier->id }} </li>
            <li class="list-group-item"><strong>نام و نام خانوادگی : </strong> {{ $supplier->name }} </li>
            <li class="list-group-item">
              <strong>وضعیت : </strong>
              @if ($supplier->status)
                <span class="text-success">فعال</span>
              @else
                <span class="text-danger">غیر فعال</span>
              @endif
            </li>
            <li class="list-group-item"><strong>شماره موبایل: </strong> {{ $supplier->mobile }} </li>
          </ul>
        </div>
        <div class="col-xl-6">
          <ul class="list-group">
            <li class="list-group-item"><strong>آدرس: </strong> {{ $supplier->address }} </li>
            <li class="list-group-item"><strong>تعداد خرید ها : </strong>{{ number_format($supplier->purchases_count) }} </li>
            <li class="list-group-item"><strong>تعداد پرداختی ها : </strong>{{ number_format($supplier->payments_count) }} </li>
            <li class="list-group-item"><strong>تاریخ ثبت : </strong> @jalaliDate($supplier->created_at) </li>
          </ul>
        </div>
      </div>
    </x-slot>
  </x-core::card>

  <x-supplier::purchase-statistics :supplier="$supplier"/>

  <x-core::card>
    <x-slot name="cardTitle">پرداختی های نقدی ({{ $cashPayments->count() }})</x-slot>
    <x-slot name="cardOptions"><x-core::card-options/></x-slot>
    <x-slot name="cardBody">
      <x-core::table>
        <x-slot name="tableTh">
          <tr>
            <th>ردیف</th>
            <th>مبلغ پرداختی (ریال)</th>
            <th>تاریخ پرداخت</th>
            <th>عکس رسید</th>
            <th>تاریخ ثبت</th>
            <th>عملیات</th>
          </tr>
        </x-slot>
        <x-slot name="tableTd">
          @forelse ($cashPayments as $payment)
            <tr>
              <td class="font-weight-bold">{{ $loop->iteration }}</td>
              <td>{{ number_format($payment->amount) }}</td>
              <td> @jalaliDateFormat($payment->payment_date) </td>
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
              <td> @jalaliDate($payment->created_at) </td>
              <td>
                <button
                  class="btn btn-sm btn-icon btn-teal"
                  data-toggle="modal"
                  data-target="#payment-description-modal{{$payment->id}}">
                  <i class="fa fa-book"></i>
                </button>
                @can('edit payments')
                  <x-core::edit-button target="#edit-payment-modal{{$payment->id}}"/>
                @endcan
                @can('delete payments')
                  <x-core::delete-button route="admin.payments.destroy" :model="$payment"/>
                @endcan
              </td>
            </tr>
          @empty
            <x-core::data-not-found-alert :colspan="6"/>
          @endforelse
        </x-slot>
      </x-core::table>
    </x-slot>
  </x-core::card>

  <x-core::card>
    <x-slot name="cardTitle">اقساط ({{ $installmentPayments->count() }})</x-slot>
    <x-slot name="cardOptions"><x-core::card-options/></x-slot>
    <x-slot name="cardBody">
      <x-core::table>
        <x-slot name="tableTh">
          <tr>
            <th>ردیف</th>
            <th>مبلغ (ریال)</th>
            <th>تاریخ پرداخت</th>
            <th>عکس رسید</th>
            <th>تاریخ سررسید</th>
            <th>وضعیت</th>
            <th>تاریخ ثبت</th>
            <th>عملیات</th>
          </tr>
        </x-slot>
        <x-slot name="tableTd">
          @forelse ($installmentPayments as $payment)
            <tr>
              <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
              <td class="text-center">{{ number_format($payment->amount) }}</td>
              <td class="text-center">{{ $payment->getPaymentDate() }}</td>
              <td class="text-center m-0 p-0">
                @if ($payment->image)
                  <figure class="figure my-2">
                    <a target="_blank" href="{{ Storage::url($payment->image) }}">
                      <img src="{{ Storage::url($payment->image) }}" class="img-thumbnail" alt="image" width="50" style="max-height: 32px;" />
                    </a>
                  </figure>
                @else
                  <span> - </span>
                @endif
              </td>
              <td class="text-center"> @jalaliDateFormat($payment->due_date) </td>
              <td class="text-center">
                <x-core::light-badge
                  type="{{ $payment->status ? 'success' : 'danger' }}"
                  text="{{ $payment->status ? 'پرداخت شده' : 'پرداخت نشده' }}"
                />
              </td>
              <td class="text-center"> @jalaliDate($payment->created_at) </td>
              <td class="text-center">
                <button
                  class="btn btn-sm btn-icon btn-teal"
                  data-toggle="modal"
                  data-target="#payment-description-modal{{$payment->id}}">
                  <i class="fa fa-book"></i>
                </button>
                @can('edit payments')
                  <x-core::edit-button target="edit-payment-modal{{$payment->id}}"/>
                @endcan
                @can('delete payments')
                  <x-core::delete-button route="admin.payments.destroy" :model="$payment"/>
                @endcan
              </td>
            </tr>
          @empty
            <x-core::data-not-found-alert :colspan="8"/>
          @endforelse
        </x-slot>
      </x-core::table>
    </x-slot>
  </x-core::card>

  <x-core::card>
    <x-slot name="cardTitle">چک ها ({{ $chequePayments->count() }})</x-slot>
    <x-slot name="cardOptions"><x-core::card-options/></x-slot>
    <x-slot name="cardBody">
      <x-core::table>
        <x-slot name="tableTh">
          <tr>
            <th>ردیف</th>
            <th>سریال</th>
            <th>صاحب چک</th>
            <th>بانک</th>
            <th>در وجه</th>
            <th>مبلغ (ریال)</th>
            <th>تاریخ سررسید</th>
            <th>تاریخ پرداخت</th>
            <th>عکس رسید</th>
            <th>وضعیت</th>
            <th>عملیات</th>
          </tr>
        </x-slot>
        <x-slot name="tableTd">
          @forelse ($chequePayments as $payment)
            <tr>
              <td class="font-weight-bold">{{ $loop->iteration }}</td>
              <td>{{ $payment->cheque_serial }}</td>
              <td>{{ $payment->cheque_holder }}</td>
              <td>{{ $payment->bank_name }}</td>
              <td>{{ $payment->pay_to }}</td>
              <td>{{ number_format($payment->amount) }}</td>
              <td> {{ verta($payment->due_date)->format('Y/m/d') }} </td>
              <td>{{ $payment->getPaymentDate() }}</td>
              <td class="m-0 p-0">
                @if ($payment->image)
                  <figure class="figure my-2">
                    <a target="_blank" href="{{ Storage::url($payment->image) }}">
                      <img src="{{ Storage::url($payment->image) }}" class="img-thumbnail" alt="image" width="50" style="max-height: 32px;" />
                    </a>
                  </figure>
                @else
                  <span> - </span>
                @endif
              </td>
              <td>
                <x-core::light-badge
                  type="{{ $payment->status ? 'success' : 'danger' }}"
                  text="{{ $payment->status ? 'پاس شده' : 'پاس نشده' }}"
                />
              </td>
              <td>
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
            <x-core::data-not-found-alert :colspan="11"/>
          @endforelse
        </x-slot>
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
@endsection