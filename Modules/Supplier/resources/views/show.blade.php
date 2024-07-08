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
        <a href="{{ route('admin.suppliers.index') }}">لیست تامین کنندگان</a>
      </li>
      <li class="breadcrumb-item active">
        <a>نمایش تامین کننده</a>
      </li>
    </ol>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
      @can('edit suppliers')
        <a href="{{ route('admin.suppliers.edit', $supplier) }}" class="btn btn-warning mx-1">
          ویرایش تامین کننده
          <i class="fa fa-pencil"></i>
        </a>
      @endcan
      @can('delete suppliers')
        <button
          onclick="confirmDelete('delete-{{ $supplier->id }}')"
          class="btn btn-danger mx-1"
          @disabled(!$supplier->isDeletable())>
          حذف تامین کننده<i class="fa fa-trash-o mr-2"></i>
        </button>
        <form
          action="{{ route('admin.suppliers.destroy', $supplier) }}"
          method="POST"
          id="delete-{{ $supplier->id }}"
          style="display: none">
          @csrf
          @method('DELETE')
        </form>
      @endcan
      @can('create purchases')
        <a href="{{ route('admin.purchases.create') }}" class="btn btn-indigo mx-1">
          ثبت خرید جدید
          <i class="fa fa-plus"></i>
        </a>
      @endcan
      @can('view payments')
        <a href="{{ route('admin.payments.index', $supplier) }}" class="btn btn-flickr mx-1">
          مشاهده همه پرداختی ها
          <i class="fa fa-eye"></i>
        </a>
      @endcan
      @can('create payments')
        <a href="{{ route('admin.payments.create', $supplier) }}" class="btn btn-lime mx-1">
          ثبت پرداختی جدید
          <i class="fa fa-plus"></i>
        </a>
      @endcan
    </div>
  </div>

  <div class="card">
    <div class="card-header border-0">
      <p class="card-title">اطلاعات تامین کننده</p>
      <x-core::card-options/>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-lg-6">
          <ul class="list-group">
            <li class="list-group-item"><strong>کد: </strong> {{ $supplier->id }} </li>
            <li class="list-group-item"><strong>نام و نام خانوادگی: </strong> {{ $supplier->name }} </li>
            <li class="list-group-item"><strong>شماره موبایل: </strong> {{ $supplier->mobile }} </li>
            <li class="list-group-item"><strong>تلفن ثابت: </strong> {{ $supplier->telephone }} </li>
            <li class="list-group-item"><strong>کد ملی: </strong> {{ $supplier->national_code }} </li>
            <li class="list-group-item"><strong>کد پستی: </strong> {{ $supplier->postal_code }} </li>
          </ul>
        </div>
        <div class="col-lg-6">
          <ul class="list-group">
            <li class="list-group-item"><strong>تعداد خرید ها: </strong> {{ number_format($numberOfPurchases) }} </li>
            <li class="list-group-item"><strong>تعداد پرداختی ها: </strong> {{ number_format($numberOfPayments) }} </li>
            <li class="list-group-item"><strong>آدرس: </strong> {{ $supplier->address }} </li>
            <li class="list-group-item"><strong>نوع: </strong> {{ config('supplier.types.'.$supplier->type) }} </li>
            <li class="list-group-item">
              <strong>وضعیت:  </strong>
              @if ($supplier->status)
                <span class="text-success">فعال</span>
              @else
                <span class="text-danger">غیر فعال</span>
              @endif
            </li>
            <li class="list-group-item"><strong>تاریخ ثبت: </strong> @jalaliDate($supplier->created_at) </li>
          </ul>
        </div>
        <div class="col-12 mt-5">
          <ul class="list-group">
            <li class="list-group-item"><strong>توضیحات: </strong> {{ $supplier->description}} </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  @include('supplier::includes.purchase-statistics')
  <div class="card">
    <div class="card-header border-0">
      <p class="card-title">خرید ها ({{ $numberOfPurchases }})</p>
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
                <th class="text-center">مبلغ خرید (ریال)</th>
                <th class="text-center">تخفیف کلی (ریال)</th>
                <th class="text-center">مبلغ خرید با تخفیف (ریال)</th>
                <th class="text-center">تاریخ خرید</th>
                <th class="text-center">عملیات</th>
              </tr>
              </thead>
              <tbody>
              @forelse ($purchases as $purchase)
                <tr>
                  <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                  <td class="text-center">{{ number_format($purchase->getTotalPurchaseAmount()) }}</td>
                  <td class="text-center">{{ number_format($purchase->discount) }}</td>
                  <td class="text-center">{{ number_format($purchase->getTotalAmountWithDiscount()) }}</td>
                  <td class="text-center"> @jalaliDate($purchase->purchased_at) </td>
                  <td class="text-center">
                    @can('view purchases')
                      <a
                        href="{{route('admin.purchases.show', $purchase)}}"
                        class="btn btn-sm btn-cyan">
                        جزئیات خرید
                      </a>
                    @endcan
                  </td>
                </tr>
              @empty
                <x-core::data-not-found-alert :colspan="6"/>
              @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header border-0">
      <p class="card-title">پرداختی ها ({{ $numberOfPayments }})</p>
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
                <th class="text-center">نوع پراخت</th>
                <th class="text-center">مبلغ (ریال)</th>
                <th class="text-center">تاریخ پرداخت</th>
                <th class="text-center">عکس رسید</th>
                <th class="text-center">تاریخ سررسید</th>
                <th class="text-center">وضعیت</th>
                <th class="text-center">تاریخ ثبت</th>
              </tr>
              </thead>
              <tbody>
              @forelse ($payments as $payment)
                <tr>
                  <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                  <td class="text-center">{{ $payment->getType() }}</td>
                  <td class="text-center">{{ number_format($payment->amount) }}</td>
                  <td class="text-center">{{ $payment->getPaymentDate() }}</td>
                  <td class="text-center m-0 p-0">
                    @if ($payment->image)
                      <figure class="figure my-2">
                        <a target="_blank" href="{{ Storage::url($payment->image) }}">
                          <img
                            src="{{ Storage::url($payment->image) }}"
                            class="img-thumbnail"
                            alt="image"
                            width="50"
                            style="max-height: 32px;"
                          />
                        </a>
                      </figure>
                    @else
                      <span> - </span>
                    @endif
                  </td>
                  <td class="text-center"> @jalaliDate($payment->due_date) </td>
                  <td class="text-center">
                    <x-core::badge
                      type="{{ $payment->status ? 'success' : 'danger' }}"
                      text="{{ $payment->status ? 'پرداخت شده' : 'پرداخت نشده' }}"
                    />
                  </td>
                  <td class="text-center"> @jalaliDate($payment->created_at) </td>
                </tr>
              @empty
                <x-core::data-not-found-alert :colspan="8"/>
              @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
