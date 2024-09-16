@extends('admin.layouts.master')
@section('content')
  <div class="page-header">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}"><i class="fe fe-home ml-1"></i> داشبورد </a>
      </li>
      <li class="breadcrumb-item active">لیست سرفصل ها</li>
    </ol>
    @can('create headlines')
      <button class="btn btn-indigo" data-target="#createHeadlineModal" data-toggle="modal">
        ثبت سرفصل جدید
        <i class="fa fa-plus font-weight-bolder"></i>
      </button>
    @endcan
  </div>
  <div class="card">
    <div class="card-header border-0">
      <p class="card-title"> لیست همه سرفصل ها ({{ $totalHeadlines }}) </p>
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
                <th class="text-center">کد</th>
                <th class="text-center">نوع</th>
                <th class="text-center">وضعیت</th>
                <th class="text-center">تاریخ ثبت</th>
                <th class="text-center">عملیات</th>
              </tr>
              </thead>
              <tbody>
              @forelse ($headlines as $headline)
                <tr>
                  <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                  <td class="text-center">{{ $headline->title }}</td>
                  <td class="text-center">{{ $headline->id }}</td>
                  <td class="text-center">
                    <x-core::light-badge
                      type="{{ $headline->getTypeBadgeType() }}"
                      text="{{ $headline->getHeadlineType() }}"
                    />
                  </td>
                  <td class="text-center">
                    <x-core::badge
                      type="{{ $headline->getStatusBadgeType() }}"
                      text="{{ $headline->getHeadlineStatus() }}"
                    />
                  </td>
                  <td class="text-center">{{ verta($headline->created_at)->format('Y/m/d H:i') }}</td>
                  <td class="text-center">
                    @can('edit headlines')
                      <button
                        class="btn btn-sm btn-icon btn-warning text-white"
                        data-target="#editHeadlineModal-{{ $headline->id }}"
                        data-toggle="modal"
                        data-original-title="ویرایش">
                        <i class="fa fa-pencil"></i>
                      </button>
                    @endcan
                    @can('delete headlines')
                    <x-core::delete-button 
                      route="admin.headlines.destroy" 
                      :model="$headline" 
                      disabled="{{ !$headline->isDeletable() }}" />
                    @endcan
                  </td>
                </tr>
              @empty
                <x-core::data-not-found-alert :colspan="7"/>
              @endforelse
              </tbody>
            </table>
            {{ $headlines->onEachSide(0)->links("vendor.pagination.bootstrap-4") }}
          </div>
        </div>
      </div>
    </div>
  </div>

  @include('accounting::headline.includes.create-modal')
  @include('accounting::headline.includes.edit-modal')

@endsection

