@extends('admin.layouts.master')
@section('content')

<div class="page-header">
  <x-core::breadcrumb :items="[['title' => 'لیست خرید ها']]"/>
  @can('create purchases')
    <x-core::create-button route="admin.purchases.create" title="ثبت خرید جدید"/>
  @endcan
</div>

@include('purchase::includes._filter-form')

<x-core::card>
  <x-slot name="cardTitle">لیست خرید ها ({{ $purchasesCount }})</x-slot>
  <x-slot name="cardOptions"><x-core::card-options/></x-slot>
  <x-slot name="cardBody">
    <x-core::table>
      <x-slot name="tableTh">
        <tr>
          <th>ردیف</th>
          <th>نام تامین کننده</th>
          <th>شماره موبایل</th>
          <th>مبلغ خرید (ریال)</th>
          <th>تخفیف کلی (ریال)</th>
          <th>مبلغ خرید با تخفیف (ریال)</th>
          <th>تاریخ خرید</th>
          <th>عملیات</th>
        </tr>
      </x-slot>
      <x-slot name="tableTd">
        @forelse ($purchases as $purchase)
          <tr>
            <td class="font-weight-bold">{{ $loop->iteration }}</td>
            <td><a href="{{ route('admin.suppliers.show', $purchase->supplier) }}" target="_blank">{{ $purchase->supplier->name }}</a></td>
            <td>{{ $purchase->supplier->mobile }}</td>
            <td>{{ number_format($purchase->total_items_amount) }}</td>
            <td>{{ number_format($purchase->discount) }}</td>
            <td>{{ number_format($purchase->total_amount) }}</td>
            <td> @jalaliDateFormat($purchase->purchased_at) </td>
            <td>
              @can('view purchases')
                <x-core::show-button route="admin.purchases.show" :model="$purchase"/>
              @endcan
              @can('edit purchases')
                <a
                  href="{{route('admin.purchases.edit', $purchase)}}"
                  class="btn btn-sm btn-icon btn-warning text-white"
                  data-toggle="tooltip"
                  data-original-title="ویرایش">
                  <i class="fa fa-pencil"></i>
                </a>
              @endcan
              @can('delete purchases')
                <x-core::delete-button route="admin.purchases.destroy" :model="$purchase"/>
              @endcan
            </td>
          </tr>
        @empty
          <x-core::data-not-found-alert :colspan="8"/>
        @endforelse
      </x-slot>
      <x-slot name="extraData">{{ $purchases->onEachSide(0)->links("vendor.pagination.bootstrap-4") }}</x-slot>
    </x-core::table>
  </x-slot>
</x-core::card>

@endsection