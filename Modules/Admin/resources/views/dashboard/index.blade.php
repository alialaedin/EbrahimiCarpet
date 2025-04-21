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

      @php
        $adminOnlyData = [
          ['title' => 'فاکتور های خرید امروز', 'value' => $todayPurchaseCount, 'color' => 'primary', 'icon' => 'credit-card'],
          ['title' => 'اقلام خرید امروز', 'value' => $todayPurchaseItems, 'color' => 'pink', 'icon' => 'tags'],
          ['title' => 'میزان خرید امروز', 'value' => number_format($todayPurchaseAmount), 'color' => 'success', 'icon' => 'money'],
          ['title' => 'میزان خرید ماه', 'value' => number_format($thisMonthPurchaseAmount), 'color' => 'warning', 'icon' => 'money']
        ];
      @endphp

      @foreach ($adminOnlyData as $data)
        <div class="col-xl-3 col-lg-6 col-12">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-xl-9">
                  <div class="mt-0 text-right">
                    <span class="fs-14 font-weight-bold"> {{ $data['title'] }} : </span>
                    <p class="mb-0 mt-1 text-{{ $data['color'] }} fs-16">{{ $data['value'] }}</p>
                  </div>
                </div>
                <div class="col-xl-3">
                  <i class="fa fa-{{ $data['icon'] }} icon-dropshadow-{{ $data['color'] }} text-{{ $data['color'] }} fs-50"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endforeach

    @endrole

    @php
      $publicData = [
        ['title' => 'فاکتور های فروش امروز', 'value' => $todaySaleCount, 'color' => 'secondary', 'icon' => 'credit-card', 'permission' => 'view today sales'],
        ['title' => 'اقلام فروش امروز', 'value' => $todaySaleItems, 'color' => 'danger', 'icon' => 'tags', 'permission' => 'view today sale_items'],
        ['title' => 'میزان فروش امروز', 'value' => number_format($todaySaleAmount), 'color' => 'purple', 'icon' => 'money', 'permission' => 'view today sale_amount'],
        ['title' => 'میزان فروش ماه', 'value' => number_format($thisMonthSaleAmount), 'color' => 'info', 'icon' => 'money', 'permission' => 'view sale_amount'] 
      ];
    @endphp

    @foreach ($publicData as $data)
      @can($data['permission'])
        <div class="col-xl-3 col-lg-6 col-12">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-xl-9">
                  <div class="mt-0 text-right">
                    <span class="fs-14 font-weight-bold"> {{ $data['title'] }} : </span>
                    <p class="mb-0 mt-1 text-{{ $data['color'] }} fs-16">{{ $data['value'] }}</p>
                  </div>
                </div>
                <div class="col-xl-3">
                  <i class="fa fa-{{ $data['icon'] }} icon-dropshadow-{{ $data['color'] }} text-{{ $data['color'] }} fs-50"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endcan
    @endforeach

  </div>

  <div class="row">

    <div class="col-xl-6 col-12">
      <div id="cheques-tab-content" class="card">
        <div class="card-header border-bottom-0">
          <h3 class="card-title fs-15 font-weight-bold">مدیریت چک های پاس نشده</h3>
        </div>
        <div class="tab-menu-heading table_tabs mt-4 p-0 ">
          <div class="tabs-menu1">
            <ul class="nav panel-tabs mr-2">

              @php
                $panelTabs = [
                  ['title' => 'todaySuppliersCheques', 'label' => 'چک های پرداختی امروز', 'permission' => 'view supplier cheques'],
                  ['title' => 'todayCustomersCheques', 'label' => 'چک های دریافتی امروز', 'permission' => 'view customer cheques'],
                  ['title' => 'supplierCheques', 'label' => 'چک های پرداختی', 'permission' => 'view supplier cheques'],
                  ['title' => 'customerCheques', 'label' => 'چک های دریافتی', 'permission' => 'view customer cheques'],
                ];
              @endphp

              @foreach ($panelTabs as $tab)
                @can($tab['permission'])
                  <li>
                    <a class="fs-11" style="font-weight: 600" href="#{{ $tab['title'] }}" data-toggle="tab">{{ $tab['label'] }}</a>
                  </li>
                @endcan
              @endforeach

            </ul>
          </div>
        </div>
        <div class="panel-body tabs-menu-body table_tabs1 p-0 border-0">
          <div class="tab-content mt-2">

            @php
              $tabPanes = [
                ['id' => 'todaySuppliersCheques', 'table' => 'payments', 'allData' => $todayPayableCheques, 'permission' => 'view supplier cheques'],
                ['id' => 'todayCustomersCheques', 'table' => 'sale-payments', 'allData' => $todayReceivedCheques, 'permission' => 'view customer cheques'],
                ['id' => 'supplierCheques', 'table' => 'payments', 'allData' => $payableCheques, 'permission' => 'view supplier cheques'],
                ['id' => 'customerCheques', 'table' => 'sale-payments', 'allData' => $receivedCheques, 'permission' => 'view customer cheques'],
              ];
            @endphp

            @foreach ($tabPanes as $tabPane)
              @can($tabPane['permission'])
                @include('admin::dashboard.includes.tab-pane', [
                  'tabId'   => $tabPane['id'],
                  'table'   => $tabPane['table'],
                  'allData' => $tabPane['allData'],
                ])
              @endcan
            @endforeach

          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-6 col-12">
      <div id="installments-tab-content" class="card">
        <div class="card-header border-bottom-0">
          <h3 class="card-title fs-15 font-weight-bold">مدیریت اقساط پرداخت نشده</h3>
        </div>
        <div class="tab-menu-heading table_tabs mt-4 p-0 ">
          <div class="tabs-menu1">
            <ul class="nav panel-tabs mr-2">

              @php
                $panelTabs = [
                  ['title' => 'todaySuppliersInstallments', 'label' => 'اقساط پرداختی امروز', 'permission' => 'view supplier installments'],
                  ['title' => 'todayCustomersInstallments', 'label' => 'اقساط دریافتی امروز', 'permission' => 'view customer installments'],
                  ['title' => 'supplierInstallments', 'label' => 'اقساط پرداختی', 'permission' => 'view supplier installments'],
                  ['title' => 'customerInstallments', 'label' => 'اقساط دریافتی', 'permission' => 'view customer installments'],
                ];
              @endphp

              @foreach ($panelTabs as $tab)
                @can($tab['permission'])
                  <li>
                    <a class="fs-11" style="font-weight: 600" href="#{{ $tab['title'] }}" data-toggle="tab">{{ $tab['label'] }}</a>
                  </li>
                @endcan
              @endforeach

            </ul>
          </div>
        </div>
        <div class="panel-body tabs-menu-body table_tabs1 p-0 border-0">
          <div class="tab-content mt-2">

            @php
              $tabPanes = [
                ['id' => 'todaySuppliersInstallments', 'table' => 'payments', 'allData' => $todayPayableInstallments, 'permission' => 'view supplier installments'],
                ['id' => 'todayCustomersInstallments', 'table' => 'sale-payments', 'allData' => $todayReceivedInstallments, 'permission' => 'view customer installments'],
                ['id' => 'supplierInstallments', 'table' => 'payments', 'allData' => $payableInstallments, 'permission' => 'view supplier installments'],
                ['id' => 'customerInstallments', 'table' => 'sale-payments', 'allData' => $receivedInstallments, 'permission' => 'view customer installments'],
              ];
            @endphp

            @foreach ($tabPanes as $tabPane)
              @can($tabPane['permission'])
                @include('admin::dashboard.includes.tab-pane', [
                  'tabId'   => $tabPane['id'],
                  'table'   => $tabPane['table'],
                  'allData' => $tabPane['allData'],
                ])
              @endcan
            @endforeach

          </div>
        </div>
      </div>
    </div>

  </div>

  <div class="row">
    <div class="col-xl-3 col-12">
      <x-core::card>
        <x-slot name="cardTitle">مشتریان</x-slot>
        <x-slot name="cardOptions"></x-slot>
        <x-slot name="cardBody">
          <div id="customer-genders-chart" class="mx-auto pt-2"></div>
          <div class="row pt-4 mx-auto text-center">
            <div class="col-lg-9 col-md-12 mx-auto d-block">
              <div class="row">
                <div class="col-md-6">
                  <div class="d-flex font-weight-bold">
                    <span class="dot-label bg-primary ml-2 my-auto"></span>مرد
                  </div>
                </div>
                <div class="col-md-6 mt-3 mt-md-0">
                  <div class="d-flex font-weight-bold">
                    <span class="dot-label badge-danger ml-2 my-auto"></span>زن
                  </div>
                </div>
              </div>
            </div>
          </div>
        </x-slot>
      </x-core::card>
    </div>
    <div class="col-xl-3 col-12">
      <x-core::card>
        <x-slot name="cardTitle">سفارشات</x-slot>
        <x-slot name="cardOptions"></x-slot>
        <x-slot name="cardBody">
          <div id="orders-chart" class="mx-auto pt-2"></div>
          <div class="row pt-4 mx-auto text-center">
            <div class="col-lg-9 col-md-12 mx-auto d-block">
              <div class="row">
                <div class="col-md-6">
                  <div class="d-flex font-weight-bold">
                    <span class="dot-label bg-primary ml-2 my-auto"></span>خرید
                  </div>
                </div>
                <div class="col-md-6 mt-3 mt-md-0">
                  <div class="d-flex font-weight-bold">
                    <span class="dot-label bg-danger ml-2 my-auto"></span>فروش
                  </div>
                </div>
              </div>
            </div>
          </div>
        </x-slot>
      </x-core::card>
    </div>
    <div class="col-xl-3 col-12">
      <x-core::card>
        <x-slot name="cardTitle">پرداختی به تامین کننده</x-slot>
        <x-slot name="cardOptions"></x-slot>
        <x-slot name="cardBody">
          <div id="payments-chart" class="mx-auto pt-2"></div>
          <div class="row pt-4 mx-auto text-center">
            <div class="col-lg-9 col-md-12 mx-auto d-block">
              <div class="row">
                <div class="col-md-4">
                  <div class="d-flex font-weight-bold">
                    <span class="dot-label bg-secondary ml-2 my-auto"></span>نقد
                  </div>
                </div>
                <div class="col-md-4 mt-3 mt-md-0">
                  <div class="d-flex font-weight-bold">
                    <span class="dot-label bg-success ml-2 my-auto"></span>قسط
                  </div>
                </div>
                <div class="col-md-4 mt-3 mt-md-0">
                  <div class="d-flex font-weight-bold">
                    <span class="dot-label bg-warning ml-2 my-auto"></span>چک
                  </div>
                </div>
              </div>
            </div>
          </div>
        </x-slot>
      </x-core::card>
    </div>
    <div class="col-xl-3 col-12">
      <x-core::card>
        <x-slot name="cardTitle">دریافتی از مشتری</x-slot>
        <x-slot name="cardOptions"></x-slot>
        <x-slot name="cardBody">
          <div id="sale-payments-chart" class="mx-auto pt-2"></div>
          <div class="row pt-4 mx-auto text-center">
            <div class="col-lg-9 col-md-12 mx-auto d-block">
              <div class="row">
                <div class="col-md-4">
                  <div class="d-flex font-weight-bold">
                    <span class="dot-label bg-secondary ml-2 my-auto"></span>نقد
                  </div>
                </div>
                <div class="col-md-4 mt-3 mt-md-0">
                  <div class="d-flex font-weight-bold">
                    <span class="dot-label bg-success ml-2 my-auto"></span>قسط
                  </div>
                </div>
                <div class="col-md-4 mt-3 mt-md-0">
                  <div class="d-flex font-weight-bold">
                    <span class="dot-label bg-warning ml-2 my-auto"></span>چک
                  </div>
                </div>
              </div>
            </div>
          </div>
        </x-slot>
      </x-core::card>
    </div>
  </div>

