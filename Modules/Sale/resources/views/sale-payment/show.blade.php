@extends('admin.layouts.master')
@section('content')
  <div class="page-header">

    <x-core::breadcrumb
      :items="[
        ['title' => 'لیست مشتریان', 'route_link' => 'admin.customers.index'],
        ['title' => 'نمایش مشتری', 'route_link' => 'admin.customers.show', 'parameter' => $customer],
        ['title' => 'دریافتی ها'],
      ]"
    />

    @can('create payments')
      <x-core::create-button route="admin.sale-payments.create" :param="$customer" title="ثبت دریافتی جدید"/>
    @endcan
  
  </div>

  <x-core::card>
    <x-slot name="cardTitle">اطلاعات مشتری</x-slot>
    <x-slot name="cardOptions"><x-core::card-options/></x-slot>
    <x-slot name="cardBody">
      <div class="row">
        <div class="col-xl-6">
          <ul class="list-group">
            <li class="list-group-item"><strong>کد : </strong> {{ $customer->id }} </li>
            <li class="list-group-item">
              <strong>نام و نام خانوادگی : </strong>
              <a href="{{ route('admin.customers.show', $customer) }}">{{ $customer->name }}</a>
            </li>
            <li class="list-group-item">
              <strong>وضعیت : </strong>
              @if ($customer->status)
                <span class="text-success">فعال</span>
              @else
                <span class="text-danger">غیر فعال</span>
              @endif
            </li>
            <li class="list-group-item"><strong>شماره موبایل: </strong> {{ $customer->mobile }} </li>
          </ul>
        </div>
        <div class="col-xl-6">
          <ul class="list-group">
            <li class="list-group-item"><strong>آدرس: </strong> {{ $customer->address }} </li>
            <li class="list-group-item"><strong>تعداد خرید ها : </strong>{{ number_format($customer->countSales()) }} </li>
            <li class="list-group-item"><strong>تعداد پرداختی ها : </strong>{{ number_format($customer->countPayments()) }} </li>
            <li class="list-group-item"><strong>تاریخ ثبت : </strong> @jalaliDate($customer->created_at) </li>
          </ul>
        </div>
      </div>
    </x-slot>
  </x-core::card>

  @include('customer::includes.helper-boxes')

  <x-core::card>
    <x-slot name="cardTitle">نقدی ها ({{ $cashPayments->count() }})</x-slot>
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
                <x-sale::sale-payment-description-button target="#payment-description-modal{{$payment->id}}"/>
                @can('edit sale_payments')
                  <x-core::edit-button target="#edit-cash-payment-modal{{$payment->id}}"/>
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
              <td class="font-weight-bold">{{ $loop->iteration }}</td>
              <td>{{ number_format($payment->amount) }}</td>
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
              <td> @jalaliDateFormat($payment->due_date) </td>
              <td>
                <x-core::light-badge
                  type="{{ $payment->status ? 'success' : 'danger' }}"
                  text="{{ $payment->status ? 'پرداخت شده' : 'پرداخت نشده' }}"
                />
              </td>
              <td> @jalaliDate($payment->created_at) </td>
              <td>
                <x-sale::sale-payment-description-button target="#payment-description-modal{{$payment->id}}"/>
                @can('edit sale_payments')
                  <x-core::edit-button target="#edit-installment-payment-modal{{$payment->id}}"/>
                @endcan
                @can('delete sale_payments')
                  <x-core::delete-button route="admin.sale-payments.destroy" :model="$payment"/>
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
              <td>{{ verta($payment->due_date)->format('Y/m/d') }} </td>
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
                <x-sale::sale-payment-description-button target="#payment-description-modal{{$payment->id}}"/>
                @can('edit sale_payments')
                  <x-core::edit-button target="#edit-cheque-payment-modal{{$payment->id}}"/>
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
      </x-core::table>
    </x-slot>
  </x-core::card>

  <x-sale::edit-sale-payment-modal :payments="$cashPayments" idExtention="edit-cash-payment-modal"/>
  <x-sale::edit-sale-payment-modal :payments="$chequePayments" idExtention="edit-cheque-payment-modal"/>
  <x-sale::edit-sale-payment-modal :payments="$installmentPayments" idExtention="edit-installment-payment-modal"/>
  <x-sale::sale-payment-description-modal :payments="$salePayments" idExtention="payment-description-modal"/>

  {{-- <div class="card">
    <div class="card-header border-0">
      <p class="card-title">پرداختی های نقدی ({{ $cashPayments->count() }})</p>
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
                <th class="text-center">مبلغ پرداختی (ریال)</th>
                <th class="text-center">تاریخ پرداخت</th>
                <th class="text-center">عکس رسید</th>
                <th class="text-center">تاریخ ثبت</th>
                <th class="text-center">عملیات</th>
              </tr>
              </thead>
              <tbody>
              @forelse ($cashPayments as $payment)
                <tr>
                  <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                  <td class="text-center">{{ number_format($payment->amount) }}</td>
                  <td class="text-center"> @jalaliDate($payment->payment_date) </td>
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
                  <td class="text-center"> @jalaliDate($payment->created_at)</td>
                  <td class="text-center">
                    <button
                      class="btn btn-sm btn-icon btn-primary"
                      onclick="showPaymentDescriptionModal('{{$payment->description}}')"
                      data-toggle="tooltip"
                      data-original-title="توضیحات">
                      <i class="fa fa-book" ></i>
                    </button>
                    @can('edit sale_payments')
                      <button
                        data-target="#editSalePaymentModal-{{$payment->id}}"
                        class="btn btn-sm btn-icon btn-warning text-white"
                        data-toggle="modal">
                        <i class="fa fa-pencil" ></i>
                      </button>
                    @endcan
                    @can('delete sale_payments')
                      <x-core::delete-button route="admin.sale-payments.destroy" :model="$payment"/>
                    @endcan
                  </td>
                </tr>
              @empty
                <x-core::data-not-found-alert :colspan="6"/>
              @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header border-0">
      <p class="card-title">اقساط ({{ $installmentPayments->count() }})</p>
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
                <th class="text-center">مبلغ (ریال)</th>
                <th class="text-center">تاریخ پرداخت</th>
                <th class="text-center">عکس رسید</th>
                <th class="text-center">تاریخ سررسید</th>
                <th class="text-center">وضعیت</th>
                <th class="text-center">تاریخ ثبت</th>
                <th class="text-center">عملیات</th>
              </tr>
              </thead>
              <tbody>
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
                    <x-core::badge
                      type="{{ $payment->status ? 'success' : 'danger' }}"
                      text="{{ $payment->status ? 'پرداخت شده' : 'پرداخت نشده' }}"
                    />
                  </td>
                  <td class="text-center"> @jalaliDate($payment->created_at) </td>
                  <td class="text-center">
                    <button
                      class="btn btn-sm btn-icon btn-primary"
                      onclick="showPaymentDescriptionModal('{{$payment->description}}')"
                      data-toggle="tooltip"
                      data-original-title="توضیحات">
                      <i class="fa fa-book" ></i>
                    </button>
                    @can('edit sale_payments')
                      <button
                        data-target="#editSalePaymentModal-{{$payment->id}}"
                        class="btn btn-sm btn-icon btn-warning text-white"
                        data-toggle="modal">
                        <i class="fa fa-pencil" ></i>
                      </button>
                    @endcan
                    @can('delete sale_payments')
                      <x-core::delete-button route="admin.sale-payments.destroy" :model="$payment"/>
                    @endcan
                  </td>
                </tr>
              @empty
                <x-core::data-not-found-alert :colspan="8"/>
              @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header border-0">
      <p class="card-title">چک ها ({{ $chequePayments->count() }})</p>
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
                <th class="text-center">سریال</th>
                <th class="text-center">صاحب چک</th>
                <th class="text-center">بانک</th>
                <th class="text-center">در وجه</th>
                <th class="text-center">مالک چک</th>
                <th class="text-center">مبلغ (ریال)</th>
                <th class="text-center">تاریخ سررسید</th>
                <th class="text-center">تاریخ پرداخت</th>
                <th class="text-center">عکس رسید</th>
                <th class="text-center">وضعیت</th>
                <th class="text-center">عملیات</th>
              </tr>
              </thead>
              <tbody>
              @forelse ($chequePayments as $payment)
                <tr>
                  <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                  <td class="text-center">{{ $payment->cheque_serial }}</td>
                  <td class="text-center">{{ $payment->cheque_holder }}</td>
                  <td class="text-center">{{ $payment->bank_name }}</td>
                  <td class="text-center">{{ $payment->pay_to }}</td>
                  <td class="text-center">{{ $payment->is_mine }}</td>
                  <td class="text-center">{{ number_format($payment->amount) }}</td>
                  <td class="text-center"> {{ verta($payment->due_date)->format('Y/m/d') }} </td>
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
                  <td class="text-center">
                    <x-core::badge
                      type="{{ $payment->status ? 'success' : 'danger' }}"
                      text="{{ $payment->status ? 'پاس شده' : 'پاس نشده' }}"
                    />
                  </td>
                  <td class="text-center">
                    <button
                      class="btn btn-sm btn-icon btn-primary"
                      onclick="showPaymentDescriptionModal('{{$payment->description}}')"
                      data-toggle="tooltip"
                      data-original-title="توضیحات">
                      <i class="fa fa-book" ></i>
                    </button>
                    @can('edit sale_payments')
                      <button
                        data-target="#editSalePaymentModal-{{$payment->id}}"
                        class="btn btn-sm btn-icon btn-warning text-white"
                        data-toggle="modal">
                        <i class="fa fa-pencil" ></i>
                      </button>
                    @endcan
                    @can('delete sale_payments')
                      <x-core::delete-button route="admin.sale-payments.destroy" :model="$payment"/>
                    @endcan
                  </td>
                </tr>
              @empty
                <x-core::data-not-found-alert :colspan="12"/>
              @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div> --}}

  {{-- @include('sale::sale-payment._show-description-modal')
  @include('sale::sale-payment.edit-modal') --}}
@endsection

@section('scripts')
  <x-sale::edit-sale-payment-scripts   
    :cashes="$cashPayments"
    :cheques="$chequePayments"
    :installments="$installmentPayments"
  />  
@endsection
