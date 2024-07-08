@extends('admin.layouts.master')
@section('content')
  <div class="page-header">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fe fe-home ml-1"></i> داشبورد </a></li>
      <li class="breadcrumb-item active">لیست حساب بانکی ها</li>
    </ol>
    @can('create accounts')
      <button class="btn btn-indigo" data-target="#createAccountModal" data-toggle="modal">
        ثبت حساب جدید
        <i class="fa fa-plus font-weight-bolder mr-1"></i>
      </button>
    @endcan
  </div>
  <div class="card">
    <div class="card-header border-0">
      <p class="card-title"> لیست همه حساب ها ({{ $totalAccounts }}) </p>
      <x-core::card-options/>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <div class="dataTables_wrapper dt-bootstrap4 no-footer">
          <div class="row">
            <table class="table table-vcenter table-striped text-nowrap table-bordered border-bottom">
              <thead class="thead-light">
              <tr>
                <th class="text-center">ردیف</th>
                <th class="text-center">نام تامین کننده</th>
                <th class="text-center">شماره موبایل</th>
                <th class="text-center">شماره حساب</th>
                <th class="text-center">شماره کارت</th>
                <th class="text-center">نام بانک</th>
                <th class="text-center">تاریخ ثبت</th>
                <th class="text-center">عملیات</th>
              </tr>
              </thead>
              <tbody>
              @forelse ($accounts as $account)
                <tr>
                  <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                  <td class="text-center">{{ $account->supplier->name }}</td>
                  <td class="text-center">{{ $account->supplier->mobile }}</td>
                  <td class="text-center">{{ $account->account_number }}</td>
                  <td class="text-center">{{ $account->card_number }}</td>
                  <td class="text-center"> @jalaliDate($account->created_at) </td>
                  <td class="text-center">
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
              </tbody>
            </table>
            {{ $accounts->onEachSide(0)->links("vendor.pagination.bootstrap-4") }}
          </div>
        </div>
      </div>
    </div>
  </div>

  @include('accounting::headline.includes.create-headline-modal')
  @include('accounting::headline.includes.edit-headline-modal')

@endsection

