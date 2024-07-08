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
      <li class="breadcrumb-item">
        <a href="{{ route('admin.suppliers.show', $supplier) }}">نمایش تامین کننده</a>
      </li>
      <li class="breadcrumb-item active">
        <a>پرداختی ها</a>
      </li>
    </ol>
    @can('create payments')
      <a href="{{ route('admin.payments.create', $supplier) }}" class="btn btn-indigo">
        ثبت پرداختی جدید
        <i class="fa fa-plus mr-1"></i>
      </a>
    @endcan
  </div>
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-12">
          <p class="header fs-20 px-5">اطلاعات تامین کننده</p>
        </div>
        <div class="col-lg-6">
          <ul class="list-group">
            <li class="list-group-item"><strong>کد : </strong> {{ $supplier->id }} </li>
            <li class="list-group-item"><strong>نام و نام خانوادگی : </strong> {{ $supplier->name }} </li>
            <li class="list-group-item">
              <strong>وضعیت : </strong>
              @if ($supplier->status)
                <span class="text-success">فعال</span>
              @else
                <span class="text-danger">غیر فعال</span>
              @endif
            </li>
            <li class="list-group-item"><strong>شماره موبایل: </strong> {{ $supplier->mobile }} </li>
          </ul>
        </div>
        <div class="col-lg-6">
          <ul class="list-group">
            <li class="list-group-item"><strong>آدرس: </strong> {{ $supplier->address }} </li>
            <li class="list-group-item"><strong>تعداد خرید ها : </strong>{{ number_format($supplier->countPurchases()) }} </li>
            <li class="list-group-item"><strong>تعداد پرداختی ها : </strong>{{ number_format($supplier->countPayments()) }} </li>
            <li class="list-group-item"><strong>تاریخ ثبت : </strong> @jalaliDate($supplier->created_at) </li>
          </ul>
        </div>
      </div>

    </div>
  </div>
  @include('supplier::includes.purchase-statistics')
  <div class="card">
    <div class="card-header border-0">
      <p class="card-title">پرداختی های نقدی ({{ $cashPayments->count() }})</p>
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
                <th class="text-center">مبلغ پرداختی (ریال)</th>
                <th class="text-center">تاریخ پرداخت</th>
                <th class="text-center">عکس رسید</th>
                <th class="text-center">تاریخ ثبت</th>
                <th class="text-center">عملیات</th>
              </tr>
              </thead>
              <tbody>
              @forelse ($cashPayments as $payment)
                <tr>
                  <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                  <td class="text-center">{{ number_format($payment->amount) }}</td>
                  <td class="text-center"> @jalaliDate($payment->payment_date) </td>
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
                  <td class="text-center"> @jalaliDate($payment->created_at) </td>
                  <td class="text-center">
                    <button
                      class="btn btn-sm btn-icon btn-primary"
                      onclick="showPaymentDescriptionModal('{{$payment->description}}')"
                      data-toggle="tooltip"
                      data-original-title="توضیحات">
                      <i class="fa fa-book" ></i>
                    </button>
                    @can('edit payments')
                      <button
                        data-target="#editPaymentModal-{{$payment->id}}"
                        class="btn btn-sm btn-icon btn-warning text-white"
                        data-toggle="modal">
                        <i class="fa fa-pencil" ></i>
                      </button>
                    @endcan
                    @can('delete payments')
                      <x-core::delete-button route="admin.payments.destroy" :model="$payment"/>
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
      <p class="card-title">اقساط ({{ $installmentPayments->count() }})</p>
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
                <th class="text-center">مبلغ (ریال)</th>
                <th class="text-center">تاریخ پرداخت</th>
                <th class="text-center">عکس رسید</th>
                <th class="text-center">تاریخ سررسید</th>
                <th class="text-center">وضعیت</th>
                <th class="text-center">تاریخ ثبت</th>
                <th class="text-center">عملیات</th>
              </tr>
              </thead>
              <tbody>
              @forelse ($installmentPayments as $payment)
                <tr>
                  <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                  <td class="text-center">{{ number_format($payment->amount) }}</td>
                  <td class="text-center">{{ $payment->getPaymentDate() }}</td>
                  <td class="text-center m-0 p-0">
                    @if ($payment->image)
                      <figure class="figure my-2">
                        <a target="_blank" href="{{ Storage::url($payment->image) }}">
                          <img src="{{ Storage::url($payment->image) }}" class="img-thumbnail" alt="image" width="50" style="max-height: 32px;" />
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
                  <td class="text-center">
                    <button
                      class="btn btn-sm btn-icon btn-primary"
                      onclick="showPaymentDescriptionModal('{{$payment->description}}')"
                      data-toggle="tooltip"
                      data-original-title="توضیحات">
                      <i class="fa fa-book" ></i>
                    </button>
                    @can('edit payments')
                      <button
                        data-target="#editPaymentModal-{{$payment->id}}"
                        class="btn btn-sm btn-icon btn-warning text-white"
                        data-toggle="modal">
                        <i class="fa fa-pencil" ></i>
                      </button>
                    @endcan
                    @can('delete payments')
                      <x-core::delete-button route="admin.payments.destroy" :model="$payment"/>
                    @endcan
                  </td>
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
  <div class="card">
    <div class="card-header border-0">
      <p class="card-title">چک ها ({{ $chequePayments->count() }})</p>
      <x-core::card-options/>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <div class="dataTables_wrapper dt-bootstrap4 no-footer">
          <div class="row">
            <table class="table table-vcenter table-striped text-nowrap table-bordered border-bottom" >
              <thead class="thead-light">
              <tr>
                <th class="text-center">ردیف</th>
                <th class="text-center">سریال</th>
                <th class="text-center">صاحب چک</th>
                <th class="text-center">بانک</th>
                <th class="text-center">در وجه</th>
                <th class="text-center">مالک چک</th>
                <th class="text-center">مبلغ (ریال)</th>
                <th class="text-center">تاریخ سررسید</th>
                <th class="text-center">تاریخ پرداخت</th>
                <th class="text-center">عکس رسید</th>
                <th class="text-center">وضعیت</th>
                <th class="text-center">عملیات</th>
              </tr>
              </thead>
              <tbody>
              @forelse ($chequePayments as $payment)
                <tr>
                  <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                  <td class="text-center">{{ $payment->cheque_serial }}</td>
                  <td class="text-center">{{ $payment->cheque_holder }}</td>
                  <td class="text-center">{{ $payment->bank_name }}</td>
                  <td class="text-center">{{ $payment->pay_to }}</td>
                  <td class="text-center">{{ $payment->is_mine }}</td>
                  <td class="text-center">{{ number_format($payment->amount) }}</td>
                  <td class="text-center"> {{ verta($payment->due_date)->format('Y/m/d') }} </td>
                  <td class="text-center">{{ $payment->getPaymentDate() }}</td>
                  <td class="text-center m-0 p-0">
                    @if ($payment->image)
                      <figure class="figure my-2">
                        <a target="_blank" href="{{ Storage::url($payment->image) }}">
                          <img src="{{ Storage::url($payment->image) }}" class="img-thumbnail" alt="image" width="50" style="max-height: 32px;" />
                        </a>
                      </figure>
                    @else
                      <span> - </span>
                    @endif
                  </td>
                  <td class="text-center">
                    <x-core::badge
                      type="{{ $payment->status ? 'success' : 'danger' }}"
                      text="{{ $payment->status ? 'پاس شده' : 'پاس نشده' }}"
                    />
                  </td>
                  <td class="text-center">
                    <button
                      class="btn btn-sm btn-icon btn-primary"
                      onclick="showPaymentDescriptionModal('{{$payment->description}}')"
                      data-toggle="tooltip"
                      data-original-title="توضیحات">
                      <i class="fa fa-book" ></i>
                    </button>
                    @can('edit payments')
                      <button
                        data-target="#editPaymentModal-{{$payment->id}}"
                        class="btn btn-sm btn-icon btn-warning text-white"
                        data-toggle="modal">
                        <i class="fa fa-pencil" ></i>
                      </button>
                    @endcan
                    @can('delete payments')
                      <x-core::delete-button route="admin.payments.destroy" :model="$payment"/>
                    @endcan
                  </td>
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
  @include('payment::_show-description-modal')
  @foreach ($payments as $payment)
    <div class="modal fade" id="editPaymentModal-{{ $payment->id }}" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document" style="margin-top: 15vh;">
        <div class="modal-content modal-content-demo">
          <div class="modal-header">
            <p class="modal-title" style="font-size: 20px;">ویرایش پرداختی - کد {{ $payment->id }}</p>
            <button aria-label="Close" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
          </div>
          <div class="modal-body">
            <form action="{{ route('admin.payments.update', $payment) }}" method="post" class="save">
              @csrf
              @method('PATCH')
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <label for="type" class="control-label">نوع پرداخت: <span class="text-danger">&starf;</span></label>
                    <input class="form-control" id="type" type="text"
                           value="{{ config('payment.types.'.$payment->type) }}" readonly>
                  </div>
                </div>
                @if($payment->type === 'cash')
                  <div class="col-12">
                    <div class="form-group">
                      <label for="amount" class="control-label">مبلغ پرداخت (ریال): <span
                          class="text-danger">&starf;</span></label>
                      <input
                        type="text"
                        id="amount"
                        class="form-control comma"
                        name="amount"
                        placeholder="مبلغ پرداختی را به ریال وارد کنید"
                        value="{{ old('amount', number_format($payment->amount)) }}"
                      />
                      <x-core::show-validation-error name="amount"/>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-group">
                      <label for="cash_payment_date_show" class="control-label">تاریخ پرداخت:</label>
                      <input class="form-control fc-datepicker" id="cash_payment_date_show-{{ $payment->id }}" type="text" autocomplete="off"
                             placeholder="تاریخ پرداخت را در صورت نیاز وارد کنید"/>
                      <input name="payment_date" id="cash_payment_date_hidden-{{ $payment->id }}" type="hidden" required
                             value="{{	old('payment_date', $payment->payment_date) }}"/>
                      <x-core::show-validation-error name="payment_date"/>
                    </div>
                  </div>
                @elseif($payment->type === 'cheque')
                  <div class="col-12 col-lg-6">
                    <div class="form-group">
                      <label for="amount" class="control-label">مبلغ چک (ریال): <span
                          class="text-danger">&starf;</span></label>
                      <input
                        type="text"
                        id="amount"
                        class="form-control comma"
                        name="amount"
                        placeholder="مبلغ پرداختی را به ریال وارد کنید"
                        value="{{ old('amount', number_format($payment->amount)) }}"
                      />
                      <x-core::show-validation-error name="amount"/>
                    </div>
                  </div>
                  <div class="col-12 col-lg-6">
                    <div class="form-group">
                      <label for="cheque_serial" class="control-label">سریال چک: <span class="text-danger">&starf;</span></label>
                      <input
                        type="text"
                        id="cheque_serial"
                        class="form-control"
                        name="cheque_serial"
                        placeholder="سریال چک وارد کنید"
                        value="{{ old('cheque_serial', $payment->cheque_serial) }}"
                      />
                      <x-core::show-validation-error name="cheque_serial"/>
                    </div>
                  </div>
                  <div class="col-12 col-lg-6">
                    <div class="form-group">
                      <label for="cheque_holder" class="control-label">نام و نام خانوادگی صاحب چک: <span
                          class="text-danger">&starf;</span></label>
                      <input
                        type="text"
                        id="cheque_holder"
                        class="form-control"
                        name="cheque_holder"
                        placeholder="نام و نام خانوادگی صاحب وارد کنید"
                        value="{{ old('cheque_holder', $payment->cheque_holder) }}"
                      />
                      <x-core::show-validation-error name="cheque_holder"/>
                    </div>
                  </div>
                  <div class="col-12 col-lg-6">
                    <div class="form-group">
                      <label for="bank_name" class="control-label">نام بانک: <span
                          class="text-danger">&starf;</span></label>
                      <input

                        type="text"
                        id="bank_name"
                        class="form-control"
                        name="bank_name"
                        placeholder="نام بانک وارد کنید"
                        value="{{ old('bank_name', $payment->bank_name) }}"
                      />
                      <x-core::show-validation-error name="bank_name"/>
                    </div>
                  </div>
                  <div class="col-12 col-lg-6">
                    <div class="form-group">
                      <label for="pay_to" class="control-label">در وجه: <span class="text-danger">&starf;</span></label>
                      <input
                        type="text"
                        id="pay_to"
                        class="form-control"
                        name="pay_to"
                        placeholder="چک در وجه چه کسی است"
                        value="{{ old('pay_to', $payment->pay_to) }}"
                      />
                      <x-core::show-validation-error name="pay_to"/>
                    </div>
                  </div>
                  <div class="col-12 col-lg-6">
                    <div class="form-group">
                      <label for="cheque_payment_date_show" class="control-label">تاریخ پرداخت:</label>
                      <input class="form-control fc-datepicker" id="cheque_payment_date_show-{{ $payment->id }}" type="text"
                             autocomplete="off" placeholder="تاریخ پرداخت را در صورت نیاز وارد کنید"/>
                      <input name="payment_date" id="cheque_payment_date_hidden-{{ $payment->id }}" type="hidden" required
                             value="{{	old('payment_date', $payment->payment_date) }}"/>
                      <x-core::show-validation-error name="payment_date"/>
                    </div>
                  </div>
                  <div class="col-12 col-lg-6">
                    <div class="form-group">
                      <label for="cheque_due_date_show" class="control-label">تاریخ سررسید:</label>
                      <input class="form-control fc-datepicker" id="cheque_due_date_show-{{ $payment->id }}" type="text" autocomplete="off"
                             placeholder="تاریخ سررسید را در صورت نیاز وارد کنید"/>
                      <input name="due_date" id="cheque_due_date_hidden-{{ $payment->id }}" type="hidden" required
                             value="{{	old('due_date', $payment->due_date) }}"/>
                      <x-core::show-validation-error name="due_date"/>
                    </div>
                  </div>
                  <div class="col-12 col-lg-6">
                    <div class="form-group">
                      <label class="control-label"> چک برای خودم است:<span class="text-danger">&starf;</span></label>
                      <div class="custom-controls-stacked">
                        <label class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" name="is_mine"
                                 value="1" @checked(old('is_mine', $payment->is_mine) == '1')>
                          <span class="custom-control-label">بله</span>
                        </label>
                      </div>
                      <x-core::show-validation-error name="is_mine"/>
                    </div>
                  </div>
                @elseif($payment->type === 'installment')
                  <div class="col-12">
                    <div class="form-group">
                      <label for="amount" class="control-label">مبلغ قسط: <span class="text-danger">&starf;</span></label>
                      <input
                        type="text"
                        id="amount"
                        class="form-control comma"
                        name="amount"
                        placeholder="مبلغ هر قسط را وارد کنید"
                        value="{{ old('amount', number_format($payment->amount)) }}"
                      />
                      <x-core::show-validation-error name="amount"/>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-group">
                      <label for="installment_payment_date_show" class="control-label">تاریخ پرداخت:</label>
                      <input class="form-control fc-datepicker" id="installment_payment_date_show-{{ $payment->id }}" type="text"
                             autocomplete="off" placeholder="تاریخ پرداخت را در صورت نیاز وارد کنید"/>
                      <input name="payment_date" id="installment_payment_date_hidden-{{ $payment->id }}" type="hidden" required
                             value="{{	old('payment_date', $payment->payment_date) }}"/>
                      <x-core::show-validation-error name="payment_date"/>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-group">
                      <label for="installment_due_date_show" class="control-label">تاریخ سررسید:</label>
                      <input class="form-control fc-datepicker" id="installment_due_date_show-{{ $payment->id }}" type="text"
                             autocomplete="off" placeholder="تاریخ سررسید را در صورت نیاز وارد کنید"/>
                      <input name="due_date" id="installment_due_date_hidden-{{ $payment->id }}" type="hidden" required
                             value="{{	old('due_date', $payment->due_date) }}"/>
                      <x-core::show-validation-error name="due_date"/>
                    </div>
                  </div>
                @endif
                <div class="col-12">
                  <div class="form-group">
                    <label for="description" class="control-label"> توضیحات: </label>
                    <textarea id="description" class="form-control" rows="2" name="description">
                      {{ $payment->description }}
                    </textarea>
                    <x-core::show-validation-error name="description"/>
                  </div>
                </div>

              </div>

              <div class="modal-footer">
                <button class="btn btn-warning" type="submit">بروزرسانی</button>
                <button class="btn btn-danger" data-dismiss="modal">انصراف</button>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
  @endforeach
