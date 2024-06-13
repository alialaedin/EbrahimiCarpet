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
          <a href="{{ route('admin.customers.index') }}">لیست مشتریان</a>
        </li>
        <li class="breadcrumb-item">
          <a href="{{ route('admin.customers.show', $salePayment->customer) }}">نمایش مشتری</a>
        </li>
        <li class="breadcrumb-item">
          <a href="{{ route('admin.sale-payments.index', $salePayment->customer) }}">پرداختی ها</a>
        </li>
        <li class="breadcrumb-item active">
          <a>ویرایش پرداختی</a>
        </li>
      </ol>
    </div>

    <div class="row">
      <div class="col-md-6">
        <div class="card overflow-hidden">
          <div class="card-header border-0">
            <p class="card-title">اطلاعات مشتری</p>
            <x-core::card-options/>
          </div>
          <div class="card-body">
            <ul class="list-group">
              <li class="list-group-item"><strong class="ml-1">کد : </strong> {{ $salePayment->customer->id }} </li>
              <li class="list-group-item"><strong class="ml-1">نام و نام خانوادگی : </strong> {{ $salePayment->customer->name }} </li>
              <li class="list-group-item"><strong class="ml-1">شماره موبایل : </strong> {{ $salePayment->customer->mobile }} </li>
              <li class="list-group-item"><strong class="ml-1">تاریخ ثبت : </strong> {{ verta($salePayment->customer->created_at)->format('Y/m/d H:i') }} </li>
              <li class="list-group-item"><strong class="ml-1">آدرس : </strong> {{ $salePayment->customer->address }} </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card overflow-hidden">
          <div class="card-header border-0">
            <p class="card-title">اطلاعات خرید</p>
            <x-core::card-options/>
          </div>
          <div class="card-body">
            <ul class="list-group">
              <li class="list-group-item"><strong class="ml-1">تعداد خرید ها : </strong> {{ $salePayment->customer->countSales() }} </li>
              <li class="list-group-item"><strong class="ml-1">تعداد پرداختی ها : </strong> {{ $salePayment->customer->countPayments() }} </li>
              <li class="list-group-item"><strong class="ml-1">مبلغ کل خرید : </strong> {{ number_format($salePayment->customer->calcTotalSalesAmount()) }} تومان</li>
              <li class="list-group-item"><strong class="ml-1">جمع پرداخت شده ها : </strong> {{ number_format($salePayment->customer->calcTotalSalePaymentsAmount()) }} تومان</li>
              <li class="list-group-item"><strong class="ml-1">مبلغ باقی مانده : </strong> {{ number_format($salePayment->customer->getRemainingAmount()) }} تومان </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

		<div class="card">
			<div class="card-header border-0">
				<p class="card-title">ویرایش پرداختی</p>
			</div>
			<div class="card-body">
				<form action="{{ route('admin.sale-payments.update', $salePayment) }}" method="post" class="save" enctype="multipart/form-data">
          @csrf
          @method('PATCH')
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="type" class="control-label">نوع پرداخت: <span class="text-danger">&starf;</span></label>
                <select name="type" id="type" class="form-control">
                  <option value="" class="text-muted"> نوع پرداخت را انتخاب کنید </option>
                  <option value="cash" @selected(old('type', $salePayment->type) == 'cash')>وجه نقد</option>
                  <option value="cheque" @selected(old('type', $salePayment->type) == 'cheque')>چک</option>
                  <option value="installment" @selected(old('type', $salePayment->type) == 'installment')>قسط</option>
                </select>
                <x-core::show-validation-error name="type" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="amount" class="control-label">مبلغ پرداخت (تومان): <span class="text-danger">&starf;</span></label>
                <input type="text" id="amount" class="form-control comma" name="amount" placeholder="مبلغ پرداختی را به تومان وارد کنید" value="{{ old('amount', number_format($salePayment->amount)) }}">
                <x-core::show-validation-error name="amount" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="salePayment_date_show" class="control-label">تاریخ پرداخت:</label>
                <input class="form-control fc-datepicker" id="salePayment_date_show" type="text" autocomplete="off" placeholder="تاریخ پرداخت را در صورت نیاز وارد کنید"/>
                <input name="salePayment_date" id="salePayment_date_hidden" type="hidden" required value="{{	old('payment_date', $salePayment->payment_date) }}"/>
                <x-core::show-validation-error name="salePayment_date" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="due_date_show" class="control-label">تاریخ سررسید:</label>
                <input class="form-control fc-datepicker" id="due_date_show" type="text" autocomplete="off" placeholder="تاریخ سررسید را در صورت نیاز وارد کنید"/>
                <input name="due_date" id="due_date_hidden" type="hidden" required value="{{	old('due_date', $salePayment->due_date) }}"/>
                <x-core::show-validation-error name="due_date" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label"> انتخاب عکس </label>
                <input type="file" class="form-control" name="image" value="{{ old('image') }}">
                <x-core::show-validation-error name="image" />
              </div>
            </div>
            @if ($salePayment->image)
							<div class="col-md-6">
								<button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('delete-image-{{ $salePayment->id }}')">
									<i class="fa fa-trash-o"></i>
								</button>
								<br>
								<figure class="figure">
									<a target="_blank" href="{{ Storage::url($salePayment->image) }}">
										<img src="{{ Storage::url($salePayment->image) }}" class="img-thumbnail" alt="image" width="50" height="50" />
									</a>
								</figure>
							</div>
						@endif
            <div class="col-12">
              <div class="form-group">
                <label for="description" class="control-label">توضیحات :</label>
                <textarea name="description" id="description" class="form-control" rows="4"> {{ old('description', $salePayment->description) }} </textarea>
                <x-core::show-validation-error name="description" />
              </div>
            </div>
            <div class="col-md-6">
							<div class="form-group">
								<label class="control-label"> انتخاب وضعیت:<span class="text-danger">&starf;</span></label>
                <div class="custom-controls-stacked">
									<label class="custom-control custom-radio">
										<input type="radio" class="custom-control-input" name="status" value="1" @checked(old('status', $salePayment->status) == '1')>
										<span class="custom-control-label">فعال</span>
									</label>
									<label class="custom-control custom-radio">
										<input type="radio" class="custom-control-input" name="status" value="0" @checked(old('status', $salePayment->status) == '0')>
										<span class="custom-control-label">غیر فعال</span>
									</label>
								</div>
                <x-core::show-validation-error name="status" />
              </div>
						</div>
          </div>
          <x-core::update-button/>
        </form>
        @if ($salePayment->image)
          <form
            action="{{ route('admin.sale-payments.image.destroy', $salePayment) }}"
            id="delete-image-{{$salePayment->id}}"
            method="POST"
            style="display: none;">
            @csrf
            @method("DELETE")
          </form>
        @endif
			</div>
    </div>
  </div>
@endsection

@section('scripts')
  <script>

    $('#salePayment_date_show').MdPersianDateTimePicker({
      targetDateSelector: '#salePayment_date_hidden',
      targetTextSelector: '#salePayment_date_show',
      englishNumber: false,
      toDate:true,
      enableTimePicker: false,
      dateFormat: 'yyyy-MM-dd',
      textFormat: 'yyyy-MM-dd',
      groupId: 'rangeSelector1',
    });

    $('#due_date_show').MdPersianDateTimePicker({
      targetDateSelector: '#due_date_hidden',
      targetTextSelector: '#due_date_show',
      englishNumber: false,
      toDate:true,
      enableTimePicker: false,
      dateFormat: 'yyyy-MM-dd',
      textFormat: 'yyyy-MM-dd',
      groupId: 'rangeSelector1',
    });

  </script>
@endsection
