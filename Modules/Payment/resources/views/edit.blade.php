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
          <a href="{{ route('admin.suppliers.index') }}">لیست تامین کنندگان</a>
        </li>
        <li class="breadcrumb-item">
          <a href="{{ route('admin.suppliers.show', $payment->supplier) }}">نمایش تامین کننده</a>
        </li>
        <li class="breadcrumb-item">
          <a href="{{ route('admin.payments.index', $payment->supplier) }}">پرداختی ها</a>
        </li>
        <li class="breadcrumb-item active">
          <a>ویرایش پرداختی</a>
        </li>
      </ol>
    </div>
		<div class="card">
			<div class="card-header border-0 justify-content-between">
				<p class="card-title">ویرایش پرداختی</p>
			</div>
			<div class="card-body">
				<form action="{{ route('admin.payments.update', $payment) }}" method="post" class="save" enctype="multipart/form-data">
          @csrf
          @method('PATCH')
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="type" class="control-label">نوع پرداخت: <span class="text-danger">&starf;</span></label>
                <select name="type" id="type" class="form-control">
                  <option value="" class="text-muted">-- نوع پرداخت را انتخاب کنید --</option>
                  @foreach(config('core.payment_types') as $name => $label)
                    <option value="{{ $name }}" @selected(old('type', $payment->type) == $name)>{{ $label }}</option>
                  @endforeach
                </select>
                <x-core::show-validation-error name="type" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="amount" class="control-label">مبلغ پرداخت (ریال): <span class="text-danger">&starf;</span></label>
                <input
                  type="text"
                  id="amount"
                  class="form-control comma"
                  name="amount"
                  placeholder="مبلغ پرداختی را به ریال وارد کنید"
                  value="{{ old('amount', number_format($payment->amount)) }}"
                />
                <x-core::show-validation-error name="amount" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="payment_date_show" class="control-label">تاریخ پرداخت:</label>
                <input class="form-control fc-datepicker" id="payment_date_show" type="text" autocomplete="off" placeholder="تاریخ پرداخت را در صورت نیاز وارد کنید"/>
                <input name="payment_date" id="payment_date_hidden" type="hidden" required value="{{	old('payment_date', $payment->payment_date) }}"/>
                <x-core::show-validation-error name="payment_date" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="due_date_show" class="control-label">تاریخ سررسید:</label>
                <input class="form-control fc-datepicker" id="due_date_show" type="text" autocomplete="off" placeholder="تاریخ سررسید را در صورت نیاز وارد کنید"/>
                <input name="due_date" id="due_date_hidden" type="hidden" required value="{{	old('due_date', $payment->due_date) }}"/>
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
            @if ($payment->image)
							<div class="col-md-6">
								<button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('delete-image-{{ $payment->id }}')">
									<i class="fa fa-trash-o"></i>
								</button>
								<br>
								<figure class="figure">
									<a target="_blank" href="{{ Storage::url($payment->image) }}">
										<img src="{{ Storage::url($payment->image) }}" class="img-thumbnail" alt="image" width="50" height="50" />
									</a>
								</figure>
							</div>
						@endif
            <div class="col-12">
              <div class="form-group">
                <label for="description" class="control-label">توضیحات :</label>
                <textarea
                  name="description"
                  id="description"
                  class="form-control"
                  rows="4">
                  {{ old('description', $payment->description) }}
                </textarea>
                <x-core::show-validation-error name="description" />
              </div>
            </div>
            <div class="col-md-6">
							<div class="form-group">
								<label class="control-label"> انتخاب وضعیت:<span class="text-danger">&starf;</span></label>
                <div class="custom-controls-stacked">
									<label class="custom-control custom-radio">
										<input
                      type="radio"
                      class="custom-control-input"
                      name="status"
                      value="1"
                      @checked(old('status', $payment->status) == '1')
                    />
										<span class="custom-control-label">فعال</span>
									</label>
									<label class="custom-control custom-radio">
										<input
                      type="radio"
                      class="custom-control-input"
                      name="status"
                      value="0"
                      @checked(old('status', $payment->status) == '0')
                    />
										<span class="custom-control-label">غیر فعال</span>
									</label>
								</div>
                <x-core::show-validation-error name="status" />
              </div>
						</div>
          </div>
          <x-core::update-button/>
        </form>
        @if ($payment->image)
          <form
            action="{{ route('admin.payments.image.destroy', $payment) }}"
            id="delete-image-{{$payment->id}}"
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
  <x-core::date-input-script textInputId="payment_date_show" dateInputId="payment_date_hidden"/>
  <x-core::date-input-script textInputId="due_date_show" dateInputId="due_date_hidden"/>
@endsection
