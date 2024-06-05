@extends('admin.layouts.master')
@section('content')
  <div class="col-12">
    <div class="page-header">
      <x-core::breadcrumb :items="$breadcrumbItems" />
    </div>
    <div class="row">
      <div class="card">
        <div class="card-header border-0">
          <p class="card-title ml-2">مشخصات ادمین</p>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-4 col-md-6 col-12">
              <div class="d-flex align-items-center my-1">
                <span class="fs-16 font-weight-bold ml-1">شناسه کاربر :</span>
                <span class="fs-14 mr-1"> {{ $admin->id }} </span>
              </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12">
              <div class="d-flex align-items-center my-1">
                <span class="fs-16 font-weight-bold ml-1">نام و نام خانوادگی :</span>
                <span class="fs-14 mr-1"> {{ $admin->name }} </span>
              </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12">
              <div class="d-flex align-items-center my-1">
                <span class="fs-16 font-weight-bold ml-1">شماره موبایل :</span>
                <span class="fs-14 mr-1"> {{ $admin->mobile }} </span>
              </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12">
              <div class="d-flex align-items-center my-1">
                <span class="fs-16 font-weight-bold ml-1">نقش :</span>
                <span class="fs-14 mr-1"> {{ $admin->getRoleLabel() }} </span>
              </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12">
              <div class="d-flex align-items-center my-1">
                <span class="fs-16 font-weight-bold ml-1">وضعیت :</span>
                <x-core::badge
                  type="{{ $admin->status ? 'success' : 'danger' }}"
                  text="{{ $admin->status ? 'فعال' : 'غیر فعال' }}"
                />
              </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12">
              <div class="d-flex align-items-center my-1">
                <span class="fs-16 font-weight-bold ml-1">تاریخ ثبت :</span>
                <span class="fs-14 mr-1"> {{ verta($admin->created_at)->format('Y/m/d') }} </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="card">
        <div class="card-header border-0">
          <p class="card-title ml-2">لیست فعالیت ها</p>
          <span class="fs-15">({{ $totalActivity }})</span>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <div id="hr-table-wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
              <div class="row">
                <table class="table table-vcenter text-nowrap table-bordered border-bottom" id="hr-table">
                  <thead class="thead-light">
                    <tr>
                      <th class="text-center border-top">توضیحات</th>
                      <th class="text-center border-top">تاریخ</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($activities as $activity)
                      <tr>
                        <td class="text-start">{{ $activity->description }}</td>
                        <td class="text-start">{{ $activity->created_at->diffForHumans() }}</td>
                      </tr>
                      @empty
                        <x-core::data-not-found-alert :colspan="2"/>
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