@endsection

@section('scripts')

  @foreach ($cashPayments as $payment)
    <x-core::date-input-script
      textInputId="cash_payment_date_show-{{ $payment->id }}"
      dateInputId="cash_payment_date_hidden-{{ $payment->id }}"
    />
  @endforeach
  @foreach ($chequePayments as $payment)
    <x-core::date-input-script
      textInputId="cheque_due_date_show-{{ $payment->id }}"
      dateInputId="cheque_due_date_hidden-{{ $payment->id }}"
    />
    <x-core::date-input-script
      textInputId="cheque_payment_date_show-{{ $payment->id }}"
      dateInputId="cheque_payment_date_hidden-{{ $payment->id }}"
    />
  @endforeach
  @foreach ($installmentPayments as $payment)
    <x-core::date-input-script
      textInputId="installment_payment_date_show-{{ $payment->id }}"
      dateInputId="installment_payment_date_hidden-{{ $payment->id }}"
    />
    <x-core::date-input-script
      textInputId="installment_due_date_show-{{ $payment->id }}"
      dateInputId="installment_due_date_hidden-{{ $payment->id }}"
    />
  @endforeach

  <script>
    function showPaymentDescriptionModal (description) {
      let modal = $('#showDescriptionModal');
      modal.find('#description').text(description ?? '-');
      modal.modal('show');
    }
  </script>
@endsection

