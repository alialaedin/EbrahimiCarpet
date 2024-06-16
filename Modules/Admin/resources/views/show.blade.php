@extends('admin.layouts.master')
@section('content')
  <div class="col-12">

    <div class="page-header">

      <ol class="breadcrumb align-items-center">
        <li class="breadcrumb-item">
          <a href="{{ route('admin.dashboard') }}">
            <i class="fe fe-home ml-1"></i> داشبورد
          </a>
        </li>
        <li class="breadcrumb-item">
          <a href="{{ route('admin.admins.index') }}">لیست ادمین ها</a>
        </li>
        <li class="breadcrumb-item active">نمایش ادمین</li>
      </ol>

    </div>

    <div class="row">

      <div class="card">

        <div class="card-header border-0">
          <p class="card-title">مشخصات ادمین</p>
        </div>

        <div class="card-body">

          <div class="row">

            <div class="col-lg-4 col-md-6 col-12 fs-16 my-1">
              <span><strong>شناسه کاربر : </strong> {{ $admin->id }}</span>
            </div>

            <div class="col-lg-4 col-md-6 col-12 fs-16 my-1">
              <span><strong>نام و نام خانوادگی : </strong> {{ $admin->name }}</span>
            </div>

            <div class="col-lg-4 col-md-6 col-12 fs-16 my-1">
              <span><strong>شماره موبایل : </strong> {{ $admin->mobile }}</span>
            </div>

            <div class="col-lg-4 col-md-6 col-12 fs-16 my-1">
              <span><strong>نقش : </strong> {{ $admin->getRoleLabel() }}</span>
            </div>

            <div class="col-lg-4 col-md-6 col-12 fs-16 my-1">
              <span>
                <strong>وضعیت : </strong>
                <x-core::badge
                  type="{{ $admin->status ? 'success' : 'danger' }}"
                  text="{{ $admin->status ? 'فعال' : 'غیر فعال' }}"
                />
              </span>
            </div>

            <div class="col-lg-4 col-md-6 col-12 fs-16 my-1">
              <span><strong>تاریخ ثبت : </strong> @jalaliDate($admin->created_at) </span>
            </div>

          </div>

        </div>

      </div>

    </div>

    <div class="row">

      <div class="card">

        <div class="card-header border-0">

          <p class="card-title ml-2">لیست فعالیت ها <span class="fs-15">({{ $totalActivity }})</span></p>

          <x-core::card-options/>

        </div>

        <div class="card-body">
          <div class="table-responsive">
            <div id="hr-table-wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
              <div class="row">
                <table class="table table-vcenter text-nowrap table-bordered border-bottom" id="hr-table">
                  <thead class="thead-light">
                    <tr>
                      <th class="text-center">ردیف</th>
                      <th class="text-center">توضیحات</th>
                      <th class="text-center">تاریخ</th>
                      <th class="text-center">ساعت</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($activities as $activity)
                      <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $activity->description }}</td>
                        <td class="text-center">{{ verta($activity->created_at)->formatDate() }}</td>
                        <td class="text-center">{{ verta($activity->created_at)->formatTime() }}</td>
                      </tr>
                      @empty
                        <x-core::data-not-found-alert :colspan="4"/>
                    @endforelse
                  </tbody>
                </table>
                {{ $activities->onEachSide(1)->links("vendor.pagination.bootstrap-4") }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
