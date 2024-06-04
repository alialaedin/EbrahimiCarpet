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
            <a href="{{ route('admin.stores.index') }}">لیست انبار</a>
          </li>
          <li class="breadcrumb-item">لیست تراکنش ها</li>
        </ol>

        <x-core::return-to-previous-page-button />

      </div>

      <div class="row">
        <div class="card">

          <div class="card-header border-0">
            <p class="card-title">مشخصات محصول</p>
          </div>

          <div class="card-body">
            <div class="row">

              <div class="col-xl-3 col-md-6 col-12">
                <div class="d-flex align-items-center my-1">
                  <span class="fs-16 font-weight-bold ml-1">شناسه محصول :</span>
                  <span class="fs-14 mr-1"> {{ $store->product->id }} </span>
                </div>
              </div>

              <div class="col-xl-3 col-md-6 col-12">
                <div class="d-flex align-items-center my-1">
                  <span class="fs-16 font-weight-bold ml-1">نام محصول :</span>
                  <a href="{{ route('admin.products.show', $store->product) }}" class="fs-14 mr-1"> {{ $store->product->title }} </a>
                </div>
              </div>

              <div class="col-xl-3 col-md-6 col-12">
                <div class="d-flex align-items-center my-1">
                  <span class="fs-16 font-weight-bold ml-1">نام دسته بندی :</span>
                  <span class="fs-14 mr-1"> {{ $store->product->category->title }} </span>
                </div>
              </div>

              <div class="col-xl-3 col-md-6 col-12">
                <div class="d-flex align-items-center my-1">
                  <span class="fs-16 font-weight-bold ml-1">موجودی (تعداد) :</span>
                  <span class="fs-14 mr-1"> {{ $store->balance }} </span>
                </div>
              </div>

            </div>
          </div>

        </div>
      </div>

      <div class="card">

        <div class="card-header border-0">
          <p class="card-title">لیست تراکنش ها</p>
        </div>

        <div class="card-body">
          <div class="table-responsive">
            <div id="hr-table-wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
              <div class="row">
                <table class="table table-vcenter text-nowrap table-bordered border-bottom" id="hr-table">
                  <thead class="thead-light">
                  <tr>
                    <th class="text-center">ردیف</th>
                    <th class="text-center">شناسه خرید</th>
                    <th class="text-center">تامین کننده</th>
                    <th class="text-center">نوع تراکنش</th>
                    <th class="text-center">تعداد</th>
                    <th class="text-center">تاریخ ثبت</th>
                  </tr>
                  </thead>
                  <tbody>
                  @forelse ($transactions as $transaction)
                    <tr>
                      <td class="text-center">{{ $loop->iteration }}</td>
                      <td class="text-center">
                        <a href="{{ route('admin.purchases.show', $transaction->purchase) }}">
                          {{ $transaction->purchase->id }}
                        </a>
                      </td>
                      <td class="text-center">
                        <a href="{{ route('admin.suppliers.show', $transaction->purchase->supplier) }}">
                          {{ $transaction->purchase->supplier->name }}
                        </a>
                      </td>
                      <td class="text-center">
                        @if($transaction->type == 'increment')
                          <span class="text-success bg-success-transparent px-4 py-1" style="border-radius: 10rem">افزایش</span>
                        @else
                          <span class="text-danger bg-danger-transparent px-4 py-1" style="border-radius: 10rem">کاهش</span>
                        @endif
                      </td>
                      <td class="text-center">{{ $transaction->quantity }}</td>
                      <td class="text-center">{{ verta($transaction->created_at)->formatDate() }}</td>
                    </tr>
                  @empty
                    <x-core::data-not-found-alert :colspan="6"/>
                  @endforelse
                  </tbody>
                </table>
                {{ $transactions->onEachSide(1)->links("vendor.pagination.bootstrap-4") }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
