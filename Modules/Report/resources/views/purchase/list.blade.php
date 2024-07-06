@extends('admin.layouts.master')
@section('content')
  <div class="page-header d-print-none">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}">
          <i class="fe fe-home ml-1"></i> داشبورد
        </a>
      </li>
      <li class="breadcrumb-item"><a href="{{ route('admin.reports.purchases-filter') }}">فیلتر گزارش خرید</a></li>
      <li class="breadcrumb-item active">خرید از {{ $supplier->name }}</li>
    </ol>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
      <x-core::print-button/>
    </div>
  </div>
  <div class="row justify-content-center d-none d-print-flex">
    <p class="fs-22">گزارش خرید ها</p>
  </div>
  <div class="card">
    <div class="card-body">
      <div class="table-responsive">
        <div class="dataTables_wrapper dt-bootstrap4 no-footer">
          <div class="row">

            <div class="col-12 d-flex align-items-center mb-5">
              <p class="col-3 text-center">
                <span>گزارش خرید از آقا / خانم</span>
                <strong class="font-weight-bold">{{ $supplier->name }}</strong>
              </p>
              <figure class="figure my-2 col-6 text-center">
                <img
                  src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAACXBIWXMAAAsTAAALEwEAmpwYAAADmklEQVR4nO2by2sUQRDGfzH4QF0DelASTxq9aUzwgUHFJySY4FEh6EFBQe8aIT5ABP8ETUQPgheJ3hSD0TzwoBcvKiuKaDwl0URNcsjFkYZvoBlmd2fGdXdnZj8oSE8/auvr7qqengpUUUUVBXAI6AOywCwwB3wAbgP7STA2AsOAU0BGgEYShj3AlAwcB3qALcAySbOeTajNT6CVBM38lAx7AGTytF0B9FskrCMBGLaMrwnQfoFFwhAJcHiOln2+mfeiztoO+4gx+mSE2d9hcVl9zRixRVZGNEXo2xwgYpRavgHtYYz4rY5hlr+LTAUY7CdjYYxwJFHxr/2LDT9CRoN0SDIBI0E6REU5lrg9oy895dD2ODEkwEi99I96ZrhsBJQKT6SvO0d94gloA/4A8yKhPm0EGFwVCX4+ITVRoA147KM/llFgTm+j5tLlIXAKWBJSf2R7nAogwE++Ajsj/P5Y+oClwEpgK3AaeKUxzTF9cxoI8KIWuKNx3+j+IVUEGCwGPmrs4+RGECcY2yjQpbE/A4sC6i9bFPgfqLXuK86mbQu4OGpd2a0mhQQYPJWOZz4OMRUErNUKMHruAQvTRgA6FM1YKyEVUcBGq0XAi7REAb8tcN8TElOxBQakY1ChkTQRcMwKg2sC6E8UAbXWQehcQP2JIqCrSEfh0bi+DH3S2CdC6E9EFKgF7qbpdTgDrAK2A2eA19aFyKaQ+stGgFNk+QLsiPD7Y0nArC5Fs8o8ORnHS9FSo936SlTUKFAD7FVO4Fvgl8T83atssnz9y/FhZKRYUaBRx81CS3bAyg4rNQHt1qex88X8NLYNmA6RJzila+xSE+Au+wsF7AkMRzIdIU/Q7VMOqS92foDjyRM8ohzAWYl5B+/0yRMsh9h7umj5AePWzN/Io/y6T55gOaJALkSOAj3WzDseJ1OvPTevug5PnmClExAoCjSpPJTHyXSr7nkF5wlGTpPLqOzeu3nDC3rmntErNU9wTLkDgTETgoAG1ZmDkRsR7HIskZURJuYjb58rCemi6sxhyaBF5ffEGL0y4pLKnZYTdJOQGmS86wQPq+0VlW8SYxyUERNa0ijU5dpj16wwOKlnsf8/oiEZ0m/dvnTI289IBq2ZN20eebZDrLEB+GGRYGY3F+qUyGTafgfWkxDstkiY1CHHOLnlihAtejZpGb+LhKFRy75QrB1M0sz74QBwS+HN9QHv5O1j7/CqqIJ04y+YS52m5iZ/awAAAABJRU5ErkJggg=="
                  alt="image"
                  style="max-height: 80px;"
                />
              </figure>
              <span class="col-3 text-center">{{ verta()->format('Y/m/d') }}</span>
            </div>

            <table class="table table-vcenter table-striped text-nowrap table-bordered border-bottom">
              <thead class="thead-light">
              <tr>
                <th class="text-center">ردیف</th>
                <th class="text-center">شناسه خرید</th>
                <th class="text-center">مبلغ خرید (ریال)</th>
                <th class="text-center">تخفیف کلی (ریال)</th>
                <th class="text-center">مبلغ خرید با تخفیف (ریال)</th>
                <th class="text-center">تاریخ خرید</th>
                <th class="text-center">تاریخ ثبت</th>
              </tr>
              </thead>
              <tbody>

              @php $totalAmountWithDiscount = 0; @endphp

              @forelse ($purchases as $purchase)
                <tr>
                  <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                  <td class="text-center">{{ $purchase->id }}</td>
                  <td class="text-center">{{ number_format($purchase->getTotalPurchaseAmount()) }}</td>
                  <td class="text-center">{{ number_format($purchase->discount) }}</td>
                  <td class="text-center">{{ number_format($purchase->getTotalAmountWithDiscount()) }}</td>
                  <td class="text-center"> @jalaliDate($purchase->purchased_at)</td>
                  <td class="text-center"> @jalaliDate($purchase->created_at)</td>
                </tr>

                @php $totalAmountWithDiscount += $purchase->getTotalAmountWithDiscount(); @endphp

              @empty
                <x-core::data-not-found-alert :colspan="7"/>
              @endforelse
              <tr>
                <td class="text-center font-weight-bold" colspan="4">جمع کل</td>
                <td class="text-center font-weight-bold" colspan="1">{{ number_format($totalAmountWithDiscount) }}</td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