@endsection

@section('scripts')

  <script src="{{ asset('assets/plugins/apexchart/apexcharts.js') }}"></script>

  <script>
    $(document).ready(() => {
      $('#cheques-tab-content .tab-pane').first().addClass('active');
      $('#cheques-tab-content .panel-tabs li').first().find('a').addClass('active');
      $('#installments-tab-content .tab-pane').first().addClass('active');
      $('#installments-tab-content .panel-tabs li').first().find('a').addClass('active');
    });
  </script>

  <script>
    const customerGendersStatistics = @json($customerGendersStatistics);
    var options = {
      series: [
        customerGendersStatistics.find(c => c.title == 'male').count, 
        customerGendersStatistics.find(c => c.title == 'female').count,
      ],
      chart: { height:350, type: 'donut' },
      dataLabels: { enabled: false },
      legend: { show: false },
      stroke: { show: true, width:0 },
      plotOptions: {
        pie: {
          donut: {
            size: '85%',
            background: 'transparent',
            labels: {
              show: true,
              name: {
                show: true,
                fontSize: '22px',
                fontFamily: 'Vazir',
                color:'#263871',
                offsetY: -10
              },
              value: {
                show: true,
                fontSize: '26px',
                color: undefined,
                offsetY: 16,
              },
              total: {
                show: true,
                showAlways: false,
                label: 'کل مشتریان',
                fontSize: '22px',
                fontWeight: 600,
                color: '#263871',
              }
            }
          }
        }
      },
      responsive: [{
        breakpoint: 480,
        options: { legend: { show: false } }
      }],
      labels: ["مرد", "زن"],
      colors: ['#3366ff', '#f7284a'],
    };
    var chart = new ApexCharts(document.querySelector("#customer-genders-chart"), options);
    chart.render();
  </script>

  <script>
    const ordersStatistics = @json($ordersStatistics);
    var options = {
      series: [
        ordersStatistics.find(c => c.title == 'purchase').count,
        ordersStatistics.find(c => c.title == 'sale').count,
      ],
      chart: {
        height:350,
        type: 'donut',
      },
      dataLabels: {
        enabled: false
      },
      legend: {
        show: false,
      },
      stroke: {
        show: true,
        width:0
      },
      plotOptions: {
        pie: {
          donut: {
            size: '85%',
            background: 'transparent',
            labels: {
              show: true,
              name: {
                show: true,
                fontSize: '22px',
                fontFamily: 'Vazir',
                color:'#263871',
                offsetY: -10
              },
              value: {
                show: true,
                fontSize: '26px',
                color: undefined,
                offsetY: 16,
              },
              total: {
                show: true,
                showAlways: false,
                label: 'کل سفارشات',
                fontSize: '22px',
                fontWeight: 600,
                color: '#263871',
              }
            }
          }
        }
      },
      responsive: [{
        breakpoint: 480,
        options: {
          legend: {
            show: false,
          }
        }
      }],
      labels: ["خرید", "فروش"],
      colors: ['#3366ff', '#f7284a'],
    };
    var chart = new ApexCharts(document.querySelector("#orders-chart"), options);
    chart.render();
  </script>

