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
    @foreach ($reports as $report)
    <div class="col-xl-4 col-lg-6 col-md-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col">
              <div class="mt-0 text-center">
                <a href="{{ route($report['route']) }}">
                  <span class="fs-20 font-weight-semibold"> {{ $report['title'] }} </span>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>
@endsection
