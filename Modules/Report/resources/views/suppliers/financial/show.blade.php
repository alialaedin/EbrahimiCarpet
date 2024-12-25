@extends('admin.layouts.master')
@section('content')
  
  <div class="page-header d-print-none">
    <x-core::breadcrumb :items="[
      ['title' => 'گزارشات', 'route_link' => 'admin.reports.index'],
      ['title' => 'فیلتر گزارش مالی تامین', 'route_link' => 'admin.reports.suppliers-finance-filter'],
      ['title' => 'گزارش مالی تامین کننده'],
    ]"/>
    <x-core::print-button title="پرینت"/>
  </div>

  <div class="row justify-content-center d-flex">
    <p class="fs-22">گزارش مالی تامین کننده با نام <b>{{ $supplier->name }}</b> و شماره همراه <b>{{ $supplier->mobile }}</b></p>
  </div>

  <div class="card">
    <div class="card-header border-0">
      <p class="card-title font-weight-bold">فاکتور خرید ها</p>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <div class="dataTables_wrapper dt-bootstrap4 no-footer">
          <div class="row">
            <table class="table table-vcenter text-center table-striped text-nowrap table-bordered border-bottom">
              <thead>
              <tr>
                <th>ردیف</th>
                <th>تاریخ خرید</th>
                <th>تاریخ ثبت</th>
                <th>مبلغ خرید (ریال)</th>
                <th>تخفیف (ریال)</th>
                <th>مبلغ با تخفیف (ریال)</th>
              </tr>
              </thead>
              <tbody>

              @php
                $totalPurchaseAmount = 0;
                $totalPurchaseDiscount = 0;
              @endphp

              @forelse ($supplier->purchases as $purchase)

                <tr>
                  <td class="font-weight-bold">{{ $loop->iteration }}</td>
                  <td>{{ verta($purchase->purchased_at)->format('Y/m/d') }}</td>
                  <td>{{ verta($purchase->created_at)->format('Y/m/d') }}</td>
                  <td>{{ number_format($purchase->total_items_amount) }}</td>
                  <td>{{ number_format($purchase->discount) }}</td>
                  <td>{{ number_format($purchase->total_amount) }}</td>
                </tr>

                @php
                  $totalPurchaseAmount += $purchase->total_items_amount;
                  $totalPurchaseDiscount += $purchase->discount;
                @endphp

              @empty
                <x-core::data-not-found-alert :colspan="6"/>
              @endforelse
              @if($supplier->purchases->isNotEmpty())

                <tr>
                  <td colspan="3" class="font-weight-bold">جمع کل</td>
                  <td colspan="1">{{ number_format($totalPurchaseAmount) }}</td>
                  <td colspan="1">{{ number_format($totalPurchaseDiscount) }}</td>
                  <td colspan="1">{{ number_format($totalPurchaseAmount - $totalPurchaseDiscount) }}</td>
                </tr>

              @endif
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  @isset($cashPayments)
    <div class="row justify-content-center d-none d-print-flex">
      <p class="fs-22 mt-5">پرداختی های نقدی</p>
    </div>
    <div class="card">
      <div class="card-header border-0">
        <p class="card-title">نقدی ها</p>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <div class="dataTables_wrapper dt-bootstrap4 no-footer">
            <div class="row">
              <table class="table table-vcenter text-nowrap table-bordered border-bottom">
                <thead>
                <tr>
                  <th class="text-center">ردیف</th>
                  <th class="text-center">تاریخ پرداخت</th>
                  <th class="text-center">تاریخ ثبت</th>
                  <th class="text-center">مبلغ پرداختی (ریال)</th>
                </tr>
                </thead>
                <tbody>

                @foreach($cashPayments as $payment)
                  <tr>
                    <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ verta($payment->payment_date)->format('Y/m/d') }}</td>
                    <td class="text-center">{{ verta($payment->created_at)->format('Y/m/d') }}</td>
                    <td class="text-center">{{ number_format($payment->amount) }}</td>
                  </tr>
                @endforeach
                <tr>
                  <td colspan="3" class="text-center font-weight-bold">جمع کل</td>
                  <td colspan="1" class="text-center">{{ number_format($cashPayments->sum('amount')) }}</td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endisset
  @isset($chequePayments)
    <div class="row justify-content-center d-none d-print-flex">
      <p class="fs-22 mt-5">پرداختی های چکی</p>
    </div>
    <div class="card">
      <div class="card-header border-0">
        <p class="card-title">چک ها</p>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <div class="dataTables_wrapper dt-bootstrap4 no-footer">
            <div class="row">
              <table class="table table-vcenter text-nowrap table-bordered border-bottom">
                <thead>
                <tr>
                  <th class="text-center">ردیف</th>
                  <th class="text-center">صاحب چک</th>
                  <th class="text-center">سریال</th>
                  <th class="text-center">بانک</th>
                  <th class="text-center">سررسید</th>
                  <th class="text-center">وضعیت</th>
                  <th class="text-center">مبلغ (ریال)</th>
                </tr>
                </thead>
                <tbody>

                @foreach($chequePayments as $payment)
                  <tr>
                    <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ $payment->cheque_holder }}</td>
                    <td class="text-center">{{ $payment->cheque_serial }}</td>
                    <td class="text-center">{{ $payment->bank_name }}</td>
                    <td class="text-center">{{ verta($payment->due_date)->format('Y/m/d') }}</td>
                    <td class="text-center">{{ config('payment.statuses.'.$payment->type.'.'.$payment->status) }}</td>
                    <td class="text-center">{{ number_format($payment->amount) }}</td>
                  </tr>
                @endforeach
                <tr>
                  <td colspan="6" class="text-center font-weight-bold">جمع کل</td>
                  <td colspan="1" class="text-center">{{ number_format($chequePayments->sum('amount')) }}</td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endisset
  @isset($installmentPayments)
    <div class="row justify-content-center d-none d-print-flex">
      <p class="fs-22 mt-5">پرداختی های قسطی</p>
    </div>
    <div class="card">
      <div class="card-header border-0">
        <p class="card-title">اقساط</p>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <div class="dataTables_wrapper dt-bootstrap4 no-footer">
            <div class="row">
              <table class="table table-vcenter text-nowrap table-bordered border-bottom">
                <thead>
                <tr>
                  <th class="text-center">ردیف</th>
                  <th class="text-center">سررسید</th>
                  <th class="text-center">تاریخ پرداخت</th>
                  <th class="text-center">وضعیت</th>
                  <th class="text-center">مبلغ (ریال)</th>
                </tr>
                </thead>
                <tbody>

                @foreach($installmentPayments as $payment)
                  <tr>
                    <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ verta($payment->due_date)->format('Y/m/d') }}</td>
                    <td class="text-center">{{ verta($payment->created_at)->format('Y/m/d') }}</td>
                    <td class="text-center">{{ config('payment.statuses.'.$payment->type.'.'.$payment->status) }}</td>
                    <td class="text-center">{{ number_format($payment->amount) }}</td>
                  </tr>
                @endforeach
                <tr>
                  <td colspan="4" class="text-center font-weight-bold">جمع کل</td>
                  <td colspan="1" class="text-center">{{ number_format($installmentPayments->sum('amount')) }}</td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endisset
@endsection
