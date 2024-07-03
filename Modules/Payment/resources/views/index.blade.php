@extends('admin.layouts.master')
@section('content')
  <div class="page-header">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}">
          <i class="fe fe-home ml-1"></i> داشبورد
        </a>
      </li>
      <li class="breadcrumb-item active">
        <a>پرداختی ها</a>
      </li>
    </ol>
  </div>
  <div class="card">
    <div class="card-header border-0">
      <p class="card-title">لیست تمام پرداختی ها ({{ $totalPayments }})</p>
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
                <th class="text-center">تامین کننده</th>
                <th class="text-center">مبلغ پرداختی (تومان)</th>
                <th class="text-center">نوع پرداخت</th>
                <th class="text-center">عکس رسید</th>
                <th class="text-center">تاریخ سررسید</th>
                <th class="text-center">تاریخ پرداخت</th>
                <th class="text-center">تاریخ ثبت</th>
                <th class="text-center">وضعیت</th>
                <th class="text-center">عملیات</th>
              </tr>
              </thead>
              <tbody>
              @forelse ($payments as $payment)
                <tr>
                  <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                  <td class="text-center">
                    <a href="{{ route('admin.suppliers.show', $payment->supplier->id) }}">
                      {{ $payment->supplier->name }}
                    </a>
                  </td>
                  <td class="text-center">{{ number_format($payment->amount) }}</td>
                  <td class="text-center">{{ config('payment.types.'.$payment->type) }}</td>
                  <td class="text-center m-0 p-0">
                    @if ($payment->image)
                      <figure class="figure my-2">
                        <a target="_blank" href="{{ Storage::url($payment->image) }}">
                          <img
                            src="{{ Storage::url($payment->image) }}"
                            class="img-thumbnail"
                            alt="image"
                            width="50"
                            style="max-height: 32px;"
                          />
                        </a>
                      </figure>
                    @else
                      <span> - </span>
                    @endif
                  </td>
                  <td class="text-center"> @jalaliDate($payment->due_date)</td>
                  <td class="text-center"> @jalaliDate($payment->payment_date)</td>
                  <td class="text-center"> @jalaliDate($payment->created_at)</td>
                  <td class="text-center">
                    <x-core::badge
                      type="{{ $payment->status ? 'success' : 'danger' }}"
                      text="{{ config('payment.statuses.' . $payment->type . $payment->status) }}"
                    />
                  </td>
                  <td class="text-center">
                    <a
                      class="btn btn-lime btn-sm btn-icon"
                      href="{{ route('admin.payments.create', $payment->supplier) }}"
                      data-toggle="tooltip"
                      data-original-title="ثبت پرداختی">
                      <i class="fa fa-plus-circle"></i>
                    </a>
                    <x-core::show-button route="admin.payments.show" :model="$payment->supplier"/>
                    <button
                      class="btn btn-sm btn-icon btn-teal "
                      onclick="showPaymentDescriptionModal('{{$payment->description}}')"
                      data-toggle="tooltip"
                      data-original-title="توضیحات">
                      <i class="fa fa-book"></i>
                    </button>
                    @can('edit payments')
                      <x-core::edit-button route="admin.payments.edit" :model="$payment"/>
                    @endcan
                    @can('delete payments')
                      <x-core::delete-button route="admin.payments.destroy" :model="$payment"/>
                    @endcan
                  </td>
                </tr>
              @empty
                <x-core::data-not-found-alert :colspan="10"/>
              @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  @include('payment::_show-description-modal')
@endsection

@section('scripts')
  <script>
    function showPaymentDescriptionModal(description) {
      let modal = $('#showDescriptionModal');
      modal.find('#description').text(description ?? '-');
      modal.modal('show');
    }
  </script>
@endsection
