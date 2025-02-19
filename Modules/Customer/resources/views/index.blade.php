@extends('admin.layouts.master')
@section('content')

  <div class="page-header">
    <x-core::breadcrumb :items="[['title' => 'لیست مشتریان']]"/>
    @can('create customers')
      <x-core::create-button route="admin.customers.create" title="ثبت مشتری جدید"/>
    @endcan
  </div>

  <x-core::card>
  <x-slot name="cardTitle">جستجوی پیشرفته</x-slot>
  <x-slot name="cardOptions"><x-core::card-options/></x-slot>
  <x-slot name="cardBody">
    <div class="row">
      <form action="{{ route("admin.customers.index") }}" class="col-12">
        <div class="row">
          <div class="col-12 col-md-6 col-xl-3">
            <div class="form-group">
              <label for="name">نام و نام خانوادگی :</label>
              <input type="text" id="name" name="full_name" class="form-control" value="{{ request('full_name') }}">
            </div>
          </div>
          <div class="col-12 col-md-6 col-xl-3">
            <div class="form-group">
              <label for="telephone">تلفن ثابت :</label>
              <input type="text" id="telephone" name="telephone" class="form-control" value="{{ request('telephone') }}">
            </div>
          </div>
          <div class="col-12 col-md-6 col-xl-3">
            <div class="form-group">
              <label for="mobile">تلفن همراه :</label>
              <input type="text" id="mobile" name="mobile" class="form-control" value="{{ request('mobile') }}">
            </div>
          </div>
          <div class="col-12 col-md-6 col-xl-3">
            <div class="form-group">
              <label for="status">وضعیت :</label>
              <select name="status" id="status" class="form-control">
                <option value="">همه</option>
                <option value="1" @selected(request("status") == "1")>فعال</option>
                <option value="0" @selected(request("status") == "0")>غیر فعال</option>
              </select>
            </div>
          </div>
          <div class="col-12 col-md-6 col-xl-3">
            <div class="form-group">
              <label for="start_date_show">از تاریخ :</label>
              <input class="form-control fc-datepicker" id="start_date_show" type="text" autocomplete="off"/>
              <input name="start_date" id="start_date" type="hidden" value="{{ request("start_date") }}"/>
            </div>
          </div>
          <div class="col-12 col-md-6 col-xl-3">
            <div class="form-group">
              <label for="end_date_show">تا تاریخ :</label>
              <input class="form-control fc-datepicker" id="end_date_show" type="text" autocomplete="off"/>
              <input name="end_date" id="end_date" type="hidden" value="{{ request("end_date") }}"/>
            </div>
          </div>
        </div>
        <x-core::filter-buttons table="customers"/>
      </form>
    </div>
  </x-slot>
  </x-core::card>

  <x-core::card>
    <x-slot name="cardTitle">لیست مشتریان ({{ $customersCount }})</x-slot>
    <x-slot name="cardOptions"><x-core::card-options/></x-slot>
    <x-slot name="cardBody">
      <x-core::table>
        <x-slot name="tableTh">
          <tr>
            <th>ردیف</th>
            <th>نام و نام خانوادگی</th>
            <th>جنسیت</th>
            <th>شماره موبایل</th>
            <th>تاریخ تولد</th>
            <th>وضعیت</th>
            <th>تاریخ ثبت</th>
            <th>عملیات</th>
          </tr>
        </x-slot>
        <x-slot name="tableTd">
          @forelse ($customers as $customer)
            <tr>
              <td class="font-weight-bold">{{ $loop->iteration }}</td>
              <td>{{ $customer->name }}</td>
              <td>{{ config('customer.genders.'.$customer->gender) }}</td>
              <td>{{ $customer->mobile }}</td>
              <td>{{ verta($customer->birthday)->format("Y/m/d") }}</td>
              <td>
                <x-core::badge
                  :type="$customer->status ? 'success' : 'danger'"
                  :text="$customer->status ? 'فعال' : 'غیر فعال'"
                />
              </td>
              <td>@jalaliDate($customer->created_at)</td>
              <td>
                <button
                  class="btn btn-vimeo btn-icon btn-sm"
                  onclick="$('#Form-{{  $customer->id }}').submit()"
                  data-toggle="tooltip"
                  data-original-title="فاکتور فروش جدید">
                  <i class="fa fa-shopping-cart"></i>
                </button>
                <form
                  action="{{ route('admin.sales.create') }}"
                  id="Form-{{  $customer->id }}"
                  method="GET"
                  class="d-none">
                  <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                </form>
                <a
                  href="{{ route('admin.customers.show-invoice', $customer) }}"
                  target="_blank"
                  class="btn btn-sm btn-purple btn-icon text-white p-0"
                  data-toggle="tooltip"
                  data-original-title="پرینت">
                  <i class="fe fe-printer" style="margin: 1px 0; padding: 0 6px;"></i>
                </a>
                @can('view sale_payments')
                  <button
                    class="btn btn-green btn-icon btn-sm show-all-payments-button"
                    data-toggle="tooltip"
                    data-customer-id="{{ $customer->id }}"
                    data-original-title="دریافتی ها">
                    <i class="fa fa-money"></i>
                  </button>
                @endcan
                @can('view customers')
                  <x-core::show-button route="admin.customers.show" :model="$customer"/>
                @endcan
                @can('edit customers')
                  <x-core::edit-button route="admin.customers.edit" :model="$customer"/>
                @endcan
                @can('delete customers')
                  <x-core::delete-button
                    route="admin.customers.destroy"
                    :model="$customer"
                    disabled="{{ !$customer->isDeletable() }}"
                  />
                @endcan
              </td>
            </tr>
          @empty
            <x-core::data-not-found-alert :colspan="8"/>
          @endforelse
        </x-slot>
        <x-slot name="extraData">{{ $customers->onEachSide(0)->links("vendor.pagination.bootstrap-4") }}</x-slot>
      </x-core::table>
    </x-slot>
  </x-core::card>

  <form id="all-payments-form" action="{{ route('admin.sale-payments.index') }}" method="GET" class="d-none">
    <input type="hidden" name="customer_id" value="">
  </form>

@endsection

@section('scripts')

  <x-core::date-input-script textInputId="start_date_show" dateInputId="start_date"/>
  <x-core::date-input-script textInputId="end_date_show" dateInputId="end_date"/>

  <script>
    $(document).ready(() => {
      $('.show-all-payments-button').each(function () {
        $(this).click(() => {
          let customerId = $(this).data('customer-id');
          let form = $('#all-payments-form');
          form.find('input[name=customer_id]').val(customerId);
          form.submit();
        }); 
      });
    });
  </script>

@endsection