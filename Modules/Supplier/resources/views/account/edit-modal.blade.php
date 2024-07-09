@foreach ($accounts as $account)
  <div class="modal fade" id="editAccountModal-{{ $account->id }}" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content modal-content-demo">
        <div class="modal-header">
          <p class="modal-title" style="font-size: 20px;">ویرایش حساب - کد {{ $account->id }}</p><button aria-label="Close" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('admin.accounts.update', $account) }}" method="post" class="save">
            @csrf
            @method('PATCH')
            <input type="hidden" name="supplier_id" value="{{ $account->supplier_id }}">
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
                    value="{{ old('account_number', $account->account_number) }}"
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
                    value="{{ old('card_number', $account->card_number) }}"
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
                    value="{{ old('bank_name', $account->bank_name) }}"
                  />
                  <x-core::show-validation-error name="bank_name" />
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
