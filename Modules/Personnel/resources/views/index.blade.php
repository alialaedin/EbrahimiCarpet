@extends('admin.layouts.master')

@section('content')
  <div class="col-12">
    <div class="col-xl-12 col-md-12 col-lg-12">

			<div class="page-header">
        <x-core::breadcrumb :items="$breadcrumbItems" />
        @can('create personnels')
          <x-core::register-button route="admin.personnels.create" title="ثبت کارمند جدید"/>
        @endcan
    	</div>

      <div class="card">

        <div class="card-header border-0">
          <p class="card-title" style="font-weight: bolder;">جستجو پیشرفته</p>
        </div>

        <div class="card-body">
          <div class="row">
            <form action="{{ route("admin.personnels.index") }}" class="col-12">
              <div class="row">
      
                <div class="col-12 col-md-6 col-xl-3 col-xxl-2">
                  <div class="form-group">
                    <label class="font-weight-bold">نام و نام خانوادگی :</label>
                    <input type="text" name="full_name" class="form-control" value="{{ request('full_name') }}">
                  </div>
                </div>

                <div class="col-12 col-md-6 col-xl-3 col-xxl-2">
                  <div class="form-group">
                    <label class="font-weight-bold">تلفن همراه :</label>
                    <input type="text" name="mobile" class="form-control" value="{{ request('mobile') }}">
                  </div>
                </div>

                <div class="col-12 col-md-6 col-xl-3">
                  <div class="form-group">
                    <label for="from_employmented_date_show" class="font-weight-bold">استخدام از تاریخ :</label>
                    <input class="form-control fc-datepicker" id="from_employmented_date_show" type="text" autocomplete="off"/>
                    <input name="from_employmented_at" id="from_employmented_date" type="hidden" value="{{ request("from_employmented_at") }}"/>  
                  </div>
                </div>
      
                <div class="col-12 col-md-6 col-xl-3">
                  <div class="form-group">
                    <label for="to_employmented_date_show" class="font-weight-bold">تا تاریخ :</label>
                    <input class="form-control fc-datepicker" id="to_employmented_date_show" type="text" autocomplete="off"/>
                    <input name="to_employmented_at" id="to_employmented_date" type="hidden" value="{{ request("to_employmented_at") }}"/>
                  </div>
                </div>

              </div>

              <x-core::filter-buttons table="personnels"/>
              
            </form>
          </div>
        </div>
      </div>

      <div class="card">

        <div class="card-header border-0 justify-content-between ">
          <div class="d-flex">
            <p class="card-title ml-2" style="font-weight: bolder;">لیست پرسنل</p>
            <span class="fs-15 ">({{ $totalPersonnels }})</span>
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
                      <th class="text-center border-top">نام و نام خانوادگی</th>
                      <th class="text-center border-top">شماره موبایل</th>
                      <th class="text-center border-top">کد ملی</th>
                      <th class="text-center border-top">میزان حقوق (تومان)</th>
                      <th class="text-center border-top">تاریخ استخدام</th>
                      <th class="text-center border-top">عملیات</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($personnels as $personnel)
                      <tr>
                        <td class="text-center">{{ $personnel->id }}</td>
                        <td class="text-center">{{ $personnel->name }}</td>
                        <td class="text-center">{{ $personnel->mobile }}</td>
                        <td class="text-center">{{ $personnel->national_code }}</td>
                        <td class="text-center">{{ number_format($personnel->salary) }}</td>
                        <td class="text-center">{{ verta($personnel->employmented_at)->format('Y/m/d') }}</td>
                        <td class="text-center">
                          @can('view personnels')
                            <x-core::show-button route="admin.personnels.show" :model="$personnel"/>
                          @endcan
                          @can('edit personnels')
                            <x-core::edit-button route="admin.personnels.edit" :model="$personnel"/>
                          @endcan
                          @can('delete personnels')
                            <x-core::delete-button route="admin.personnels.destroy" :model="$personnel"/>
                          @endcan
                        </td>
                      </tr>
                      @empty
												<x-core::data-not-found-alert :colspan="7"/>
                    @endforelse
                  </tbody>
                </table>
                {{ $personnels->onEachSide(1)->links("vendor.pagination.bootstrap-4") }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')

  <script>   
    $('#from_employmented_date_show').MdPersianDateTimePicker({
      targetDateSelector: '#from_employmented_date',        
      targetTextSelector: '#from_employmented_date_show',
      englishNumber: false,        
      toDate:true,
      enableTimePicker: false,        
      dateFormat: 'yyyy-MM-dd',
      textFormat: 'yyyy-MM-dd',        
      groupId: 'rangeSelector1',
    });

    $('#to_employmented_date_show').MdPersianDateTimePicker({
      targetDateSelector: '#to_employmented_date',        
      targetTextSelector: '#to_employmented_date_show',
      englishNumber: false,        
      toDate:true,
      enableTimePicker: false,        
      dateFormat: 'yyyy-MM-dd',
      textFormat: 'yyyy-MM-dd',        
      groupId: 'rangeSelector1',
    });
  </script>

@endsection