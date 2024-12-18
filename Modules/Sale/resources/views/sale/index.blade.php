@extends('admin.layouts.master')
@section('content')

<div class="page-header">
  <x-core::breadcrumb :items="[['title' => 'لیست فروش ها']]"/>
  @can('create sales')
    <x-core::create-button route="admin.sales.create" title="ثبت فروش جدید"/>
  @endcan
</div>

@include('sale::sale.includes._filter-form')

<x-core::card>
  <x-slot name="cardTitle">لیست فروش ها ({{ $salesCount }})</x-slot>
  <x-slot name="cardOptions"><x-core::card-options/></x-slot>
  <x-slot name="cardBody">
    <x-core::table>
      <x-slot name="tableTh">
        <tr>
          <th>ردیف</th>
          <th>نام مشتری</th>
          <th>شماره موبایل</th>
          <th>پرسنل ارجاع</th>
          <th>مبلغ خرید (ریال)</th>
          <th>هزینه دوخت / نصب (ریال)</th>
          <th>تخفیف کلی (ریال)</th>
          <th>مبلغ کل فاکتور (ریال)</th>
          <th>تاریخ خرید</th>
          <th>عملیات</th>
        </tr>
      </x-slot>
      <x-slot name="tableTd">
        @forelse ($sales as $sale)
          <tr>
            <td class="font-weight-bold">{{ $loop->iteration }}</td>
            <td>
              <a href="{{ route('admin.customers.show', $sale->customer) }}" target="_blank">{{ $sale->customer->name }}</a>
            </td>
            <td>{{ $sale->customer->mobile }}</td>
            <td>{{ $sale->employee->name ?? '-' }}</td>
            <td>{{ number_format($sale->getTotalAmount() - $sale->cost_of_sewing) }}</td>
            <td>{{ number_format($sale->cost_of_sewing) }}</td>
            <td>{{ number_format($sale->discount) }}</td>
            <td>{{ number_format($sale->getTotalAmountWithDiscount()) }}</td>
            <td>{{ verta($sale->sold_at)->formatDate() }}</td>
            <td>
              <a
                href="{{ route('admin.sales.invoice.show', $sale) }}"
                target="_blank"
                class="btn btn-sm btn-purple btn-icon text-white p-0"
                data-toggle="tooltip"
                data-original-title="فاکتور">
                <i class="fe fe-printer" style="margin: 1px 0; padding: 0 6px;"></i>
              </a>
              @can('view sales')
                <x-core::show-button route="admin.sales.show" :model="$sale"/>
              @endcan
              @can('edit sales')
                <a
                  href="{{route('admin.sales.edit', $sale)}}"
                  class="btn btn-sm btn-icon btn-warning text-white"
                  data-toggle="tooltip"
                  data-original-title="ویرایش">
                  <i class="fa fa-pencil"></i>
                </a>
              @endcan
              @can('delete sales')
                <x-core::delete-button route="admin.sales.destroy" :model="$sale"/>
              @endcan
            </td>
          </tr>
        @empty
          <x-core::data-not-found-alert :colspan="10"/>
        @endforelse
      </x-slot>
      <x-slot name="extraData">{{ $sales->onEachSide(0)->links("vendor.pagination.bootstrap-4") }}</x-slot>
    </x-core::table>
  </x-slot>
</x-core::card>

@endsection
