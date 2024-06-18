@extends('admin.layouts.master')
@section('content')
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
      <button class="btn btn-indigo" data-target="#createCategoryModal" data-toggle="modal">
        ثبت دسته بندی جدید
        <i class="fa fa-plus mr-1"></i>
      </button>
    @endcan
  </div>
  @include('product::category.includes._filter-form')
  <div class="card">
    <div class="card-header border-0">
      <p class="card-title">لیست دسته بندی ها ({{ $categoriesCount }})</p>
      <x-core::card-options/>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <div class="dataTables_wrapper dt-bootstrap4 no-footer">
          <div class="row">
            <table class="table table-vcenter table-striped text-nowrap table-bordered border-bottom">
              <thead class="thead-light">
              <tr>
                <th class="text-center">ردیف</th>
                <th class="text-center">عنوان</th>
                <th class="text-center">والد</th>
                <th class="text-center">نوع واحد</th>
                <th class="text-center">وضعیت</th>
                <th class="text-center">تاریخ ثبت</th>
                <th class="text-center">تاریخ آخرین ویرایش</th>
                <th class="text-center">عملیات</th>
              </tr>
              </thead>
              <tbody>
              @forelse ($categories as $category)
                <tr>
                  <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                  <td class="text-center">{{ $category->title }}</td>
                  <td class="text-center">{{ $category->getParentTitle() }}</td>
                  <td class="text-center">{{ $category->getUnitType() }}</td>
                  <td class="text-center">
                    <x-core::badge
                      type="{{ $category->status ? 'success' : 'danger' }}"
                      text="{{ $category->status ? 'فعال' : 'غیر فعال' }}"
                    />
                  </td>
                  <td class="text-center"> @jalaliDate($category->created_at)</td>
                  <td class="text-center"> @jalaliDate($category->updated_at)</td>
                  <td class="text-center">
                    @can('edit categories')
                      <button
                        class="btn btn-sm btn-icon btn-warning text-white"
                        data-target="#editCategoryModal-{{ $category->id }}"
                        data-toggle="modal"
                        data-original-title="ویرایش">
                        <i class="fa fa-pencil"></i>
                      </button>
                    @endcan
                    @can('delete categories')<x-core::delete-button
                        route="admin.categories.destroy"
                        :model="$category"
                        disabled="{{ !$category->isDeletable() }}"
                      />@endcan
                  </td>
                </tr>
              @empty
                <x-core::data-not-found-alert :colspan="8"/>
              @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  @include('product::category.includes.create-category-modal')
  @include('product::category.includes.edit-category-modal')
@endsection
