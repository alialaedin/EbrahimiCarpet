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

      #header::before {
        content: '';
        width: 20px;
        height: 100%;
        position: absolute;
        right: 0;
        bottom: 0;
        background-color: #CBCACA;
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
        <table class="table table-vcenter table-striped text-nowrap table-bordered border-bottom card-table">
          <thead style="background-color: #EAEAEA">
          <tr>
            <th class="text-center border-top">ردیف</th>
            <th class="text-center border-top">نام محصول</th>
            <th class="text-center border-top">نوع واحد</th>
            <th class="text-center border-top">تعداد</th>
            <th class="text-center border-top">قیمت واحد (تومان)</th>
            <th class="text-center border-top">تخفیف (تومان)</th>
            <th class="text-center border-top">قیمت با تخفیف (تومان)</th>
            <th class="text-center border-top">قیمت کل (تومان)</th>
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
                <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
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
              @endphp

            @endforeach

            <tr class="bg-light">
              <td class="text-center" colspan="3"> جمع کل </td>
              <td class="text-center"> {{ number_format($totalQuantity) }} </td>
              <td class="text-center"> {{ number_format($totalPrice) }} </td>
              <td class="text-center"> {{ number_format($totalDiscount) }} </td>
              <td class="text-center"> {{ number_format($totalPriceWithDiscount) }} </td>
              <td class="text-center"> {{ number_format($totalTotalItemPrice) }} </td>
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

    </div>

    <a href="#" id="back-to-top"><span class="feather feather-chevrons-up"></span></a>

    @include("admin.layouts.includes.scripts")

  </body>
</html>