<script>
  const paymentsStatistics = @json($paymentsStatistics);
  var options = {
    series: [
      paymentsStatistics.find(c => c.title == 'cash').count,
      paymentsStatistics.find(c => c.title == 'installment').count,
      paymentsStatistics.find(c => c.title == 'cheque').count,
    ],
    chart: {
      height:350,
      type: 'donut',
    },
    dataLabels: {
      enabled: false
    },
    legend: {
      show: false,
    },
    stroke: {
      show: true,
      width:0
    },
    plotOptions: {
      pie: {
        donut: {
          size: '85%',
          background: 'transparent',
          labels: {
            show: true,
            name: {
              show: true,
              fontSize: '22px',
              fontFamily: 'Vazir',
              color:'#263871',
              offsetY: -10
            },
            value: {
              show: true,
              fontSize: '26px',
              color: undefined,
              offsetY: 16,
            },
            total: {
              show: true,
              showAlways: false,
              label: 'کل پرداختی ها',
              fontSize: '22px',
              fontWeight: 600,
              color: '#263871',
            }
          }
        }
      }
    },
    responsive: [{
      breakpoint: 480,
      options: {
        legend: {
          show: false,
        }
      }
    }],
    labels: ["نقد", "قسط", "چک"],
    colors: ['#fe7f00', '#01c353', '#fbc518'],
  };
  var chart = new ApexCharts(document.querySelector("#payments-chart"), options);
  chart.render();
