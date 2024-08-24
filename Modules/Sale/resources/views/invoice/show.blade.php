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

        #header::before {
          content: '';
          width: 20px;
          height: 100%;
          position: absolute;
          right: 0;
          bottom: 0;
          background-color: #CBCACA;
        }

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

      <div>
        <ul>
          <li class="fs-14"> تاریخ خرید : {{ verta($sale->created_at)->format('Y/m/d') }}
          <li class="fs-14"> کد فروش : {{ $sale->id }}</li>
        </ul>
      </div>

    </div>

    <div id="main">

      <div class="d-flex justify-content-center">
        <span class="fs-28 font-weight-bold">فاکتور فروش</span>
      </div>

      <div class="mt-5 d-flex justify-content-center">
        <p class="fs-17">
          فاکتور ارائه شده به آقا / خانم <strong>{{ $sale->customer->name }}</strong>
          شماره تماس <strong>{{ $sale->customer->mobile }}</strong>
          به نشانی <strong>{{ $sale->customer->address }}</strong>
        </p>
      </div>

      <div>
        <table class="table table-vcenter text-center table-striped text-nowrap table-bordered border-bottom card-table">
          <thead style="background-color: #EAEAEA">
          <tr>
            <th>ردیف</th>
            <th>محصول</th>
            <th>ابعاد</th>
            <th>تعداد</th>
            <th>قیمت واحد (ریال)</th>
            <th>تخفیف (ریال)</th>
            <th>قیمت با تخفیف (ریال)</th>
            <th>قیمت کل (ریال)</th>
          </tr>
          </thead>
          <tbody>

            @php
              $totalQuantity = 0;
              $totalPrice = 0;
              $totalDiscount = 0;
              $totalPriceWithDiscount = 0;
              $totalTotalItemPrice = 0;
            @endphp

            @foreach($sale->items as $item)

              <tr>
                <td class=" font-weight-bold">{{ $loop->iteration }}</td>
                <td>{{ $item->product->title }}</td>
                <td>{{ $item->product->sub_title }}</td>
                <td>{{ $item->quantity .' '. $item->product->category->getUnitType()}}</td>
                <td>{{ number_format($item->price) }}</td>
                <td>{{ number_format($item->discount) }}</td>
                <td>{{ number_format($item->getPriceWithDiscount()) }}</td>
                <td>{{ number_format($item->getTotalItemPrice()) }}</td>
              </tr>

              @php
                $totalQuantity += $item->quantity;
                $totalPrice += $item->price;
                $totalDiscount += $item->discount;
                $totalPriceWithDiscount += $item->getPriceWithDiscount();
                $totalTotalItemPrice += $item->getTotalItemPrice();
              @endphp

            @endforeach

            <tr class="bg-gray-dark-lighter text-danger">
              <td class="font-weight-bold fs-17" colspan="7"> تخفیف کل فاکتور {{ $sale->discount_for ? 'بابت ' .  $sale->discount_for : null }} </td>
              <td class="font-weight-bold fs-17"> {{ number_format($sale->discount) }} </td>
            </tr>

            <tr class="bg-dark text-white">
              <td class="font-weight-bold fs-17" colspan="7"> جمع کل </td>
              <td class="font-weight-bold fs-17"> {{ number_format($totalTotalItemPrice - $sale->discount) }} </td>
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
