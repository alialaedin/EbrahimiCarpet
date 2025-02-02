<!doctype html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>@yield('title', config('app.name'))</title>
  @include("admin.layouts.includes.styles")

  <style>

    body {
      background: white;
    }

    #header {
      padding: 20px 5%;
      margin-top: 20px;
      position: relative;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    #header-text {
      font-weight: bold;
      font-size: 20px;
    }

    #main {
      padding: 20px 5%;
    }

    @media print {

      thead {
        background-color: #EAEAEA;
      }

    }

  </style>

</head>
<body>

<div id="header">

  <div>
    <span id="header-text">سرای فرش ابراهیمی</span>
  </div>

  <div class="d-flex justify-content-center">
    <span class="fs-28 font-weight-bold">فاکتور خرید مشتری</span>
  </div>

  <div>
    <ul>
      <li class="fs-14"> تاریخ : {{ verta()->format('Y/m/d') }}
      <li class="fs-14"> کد مشتری : {{ $customer->id }}</li>
    </ul>
  </div>

</div>

<div id="main">

  <div class="d-flex justify-content-center" style="margin-bottom: 70px;">
    <p class="fs-17">
      فاکتور ارائه شده به
      <strong> {{ config('customer.gender_prefix_to_print.' . $customer->gender) .' '. $customer->name}} </strong>
      با شماره تماس <strong> {{ $customer->mobile }} </strong>
      به نشانی <strong> {{ $customer->address }} </strong>
    </p>
  </div>

  @foreach ($customer->sales as $sale)

    <div style="margin-top: 100px;">
      <table class="table table-vcenter text-center table-striped text-nowrap table-bordered border-bottom card-table">
        <thead style="background-color: #EAEAEA">
        <tr>
          <th>ردیف</th>
          <th>محصول</th>
          <th>نوع واحد</th>
          <th>تعداد</th>
          <th>قیمت واحد (ریال)</th>
          <th>تخفیف واحد (ریال)</th>
          <th>قیمت کل (ریال)</th>
          <th>تخفیف کل (ریال)</th>
          <th>قیمت نهایی (ریال)</th>
        </tr>
        </thead>
        <tbody>
          @foreach ($sale->items as $item)
          <tr>
            <td class="font-weight-bold">{{ $loop->iteration }}</td>
            <td>{{ $item->product->print_title .' '. $item->product->sub_title }}</td>
            <td>{{ $item->product->category->getUnitType() }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ number_format($item->price) }}</td>
            <td>{{ number_format($item->discount) }}</td>
            <td>{{ number_format($item->total_amount_without_discount) }}</td>
            <td>{{ number_format($item->total_discount_amount) }}</td>
            <td>{{ number_format($item->total_amount) }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="row justify-content-center mt-2">
      <div class="col-12 col-xl-10 py-3 px-4 d-flex  justify-content-between align-items-center" style="border: black 2px solid; border-radius: 22px;">
        <span>تخفیف روی فاکتور : <b>{{ number_format($sale->discount) }} تومان</b></span>
        <span>جمع کل آیتم ها : <b>{{ number_format($sale->total_items_amount) }} تومان</b></span>
        <span>هزینه نصب : <b>{{ number_format($sale->cost_of_sewing) }} تومان</b></span>
        <span>مبلغ نهایی فاکتور : <b>{{ number_format($sale->total_amount) }} تومان</b></span>
      </div>
    </div>

  @endforeach

  @if($customer->payments->where('type', '===', 'cash')->isNotEmpty())
    <div style="margin-top: 70px;">
      <p class="d-block text-center fs-22 font-weight-bold">نقدی ها</p>
      <table class="table table-vcenter table-striped text-nowrap table-bordered border-bottom card-table">
        <thead style="background-color: #EAEAEA">
        <tr>
          <th class="text-center border-top">ردیف</th>
          <th class="text-center border-top">تاریخ پرداخت</th>
          <th class="text-center border-top">وضعیت</th>
          <th class="text-center border-top">مبلغ (ریال)</th>
        </tr>
        </thead>
        <tbody>
        @foreach($customer->payments->where('type', '===', 'cash') as $payment)
          <tr>
            <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
            <td class="text-center"> {{ verta($payment->payment_date)->format('Y/m/d') }} </td>
            <td class="text-center">{{ config('payment.statuses.'.$payment->type.'.'.$payment->status) }}</td>
            <td class="text-center">{{ number_format($payment->amount) }}</td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  @endif

  @if($customer->payments->where('type', '===', 'cheque')->isNotEmpty())
    <div style="margin-top: 70px;">
      <p class="d-block text-center fs-22 font-weight-bold">چک ها</p>
      <table class="table table-vcenter table-striped text-nowrap table-bordered border-bottom card-table">
        <thead style="background-color: #EAEAEA">
        <tr>
          <th class="text-center border-top">ردیف</th>
          <th class="text-center border-top">سریال چک</th>
          <th class="text-center border-top">نام بانک</th>
          <th class="text-center border-top">صاحب چک</th>
          <th class="text-center border-top">موعد چک</th>
          <th class="text-center border-top">تاریخ پرداخت</th>
          <th class="text-center border-top">وضعیت</th>
          <th class="text-center border-top">مبلغ (ریال)</th>
        </tr>
        </thead>
        <tbody>
        @foreach($customer->payments->where('type', '===', 'cheque') as $payment)
          <tr>
            <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
            <td class="text-center">{{ $payment->cheque_serial }}</td>
            <td class="text-center">{{ $payment->bank_name }}</td>
            <td class="text-center">{{ $payment->cheque_holder }}</td>
            <td class="text-center"> {{ verta($payment->due_date)->format('Y/m/d') }} </td>
            <td class="text-center"> {{ verta($payment->payment_date)->format('Y/m/d') }} </td>
            <td class="text-center">{{ config('payment.statuses.'.$payment->type.'.'.$payment->status) }}</td>
            <td class="text-center">{{ number_format($payment->amount) }}</td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  @endif

  @if($customer->payments->where('type', '===', 'installment')->isNotEmpty())
    <div style="margin-top: 70px;">
      <p class="d-block text-center fs-22 font-weight-bold">قسط ها</p>
      <table class="table table-vcenter table-striped text-nowrap table-bordered border-bottom card-table">
        <thead style="background-color: #EAEAEA">
        <tr>
          <th class="text-center border-top">ردیف</th>
          <th class="text-center border-top">تاریخ سررسید</th>
          <th class="text-center border-top">تاریخ پرداخت</th>
          <th class="text-center border-top">وضعیت</th>
          <th class="text-center border-top">مبلغ (ریال)</th>
        </tr>
        </thead>
        <tbody>
        @foreach($customer->payments->where('type', '!==', 'installment') as $payment)
          <tr>
            <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
            <td class="text-center"> {{ verta($payment->due_date)->format('Y/m/d') }} </td>
            <td class="text-center"> {{ verta($payment->payment_date)->format('Y/m/d') }} </td>
            <td class="text-center">{{ config('payment.statuses.'.$payment->type.'.'.$payment->status) }}</td>
            <td class="text-center">{{ number_format($payment->amount) }}</td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  @endif

  <div style="margin-top: 40px;">

    <div class="row d-flex align-items-center">

      <div class="col-8">
        <ul>
          <li class="fs-16 mb-2"><strong>آدرس فروشگاه :</strong> گلوگاه - خیابان شهید پهلوان - نبش کوچه چهارم - سرای فرش ابراهیمی</li>
          <li class="fs-16 mt-2"><strong>شماره تماس فروشکاه :</strong> 01134669718 - 09904712648</li>
        </ul>
      </div>

      <div class="col-4">
        <ul>
          <li class="fs-16"><strong>مهر و امضای مدیر :</strong></li>
        </ul>
      </div>
    </div>

  </div>

  <div class="row justify-content-center d-print-none" style="margin-top: 4%;">
    <x-core::print-button title="چاپ"/>
  </div>

</div>

<a href="#" id="back-to-top"><span class="feather feather-chevrons-up"></span></a>

@include("admin.layouts.includes.scripts")

</body>
</html>
