@extends('admin.layouts.master')

@section('content')
  <div class="col-12">
    <div class="page-header">
      <ol class="breadcrumb align-items-center">
        <li class="breadcrumb-item">
          <a href="{{ route('admin.dashboard') }}">
            <i class="fe fe-home ml-1"></i> داشبورد
          </a>
        </li>
        <li class="breadcrumb-item">
          <a href="{{ route('admin.stores.index') }}">لیست انبار</a>
        </li>
        <li class="breadcrumb-item active">لیست تراکنش ها</li>
      </ol>
    </div>

    <div class="row">
      <div class="col-xl-3 col-lg-6 col-md-12">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-7">
                <div class="mt-0 text-right">
                  <span class="fs-16 font-weight-semibold"> شناسه محصول : </span>
                  <h3 class="mb-0 mt-1 text-info fs-20"> {{ $store->product->id }} </h3>
                </div>
              </div>
              <div class="col-5">
                <div class="icon1 bg-info-transparent my-auto float-left">
                  <i class="fa fa-money"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-6 col-md-12">
        <div class="card">
          <a href="{{ route('admin.products.show', $store->product) }}">
            <div class="card-body">
              <div class="row">
                <div class="col-7">
                  <div class="mt-0 text-right">
                    <span class="fs-16 font-weight-semibold"> نام محصول : </span>
                    <h3 class="mb-0 mt-1 text-danger fs-20"> {{ $store->product->title }} </h3>
                  </div>
                </div>
                <div class="col-5">
                  <div class="icon1 bg-danger-transparent my-auto float-left">
                    <i class="fa fa-money"></i>
                  </div>
                </div>
              </div>
            </div>
          </a>
        </div>
      </div>
      <div class="col-xl-3 col-lg-6 col-md-12">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-8">
                <div class="mt-0 text-right">
                  <span class="fs-16 font-weight-semibold"> نام دسته بندی : </span>
                  <h3 class="mb-0 mt-1 text-success fs-20"> {{ $store->product->category->title }}  </h3>
                </div>
              </div>
              <div class="col-4">
                <div class="icon1 bg-success-transparent my-auto float-left">
                  <i class="fa fa-money"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-6 col-md-12">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-8">
                <div class="mt-0 text-right">
                  <span class="fs-16 font-weight-semibold"> موجودی (تعداد / متر) : </span>
                  <h3 class="mb-0 mt-1 text-warning fs-20"> {{ $store->balance }}  </h3>
                </div>
              </div>
              <div class="col-4">
                <div class="icon1 bg-warning-transparent my-auto float-left">
                  <i class="fa fa-money"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
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
      <div class="card">
        <div class="card-header border-0">
          <p class="card-title">لیست تراکنش ها</p>
          <div class="card-options">
            <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
            <a href="#" class="card-options-fullscreen" data-toggle="card-fullscreen"><i class="fe fe-maximize"></i></a>
            <a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
          </div>
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
                    <th class="text-center">تعداد / متر</th>
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
