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
        <li class="breadcrumb-item active">لیست تامین کنندگان</li>
      </ol>
      @can('create suppliers')
        <x-core::register-button route="admin.suppliers.create" title="ثبت تامین کننده جدید"/>
      @endcan
    </div>
    <div class="card">
      <div class="card-header border-0">
        <p class="card-title">جستجوی پیشرفته</p>
      </div>
      <div class="card-body">
        <div class="row">
          <form action="{{ route("admin.suppliers.index") }}" class="col-12">
            <div class="row">
              <div class="col-xl-4 col-md-6 col-12">
                <div class="form-group">
                  <label for="name">نام و نام خانوادگی :</label>
                  <input type="text" id="name" name="full_name" class="form-control" value="{{ request('full_name') }}">
                </div>
              </div>
              <div class="col-xl-4 col-md-6 col-12">
                <div class="form-group">
                  <label for="mobile">تلفن همراه :</label>
                  <input type="text" id="mobile" name="mobile" class="form-control" value="{{ request('mobile') }}">
                </div>
              </div>
              <div class="col-xl-4 col-md-6 col-12">
                <div class="form-group">
                  <label for="status">وضعیت :</label>
                  <select name="status" id="status" class="form-control">
                    <option value="">همه</option>
                    <option value="1" @selected(request("status") == "1")>فعال</option>
                    <option value="0" @selected(request("status") == "0")>غیر فعال</option>
                  </select>
                </div>
              </div>
            </div>
            <x-core::filter-buttons table="suppliers"/>
          </form>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-header border-0">
        <p class="card-title ml-2">لیست تامین کنندگان</p>
        <span class="fs-15">({{ $totalSuppliers }})</span>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <div id="hr-table-wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
            <div class="row">
              <table class="table table-vcenter text-nowrap table-bordered border-bottom" id="hr-table">
                <thead class="thead-light">
                  <tr>
                    <th class="text-center border-top">ردیف</th>
                    <th class="text-center border-top">نام و نام خانوادگی</th>
                    <th class="text-center border-top">شماره موبایل</th>
                    <th class="text-center border-top">وضعیت</th>
                    <th class="text-center border-top">تاریخ ثبت</th>
                    <th class="text-center border-top">عملیات</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($suppliers as $supplier)
                    <tr>
                      <td class="text-center">{{ $loop->iteration }}</td>
                      <td class="text-center">{{ $supplier->name }}</td>
                      <td class="text-center">{{ $supplier->mobile }}</td>
                      <td class="text-center">
                        <x-core::badge
                          type="{{ $supplier->status ? 'success' : 'danger' }}"
                          text="{{ $supplier->status ? 'فعال' : 'غیر فعال' }}"
                        />
                      <td class="text-center">{{ verta($supplier->created_at)->formatDate() }}</td>
                      <td class="text-center">
                        @can('view suppliers')
                          <x-core::show-button route="admin.suppliers.show" :model="$supplier"/>
                        @endcan
                        @can('edit suppliers')
                          <x-core::edit-button route="admin.suppliers.edit" :model="$supplier"/>
                        @endcan
                        @can('delete suppliers')
                          <x-core::delete-button route="admin.suppliers.destroy" :model="$supplier"/>
                        @endcan
                      </td>
                    </tr>
                    @empty
											<x-core::data-not-found-alert :colspan="6"/>
                  @endforelse
                </tbody>
              </table>
              {{ $suppliers->onEachSide(1)->links("vendor.pagination.bootstrap-4") }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')

  <script>
    $('#from_deployment_date_show').MdPersianDateTimePicker({
      targetDateSelector: '#from_deployment_date',
      targetTextSelector: '#from_deployment_date_show',
      englishNumber: false,
      toDate:true,
      enableTimePicker: false,
      dateFormat: 'yyyy-MM-dd',
      textFormat: 'yyyy-MM-dd',
      groupId: 'rangeSelector1',
    });

    $('#to_deployment_date_show').MdPersianDateTimePicker({
      targetDateSelector: '#to_deployment_date',
      targetTextSelector: '#to_deployment_date_show',
      englishNumber: false,
      toDate:true,
      enableTimePicker: false,
      dateFormat: 'yyyy-MM-dd',
      textFormat: 'yyyy-MM-dd',
      groupId: 'rangeSelector1',
    });
  </script>

@endsection
