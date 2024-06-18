@extends('admin.layouts.master')
@section('content')
  <div class="page-header">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}"><i class="fe fe-home ml-1"></i> داشبورد </a>
      </li>
      <li class="breadcrumb-item active">لیست درامد ها</li>
    </ol>
    @can('create revenues')
      <x-core::register-button route="admin.revenues.create" title="ثبت درامد جدید"/>
    @endcan
  </div>
  @include('accounting::revenue.filter-form')
  <div class="card">
    <div class="card-header border-0">
      <p class="card-title"> لیست همه درامد ها ({{ $totalRevenues }}) </p>
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
                <th class="text-center">سرفصل</th>
                <th class="text-center">عنوان</th>
                <th class="text-center">کد</th>
                <th class="text-center">مبلغ (تومان)</th>
                <th class="text-center">تاریخ پرداخت</th>
                <th class="text-center">تاریخ ثبت</th>
                <th class="text-center">عملیات</th>
              </tr>
              </thead>
              <tbody>
              @forelse ($revenues as $revenue)
                <tr>
                  <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                  <td class="text-center">{{ $revenue->headline->title }}</td>
                  <td class="text-center">{{ $revenue->title }}</td>
                  <td class="text-center">{{ $revenue->id }}</td>
                  <td class="text-center">{{ number_format($revenue->amount) }}</td>
                  <td class="text-center">{{ verta($revenue->payment_date)->format('Y/m/d H:i') }}</td>
                  <td class="text-center">{{ verta($revenue->created_at)->format('Y/m/d H:i') }}</td>
                  <td class="text-center">
                    <button
                      class="btn btn-sm btn-icon btn-primary"
                      onclick="showExpenseDescriptionModal('{{$revenue->description}}')"
                      data-toggle="modal"
                      data-original-title="توضیحات">
                      <i class="fa fa-eye"></i>
                    </button>
                    @can('edit revenues')
                      <x-core::edit-button route="admin.revenues.edit" :model="$revenue"/>
                    @endcan
                    @can('delete revenues')
                      <x-core::delete-button route="admin.revenues.destroy" :model="$revenue"/>
                    @endcan
                  </td>
                </tr>
              @empty
                <x-core::data-not-found-alert :colspan="8"/>
              @endforelse
              </tbody>
            </table>
            {{ $revenues->onEachSide(0)->links("vendor.pagination.bootstrap-4") }}
          </div>
        </div>
      </div>
    </div>
  </div>
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
