<!doctype html>
<html lang="fa" dir="rtl">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
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
        <span id="header-text">فرش ابراهیمی</span>
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

      <div class=" d-flex justify-content-center">
        <p class="fs-17">
          فاکتور ارائه شده به
          <strong> {{ config('customer.gender_prefix_to_print.' . $customer->gender) .' '. $customer->name}} </strong>
          با شماره تماس <strong> {{ $customer->mobile }} </strong>
          به نشانی <strong> {{ $customer->address }} </strong>
        </p>
      </div>

      <div style="margin-top: 70px;">
        <p class="d-block text-center fs-22 font-weight-bold">محصولات خریداری شده</p>
        <table class="table table-vcenter table-striped text-nowrap table-bordered border-bottom card-table">
          <thead style="background-color: #EAEAEA">
          <tr>
            <th class="text-center border-top">ردیف</th>
            <th class="text-center border-top">نام محصول</th>
            <th class="text-center border-top">نوع واحد</th>
            <th class="text-center border-top">تعداد</th>
            <th class="text-center border-top">قیمت واحد (ریال)</th>
            <th class="text-center border-top">تخفیف (ریال)</th>
            <th class="text-center border-top">قیمت با تخفیف (ریال)</th>
            <th class="text-center border-top">قیمت کل (ریال)</th>
          </tr>
          </thead>
          <tbody>

            @php
              $totalQuantity = 0;
              $totalPrice = 0;
              $totalDiscount = 0;
              $totalPriceWithDiscount = 0;
              $totalTotalItemPrice = 0;
              $counter = 1;
            @endphp

            @foreach($customer->sales as $sale)
              @foreach($sale->items as $item)
                <tr>
                  <td class="text-center font-weight-bold">{{ $counter }}</td>
                  <td class="text-center">{{ $item->product->title }}</td>
                  <td class="text-center">{{ $item->product->category->getUnitType() }}</td>
                  <td class="text-center">{{ $item->quantity }}</td>
                  <td class="text-center">{{ number_format($item->price) }}</td>
                  <td class="text-center">{{ number_format($item->discount) }}</td>
                  <td class="text-center">{{ number_format($item->getPriceWithDiscount()) }}</td>
                  <td class="text-center">{{ number_format($item->getTotalItemPrice()) }}</td>
                </tr>

                @php
                  $totalQuantity += $item->quantity;
                  $totalPrice += $item->price;
                  $totalDiscount += $item->discount;
                  $totalPriceWithDiscount += $item->getPriceWithDiscount();
                  $totalTotalItemPrice += $item->getTotalItemPrice();
                  $counter++;
                @endphp
              @endforeach
            @endforeach

            @php $salesDiscount = $customer->sales->sum('discount'); @endphp

            <tr>
              <td class="text-center fs-20 font-weight-bold" colspan="7"> سود شما از خرید </td>
              <td class="text-center fs-20" colspan="1"> {{ number_format($salesDiscount) }} </td>
            </tr>

            <tr class="bg-light">
              <td class="text-center fs-20 font-weight-bold" colspan="7"> جمع کل </td>
              <td class="text-center fs-20" colspan="1"> {{ number_format($totalTotalItemPrice - $salesDiscount) }} </td>
            </tr>

          </tbody>
        </table>
      </div>

      <div style="margin-top: 100px;">
        <p class="d-block text-center fs-22 font-weight-bold">پرداختی ها نقد و چکی</p>
        <table class="table table-vcenter table-striped text-nowrap table-bordered border-bottom card-table">
          <thead style="background-color: #EAEAEA">
          <tr>
            <th class="text-center border-top">ردیف</th>
            <th class="text-center border-top">نوع پراخت</th>
            <th class="text-center border-top">تاریخ پرداخت</th>
            <th class="text-center border-top">تاریخ سررسید</th>
            <th class="text-center border-top">وضعیت</th>
            <th class="text-center border-top">تاریخ ثبت</th>
            <th class="text-center border-top">مبلغ (ریال)</th>
          </tr>
          </thead>
          <tbody>

          @php $totalPayments = 0; @endphp

          @foreach($customer->payments->where('type', '!==', 'installment') as $payment)
            <tr>
              <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
              <td class="text-center">{{ $payment->getType() }}</td>
              <td class="text-center">{{ $payment->getPaymentDate() }}</td>
              <td class="text-center"> @jalaliDate($payment->due_date) </td>
              <td class="text-center">{{ config('payment.statuses.'.$payment->type.'.'.$payment->status) }}</td>
              <td class="text-center"> @jalaliDate($payment->created_at) </td>
              <td class="text-center">{{ number_format($payment->amount) }}</td>
            </tr>

            @php $totalPayments += $payment->amount; @endphp

          @endforeach

          <tr class="bg-light">
            <td class="text-center fs-20 font-weight-bold" colspan="6"> جمع کل </td>
            <td class="text-center fs-20" colspan="1"> {{ number_format($totalPayments) }} </td>
          </tr>

          </tbody>
        </table>
      </div>

      <div style="margin-top: 40px;">

        <div class="row d-flex align-items-center">

          <div class="col-8">
            <ul>
              <li class="fs-16 mb-2"><strong>آدرس فروشگاه :</strong> گلوگاه - خیابان تست - پلاک 23</li>
              <li class="fs-16 mt-2"><strong>شماره تماس فروشکاه :</strong> 093654653215 - 01754634697</li>
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
        <x-core::print-button title="صدور فاکتور فروش"/>
      </div>

    </div>

    <a href="#" id="back-to-top"><span class="feather feather-chevrons-up"></span></a>

    @include("admin.layouts.includes.scripts")

  </body>
</html>