</script>

<script>
  const salePaymentsStatistics = @json($salePaymentsStatistics);
  var options = {
    series: [
      salePaymentsStatistics.find(c => c.title == 'cash').count,
      salePaymentsStatistics.find(c => c.title == 'installment').count,
      salePaymentsStatistics.find(c => c.title == 'cheque').count,
    ],
    chart: {
      height:350,
      type: 'donut',
    },
    dataLabels: {
      enabled: false
    },
    legend: {
      show: false,
    },
    stroke: {
      show: true,
      width:0
    },
    plotOptions: {
      pie: {
        donut: {
          size: '85%',
          background: 'transparent',
          labels: {
            show: true,
            name: {
              show: true,
              fontSize: '22px',
              fontFamily: 'Vazir',
              color:'#263871',
              offsetY: -10
            },
            value: {
              show: true,
              fontSize: '26px',
              color: undefined,
              offsetY: 16,
            },
            total: {
              show: true,
              showAlways: false,
              label: 'کل دریافتی ها',
              fontSize: '20px',
              fontWeight: 900,
              color: '#263871',
            }
          }
        }
      }
    },
    responsive: [{
      breakpoint: 480,
      options: {
        legend: {
          show: false,
        }
      }
    }],
    labels: ["نقد", "قسط", "چک"],
    colors: ['#fe7f00', '#01c353', '#fbc518'],
  };
  var chart = new ApexCharts(document.querySelector("#sale-payments-chart"), options);
  chart.render();
</script>

@endsection