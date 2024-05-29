@extends('admin.layouts.master')

@section('content')

  <div class="col-12">
    <div class="col-xl-12 col-md-12 col-lg-12">
      <div class="page-header">

        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="{{ route('admin.dashboard') }}">
              <i class="fe fe-life-buoy ml-1"></i> داشبورد
            </a>
          </li>
          <li class="breadcrumb-item active">
            <a href="{{ route('admin.purchases.index') }}">لیست خرید ها</a>
          </li>
          <li class="breadcrumb-item">
            <a>پرداختی ها</a>
          </li>
        </ol>
        
        @can('create payments')
          <a href="{{ route('admin.purchases.payments.create', $purchase) }}" class="btn btn-indigo">
            ثبت پرداختی جدید
            <i class="fa fa-plus font-weight-bolder"></i>
          </a>
        @endcan

    	</div>

			@include('core::includes.validation-errors')

      <div class="card">
  
        <div class="card-header border-0 justify-content-between ">
          <div class="d-flex">
            <p class="card-title ml-2" style="font-weight: bolder;">اطلاعات پراخت</p>
          </div>
        </div>

        <div class="card-body">
          <div class="row">

            @php
              $totalAmount = $purchase->getTotalAmountWithDiscount();
              $paymentAmount = $purchase->getTotalPaymentAmount();
              $remainingamount = $totalAmount - $paymentAmount;
            @endphp

            <div class="col-lg-4 col-md-6 col-12">
              <div class="d-flex align-items-center my-1">
                <span class="fs-16 font-weight-bold text-muted ml-1">مبلغ خرید :</span>
                <span class="fs-14 mr-1"> {{ number_format($totalAmount) }} تومان</span>
              </div>
            </div>

            <div class="col-lg-4 col-md-6 col-12">
              <div class="d-flex align-items-center my-1">
                <span class="fs-16 font-weight-bold text-muted ml-1">مبلغ پرداخت شده :</span>
                <span class="fs-14 mr-1"> {{ number_format($paymentAmount) }} تومان</span>
              </div>
            </div>

            <div class="col-lg-4 col-md-6 col-12">
              <div class="d-flex align-items-center my-1">
                <span class="fs-16 font-weight-bold text-muted ml-1">مبلغ باقی مانده :</span>
                <span class="fs-14 mr-1"> {{ number_format($remainingamount) }} تومان</span>
              </div>
            </div>

          </div>
        </div>

      </div>

			<div class="card">
        <div class="card-header border-0 justify-content-between ">
          <div class="d-flex">
            <p class="card-title ml-2" style="font-weight: bolder;">پرداختی های نقدی</p>
            <span class="fs-15 ">({{ $payments->where('type', 'cash')->count() }})</span>
          </div>
         
        </div>
        
        <div class="card-body">
          <div class="table-responsive">
            <div id="hr-table-wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
              <div class="row">
                <table class="table table-vcenter text-nowrap table-bordered border-bottom" id="hr-table">
                  <thead class="thead-light">
                    <tr>
                      <th class="text-center border-top">ردیف</th>
                      <th class="text-center border-top">مبلغ پرداختی (تومان)</th>
                      <th class="text-center border-top">تاریخ پرداخت</th>
                      <th class="text-center border-top">تاریخ ثبت</th>
                      <th class="text-center border-top">عملیات</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($payments->where('type', 'cash') as $payment)
                      <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ number_format($payment->amount) }}</td>
                        <td class="text-center">{{ verta($payment->payment_date) }}</td>
                        <td class="text-center">{{ verta($payment->created_at) }}</td>
                        <td class="text-center">

                          <button class="btn btn-sm btn-icon btn-success" onclick="showPaymentImage('{{$payment->image ? Storage::url($payment->image) : null }}')">
                            <i class="fa fa-image" data-toggle="tooltip" data-original-title="تصویر"></i>
                          </button>

                          <button class="btn btn-sm btn-icon btn-primary" onclick="showPaymentDescriptionModal('{{$payment->description}}')">
                            <i class="fa fa-book" data-toggle="tooltip" data-original-title="توضیحات"></i>
                          </button>

                          @can('edit payments')
                            <button class="btn btn-sm btn-icon btn-warning" data-target="#editCashPaymentModal{{$payment->id}}" data-toggle="modal">
                              <i class="fa fa-pencil" data-toggle="tooltip" data-original-title="ویرایش"></i>
                            </button>
                          @endcan

                          @can('delete payments')
                            <x-core::delete-button route="admin.purchases.payments.destroy" :model="$payment"/>
                          @endcan

                        </td>
                      </tr>
                      @empty
                        <x-core::data-not-found-alert :colspan="5"/>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header border-0 justify-content-between ">
          <div class="d-flex">
            <p class="card-title ml-2" style="font-weight: bolder;">چک و اقساط</p>
            <span class="fs-15 ">({{ $payments->whereIn('type', ['installment', 'cheque'])->count() }})</span>
          </div>
         
        </div>
        
        <div class="card-body">
          <div class="table-responsive">
            <div id="hr-table-wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
              <div class="row">
                <table class="table table-vcenter text-nowrap table-bordered border-bottom" id="hr-table">
                  <thead class="thead-light">
                    <tr>
                      <th class="text-center border-top">ردیف</th>
                      <th class="text-center border-top">مبلغ (تومان)</th>
                      <th class="text-center border-top">نوع پراختی</th>
                      <th class="text-center border-top">تاریخ پرداخت</th>
                      <th class="text-center border-top">عکس رسید</th>
                      <th class="text-center border-top">تاریخ سررسید</th>
                      <th class="text-center border-top">وضعیت</th>
                      <th class="text-center border-top">تاریخ ثبت</th>
                      <th class="text-center border-top">عملیات</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($payments->whereIn('type', ['installment', 'cheque']) as $payment)
                      <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ number_format($payment->amount) }}</td>
                        <td class="text-center">{{ $payment->getType() }}</td>
                        <td class="text-center">
                          @if ($payment->payment_date)
                            {{ verta($payment->payment_date)->formatDate() }}
                          @else
                            <span class="text-danger">پرداخت نشده</span>
                          @endif
                        </td>
                        <td class="text-center m-0 p-0">
                          @if ($payment->image)
                            <figure class="figure my-2">
                              <a target="_blank" href="{{ Storage::url($payment->image) }}">
                                <img src="{{ Storage::url($payment->image) }}" class="img-thumbnail" width="50" height="50" />
                              </a>
                            </figure>
                          @else
                            <span> - </span>
                          @endif
                        </td>
                        <td class="text-center">{{ verta($payment->due_date)->formatDate() }}</td>
                        <td class="text-center">
                          <x-core::badge 
                            type="{{ $payment->status ? 'success' : 'danger' }}" 
                            text="{{ $payment->status ? 'فعال' : 'غیر فعال' }}" 
                          />
                        </td>
                        <td class="text-center">{{ verta($payment->created_at)->formatDate() }}</td>
                        <td class="text-center">

                          <button 
                            class="btn btn-sm btn-icon btn-primary" 
                            onclick="showPaymentDescriptionModal('{{$payment->description}}')" 
                            data-toggle="tooltip" 
                            data-original-title="توضیحات">
                            <i class="fa fa-book" ></i>
                          </button>

                          @can('edit payments')
                            <x-core::edit-button route="admin.purchases.payments.edit" :model="$payment"/>
                          @endcan

                          @can('delete payments')
                            <x-core::delete-button route="admin.purchases.payments.destroy" :model="$payment"/>
                          @endcan
                          
                        </td>
                      </tr>
                      @empty
                        <x-core::data-not-found-alert :colspan="7"/>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  @include('payment::_show-description-modal')

@endsection

@section('scripts')
  <script>
    function showPaymentDescriptionModal (description) {
      var modal = $('#showDescriptionModal');
      modal.find('#description').text(description ?? '-');
      modal.modal('show');
    }
  </script>
@endsection