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
        <li class="breadcrumb-item">لیست دسته بندی ها</li>
      </ol> 
      @can('create categories')
        <x-core::register-button route="admin.categories.create" title="ثبت دسته بندی جدید"/>
      @endcan
    </div>
    @include('product::category._filter-form')
    <div class="card">
      <div class="card-header border-0">
        <p class="card-title ml-2">لیست دسته بندی ها <span class="fs-15 ">({{ $categoriesCount }})</span></p>
        
        <div class="card-options">
          <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
          <a href="#" class="card-options-fullscreen" data-toggle="card-fullscreen"><i class="fe fe-maximize"></i></a>
          <a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <div id="hr-table-wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
            <div class="row">
              <table class="table table-vcenter text-nowrap table-bordered border-bottom" id="hr-table">
                <thead class="thead-light">
                <tr>
                  <th class="text-center border-top">ردیف</th>
                  <th class="text-center border-top">عنوان</th>
                  <th class="text-center border-top">والد</th>
                  <th class="text-center border-top">نوع واحد</th>
                  <th class="text-center border-top">وضعیت</th>
                  <th class="text-center border-top">تاریخ ثبت</th>
                  <th class="text-center border-top">تاریخ آخرین ویرایش</th>
                  <th class="text-center border-top">عملیات</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($categories as $category)
                  <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ $category->title }}</td>
                    <td class="text-center">{{ $category->getParentTitle() }}</td>
                    <td class="text-center">{{ $category->getUnitType() }}</td>
                    <td class="text-center">
                      <x-core::badge
                        type="{{ $category->status ? 'success' : 'danger' }}"
                        text="{{ $category->status ? 'فعال' : 'غیر فعال' }}"
                      />
                    </td>
                    <td class="text-center">{{ verta($category->created_at)->formatDate() }}</td>
                    <td class="text-center">{{ verta($category->updated_at)->formatDate() }}</td>
                    <td class="text-center">
                      @can('edit categories')
                        <x-core::edit-button route="admin.categories.edit" :model="$category"/>
                      @endcan
                      @can('delete categories')
                        <x-core::delete-button route="admin.categories.destroy" :model="$category"/>
                      @endcan
                    </td>
                  </tr>
                @empty
                  <x-core::data-not-found-alert :colspan="8"/>
                @endforelse
                </tbody>
              </table>
              {{ $categories->onEachSide(1)->links("vendor.pagination.bootstrap-4") }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
