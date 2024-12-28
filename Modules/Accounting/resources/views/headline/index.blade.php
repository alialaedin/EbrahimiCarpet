@extends('admin.layouts.master')
@section('content')
  
  <div class="page-header">
    <x-core::breadcrumb :items="[['title' => 'لیست سرفصل ها']]"/>
    @can('create headlines')
      <x-core::create-button type="modal" target="createHeadlineModal" title="ثبت سرفصل جدید" route=""/>
    @endcan
  </div>

  <x-core::card>
    <x-slot name="cardTitle">لیست سرفصل ها ({{ $totalHeadlines }})</x-slot>
    <x-slot name="cardOptions"><x-core::card-options/></x-slot>
    <x-slot name="cardBody">
      <x-core::table>
        <x-slot name="tableTh">
          <tr>
            <th>ردیف</th>
            <th>عنوان</th>
            <th>شناسه</th>
            <th>نوع</th>
            <th>وضعیت</th>
            <th>تاریخ ثبت</th>
            <th>عملیات</th>
          </tr>
        </x-slot>
        <x-slot name="tableTd">
          @forelse ($headlines as $headline)
            <tr>
              <td class="font-weight-bold">{{ $loop->iteration }}</td>
              <td>{{ $headline->title }}</td>
              <td>{{ $headline->id }}</td>
              <td>
                <x-core::light-badge
                  type="{{ $headline->getTypeBadgeType() }}"
                  text="{{ $headline->getHeadlineType() }}"
                />
              </td>
              <td>
                <x-core::badge
                  type="{{ $headline->getStatusBadgeType() }}"
                  text="{{ $headline->getHeadlineStatus() }}"
                />
              </td>
              <td>@jalaliDate($headline->created_at)</td>
              <td>
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
        </x-slot>
      </x-core::table>
    </x-slot>
  </x-core::card>

  @include('accounting::headline.includes.create-modal')
  @include('accounting::headline.includes.edit-modal')

@endsection

