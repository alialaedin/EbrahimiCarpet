@extends('admin.layouts.master')
@section('content')

  <div class="page-header">
    <x-core::breadcrumb :items="[['title' => 'حساب های بانکی']]"/>
    @can('create accounts')
      <button 
        class="btn btn-sm btn-indigo" 
        data-target="#createAccountModal"
        data-toggle="modal">ثبت حساب جدید
        <i class="fa fa-plus mr-1"></i>
      </button>
    @endcan
  </div>

  <x-core::card>
    <x-slot name="cardTitle">لیست تمامی حساب های بانکی ({{ $totalAccounts }})</x-slot>
    <x-slot name="cardOptions"></x-slot>
    <x-slot name="cardBody">
      <x-core::table>
        <x-slot name="tableTh">
          <tr>
            <th>ردیف</th>
            <th>نام تامین کننده</th>
            <th>شماره موبایل</th>
            <th>شماره حساب</th>
            <th>شماره کارت</th>
            <th>نام بانک</th>
            <th>تاریخ ثبت</th>
            <th>عملیات</th>
          </tr>
        </x-slot>
        <x-slot name="tableTd">
          @forelse ($accounts as $account)
            <tr>
              <td class="font-weight-bold">{{ $loop->iteration }}</td>
              <td>
                <a href="{{ route('admin.suppliers.show', $account->supplier) }}">{{ $account->supplier->name }}</a>
              </td>
              <td>{{ $account->supplier->mobile }}</td>
              <td>{{ $account->account_number }}</td>
              <td>{{ $account->card_number }}</td>
              <td>{{ $account->bank_name }}</td>
              <td> @jalaliDate($account->created_at)</td>
              <td>
                @can('edit accounts')
                  <button
                    class="btn btn-sm btn-icon btn-warning text-white"
                    data-target="#editAccountModal-{{ $account->id }}"
                    data-toggle="modal"
                    data-original-title="ویرایش">
                    <i class="fa fa-pencil"></i>
                  </button>
                @endcan
                @can('delete accounts')
                  <x-core::delete-button route="admin.accounts.destroy" :model="$account"/>
                @endcan
              </td>
            </tr>
          @empty
            <x-core::data-not-found-alert :colspan="8"/>
          @endforelse
        </x-slot>
        <x-slot name="extraData">
          {{ $accounts->onEachSide(0)->links("vendor.pagination.bootstrap-4") }}
        </x-slot>
      </x-core::table>
    </x-slot>
  </x-core::card>

  @include('supplier::account.create-modal')
  @include('supplier::account.edit-modal')

@endsection

