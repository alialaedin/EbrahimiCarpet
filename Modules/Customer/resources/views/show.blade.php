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
        class="btn btn-purple mx-1 text-white my-md-1"
        style="padding: 4px 12px;"
{{--        href="{{ route('admin.sales.invoice.show', $sale) }}"--}}
{{--        target="_blank"--}}
        >
        صدور فاکتور
        <i class="fe fe-printer mr-1"></i>
      </a>
      @can('edit customers')
        <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-warning mx-1 my-md-1">
          ویرایش مشتری
          <i class="fa fa-pencil mr-1"></i>
        </a>
      @endcan
      @can('delete customers')
        <button
          onclick="confirmDelete('delete-{{ $customer->id }}')"
          class="btn btn-danger mx-1 my-md-1"
          @disabled(!$customer->isDeletable())>
          حذف مشتری
          <i class="fa fa-trash-o mr-1"></i>
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
        <a href="{{ route('admin.sales.create') }}" class="btn btn-indigo mx-1 my-md-1">
          ثبت فروش جدید
          <i class="fa fa-plus mr-1"></i>
        </a>
      @endcan
      @can('view sale_salePayments')
        <a href="{{ route('admin.sale-payments.index', $customer) }}" class="btn btn-flickr mx-1 my-md-1">
          مشاهده همه پرداختی ها
          <i class="fa fa-eye mr-1"></i>
        </a>
      @endcan
      @can('create sale_salePayments')
        <a href="{{ route('admin.sale-payments.create', $customer) }}" class="btn btn-lime mx-1 my-md-1">
          ثبت پرداختی جدید
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
        <div class="col-xl-4 col-lg-6 col-12 fs-17 my-1">
          <span><strong>کد : </strong>{{ $customer->id }}</span>
        </div>
        <div class="col-xl-4 col-lg-6 col-12 fs-17 my-1">
          <span><strong>نام و نام خانوادگی : </strong>{{ $customer->name }}</span>
        </div>
        <div class="col-xl-4 col-lg-6 col-12 fs-17 my-1">
          <span><strong>شماره موبایل : </strong>{{ $customer->mobile }}</span>
        </div>
        <div class="col-xl-4 col-lg-6 col-12 fs-17 my-1">
          <span><strong>تلفن ثابت : </strong>{{ $customer->telephone }}</span>
        </div>
        <div class="col-xl-4 col-lg-6 col-12 fs-17 my-1">
          <strong>وضعیت : </strong>
          @if ($customer->status)
            <span class="text-success">فعال</span>
          @else
            <span class="text-danger">غیر فعال</span>
          @endif
        </div>
        <div class="col-xl-4 col-lg-6 col-12 fs-17 my-1">
          <span><strong>تاریخ ثبت : </strong> @jalaliDate($customer->created_at) </span>
        </div>
        <div class="col-xl-4 col-lg-6 col-12 fs-17 my-1">
          <span><strong>محل سکونت : </strong>{{ $customer->address }}</span>
        </div>
        <div class="col-xl-4 col-lg-6 col-12 fs-17 my-1">
          <span><strong>تعداد فروش ها : </strong>{{ number_format($salesCount) }}</span>
        </div>
        <div class="col-xl-4 col-lg-6 col-12 fs-17 my-1">
          <span><strong>تعداد پرداختی ها : </strong>{{ number_format($salePaymentsCount) }}</span>
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
                <span class="fs-16 font-weight-semibold"> مبلغ کل فروش ها (تومان) : </span>
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
                <span class="fs-16 font-weight-semibold"> جمع پرداخت شده ها (تومان) : </span>
                <h3 class="mb-0 mt-1 text-danger fs-20"> {{ number_format($customer->calcTotalSalePaymentsAmount()) }} </h3>
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
    <div class="col-xl-4 col-lg-6 col-md-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-9">
              <div class="mt-0 text-right">
                <span class="fs-16 font-weight-semibold"> مبلغ باقی مانده (تومان) : </span>
                <h3 class="mb-0 mt-1 text-success fs-20"> {{ number_format($customer->getRemainingAmount()) }}  </h3>
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
                <th class="text-center">مبلغ فروش (تومان)</th>
                <th class="text-center">تخفیف کلی (تومان)</th>
                <th class="text-center">مبلغ فروش با تخفیف (تومان)</th>
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
                  <th class="text-center border-top">مبلغ (تومان)</th>
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
