@extends('admin.layouts.master')
@section('content')

<div class="page-header">

  <x-core::breadcrumb
    :items="[
      ['title' => 'لیست تامین کنندگان', 'route_link' => 'admin.suppliers.index'],
      ['title' => 'نمایش تامین کننده']
    ]"
  />

  <div class="d-flex align-items-center flex-wrap text-nowrap" style="gap: 5px;">
    @can('create purchases')
      <button onclick="$('#NewPurchaseForm').submit()" class="btn btn-sm btn-indigo my-md-1">فاکتور خرید جدید</button>
      <form action="{{ route('admin.purchases.create', $supplier) }}" id="NewPurchaseForm" style="display: none">
        <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
      </form>
    @endcan
    @can('view payments')
      <a href="{{ route('admin.payments.show', $supplier) }}" class="btn btn-sm btn-flickr my-md-1">مشاهده پرداختی ها</a>
    @endcan
    @can('create payments')
      <a href="{{ route('admin.payments.create', $supplier) }}" class="btn btn-sm btn-lime my-md-1">پرداختی جدید</a>
    @endcan
    @can('edit suppliers')
      <a href="{{ route('admin.suppliers.edit', $supplier) }}" class="btn btn-sm btn-warning my-md-1">ویرایش</a>
    @endcan
    @can('delete suppliers')
      <button
        onclick="confirmDelete('delete-{{ $supplier->id }}')"
        class="btn btn-sm btn-danger my-md-1"
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

<x-core::card>
  <x-slot name="cardTitle">اطلاعات تامین کننده</x-slot>
  <x-slot name="cardOptions"><x-core::card-options/></x-slot>
  <x-slot name="cardBody">
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
  </x-slot>
</x-core::card>

  <x-supplier::purchase-statistics :supplier="$supplier"/>

  <x-core::card>
    <x-slot name="cardTitle">حساب های بانکی ({{ $numberOfAccounts }})</x-slot>
    <x-slot name="cardOptions">
      <div class="card-options">
        @can('create accounts')
          <button class="btn btn-outline-primary btn-sm" data-target="#createAccountModal" data-toggle="modal">
            حساب جدید
            <i class="fa fa-plus font-weight-bolder mr-1"></i>
          </button>
        @endcan
      </div>
    </x-slot>
    <x-slot name="cardBody">
      <x-core::table>
        <x-slot name="tableTh">
          <tr>
            <th>ردیف</th>
            <th>شماره حساب</th>
            <th>شماره کارت</th>
            <th>نام بانک</th>
            <th>تاریخ ثبت</th>
            <th>عملیات</th>
          </tr>
        </x-slot>
        <x-slot name="tableTd">
          @forelse ($accounts as $account)
            <tr>
              <td class="font-weight-bold">{{ $loop->iteration }}</td>
              <td>{{ $account->account_number }}</td>
              <td>{{ $account->card_number }}</td>
              <td>{{ $account->bank_name }}</td>
              <td> @jalaliDate($account->created_at) </td>
              <td>
                @can('edit accounts')
                  <x-core::edit-button target="editAccountModal-{{ $account->id }}"/>
                @endcan
                @can('delete accounts')
                  <x-core::delete-button route="admin.accounts.destroy" :model="$account"/>
                @endcan
              </td>
            </tr>
          @empty
            <x-core::data-not-found-alert :colspan="6"/>
          @endforelse
        </x-slot>
      </x-core::table>
    </x-slot>
  </x-core::card>

  <x-core::card>
    <x-slot name="cardTitle">خرید ها ({{ $numberOfPurchases }})</x-slot>
    <x-slot name="cardOptions">
      <div class="card-options">
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
    </x-slot>
    <x-slot name="cardBody">
      <x-core::table>
        <x-slot name="tableTh">
          <tr>
            <th>ردیف</th>
            <th>مبلغ خرید (ریال)</th>
            <th>تخفیف کلی (ریال)</th>
            <th>مبلغ خرید با تخفیف (ریال)</th>
            <th>تاریخ خرید</th>
            <th>عملیات</th>
          </tr>
        </x-slot>
        <x-slot name="tableTd">
          @forelse ($purchases as $purchase)
            <tr>
              <td class="font-weight-bold">{{ $loop->iteration }}</td>
              <td>{{ number_format($purchase->total_items_amount) }}</td>
              <td>{{ number_format($purchase->discount) }}</td>
              <td>{{ number_format($purchase->total_amount) }}</td>
              <td> @jalaliDateFormat($purchase->purchased_at)</td>
              <td>
                @can('view purchases')
                  <a
                    href="{{route('admin.purchases.show', $purchase)}}"
                    target="_blank"
                    class="btn btn-sm btn-cyan">
                    جزئیات خرید
                  </a>
                @endcan
              </td>
            </tr>
          @empty
            <x-core::data-not-found-alert :colspan="6"/>
          @endforelse
        </x-slot>
      </x-core::table>
    </x-slot>
  </x-core::card>

  <x-core::card>
    <x-slot name="cardTitle">آخرین پرداختی های ثبت شده</x-slot>
    <x-slot name="cardOptions">
      <div class="card-options">
        @can('view payments')
          <a href="{{ route('admin.payments.show', $supplier) }}" target="_blank" class="btn btn-sm btn-outline-info ml-1">
            همه پرداختی ها
            <i class="fa fa-eye"></i>
          </a>
        @endcan
        @can('create payments')
          <a href="{{ route('admin.payments.create', $supplier) }}" target="_blank" class="btn btn-sm btn-outline-primary mr-1">
            پرداختی جدید
            <i class="fa fa-plus"></i>
          </a>
        @endcan
      </div>
    </x-slot>
    <x-slot name="cardBody">
      <x-core::table>
        <x-slot name="tableTh">
          <tr>
            <th>ردیف</th>
            <th>نوع پراخت</th>
            <th>مبلغ (ریال)</th>
            <th>تاریخ پرداخت</th>
            <th>عکس رسید</th>
            <th>تاریخ سررسید</th>
            <th>وضعیت</th>
            <th>تاریخ ثبت</th>
          </tr>
        </x-slot>
        <x-slot name="tableTd">
          @forelse ($payments as $payment)
            <tr>
              <td class="font-weight-bold">{{ $loop->iteration }}</td>
              <td>{{ $payment->getType() }}</td>
              <td>{{ number_format($payment->amount) }}</td>
              <td>{{ $payment->getPaymentDate() }}</td>
              <td class="m-0 p-0">
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
              <td> @jalaliDateFormat($payment->due_date)</td>
              <td>
                <x-core::light-badge
                  type="{{ $payment->status ? 'success' : 'danger' }}"
                  text="{{ $payment->status ? 'پرداخت شده' : 'پرداخت نشده' }}"
                />
              </td>
              <td> @jalaliDate($payment->created_at)</td>
            </tr>
          @empty
            <x-core::data-not-found-alert :colspan="8"/>
          @endforelse
        </x-slot>
      </x-core::table>
    </x-slot>
  </x-core::card>

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

  @include('supplier::account.edit-modal')

@endsection
