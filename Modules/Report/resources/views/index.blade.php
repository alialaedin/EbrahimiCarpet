@extends('admin.layouts.master')
@section('content')
  <div class="page-header">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fe fe-home ml-1"></i> داشبورد</a>
      </li>
      <li class="breadcrumb-item active">گزارشات</li>
    </ol>
  </div>
  <div class="row">

    <div class="col-xl-4 col-lg-6 col-md-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col">
              <div class="mt-0 text-center">
                <a href="{{ route('admin.reports.profit') }}">
                  <span class="fs-20 font-weight-semibold"> گزارش سود و ضرر </span>
                </a>
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
            <div class="col">
              <div class="mt-0 text-center">
                <a href="{{ route('admin.reports.purchases-filter') }}">
                  <span class="fs-20 font-weight-semibold"> گزارش خرید ها </span>
                </a>
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
            <div class="col">
              <div class="mt-0 text-center">
                <a href="{{ route('admin.reports.sales-filter') }}">
                  <span class="fs-20 font-weight-semibold"> گزارش فروش ها </span>
                </a>
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
            <div class="col">
              <div class="mt-0 text-center">
                <a href="{{ route('admin.reports.all-suppliers-finance') }}">
                  <span class="fs-20 font-weight-semibold"> گزارش مالی تامین کننده (کلی) </span>
                </a>
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
            <div class="col">
              <div class="mt-0 text-center">
                <a  href="{{ route('admin.reports.suppliers-finance-filter') }}">
                  <span class="fs-20 font-weight-semibold"> گزارش مالی تامین کننده (جزئی) </span>
                </a>
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
            <div class="col">
              <div class="mt-0 text-center">
                <a href="{{ route('admin.reports.supplier-payments-filter') }}">
                  <span class="fs-20 font-weight-semibold"> گزارش پرداختی به تامین کننده </span>
                </a>
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
            <div class="col">
              <div class="mt-0 text-center">
                <a>
                  <span class="fs-20 font-weight-semibold"> گزارش مالی مشتریان (کلی) </span>
                </a>
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
            <div class="col">
              <div class="mt-0 text-center">
                <a>
                  <span class="fs-20 font-weight-semibold"> گزارش مالی مشتریان (جزئی) </span>
                </a>
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
            <div class="col">
              <div class="mt-0 text-center">
                <a>
                  <span class="fs-20 font-weight-semibold"> گزارش دریافتی از مشتری </span>
                </a>
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
            <div class="col">
              <div class="mt-0 text-center">
                <a href="{{ route('admin.reports.expenses') }}">
                  <span class="fs-20 font-weight-semibold"> گزارش مالی هزینه ها </span>
                </a>
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
            <div class="col">
              <div class="mt-0 text-center">
                <a href="{{ route('admin.reports.revenues') }}">
                  <span class="fs-20 font-weight-semibold"> گزارش مالی درامد ها </span>
                </a>
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
            <div class="col">
              <div class="mt-0 text-center">
                <a href="{{ route('admin.reports.salaries') }}">
                  <span class="fs-20 font-weight-semibold"> گزارش مالی حقوق ها </span>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
@endsection
