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
      @can('create purchases')
        <a href="{{ route('admin.purchases.create') }}" class="btn btn-sm btn-indigo mx-1 my-md-1">فاکتور خرید</a>
      @endcan
      @can('view payments')
        <a href="{{ route('admin.payments.show', $supplier) }}" class="btn btn-sm btn-flickr mx-1 my-md-1">مشاهده پرداختی ها</a>
      @endcan
      @can('create payments')
        <a href="{{ route('admin.payments.create', $supplier) }}" class="btn btn-sm btn-lime mx-1 my-md-1">پرداختی جدید</a>
      @endcan
      @can('edit suppliers')
        <a href="{{ route('admin.suppliers.edit', $supplier) }}" class="btn btn-sm btn-warning mx-1">ویرایش</a>
      @endcan
      @can('delete suppliers')
        <button
          onclick="confirmDelete('delete-{{ $supplier->id }}')"
          class="btn btn-sm btn-danger mx-1 my-1"
          @disabled(!$supplier->isDeletable())>
          حذف
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
              <strong>وضعیت: </strong>
              @if ($supplier->status)
                <span class="text-success">فعال</span>
              @else
                <span class="text-danger">غیر فعال</span>
              @endif
            </li>
            <li class="list-group-item"><strong>تاریخ ثبت: </strong> @jalaliDate($supplier->created_at)</li>
          </ul>
        </div>
        @if ( $supplier->description)
          <div class="col-12 mt-5">
            <ul class="list-group">
              <li class="list-group-item"><strong>توضیحات: </strong> {{ $supplier->description}} </li>
            </ul>
          </div>
        @endif
      </div>
    </div>
  </div>
  <div class="row">

    @include('admin::dashboard.includes.info-box', [
      'title' => 'مبلغ کل خرید (ریال)',
      'amount' => number_format($supplier->total_purchases_amount),
      'color' => 'primary',
    ])
    @include('admin::dashboard.includes.info-box', [
      'title' => 'جمع کل پرداختی ها (ریال)',
      'amount' => number_format($supplier->total_payments_amount),
      'color' => 'pink',
    ])
    @include('admin::dashboard.includes.info-box', [
      'title' => 'پرداختی های پرداخت شده (ریال)',
      'amount' => number_format($supplier->paid_payments_amount),
      'color' => 'success',
    ])
    @include('admin::dashboard.includes.info-box', [
      'title' => 'پرداختی های پرداخت نشده (ریال)',
      'amount' => number_format($supplier->unpaid_payments_amount),
      'color' => 'warning',
    ])
    @include('admin::dashboard.includes.info-box', [
      'title' => 'پرداختی های نقدی (ریال)',
      'amount' => number_format($supplier->cash_payments_amount),
      'color' => 'secondary',
    ])
    @include('admin::dashboard.includes.info-box', [
      'title' => 'پرداختی های چکی (ریال)',
      'amount' => number_format($supplier->cheque_purchases_amount),
      'color' => 'danger',
    ])
    @include('admin::dashboard.includes.info-box', [
      'title' => 'پرداختی های قسطی (ریال)',
      'amount' => number_format($supplier->installment_purchases_amount),
      'color' => 'purple',
    ])
    @include('admin::dashboard.includes.info-box', [
      'title' => 'مبلغ باقی مانده (ریال)',
      'amount' => number_format($supplier->remaining_amount),
      'color' => 'info',
    ])
  </div>
  <div class="card">
    <div class="card-header border-0 justify-content-between">
      <p class="card-title">حساب های بانکی ({{ $numberOfAccounts }})</p>
      @can('create accounts')
        <button class="btn btn-outline-primary btn-sm" data-target="#createAccountModal" data-toggle="modal">
          حساب جدید
          <i class="fa fa-plus font-weight-bolder mr-1"></i>
        </button>
      @endcan
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <div class="dataTables_wrapper dt-bootstrap4 no-footer">
          <div class="row">
            <table class="table table-vcenter table-striped text-nowrap table-bordered border-bottom">
              <thead class="thead-light">
              <tr>
                <th class="text-center">ردیف</th>
                <th class="text-center">شماره حساب</th>
                <th class="text-center">شماره کارت</th>
                <th class="text-center">نام بانک</th>
                <th class="text-center">تاریخ ثبت</th>
                <th class="text-center">عملیات</th>
              </tr>
              </thead>
              <tbody>
              @forelse ($accounts as $account)
                <tr>
                  <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                  <td class="text-center">{{ $account->account_number }}</td>
                  <td class="text-center">{{ $account->card_number }}</td>
                  <td class="text-center">{{ $account->bank_name }}</td>
                  <td class="text-center"> @jalaliDate($account->created_at) </td>
                  <td class="text-center">
                    @can('edit accounts')
                      <button
                        class="btn btn-sm btn-icon btn-warning text-white"
                        data-target="#editAccountModal-{{ $account->id }}"
                        data-toggle="modal"
                        data-original-title="ویرایش">
                        <i class="fa fa-pencil"></i>
                      </button>
                    @endcan
                    @can('delete accounts')
                      <x-core::delete-button route="admin.accounts.destroy" :model="$account"/>
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
    <div class="card-header border-0 justify-content-between">
      <p class="card-title">خرید ها ({{ $numberOfPurchases }})</p>
      @can('create purchases')
        <button onclick="$('#CreateNewPurchaseForm').submit()" class="btn btn-outline-primary btn-sm">
          فاکتور خرید جدید
          <i class="fa fa-plus"></i>
        </button>
        <form id="CreateNewPurchaseForm" action="{{ route('admin.purchases.create') }}" method="GET" class="d-none">
          <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
        </form>
      @endcan
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
                  <td class="text-center"> @jalaliDateFormat($purchase->purchased_at)</td>
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
    <div class="card-header border-0 justify-content-between">
      <p class="card-title">پرداختی ها ({{ $numberOfPayments }})</p>
      <div>
        @can('view payments')
          <a href="{{ route('admin.payments.show', $supplier) }}" class="btn btn-sm btn-outline-info ml-1">
            همه پرداختی ها
            <i class="fa fa-eye"></i>
          </a>
        @endcan
        @can('create payments')
          <a href="{{ route('admin.payments.create', $supplier) }}" class="btn btn-sm btn-outline-primary mr-1">
            پرداختی جدید
            <i class="fa fa-plus"></i>
          </a>
        @endcan
      </div>
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
                  <td class="text-center"> @jalaliDateFormat($payment->due_date)</td>
                  <td class="text-center">
                    <x-core::light-badge
                      type="{{ $payment->status ? 'success' : 'danger' }}"
                      text="{{ $payment->status ? 'پرداخت شده' : 'پرداخت نشده' }}"
                    />
                  </td>
                  <td class="text-center"> @jalaliDate($payment->created_at)</td>
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

  {{--------------------- Create Account Modal Section -----------------------------}}
  <div class="modal fade" id="createAccountModal" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="margin-top: 20vh;">
      <div class="modal-content modal-content-demo">
        <div class="modal-header">
          <p class="modal-title" style="font-size: 20px;">ثبت حساب جدید برای {{ $supplier->name }}</p><button aria-label="Close" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('admin.accounts.store') }}" method="post" class="save">
            @csrf
            <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="account_number" class="control-label">شماره حساب :<span class="text-danger">&starf;</span></label>
                  <input
                    type="text"
                    id="account_number"
                    class="form-control"
                    name="account_number"
                    placeholder="شماره حساب را وارد کنید"
                    value="{{ old('account_number') }}"
                  />
                  <x-core::show-validation-error name="account_number" />
                </div>
              </div>
              <div class="col-12">
                <div class="form-group">
                  <label for="card_number" class="control-label">شماره کارت :<span class="text-danger">&starf;</span></label>
                  <input
                    type="text"
                    id="card_number"
                    class="form-control"
                    name="card_number"
                    placeholder="شماره کارت را وارد کنید"
                    value="{{ old('card_number') }}"
                  />
                  <x-core::show-validation-error name="card_number" />
                </div>
              </div>
              <div class="col-12">
                <div class="form-group">
                  <label for="bank_name" class="control-label">نام بانک :<span class="text-danger">&starf;</span></label>
                  <input
                    type="text"
                    id="bank_name"
                    class="form-control"
                    name="bank_name"
                    placeholder="نام بانک را وارد کنید"
                    value="{{ old('bank_name') }}"
                  />
                  <x-core::show-validation-error name="bank_name" />
                </div>
              </div>
            </div>

            <div class="modal-footer">
              <button class="btn btn-success" type="submit">ثبت و ذخیره</button>
              <button class="btn btn-danger" data-dismiss="modal">انصراف</button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
  {{--------------------- End Of Create Modal Section -----------------------------}}

  {{--------------------- Edit Account Modal Section -----------------------------}}
  @include('supplier::account.edit-modal')
  {{--------------------- End Of Edit Modal Section -----------------------------}}
@endsection
