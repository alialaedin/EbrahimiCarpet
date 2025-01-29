@extends('admin.layouts.master')
@section('content')

  <div class="page-header">
    <x-core::breadcrumb :items="[
      ['title' => 'لیست ادمین ها', 'route_link' => 'admin.admins.index'],
      ['title' => 'نمایش ادمین']
    ]"/>
  </div>

  <x-core::card>
    <x-slot name="cardTitle">مشخصات ادمین</x-slot>
    <x-slot name="cardOptions"><x-core::card-options/></x-slot>
    <x-slot name="cardBody">
      <div class="row">
        <div class="col-lg-4 col-md-6 col-12 fs-16 my-1">
          <span><strong>شناسه کاربر : </strong> {{ $admin->id }}</span>
        </div>
        <div class="col-lg-4 col-md-6 col-12 fs-16 my-1">
          <span><strong>نام و نام خانوادگی : </strong> {{ $admin->name }}</span>
        </div>
        <div class="col-lg-4 col-md-6 col-12 fs-16 my-1">
          <span><strong>شماره موبایل : </strong> {{ $admin->mobile }}</span>
        </div>
        <div class="col-lg-4 col-md-6 col-12 fs-16 my-1">
          <span><strong>نقش : </strong> {{ $admin->getRoleLabel() }}</span>
        </div>
        <div class="col-lg-4 col-md-6 col-12 fs-16 my-1">
          <span>
            <strong>وضعیت : </strong>
            <x-core::badge
              type="{{ $admin->status ? 'success' : 'danger' }}"
              text="{{ $admin->status ? 'فعال' : 'غیر فعال' }}"
            />
          </span>
        </div>
        <div class="col-lg-4 col-md-6 col-12 fs-16 my-1">
          <span><strong>تاریخ ثبت : </strong> @jalaliDate($admin->created_at) </span>
        </div>
      </div>
    </x-slot>
  </x-core::card>

  <x-core::card>
    <x-slot name="cardTitle">لیست فعالیت ها <span class="fs-15">({{ $totalActivity }})</span></x-slot>
    <x-slot name="cardOptions"><x-core::card-options/></x-slot>
    <x-slot name="cardBody">
      <x-core::table>
        <x-slot name="tableTh">
          <tr>
          <th>ردیف</th>
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
              <td>{{ $activity->description }}</td>
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
