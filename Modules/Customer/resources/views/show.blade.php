@extends('admin.layouts.master')
@section('content')

  <div class="page-header">

    <x-core::breadcrumb
      :items="[
        ['title' => 'لیست مشتریان', 'route_link' => 'admin.customers.index'],
        ['title' => 'نمایش مشتری']
      ]"
    />

    <div class="d-flex align-items-center flex-wrap text-nowrap">
      <a href="{{ route('admin.customers.show-invoice', $customer) }}" class="btn btn-sm btn-purple mx-1 text-white my-md-1">فاکتور</a>
      @can('edit customers')
        <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-sm btn-warning mx-1 my-md-1">ویرایش</a>
      @endcan
      @can('delete customers')
        <button
          onclick="confirmDelete('delete-{{ $customer->id }}')"
          class="btn btn-sm btn-danger mx-1 my-md-1"
          @disabled(!$customer->isDeletable())>
          حذف 
        </button>
        <form
          action="{{ route('admin.customers.destroy', $customer) }}"
          method="POST"
          id="delete-{{ $customer->id }}"
          style="display: none">
          @csrf
          @method('DELETE')
        </form>
      @endcan
      @can('create sales')
        <a href="{{ route('admin.sales.create') }}" class="btn btn-sm btn-indigo mx-1 my-md-1">فاکتور فروش</a>
      @endcan
      @can('view sale_payments')
        <a 
          href="{{ route('admin.sale-payments.index',['customer_id' => $customer->id]) }}" 
          class="btn btn-sm btn-flickr mx-1 my-md-1">
          مشاهده پرداختی ها
        </a>
      @endcan
      @can('create sale_payments')
        <a href="{{ route('admin.sale-payments.create', $customer) }}" class="btn btn-sm btn-lime mx-1 my-md-1">پرداختی جدید</a>
      @endcan
    </div>

  </div>

  <x-core::card>
    <x-slot name="cardTitle">اطلاعات مشتری</x-slot>
    <x-slot name="cardOptions"><x-core::card-options/></x-slot>
    <x-slot name="cardBody">
      <div class="row">
        <div class="col-lg-6">
          <ul class="list-group">
            <li class="list-group-item"><strong>کد: </strong> {{ $customer->id }} </li>
            <li class="list-group-item"><strong>نام و نام خانوادگی: </strong> {{ $customer->name }} </li>
            <li class="list-group-item"><strong>جنسیت: </strong> {{ config('customer.genders.'.$customer->gender) }} </li>
            <li class="list-group-item"><strong>شماره موبایل: </strong> {{ $customer->mobile }} </li>
            <li class="list-group-item"><strong>تلفن ثابت: </strong> {{ $customer->telephone }} </li>
            <li class="list-group-item"><strong>تاریخ تولد: </strong> {{ verta($customer->birthday)->format('Y/m/d') }} </li>
          </ul>
        </div>
        <div class="col-lg-6">
          <ul class="list-group">
            <li class="list-group-item"><strong>وضعیت: </strong> {{ config('customer.statuses.'.$customer->status) }} </li>
            <li class="list-group-item"><strong>تعداد فروش ها: </strong> {{ number_format($salesCount) }} </li>
            <li class="list-group-item"><strong>تعداد پرداختی ها: </strong> {{ number_format($salePaymentsCount) }} </li>
            <li class="list-group-item"><strong>تاریخ ثبت: </strong> @jalaliDate($customer->created_at) </li>
            <li class="list-group-item"><strong>تاریخ آخرین ویرایش: </strong> @jalaliDate($customer->updated_at) </li>
            <li class="list-group-item"><strong>محل سکونت: </strong> {{ $customer->address }} </li>
          </ul>
        </div>
      </div>
    </x-slot>
  </x-core::card>

  <x-customer::sale-statistics :customer="$customer"/>

  <x-core::card>
    <x-slot name="cardTitle">فاکتور های فروش ({{ $salesCount }})</x-slot>
    <x-slot name="cardOptions">
      <div class="card-options">
        @can('create sales')
          <a
            href="{{ route('admin.sales.create', ['customer_id' => $customer->id]) }}" 
            target="_blank"
            class="btn btn-outline-primary btn-sm">
            فاکتور فروش جدید
            <i class="fa fa-plus"></i>
          </a>
        @endcan
      </div>
    </x-slot>
    <x-slot name="cardBody">
      <x-core::table>
        <x-slot name="tableTh">
          <tr>
            <th>ردیف</th>
            <th>شناسه فاکتور</th>
            <th>مبلغ (ریال)</th>
            <th>هزینه دوخت / نصب (ریال)</th>
            <th>تخفیف کلی (ریال)</th>
            <th>مبلغ کل فاکتور (ریال)</th>
            <th>تاریخ فروش</th>
            <th>عملیات</th>
          </tr>
        </x-slot>
        <x-slot name="tableTd">
          @forelse ($sales as $sale)
            <tr>
              <td class="font-weight-bold">{{ $loop->iteration }}</td>
              <td>{{ $sale->id }}</td>
              <td>{{ number_format($sale->getTotalAmount() - $sale->cost_of_sewing) }}</td>
              <td>{{ number_format($sale->cost_of_sewing) }}</td>
              <td>{{ number_format($sale->discount) }}</td>
              <td>{{ number_format($sale->getTotalAmountWithDiscount()) }}</td>
              <td>{{ verta($sale->sold_at)->formatDate() }} </td>
              <td>
                @can('view sales')
                  <a
                    href="{{route('admin.sales.show', $sale)}}"
                    target="_blank"
                    class="btn btn-sm btn-cyan">
                    جزئیات فاکتور
                  </a>
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

  @can('view sale_payments')

    @php
      $paymentsBoxData = [
        ['title' => 'تمامی دریافتی ها','route' => route('admin.sale-payments.index', ['customer_id' => $customer->id])],
        ['title' => 'دریافتی های پرداخت شده','route' => route('admin.sale-payments.index', ['customer_id' => $customer->id, 'status' => 1])],
        ['title' => 'دریافتی های پرداخت نشده','route' => route('admin.sale-payments.index', ['customer_id' => $customer->id, 'status' => 0])],
        ['title' => 'تمامی اقساط','route' => route('admin.sale-payments.installments', ['customer_id' => $customer->id])],
        ['title' => 'اقساط پرداخت شده','route' => route('admin.sale-payments.installments', ['customer_id' => $customer->id, 'status' => 1])],
        ['title' => 'اقساط پرداخت نشده','route' => route('admin.sale-payments.installments', ['customer_id' => $customer->id, 'status' => 0])],
        ['title' => 'تمامی چک ها','route' => route('admin.sale-payments.cheques', ['customer_id' => $customer->id])],
        ['title' => 'چک های پاس شده','route' => route('admin.sale-payments.cheques', ['customer_id' => $customer->id, 'status' => 1])],
        ['title' => 'چک های پاس نشده','route' => route('admin.sale-payments.cheques', ['customer_id' => $customer->id, 'status' => 0])],
        ['title' => 'تمامی نقدی ها','route' => route('admin.sale-payments.cashes', ['customer_id' => $customer->id])],
        ['title' => 'نقدی های پرداخت شده','route' => route('admin.sale-payments.cashes', ['customer_id' => $customer->id, 'status' => 1])],
        ['title' => 'نقدی های پرداخت نشده','route' => route('admin.sale-payments.cashes', ['customer_id' => $customer->id, 'status' => 0])],
      ];
    @endphp 

    <div class="row">
      @foreach ($paymentsBoxData as $item)
        <div class="col-xl-2 col-lg-3 col-md-12">
          <a href="{{ $item['route'] }}" target="_blank">
            <div class="card">
              <div class="card-body payment-box-body text-center">
                <span class="payment-box-title font-weight-bold">{{ $item['title'] }}</span>
              </div>
            </div>
          </a>
        </div>
      @endforeach
    </div>
    
  @endcan

  <x-core::card>
    <x-slot name="cardTitle">آخرین دریافتی های ثبت شده</x-slot>
    <x-slot name="cardOptions">
      <div class="card-options">
        @can('create sale_payments')
          <a href="{{ route('admin.sale-payments.create', $customer) }}" target="_blank" class="btn btn-sm btn-outline-primary">
            دریافتی جدید
            <i class="fa fa-plus"></i>
          </a>
        @endcan
      </div>
    </x-slot>
    <x-slot name="cardBody">
      <x-core::table>
        <x-slot name="tableTh">
          <tr>
            <th>ردیف</th>
            <th>نوع پراخت</th>
            <th>مبلغ (ریال)</th>
            <th>تاریخ پرداخت</th>
            <th>عکس رسید</th>
            <th>تاریخ سررسید</th>
            <th>وضعیت</th>
            <th>تاریخ ثبت</th>
          </tr>
        </x-slot>
        <x-slot name="tableTd">
          @forelse ($salePayments as $payment)
            <tr>
              <td class="font-weight-bold">{{ $loop->iteration }}</td>
              <td>{{ $payment->getType() }}</td>
              <td>{{ number_format($payment->amount) }}</td>
              <td>{{ $payment->getPaymentDate() }}</td>
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
              <td> @jalaliDateFormat($payment->due_date)</td>
              <td>
                <x-core::light-badge
                  type="{{ $payment->status ? 'success' : 'danger' }}"
                  text="{{ $payment->status ? 'پرداخت شده' : 'پرداخت نشده' }}"
                />
              </td>
              <td> @jalaliDate($payment->created_at)</td>
            </tr>
          @empty
            <x-core::data-not-found-alert :colspan="8"/>
          @endforelse
        </x-slot>
      </x-core::table>
    </x-slot>
  </x-core::card>
@endsection
