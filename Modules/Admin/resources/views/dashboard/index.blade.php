@extends('admin.layouts.master')
@section('content')
  <div class="page-header">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item active">
        <i class="fe fe-home ml-1"></i> داشبورد
      </li>
    </ol>
  </div>
  <div class="row">
    <div class="col-xl-3 col-lg-6 col-md-12">
      <a href="{{ route('admin.categories.index') }}">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-9">
                <div class="mt-0 text-right">
                  <span class="fs-16 font-weight-semibold"> دسته بندی ها : </span>
                  <p class="mb-0 mt-1 text-info fs-20"> {{ $totalCategories }} </p>
                </div>
              </div>
              <div class="col-3">
                <div class="icon1 bg-info-transparent my-auto float-left">
                  <i class="fa fa-folder-open"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-12">
      <a href="{{ route('admin.products.index') }}">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-9">
                <div class="mt-0 text-right">
                  <span class="fs-16 font-weight-semibold"> محصولات : </span>
                  <p class="mb-0 mt-1 text-danger fs-20"> {{ $totalProducts }} </p>
                </div>
              </div>
              <div class="col-3">
                <div class="icon1 bg-danger-transparent my-auto float-left">
                  <i class="fa fa-shopping-basket"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-12">
      <a href="{{ route('admin.purchases.index') }}">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-9">
                <div class="mt-0 text-right">
                  <span class="fs-16 font-weight-semibold"> خرید : </span>
                  <p class="mb-0 mt-1 text-success fs-20"> {{ $totalPurchases }}  </p>
                </div>
              </div>
              <div class="col-3">
                <div class="icon1 bg-success-transparent my-auto float-left">
                  <i class="fa fa-shopping-cart"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-12">
      <a href="{{ route('admin.sales.index') }}">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-9">
                <div class="mt-0 text-right">
                  <span class="fs-16 font-weight-semibold"> فروش : </span>
                  <p class="mb-0 mt-1 text-warning fs-20"> {{ $totalSales }}  </p>
                </div>
              </div>
              <div class="col-3">
                <div class="icon1 bg-warning-transparent my-auto float-left">
                  <i class="fa fa-dollar"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-9">
              <div class="mt-0 text-right">
                <span class="fs-16 font-weight-semibold"> خرید های امروز : </span>
                <p class="mb-0 mt-1 text-secondary fs-20"> {{ $todayPurchases }}  </p>
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

    <div class="col-xl-3 col-lg-6 col-md-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-9">
              <div class="mt-0 text-right">
                <span class="fs-16 font-weight-semibold"> فروش های امروز : </span>
                <p class="mb-0 mt-1 text-purple fs-20"> {{ $todaySales }}  </p>
              </div>
            </div>
            <div class="col-3">
              <div class="icon1 bg-purple-transparent my-auto float-left">
                <i class="fa fa-credit-card-alt"></i>
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
                <span class="fs-16 font-weight-semibold"> قلم فروش این ماه : </span>
                <p class="mb-0 mt-1 text-pink fs-20"> {{ $totalItemsSalesThisMonth }}  </p>
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
                <span class="fs-16 font-weight-semibold"> مبلغ فروش این ماه : </span>
                <p class="mb-0 mt-1 text-primary fs-20"> {{ number_format($totalAmountSalesThisMonth) }} (تومان)</p>
              </div>
            </div>
            <div class="col-3">
              <div class="icon1 bg-primary-transparent my-auto float-left">
                <i class="fa fa-money"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-xl-6">
      <div class="card">
        <div class="card-header border-0 justify-content-between">
          <p class="card-title">چک های پرداختی ({{ $payableCheques->count() }})</p>
          <a class="btn btn-outline-primary">مشاهده همه</a>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <div class="dataTables_wrapper dt-bootstrap4 no-footer">
              <div class="row">
                <table class="table table-vcenter table-striped text-nowrap table-bordered border-bottom">
                  <thead class="thead-light">
                  <tr>
                    <th class="text-center">ردیف</th>
                    <th class="text-center">تامین کننده</th>
                    <th class="text-center">تاریخ سررسید</th>
                    <th class="text-center">مبلغ (تومان)</th>
                  </tr>
                  </thead>
                  <tbody>
                  @forelse ($payableCheques as $cheque)
                    <tr>
                      <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                      <td class="text-center">{{ $cheque->supplier->name }}</td>
                      <td class="text-center"> @jalaliDate($admin->due_date)</td>
                      <td class="text-center">{{ number_format($cheque->amount) }}</td>
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
    <div class="col-xl-6">
      <div class="card">
        <div class="card-header border-0 justify-content-between">
          <p class="card-title">چک های دریافتی ({{ $receivedCheques->count() }})</p>
          <a class="btn btn-outline-primary">مشاهده همه</a>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <div class="dataTables_wrapper dt-bootstrap4 no-footer">
              <div class="row">
                <table class="table table-vcenter table-striped text-nowrap table-bordered border-bottom">
                  <thead class="thead-light">
                  <tr>
                    <th class="text-center">ردیف</th>
                    <th class="text-center">تامین کننده</th>
                    <th class="text-center">تاریخ سررسید</th>
                    <th class="text-center">مبلغ (تومان)</th>
                  </tr>
                  </thead>
                  <tbody>
                  @forelse ($receivedCheques as $cheque)
                    <tr>
                      <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                      <td class="text-center">{{ $cheque->supplier->name }}</td>
                      <td class="text-center"> @jalaliDate($admin->due_date)</td>
                      <td class="text-center">{{ number_format($cheque->amount) }}</td>
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
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header border-0 justify-content-between">
          <p class="card-title">اقساط دریافتی از مشتری ({{ $receivedInstallments->count() }})</p>
          <a class="btn btn-outline-primary">مشاهده همه</a>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <div class="dataTables_wrapper dt-bootstrap4 no-footer">
              <div class="row">
                <table class="table table-vcenter table-striped text-nowrap table-bordered border-bottom">
                  <thead class="thead-light">
                  <tr>
                    <th class="text-center">ردیف</th>
                    <th class="text-center">نام مشتری</th>
                    <th class="text-center">شماره موبایل</th>
                    <th class="text-center">مبلغ (تومان)</th>
                    <th class="text-center">عکس رسید</th>
                    <th class="text-center">تاریخ سررسید</th>
                    <th class="text-center">تاریخ ثبت</th>
                  </tr>
                  </thead>
                  <tbody>
                  @forelse ($receivedInstallments as $payment)
                    <tr>
                      <td class="text-center">{{ $loop->iteration }}</td>
                      <td class="text-center">{{ number_format($payment->amount) }}</td>
                      <td class="text-center">{{ $payment->customer->name }}</td>
                      <td class="text-center">{{ $payment->customer->mobile }}</td>
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
                      <td class="text-center"> @jalaliDate($payment->due_date) </td>
                      <td class="text-center"> @jalaliDate($payment->created_at) </td>
                  @empty
                    <x-core::data-not-found-alert :colspan="7"/>
                  @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
