@extends('admin.layouts.master')
@section('content')

  <div class="page-header">
    <x-core::breadcrumb :items="[['title' => 'لیست هزینه ها']]"/>
    @can('create expenses')
      <x-core::create-button title="ثبت هزینه جدید" route="admin.expenses.create"/>
    @endcan
  </div>

  @include('accounting::expense.filter-form')

  <x-core::card>
    <x-slot name="cardTitle">لیست هزینه ها ({{ $totalExpenses }})</x-slot>
    <x-slot name="cardOptions"><x-core::card-options/></x-slot>
    <x-slot name="cardBody">
      <x-core::table>
        <x-slot name="tableTh">
          <tr>
            <th>ردیف</th>
            <th>سرفصل</th>
            <th>عنوان</th>
            <th>کد</th>
            <th>مبلغ (ریال)</th>
            <th>تاریخ پرداخت</th>
            <th>تاریخ ثبت</th>
            <th>عملیات</th>
          </tr>
        </x-slot>
        <x-slot name="tableTd">
          @forelse ($expenses as $expense)
            <tr>
              <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
              <td class="text-center">{{ $expense->headline->title }}</td>
              <td class="text-center">{{ $expense->title }}</td>
              <td class="text-center">{{ $expense->id }}</td>
              <td class="text-center">{{ number_format($expense->amount) }}</td>
              <td class="text-center">{{ verta($expense->payment_date)->format('Y/m/d H:i') }}</td>
              <td class="text-center">{{ verta($expense->created_at)->format('Y/m/d H:i') }}</td>
              <td class="text-center">
                <button
                  class="btn btn-sm btn-icon btn-primary"
                  onclick="showExpenseDescriptionModal('{{$expense->description}}')"
                  data-toggle="modal"
                  data-expense-id="{{ $expense->id }}"
                  data-original-title="توضیحات">
                  <i class="fa fa-eye"></i>
                </button>
                @can('edit expenses')
                  <x-core::edit-button route="admin.expenses.edit" :model="$expense"/>
                @endcan
                @can('delete expenses')
                  <x-core::delete-button route="admin.expenses.destroy" :model="$expense"/>
                @endcan
              </td>
            </tr>
          @empty
            <x-core::data-not-found-alert :colspan="8"/>
          @endforelse
        </x-slot>
        <x-slot name="extraData">{{ $expenses->onEachSide(0)->links("vendor.pagination.bootstrap-4") }}</x-slot>
      </x-core::table>
    </x-slot>
  </x-core::card>


  <div class="modal fade" id="showDescriptionModal" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content modal-content-demo">
        <div class="modal-header">
          <p class="modal-title" style="font-size: 20px;">توضیحات</p><button aria-label="Close" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body">
          <p id="description"></p>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('scripts')

  <x-core::date-input-script textInputId="from_payment_date_show" dateInputId="from_payment_date"/>
  <x-core::date-input-script textInputId="to_payment_date_show" dateInputId="to_payment_date"/>

  <script>
    function showExpenseDescriptionModal (description) {
      let modal = $('#showDescriptionModal');
      modal.find('#description').text(description ?? '-');
      modal.modal('show');
    }
  </script>

@endsection
