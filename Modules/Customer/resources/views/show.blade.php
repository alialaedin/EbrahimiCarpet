@extends('admin.layouts.master')
@section('content')
  <div class="page-header">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}">
          <i class="fe fe-home ml-1"></i> داشبورد
        </a>
      </li>
      <li class="breadcrumb-item">
        <a href="{{ route('admin.customers.index') }}">لیست مشتریان</a>
      </li>
      <li class="breadcrumb-item active">
        <a>نمایش مشتری</a>
      </li>
    </ol>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
      <a
        href="{{ route('admin.customers.show-invoice', $customer) }}"
        class="btn btn-sm btn-purple mx-1 text-white my-md-1">
        فاکتور
        <i class="fe fe-printer mr-1"></i>
      </a>
      @can('edit customers')
        <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-sm btn-warning mx-1 my-md-1">
          ویرایش <i class="fa fa-pencil mr-1"></i>
        </a>
      @endcan
      @can('delete customers')
        <button
          onclick="confirmDelete('delete-{{ $customer->id }}')"
          class="btn btn-sm btn-danger mx-1 my-md-1"
          @disabled(!$customer->isDeletable())>
          حذف <i class="fa fa-trash-o mr-1"></i>
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
        <a href="{{ route('admin.sales.create') }}" class="btn btn-sm btn-indigo mx-1 my-md-1">
          فاکتور فروش
          <i class="fa fa-plus mr-1"></i>
        </a>
      @endcan
      @can('view sale_salePayments')
        <a href="{{ route('admin.sale-payments.show', $customer) }}" class="btn btn-sm btn-flickr mx-1 my-md-1">
          مشاهده پرداختی ها
          <i class="fa fa-eye mr-1"></i>
        </a>
      @endcan
      @can('create sale_salePayments')
        <a href="{{ route('admin.sale-payments.create', $customer) }}" class="btn btn-sm btn-lime mx-1 my-md-1">
          پرداختی جدید
          <i class="fa fa-plus mr-1"></i>
        </a>
      @endcan
    </div>
  </div>
  <div class="card">
    <div class="card-header border-0">
      <p class="card-title">اطلاعات مشتری</p>
      <x-core::card-options/>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-lg-6 px-0">
          <ul class="list-group">
            <li class="list-group-item"><strong>کد: </strong> {{ $customer->id }} </li>
            <li class="list-group-item"><strong>نام و نام خانوادگی: </strong> {{ $customer->name }} </li>
            <li class="list-group-item"><strong>جنسیت: </strong> {{ config('customer.genders.'.$customer->gender) }} </li>
            <li class="list-group-item"><strong>شماره موبایل: </strong> {{ $customer->mobile }} </li>
            <li class="list-group-item"><strong>تلفن ثابت: </strong> {{ $customer->telephone }} </li>
            <li class="list-group-item"><strong>تاریخ تولد: </strong> {{ verta($customer->birthday)->format('Y/m/d') }} </li>
          </ul>
        </div>
        <div class="col-lg-6 px-0">
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

    </div>
  </div>
  <div class="row">
    <div class="col-xl-4 col-lg-6 col-md-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-9">
              <div class="mt-0 text-right">
                <span class="fs-16 font-weight-semibold"> مبلغ کل فروش ها (ریال) : </span>
                <h3 class="mb-0 mt-1 text-info fs-20"> {{ number_format($customer->calcTotalSalesAmount()) }} </h3>
              </div>
            </div>
            <div class="col-3">
              <div class="icon1 bg-info-transparent my-auto float-left">
                <i class="fa fa-money"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-4 col-lg-6 col-md-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-9">
              <div class="mt-0 text-right">
                <span class="fs-16 font-weight-semibold"> جمع دریافتی ها (ریال) : </span>
                <h3 class="mb-0 mt-1 text-success fs-20"> {{ number_format($customer->calcTotalSalePaymentsAmount()) }} </h3>
              </div>
            </div>
            <div class="col-3">
              <div class="icon1 bg-success-transparent my-auto float-left">
                <i class="fa fa-money"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-4 col-lg-6 col-md-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-9">
              <div class="mt-0 text-right">
                <span class="fs-16 font-weight-semibold"> مبلغ باقی مانده (ریال) : </span>
                <h3 class="mb-0 mt-1 text-danger fs-20"> {{ number_format($customer->getRemainingAmount()) }}  </h3>
              </div>
            </div>
            <div class="col-3">
              <div class="icon1 bg-danger-transparent my-auto float-left">
                <i class="fa fa-money"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header border-0">
      <p class="card-title">فروش ها ({{ $salesCount }})</p>
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
                <th class="text-center">مبلغ فروش (ریال)</th>
                <th class="text-center">تخفیف کلی (ریال)</th>
                <th class="text-center">مبلغ فروش با تخفیف (ریال)</th>
                <th class="text-center">تاریخ فروش</th>
                <th class="text-center">عملیات</th>
              </tr>
              </thead>
              <tbody>
              @forelse ($sales as $sale)
                <tr>
                  <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                  <td class="text-center">{{ number_format($sale->getTotalAmount()) }}</td>
                  <td class="text-center">{{ number_format($sale->discount) }}</td>
                  <td class="text-center">{{ number_format($sale->getTotalAmountWithDiscount()) }}</td>
                  <td class="text-center"> @jalaliDate($sale->sold_at) </td>
                  <td class="text-center">
                    @can('view sales')
                      <a
                        href="{{route('admin.sales.show', $sale)}}"
                        target="_blank"
                        class="btn btn-sm btn-cyan">
                        جزئیات فروش ها
                      </a>
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
      <p class="card-title">پرداختی ها ({{ $salePaymentsCount }})</p>
      <x-core::card-options/>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <div class="dataTables_wrapper dt-bootstrap4 no-footer">
          <div class="row">
            <table class="table table-vcenter table-striped text-nowrap table-bordered border-bottom">
              <thead class="thead-light">
                <tr>
                  <th class="text-center border-top">ردیف</th>
                  <th class="text-center border-top">نوع پراخت</th>
                  <th class="text-center border-top">مبلغ (ریال)</th>
                  <th class="text-center border-top">تاریخ پرداخت</th>
                  <th class="text-center border-top">عکس رسید</th>
                  <th class="text-center border-top">تاریخ سررسید</th>
                  <th class="text-center border-top">وضعیت</th>
                  <th class="text-center border-top">تاریخ ثبت</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($salePayments as $payment)
                  <tr>
                    <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ $payment->getType() }}</td>
                    <td class="text-center">{{ number_format($payment->amount) }}</td>
                    <td class="text-center">{{ $payment->getPaymentDate() }}</td>
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
                    <td class="text-center"> @jalaliDate($payment->due_date) </td>
                    <td class="text-center">
                      <x-core::badge
                        type="{{ $payment->status ? 'success' : 'danger' }}"
                        text="{{ $payment->status ? 'پرداخت شده' : 'پرداخت نشده' }}"
                      />
                    </td>
                    <td class="text-center"> @jalaliDate($payment->created_at) </td>
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
@endsection
