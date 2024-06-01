@extends('admin.layouts.master')
@section('content')
  <div class="col-12">
    <div class="col-xl-12 col-md-12 col-lg-12">
      <div class="page-header">
        <x-core::breadcrumb :items="$breadcrumbItems" />
    	</div>
			<div class="card">
				<div class="card-header">
					<p class="card-title">ویرایش خرید</p>
				</div>
				<div class="card-body">
					<form action="{{ route('admin.purchases.update', $purchase) }}" method="post" class="save">

						@csrf
            @method('PATCH')
						<div class="row">

							<div class="col-lg-4 col-md-6">
								<div class="form-group">
									<label for="supplier_id" class="control-label">انتخاب تامین کننده :<span class="text-danger">&starf;</span></label>
									<select name="supplier_id" id="supplier_id" class="form-control">
										<option value="" class="text-muted">-- تامین کننده را انخاب کنید --</option>
										@foreach ($suppliers as $supplier)
											<option
												value="{{ $supplier->id }}"
												@selected(old("supplier_id", $purchase->supplier_id) == $supplier->id)>
												{{ $supplier->name .' - '. $supplier->mobile }}
											</option>
										@endforeach
									</select>
                  <x-core::show-validation-error name="supplier_id" />
								</div>
							</div>

							<div class="col-lg-4 col-md-6">
								<div class="form-group">
									<label for="purchased_date_show" class="control-label">تاریخ خرید :<span class="text-danger">&starf;</span></label>
									<input class="form-control fc-datepicker" id="purchased_date_show" type="text" autocomplete="off" placeholder="تاریخ خرید را انتخاب کنید"/>
									<input name="purchased_at" id="purchased_date" type="hidden" value="{{ old("purchased_at", $purchase->purchased_at) }}"/>
                  <x-core::show-validation-error name="purchased_at" />
                </div>
							</div>

							<div class="col-lg-4 col-md-6">
								<div class="form-group">
									<label for="discount" class="control-label"> تخفیف کلی (تومان): </label>
									<input type="text" id="discount" class="form-control comma" name="discount" placeholder="تخفیف را به تومان وارد کنید" value="{{ old('discount', number_format($purchase->discount)) }}">
                  <x-core::show-validation-error name="discount" />
                </div>
							</div>

						</div>

						<div class="row">
							<div class="col">
								<div class="text-center">
									<button class="btn btn-warning" type="submit">بروزرسانی</button>
								</div>
							</div>
						</div>

					</form>
				</div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
	<script>
		$('#purchased_date_show').MdPersianDateTimePicker({
      targetDateSelector: '#purchased_date',
      targetTextSelector: '#purchased_date_show',
      englishNumber: false,
      toDate:true,
      enableTimePicker: false,
      dateFormat: 'yyyy-MM-dd',
      textFormat: 'yyyy-MM-dd',
      groupId: 'rangeSelector1',
    });
	</script>
@endsection
