<div class="modal fade" id="createSalaryModal" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content modal-content-demo">
      <div class="modal-header">
        <p class="modal-title" style="font-size: 20px;">
          پرداخت حقوق به {{ $employee->name }} - کد {{ $employee->id }}
        </p>
        <button aria-label="Close" class="close" data-dismiss="modal">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('admin.salaries.store') }}" method="post" class="save">

          @csrf
          <input type="hidden" name="employee_id" value="{{ $employee->id }}">

          <div class="row">

            <div class="col-lg-6 col-12">
              <div class="form-group">
                <label for="amount" class="control-label">مبلغ حقوق (تومان) :<span class="text-danger">&starf;</span></label>
                <input type="text" name="amount" id="amount" placeholder="عنوان را وارد کنید" class="form-control comma" value="{{ old('amount') }}">
                <x-core::show-validation-error name="amount" />
              </div>
            </div>

            <div class="col-lg-6 col-12">
              <div class="form-group">
                <label for="overtime" class="control-label">اضافه کاری (ساعت) :</label>
                <input type="number" name="overtime" id="overtime" placeholder="تعداد ساعت اضافه کاری را وارد کنید" class="form-control" value="{{ old('overtime') }}">
                <x-core::show-validation-error name="overtime" />
              </div>
            </div>

            <div class="col-lg-6 col-12">
              <div class="form-group">
                <label for="payment_date_show" class="control-label">تاریخ پرداخت :<span class="text-danger">&starf;</span></label>
                <input class="form-control fc-datepicker" id="payment_date_show" type="text" autocomplete="off" placeholder="تاریخ پرداخت را انتخاب کنید" />
                <input name="payment_date" id="payment_date" type="hidden" value="{{ old("payment_date") }}" required/>
                <x-core::show-validation-error name="payment_date" />
              </div>
            </div>

            <div class="col-lg-6 col-12">
              <div class="form-group">
                <label for="receipt_image" class="control-label">عکس فیش :</label>
                <input type="file" name="receipt_image" id="receipt_image" class="form-control" value="{{ old('receipt_image') }}">
                <x-core::show-validation-error name="receipt_image" />
              </div>
            </div>

            <div class="col-12">
              <div class="form-group">
                <label for="description" class="control-label">توضیحات :</label>
                <textarea name="description" id="description" placeholder="توضیحات لازم را وارد کنید" class="form-control" rows="5"> {{ old('description') }} </textarea>
                <x-core::show-validation-error name="description" />
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

