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
        <a href="{{ route('admin.sales.create') }}" class="btn btn-sm btn-youtube mx-1 my-md-0 my-1">فاکتور فروش</a>
      @endcan
      @can('create purchases')
        <a href="{{ route('admin.purchases.create') }}" class="btn btn-sm btn-gray-dark mx-1 my-md-0 my-1">فاکتور خرید</a>
      @endcan
      @can('create products')
        <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-rss mx-1 my-md-0 my-1">محصول جدید</a>
      @endcan
      @can('create products')
        <a href="{{ route('admin.expenses.create') }}" class="btn btn-sm btn-purple mx-1 my-md-0 my-1">هزینه جدید</a>
      @endcan
      @can('create products')
        <a href="{{ route('admin.salaries.create') }}" class="btn btn-sm btn-primary mx-1 my-md-0 my-1">پرداخت حقوق</a>
      @endcan
      @can('create customers')
        <a href="{{ route('admin.customers.create') }}" class="btn btn-sm btn-green mx-1 my-md-0 my-1">مشتری جدید</a>
      @endcan
      @can('create suppliers')
        <a href="{{ route('admin.suppliers.create') }}" class="btn btn-sm btn-teal mx-1 my-md-0 my-1">تامین کننده جدید</a>
      @endcan
    </div>
  </div>
  <div class="row">
    @role('super_admin')
    <div class="col-xl-3 col-lg-6 col-md-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-9">
              <div class="mt-0 text-right">
                <span class="fs-14 font-weight-bold"> فاکتور های خرید امروز : </span>
                <p class="mb-0 mt-1 text-primary fs-16"> {{ $todayPurchaseCount }}  </p>
              </div>
            </div>
            <div class="col-3">
              <div class="icon1 bg-primary-transparent my-auto float-left">
                <i class="fa fa-credit-card"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-9">
              <div class="mt-0 text-right">
                <span class="fs-14 font-weight-bold"> اقلام خرید امروز : </span>
                <p class="mb-0 mt-1 text-pink fs-16"> {{ number_format($todayPurchaseItems) }}  </p>
              </div>
            </div>
            <div class="col-3">
              <div class="icon1 bg-pink-transparent my-auto float-left">
                <i class="fa fa-tags"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-9">
              <div class="mt-0 text-right">
                <span class="fs-14 font-weight-bold"> میزان خرید امروز : </span>
                <p class="mb-0 mt-1 text-success fs-16"> {{ number_format($todayPurchaseAmount) }} ریال</p>
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
    <div class="col-xl-3 col-lg-6 col-md-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-9">
              <div class="mt-0 text-right">
                <span class="fs-14 font-weight-bold"> میزان خرید ماه : </span>
                <p class="mb-0 mt-1 text-warning fs-16"> {{ number_format($thisMonthPurchaseAmount) }} ریال</p>
              </div>
            </div>
            <div class="col-3">
              <div class="icon1 bg-warning-transparent my-auto float-left">
                <i class="fa fa-money"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endrole

    @can('view today sales')
      <div class="col-xl-3 col-lg-6 col-md-12">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-9">
                <div class="mt-0 text-right">
                  <span class="fs-14 font-weight-bold"> فاکنور های فروش امروز : </span>
                  <p class="mb-0 mt-1 text-secondary fs-16"> {{ $todaySaleCount }}  </p>
                </div>
              </div>
              <div class="col-3">
                <div class="icon1 bg-secondary-transparent my-auto float-left">
                  <i class="fa fa-credit-card"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endcan
    @can('view today sale_items')
      <div class="col-xl-3 col-lg-6 col-md-12">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-9">
                <div class="mt-0 text-right">
                  <span class="fs-14 font-weight-bold"> اقلام فروش امروز : </span>
                  <p class="mb-0 mt-1 text-danger fs-16"> {{ $todaySaleItems }}  </p>
                </div>
              </div>
              <div class="col-3">
                <div class="icon1 bg-danger-transparent my-auto float-left">
                  <i class="fa fa-tags"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endcan
    @can('view today sale_amount')
      <div class="col-xl-3 col-lg-6 col-md-12">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-9">
                <div class="mt-0 text-right">
                  <span class="fs-14 font-weight-bold"> میزان فروش امروز : </span>
                  <p class="mb-0 mt-1 text-purple fs-16"> {{ number_format($todaySaleAmount) }} ریال</p>
                </div>
              </div>
              <div class="col-3">
                <div class="icon1 bg-purple-transparent my-auto float-left">
                  <i class="fa fa-money"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endcan

    @role('super_admin')
      <div class="col-xl-3 col-lg-6 col-md-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-9">
              <div class="mt-0 text-right">
                <span class="fs-14 font-weight-bold"> میزان فروش ماه : </span>
                <p class="mb-0 mt-1 text-info fs-16"> {{ number_format($thisMonthSaleAmount) }} ریال</p>
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
    @endrole
  </div>

  <div class="row">

    @can('view supplier cheques')
      <div class="col-xl-6">
        <div class="card">
          <div class="card-header border-0 justify-content-between">
            <p class="card-title">چک های پرداختی</p>
            <button onclick="$('#chequePaymentsForm').submit()" class="btn btn-outline-primary btn-sm">مشاهده همه</button>
            <form
              action="{{ route('admin.payments.index') }}"
              id="chequePaymentsForm"
              class="d-none">
              <input type="hidden" name="type" value="cheque">
            </form>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                <div class="row">
                  <table class="table text-center table-vcenter table-striped">
                    <thead>
                    <tr>
                      <th>ردیف</th>
                      <th>تامین کننده</th>
                      <th>تاریخ سررسید</th>
                      <th>مبلغ (ریال)</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($payableCheques as $cheque)
                      <tr>
                        <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                        <td>
                          <a
                            href="{{ route('admin.suppliers.show', $cheque->supplier) }}">{{ $cheque->supplier->name }}</a>
                        </td>
                        <td> {{verta($cheque->due_date)->format('Y/m/d')}} </td>
                        <td>{{ number_format($cheque->amount) }}</td>
                      </tr>
                    @empty
                      <x-core::data-not-found-alert :colspan="4"/>
                    @endforelse
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endcan
    @can('view customer cheques')
      <div class="col-xl-6">
        <div class="card">
          <div class="card-header border-0 justify-content-between">
            <p class="card-title">چک های دریافتی</p>
            <button onclick="$('#chequeSalePaymentsForm').submit()" class="btn btn-outline-primary btn-sm">مشاهده همه</button>
            <form
              action="{{ route('admin.sale-payments.index') }}"
              id="chequeSalePaymentsForm"
              class="d-none">
              <input type="hidden" name="type" value="cheque">
            </form>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                <div class="row">
                  <table class="table text-center table-vcenter table-striped">
                    <thead>
                    <tr>
                      <th>ردیف</th>
                      <th>مشتری</th>
                      <th>تاریخ سررسید</th>
                      <th>مبلغ (ریال)</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($receivedCheques as $cheque)
                      <tr>
                        <td class="font-weight-bold">{{ $loop->iteration }}</td>
                        <td>
                          <a href="{{ route('admin.customers.show', $cheque->customer) }}">{{ $cheque->customer->name }}</a>
                        </td>
                        <td> {{verta($cheque->due_date)->format('Y/m/d')}} </td>
                        <td>{{ number_format($cheque->amount) }}</td>
                      </tr>
                    @empty
                      <x-core::data-not-found-alert :colspan="4"/>
                    @endforelse
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endcan
    @can('view supplier installments')
      <div class="col-xl-6">
        <div class="card">
          <div class="card-header border-0 justify-content-between">
            <p class="card-title">اقساط پرداختی به تامین کنندگان</p>
            <button onclick="$('#installmentPaymentsForm').submit()" class="btn btn-outline-primary btn-sm">مشاهده همه</button>
            <form
              action="{{ route('admin.payments.index') }}"
              id="installmentPaymentsForm"
              class="d-none">
              <input type="hidden" name="type" value="installment">
            </form>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                <div class="row">
                  <table class="table table-vcenter table-striped text-center">
                    <thead>
                    <tr>
                      <th>ردیف</th>
                      <th>نام تامین کننده</th>
                      <th>تاریخ سررسید</th>
                      <th>مبلغ (ریال)</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($payableInstallments as $payment)
                      <tr>
                        <td class="font-weight-bold">{{ $loop->iteration }}</td>
                        <td>
                          <a
                            href="{{ route('admin.suppliers.show', $payment->supplier) }}">{{ $payment->supplier->name }}</a>
                        </td>
                        <td> {{verta($payment->due_date)->format('Y/m/d')}}</td>
                        <td>{{ number_format($payment->amount) }}</td>
                        @empty
                          <x-core::data-not-found-alert :colspan="4"/>
                    @endforelse
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endcan
    @can('view customer installments')
      <div class="col-xl-6">
        <div class="card">
          <div class="card-header border-0 justify-content-between">
            <p class="card-title">اقساط دریافتی از مشتری</p>
            <button onclick="$('#installmentSalePaymentsForm').submit()" class="btn btn-outline-primary btn-sm">مشاهده همه
            </button>
            <form
              action="{{ route('admin.sale-payments.index') }}"
              id="installmentSalePaymentsForm"
              class="d-none">
              <input type="hidden" name="type" value="installment">
            </form>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                <div class="row">
                  <table class="table table-vcenter table-striped text-center">
                    <thead>
                    <tr>
                      <th>ردیف</th>
                      <th>نام مشتری</th>
                      <th>تاریخ سررسید</th>
                      <th>مبلغ (ریال)</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($receivedInstallments as $payment)
                      <tr>
                        <td class="font-weight-bold">{{ $loop->iteration }}</td>
                        <td>
                          <a
                            href="{{ route('admin.customers.show', $payment->customer) }}">{{ $payment->customer->name }}</a>
                        </td>
                        <td> {{verta($payment->due_date)->format('Y/m/d')}} </td>
                        <td>{{ number_format($payment->amount) }}</td>
                        @empty
                          <x-core::data-not-found-alert :colspan="4"/>
                    @endforelse
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endcan

  </div>
@endsection
