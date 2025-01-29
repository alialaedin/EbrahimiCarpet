@extends('admin.layouts.master')
@section('content')

  <div class="page-header">
    <x-core::breadcrumb :items="[['title' => 'فعالیت ها']]"/>
  </div>

  <x-core::card>
    <x-slot name="cardTitle">لیست فعالیت ها <span class="fs-15">({{ number_format($activities->total()) }})</span></x-slot>
    <x-slot name="cardOptions"><x-core::card-options/></x-slot>
    <x-slot name="cardBody">
      <x-core::table>
        <x-slot name="tableTh">
          <tr>
            <th>ردیف</th>
            <th>ادمین</th>
            <th>توضیحات</th>
            <th>شناسه لاگ</th>
            <th>تاریخ</th>
            <th>ساعت</th>
          </tr>
        </x-slot>
        <x-slot name="tableTd">
          @forelse ($activities as $activity)
            <tr>
              <td class="font-weight-bold">{{ $loop->iteration }}</td>
              <td>{{ $activity->causer->name }}</td>
              <td style="white-space: wrap;">{{ $activity->description }}</td>
              <td>{{ $activity->id }}</td>
              <td>{{ verta($activity->created_at)->formatDate() }}</td>
              <td>{{ verta($activity->created_at)->formatTime() }}</td>
            </tr>
          @empty
            <x-core::data-not-found-alert :colspan="5"/>
          @endforelse
        </x-slot>
        <x-slot name="extraData">{{ $activities->onEachSide(0)->links("vendor.pagination.bootstrap-4") }}</x-slot>
      </x-core::table>
    </x-slot>
  </x-core::card>

@endsection
