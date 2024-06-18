@extends('admin.layouts.master')

@section('content')
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
  <div class="card">
    <div class="card-header border-0">
      <p class="card-title">مشخصات محصول</p>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-xl-3 col-md-6 col-12">
            <strong>شناسه محصول :</strong> {{ $store->product->id }}
        </div>
        <div class="col-xl-3 col-md-6 col-12">
          <strong>نام محصول :</strong>
          <a href="{{ route('admin.products.show', $store->product) }}" class="fs-14 mr-1"> {{ $store->product->title }} </a>
        </div>
        <div class="col-xl-3 col-md-6 col-12">
          <strong>نام دسته بندی :</strong> {{ $store->product->category->title }}
        </div>
        <div class="col-xl-3 col-md-6 col-12">
          <strong>موجودی (تعداد / متر) :</strong> {{ $store->balance }}
        </div>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header border-0">
      <p class="card-title">لیست تراکنش ها</p>
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
                <th class="text-center">نوع فروش</th>
                <th class="text-center">شناسه فروش</th>
                <th class="text-center">نوع تراکنش</th>
                <th class="text-center">تعداد / متر</th>
                <th class="text-center">تاریخ ثبت</th>
              </tr>
              </thead>
              <tbody>
              @forelse ($transactions as $transaction)
                <tr>
                  <td class="text-center">{{ $loop->iteration }}</td>
                  <td class="text-center">{{ $transaction->getTransactionableType() }}</td>
                  <td class="text-center">
                    <a href="{{ route($transaction->getRoute(), $transaction->transactionable_id) }}">
                      {{ $transaction->transactionable_id }}
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
                  <td class="text-center"> @jalaliDate($transaction->created_at) </td>
                </tr>
              @empty
                <x-core::data-not-found-alert :colspan="6"/>
              @endforelse
              </tbody>
            </table>
            {{ $transactions->onEachSide(0)->links("vendor.pagination.bootstrap-4") }}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
