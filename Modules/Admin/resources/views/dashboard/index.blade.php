@extends('admin.layouts.master')
@section('content')
  <div class="page-header mb-1">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item active">
        <i class="fe fe-home ml-1"></i> داشبورد
      </li>
    </ol>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
      @can('create sales')
        @include('admin::dashboard.includes.helper-btns', [
          'route' => 'admin.sales.create',
          'btn_class' => 'youtube',
          'title' => 'فاکتور فروش',
        ])
      @endcan
      @can('create purchases')
        @include('admin::dashboard.includes.helper-btns', [
          'route' => 'admin.purchases.create',
          'btn_class' => 'gray-dark',
          'title' => 'فاکتور خرید',
        ])
      @endcan
      @can('create products')
        @include('admin::dashboard.includes.helper-btns', [
          'route' => 'admin.products.create',
          'btn_class' => 'rss',
          'title' => 'محصول جدید',
        ])
      @endcan
      @can('create products')
        @include('admin::dashboard.includes.helper-btns', [
          'route' => 'admin.expenses.create',
          'btn_class' => 'purple',
          'title' => 'هزینه جدید',
        ])
      @endcan
      @can('create products')
        @include('admin::dashboard.includes.helper-btns', [
          'route' => 'admin.salaries.create',
          'btn_class' => 'primary',
          'title' => 'پرداخت حقوق',
        ])
      @endcan
      @can('create customers')
        @include('admin::dashboard.includes.helper-btns', [
          'route' => 'admin.customers.create',
          'btn_class' => 'green',
          'title' => 'مشتری جدید',
        ])
      @endcan
      @can('create suppliers')
        @include('admin::dashboard.includes.helper-btns', [
          'route' => 'admin.suppliers.create',
          'btn_class' => 'teal',
          'title' => 'تامین کننده جدید',
        ])
      @endcan
    </div>
  </div>
  <div class="row">
    @role('super_admin')
      @include('admin::dashboard.includes.info-box', [
        'title' => 'فاکتور های خرید امروز',
        'amount' => $todayPurchaseCount,
        'color' => 'primary',
        'icon' => 'credit-card'
      ])
      @include('admin::dashboard.includes.info-box', [
        'title' => 'اقلام خرید امروز',
        'amount' => $todayPurchaseItems,
        'color' => 'pink',
        'icon' => 'tags'
      ])
      @include('admin::dashboard.includes.info-box', [
        'title' => 'میزان خرید امروز',
        'amount' => number_format($todayPurchaseAmount),
        'color' => 'success',
        'icon' => 'money'
      ])
      @include('admin::dashboard.includes.info-box', [
        'title' => 'میزان خرید ماه',
        'amount' => number_format($thisMonthPurchaseAmount),
        'color' => 'warning',
        'icon' => 'money'
      ])
    @endrole
    @can('view today sales')
      @include('admin::dashboard.includes.info-box', [
        'title' => 'فاکتور های فروش امروز',
        'amount' => $todaySaleCount,
        'color' => 'secondary',
        'icon' => 'credit-card'
      ])
    @endcan
    @can('view today sale_items')
      @include('admin::dashboard.includes.info-box', [
        'title' => 'اقلام فروش امروز',
        'amount' => $todaySaleItems,
        'color' => 'danger',
        'icon' => 'tags'
      ])
    @endcan
    @can('view today sale_amount')
      @include('admin::dashboard.includes.info-box', [
        'title' => 'میزان فروش امروز',
        'amount' => number_format($todaySaleAmount),
        'color' => 'purple',
        'icon' => 'money'
      ])
    @endcan
    @role('super_admin')
      @include('admin::dashboard.includes.info-box', [
        'title' => 'میزان فروش ماه',
        'amount' => number_format($thisMonthSaleAmount),
        'color' => 'info',
        'icon' => 'money'
      ])
    @endrole
  </div>

  <div class="row">

    @can('view supplier cheques')
      @include('admin::dashboard.includes.table', [
        'title' => 'چک های پرداختی امروز',
        'table' => 'payments',
        'route' => route('admin.payments.cheques'),
        'parameters' => [
          'from_due_date' => now()->toDateString(),
          'to_due_date' => now()->toDateString(),
        ],
        'allData' => $todayPayableCheques
      ])
    @endcan
    @can('view customer cheques')
      @include('admin::dashboard.includes.table', [
        'title' => 'چک های دریافتی امروز',
        'route' => route('admin.sale-payments.cheques'),
        'parameters' => [
          'from_due_date' => now()->toDateString(),
          'to_due_date' => now()->toDateString(),
        ],
        'table' => 'sale-payments',
        'allData' => $todayReceivedCheques
      ])
    @endcan
    @can('view supplier installments')
      @include('admin::dashboard.includes.table', [
        'title' => 'اقساط پرداختی امروز',
        'route' => route('admin.payments.installments'),
        'parameters' => [
          'from_due_date' => now()->toDateString(),
          'to_due_date' => now()->toDateString(),
        ],
        'table' => 'payments',
        'allData' => $todayPayableInstallments
      ])
    @endcan
    @can('view customer installments')
      @include('admin::dashboard.includes.table', [
        'title' => 'اقساط دریافتی امروز',
        'route' => route('admin.sale-payments.installments'),
        'parameters' => [
          'from_due_date' => now()->toDateString(),
          'to_due_date' => now()->toDateString(),
        ],
        'table' => 'sale-payments',
        'allData' => $todayReceivedInstallments
      ])
    @endcan
    @can('view supplier cheques')
      @include('admin::dashboard.includes.table', [
        'title' => 'چک های پرداختی',
        'route' => route('admin.payments.cheques'),
        'table' => 'payments',
        'allData' => $payableCheques
      ])
    @endcan
    @can('view customer cheques')
      @include('admin::dashboard.includes.table', [
        'title' => 'چک های دریافتی',
        'route' => route('admin.sale-payments.cheques'),
        'table' => 'sale-payments',
        'allData' => $receivedCheques
      ])
    @endcan
    @can('view supplier installments')
      @include('admin::dashboard.includes.table', [
        'title' => 'اقساط پرداختی',
        'route' => route('admin.payments.installments'),
        'table' => 'payments',
        'allData' => $payableInstallments
      ])
    @endcan
    @can('view customer installments')
      @include('admin::dashboard.includes.table', [
        'title' => 'اقساط دریافتی',
        'route' => route('admin.sale-payments.installments'),
        'table' => 'sale-payments',
        'allData' => $receivedInstallments
      ])
    @endcan
  </div>
@endsection
