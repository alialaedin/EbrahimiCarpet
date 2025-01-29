@extends('admin.layouts.master')
@section('content')

  <div class="page-header">
    <x-core::breadcrumb :items="[['title' => 'لیست دسته بندی ها']]"/>
    @can('create categories')
      <x-core::create-button type="modal" target="createCategoryModal" title="ثبت دسته بندی جدید" route=""/>
    @endcan
  </div>

  <x-core::card>
    <x-slot name="cardTitle">جستجوی پیشرفته</x-slot>
    <x-slot name="cardOptions"><x-core::card-options/></x-slot>
    <x-slot name="cardBody">
      <form id="FilterForm" action="{{ route("admin.categories.index") }}" class="col-12">
        <input type="hidden" name="perPage" value="{{ request('perPage', 15) }}">
        <div class="row">
          <div class="col-12 col-md-6 col-xl-3">
            <div class="form-group">
              <label for="title">عنوان :</label>
              <input type="text" id="title" name="title" class="form-control" value="{{ request('title') }}">
            </div>
          </div>
          <div class="col-12 col-md-6 col-xl-3">
            <div class="form-group">
              <label for="parent_id">انتخاب والد :</label>
              <select name="parent_id" id="parent_id" class="form-control">
                <option value=""></option>
                <option value="all">همه</option>
                @foreach ($parentCategories as $category)
                  <option value="{{ $category->id }}" @selected(request("parent_id") == $category->id)>{{ $category->title }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-12 col-md-6 col-xl-3">
            <div class="form-group">
              <label for="unit_type">نوع واحد :</label>
              <select name="unit_type" id="unit_type" class="form-control">
                <option value=""></option>
                <option value="all">همه</option>
                <option value="meter" @selected(request("unit_type") == "meter")>متر</option>
                <option value="number" @selected(request("unit_type") == "number")>عدد</option>
              </select>
            </div>
          </div>
          <div class="col-12 col-md-6 col-xl-3">
            <div class="form-group">
              <label for="status">وضعیت :</label>
              <select name="status" id="status" class="form-control">
                <option value=""></option>
                <option value="all">همه</option>
                <option value="1" @selected(request("status") == "1")>فعال</option>
                <option value="0" @selected(request("status") == "0")>غیر فعال</option>
              </select>
            </div>
          </div>
        </div>
        <x-core::filter-buttons table="categories"/>
      </form>
    </x-slot>
  </x-core::card>

  <x-core::card>
    <x-slot name="cardTitle">لیست دسته بندی ها ({{ $categoriesCount }})</x-slot>
    <x-slot name="cardOptions"><x-core::card-options/></x-slot>
    <x-slot name="cardBody">
      <x-core::table>
        <x-slot name="tableTh">
          <tr>
            <th>ردیف</th>
            <th>عنوان</th>
            <th>والد</th>
            <th>نوع واحد</th>
            <th>تعداد محصولات</th>
            <th>وضعیت</th>
            <th>تاریخ ثبت</th>
            <th>تاریخ آخرین ویرایش</th>
            <th>عملیات</th>
          </tr>
        </x-slot>
        <x-slot name="tableTd">
          @forelse ($categories as $category)
            <tr>
              <td class="font-weight-bold">{{ $loop->iteration }}</td>
              <td>{{ $category->title }}</td>
              <td>{{ $category->parent_title }}</td>
              <td>{{ $category->getUnitType() }}</td>
              <td>{{ $category->products_count }}</td>
              <td>
                <x-core::light-badge
                  type="{{ $category->status ? 'success' : 'danger' }}"
                  text="{{ $category->status ? 'فعال' : 'غیر فعال' }}"
                />
              </td>
              <td> @jalaliDate($category->created_at)</td>
              <td> @jalaliDate($category->updated_at)</td>
              <td>
                @can('edit categories')
                  <button
                    class="btn btn-sm btn-icon btn-warning text-white"
                    data-target="#editCategoryModal-{{ $category->id }}"
                    data-toggle="modal"
                    data-original-title="ویرایش">
                    <i class="fa fa-pencil"></i>
                  </button>
                @endcan
                @can('delete categories')
                  <x-core::delete-button
                    route="admin.categories.destroy"
                    :model="$category"
                    :disabled="!$category->isDeletable()"
                  />
                @endcan
              </td>
            </tr>
          @empty
            <x-core::data-not-found-alert :colspan="8"/>
          @endforelse
        </x-slot>
      </x-core::table>
    </x-slot>
  </x-core::card>

  @include('product::category.includes.create-category-modal')
  @include('product::category.includes.edit-category-modal')

@endsection

@section('scripts')
  <script>
    new CustomSelect('#status', 'انتخاب وضعیت');
    new CustomSelect('#unit_type', 'انتخاب نوع واحد');
    new CustomSelect('#parent_id', 'انتخاب والد');
  </script>
@endsection
