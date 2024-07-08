<div class="modal fade" id="createAccountModal" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content modal-content-demo">
      <div class="modal-header">
        <p class="modal-title" style="font-size: 20px;">ثبت حساب جدید</p><button aria-label="Close" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('admin.accounts.store') }}" method="post" class="save">
          @csrf
          <div class="row">

            <div class="col-lg-6 co-12">
              <div class="form-group">
                <label for="supplier_id" class="control-label">انتخاب تامین کننده :<span class="text-danger">&starf;</span></label>
                <select name="supplier_id" id="supplier_id" class="form-control select2" required>
                  <option value="" class="text-muted"> تامین کننده را انخاب کنید </option>
                  @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" @selected(old("supplier_id") == $supplier->id)>{{ $supplier->name .' - '. $supplier->mobile }}</option>
                  @endforeach
                </select>
                <x-core::show-validation-error name="supplier_id" />
              </div>
            </div>

            <div class="col-lg-6 co-12">
              <div class="form-group">
                <label for="account_number" class="control-label">شماره حساب :<span class="text-danger">&starf;</span></label>
                <input type="text" id="account_number" class="form-control" name="account_number" required value="{{ old('account_number') }}">
                <x-core::show-validation-error name="account_number" />
              </div>
            </div>

            <div class="col-lg-6 co-12">
              <div class="form-group">
                <label for="card_number" class="control-label">شماره کارت :<span class="text-danger">&starf;</span></label>
                <input type="text" id="card_number" class="form-control" name="card_number" required value="{{ old('card_number') }}">
                <x-core::show-validation-error name="card_number" />
              </div>
            </div>

            <div class="col-lg-6 co-12">
              <div class="form-group">
                <label for="bank_name" class="control-label">نام بانک :<span class="text-danger">&starf;</span></label>
                <input type="text" id="bank_name" class="form-control" name="bank_name" required value="{{ old('bank_name') }}">
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
