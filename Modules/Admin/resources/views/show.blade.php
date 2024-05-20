@extends('admin.layouts.master')
@section('content')
  <div class="col-12">

    <div class="col-xl-12 col-md-12 col-lg-12">

      <div class="page-header">
        <x-core::breadcrumb :items="$breadcrumbItems" />
    	</div>

      <div class="row">
        <div class="col-xl-4 col-md-12 col-lg-12">
          <div class="card">
    
            <div class="card-header border-0 justify-content-between ">
              <div class="d-flex">
                <p class="card-title ml-2" style="font-weight: bolder;">مشخصات ادمین</p>
              </div>
            </div>
    
            <div class="card-body">
              <div class="row">
    
                <div class="col-12">
                  <div class="d-flex justify-content-between align-items-center my-1">
                    <span class="fs-16 font-weight-bold">شناسه کاربر :</span>
                    <span class="fs-14 mr-1"> {{ $admin->id }} </span>
                  </div>
                </div>
    
                <div class="col-12">
                  <div class="d-flex justify-content-between align-items-center my-1">
                    <span class="fs-16 font-weight-bold">نام و نام خانوادگی :</span>
                    <span class="fs-14 mr-1"> {{ $admin->name }} </span>
                  </div>
                </div>
    
                <div class="col-12">
                  <div class="d-flex justify-content-between align-items-center my-1">
                    <span class="fs-16 font-weight-bold">شماره موبایل :</span>
                    <span class="fs-14 mr-1"> {{ $admin->mobile }} </span>
                  </div>
                </div>
    
                <div class="col-12">
                  <div class="d-flex justify-content-between align-items-center my-1">
                    <span class="fs-16 font-weight-bold">نقش :</span>
                    <span class="fs-14 mr-1"> {{ $admin->getRoleLabel() }} </span>
                  </div>
                </div>
    
                <div class="col-12">
                  <div class="d-flex justify-content-between align-items-center my-1">
                    <span class="fs-16 font-weight-bold">وضعیت :</span>
                    <x-core::badge 
                      type="{{ $admin->status ? 'success' : 'danger' }}" 
                      text="{{ $admin->status ? 'فعال' : 'غیر فعال' }}" 
                    />
                  </div>
                </div>
    
                <div class="col-12">
                  <div class="d-flex justify-content-between align-items-center my-1">
                    <span class="fs-16 font-weight-bold">تاریخ ساخت :</span>
                    <span class="fs-14 mr-1"> {{ verta($admin->created_at)->format('Y/m/d') }} </span>
                  </div>
                </div>
        
              </div>
            </div>
    
          </div>
        </div>
    
        <div class="col-xl-8 col-md-12 col-lg-12">
            <div class="card">
    
              <div class="card-header border-0 justify-content-between ">
                <div class="d-flex">
                  <p class="card-title ml-2" style="font-weight: bolder;">لیست فعالیت ها</p>
                  <span class="fs-15 ">({{ $totalActivity }})</span>
                </div>
              </div>
              
              <div class="card-body">
                <div class="table-responsive">
                  <div id="hr-table-wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <div class="row">
                      <table class="table table-vcenter text-nowrap table-bordered border-bottom" id="hr-table">
                        <thead class="thead-light">
                          <tr>
                            <th class="text-center border-top">شناسه</th>
                            <th class="text-center border-top">توضیحات</th>
                          </tr>
                        </thead>
                        <tbody>
                          @forelse ($activities as $activity)
                            <tr>
                              <td class="text-center">{{ $activity->id }}</td>
                              <td class="text-start">{{ $activity->description }}</td>
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
    </div>
    
  </div>
@endsection 