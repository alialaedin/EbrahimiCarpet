@extends('admin.layouts.master')
@section('content')

  <div class="page-header">
    <x-core::breadcrumb :items="[['title' => 'لیست نقش ها']]"/>
    @can('create roles')
      <x-core::create-button route="admin.roles.create" title="ثبت نقش جدید"/>
    @endcan
  </div>

  <x-core::card>
    <x-slot name="cardTitle">لیست نقش ها ({{ $roles->count() }})</x-slot>
    <x-slot name="cardOptions"></x-slot>
    <x-slot name="cardBody">
      <x-core::table>
        <x-slot name="tableTh">
          <tr>
            <th>ردیف</th>
            <th>نام</th>
            <th>نام قابل مشاهده</th>
            <th>تاریخ ثبت</th>
            <th>عملیات</th>
          </tr>
        </x-slot>
        <x-slot name="tableTd">
          @forelse ($roles as $role)
            <tr>
              <td class="font-weight-bold">{{ $loop->iteration }}</td>
              <td>{{ $role->name }}</td>
              <td>{{ $role->label }}</td>
              <td>@jalaliDate($role->created_at)</td>
              <td>
                <a
                  href="{{route('admin.roles.edit', $role)}}"
                  class="btn btn-sm btn-icon btn-warning text-white"
                  data-toggle="tooltip"
                  data-original-title="ویرایش"
                  @if ($role->name == 'super_admin') style="pointer-events: none;" @endif>
                  <i class="fa fa-pencil"></i>
                </a>
                <x-core::delete-button route="admin.roles.destroy" :model="$role" disabled="{{ !$role->isDeletable() }}"/>
              </td>
            </tr>
          @empty
            <x-core::data-not-found-alert :colspan="5"/>
          @endforelse
        </x-slot>
      </x-core::table>
    </x-slot>
  </x-core::card>
@endsection
