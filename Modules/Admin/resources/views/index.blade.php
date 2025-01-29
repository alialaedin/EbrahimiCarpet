@extends('admin.layouts.master')
@section('content')

  <div class="page-header">
    <x-core::breadcrumb :items="[['title' => 'لیست ادمین ها']]"/>
    @can('create admins')
      <x-core::create-button route="admin.admins.create" title="ثبت ادمین جدید"/>
    @endcan
  </div>

  <x-core::card>
    <x-slot name="cardTitle">لیست ادمین ها ({{ $adminsCount }})</x-slot>
    <x-slot name="cardOptions"><x-core::card-options/></x-slot>
    <x-slot name="cardBody">
      <x-core::table>
        <x-slot name="tableTh">
          <tr>
            <th>ردیف</th>
            <th>نام و نام خانوادگی</th>
            <th>شناسه</th>
            <th>شماره موبایل</th>
            <th>نقش</th>
            <th>وضعیت</th>
            <th>تاریخ ثبت</th>
            <th>عملیات</th>
          </tr>
        </x-slot>
        <x-slot name="tableTd">
          @forelse ($admins as $admin)
            <tr>
              <td class="font-weight-bold">{{ $loop->iteration }}</td>
              <td>{{ $admin->name }}</td>
              <td>{{ $admin->id }}</td>
              <td>{{ $admin->mobile }}</td>
              <td>{{ $admin->getRoleLabel() }}</td>
              <td>
                <x-core::badge
                  type="{{ $admin->getStatusBadgeType() }}"
                  text="{{ $admin->getStatus() }}"
                />
              </td>
              <td> @jalaliDate($admin->created_at)</td>
              <td>
                @can('view admins')
                  <x-core::show-button route="admin.admins.show" :model="$admin"/>
                @endcan
                @can('edit admins')
                  <x-core::edit-button route="admin.admins.edit" :model="$admin"/>
                @endcan
                @can('delete admins')
                  <x-core::delete-button
                    route="admin.admins.destroy"
                    :model="$admin"
                    disabled="{{ !$admin->isDeletable() }}"
                  />
                @endcan
              </td>
            </tr>
          @empty
            <x-core::data-not-found-alert :colspan="8"/>
          @endforelse
        </x-slot>
        <x-slot name="extraData">{{ $admins->onEachSide(0)->links("vendor.pagination.bootstrap-4") }}</x-slot>
      </x-core::table>
    </x-slot>
  </x-core::card>
 
@endsection
