@extends('admin.layouts.master')

@section('content')
  <div class="col-12">
    <div class="col-xl-12 col-md-12 col-lg-12">

			<div class="page-header">
        <x-core::breadcrumb :items="$breadcrumbItems" />
        @can('create categories')
          <x-core::register-button route="admin.categories.create" title="ثبت دسته بندی جدید"/>
        @endcan
    	</div>

      @include('product::category._fliter-form')

      <div class="card">

        <div class="card-header border-0 justify-content-between ">
          <div class="d-flex">
            <p class="card-title ml-2" style="font-weight: bolder;">لیست دسته بندی ها</p>
            <span class="fs-15 ">({{ $categoriesCount }})</span>
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
                        <td class="text-center">{{ $category->id }}</td>
                        <td class="text-center">{{ $category->title }}</td>
                        <td class="text-center">{{ $category->getParentTitle() }}</td>
                        <td class="text-center">{{ $category->getUnitType() }}</td>
                        <td class="text-center">
                          <x-core::badge 
                            type="{{ $category->status ? 'success' : 'danger' }}" 
                            text="{{ $category->status ? 'فعال' : 'غیر فعال' }}" 
                          />
                        </td>
                        <td class="text-center">{{ verta($category->created_at) }}</td>
                        <td class="text-center">{{ verta($category->updated_at) }}</td>
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
  </div>
@endsection